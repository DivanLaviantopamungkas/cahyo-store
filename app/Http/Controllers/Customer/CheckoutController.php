<?php

namespace App\Http\Controllers\Customer;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\Provider;
use App\Services\TokopayService;
use App\Services\DigiflazzService;
use App\Services\TelegramService;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\ProductNominal;
use App\Models\Trancsaction;
use App\Models\TransactionItem;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'create']);
    }
    /**
     * Halaman checkout untuk single product (dari detail produk)
     */
    public function create(Request $request, $product_slug)
    {
        // Cari produk berdasarkan slug
        $product = Product::where('slug', $product_slug)->firstOrFail();

        // Validasi parameter nominal_id
        $nominalId = $request->query('nominal_id');
        if (!$nominalId) {
            return redirect()->route('product.show', $product_slug)
                ->with('error', 'Silakan pilih nominal terlebih dahulu.');
        }

        // Cari nominal
        $nominal = ProductNominal::where('id', $nominalId)
            ->where('product_id', $product->id)
            ->where('available_stock', '>', 0)
            ->firstOrFail();

        // Validasi input dari form
        $formData = $request->only(['phone', 'customer_id']);

        // Validasi: pastikan ada phone number untuk produk yang butuh
        if (in_array($product->type, ['pulsa', 'data', 'e-wallet']) && empty($formData['phone'])) {
            return redirect()->route('product.show', $product_slug)
                ->with('error', 'Nomor handphone harus diisi.')
                ->withInput();
        }

        if ($product->type === 'pln' && empty($formData['customer_id'])) {
            return redirect()->route('product.show', $product_slug)
                ->with('error', 'Nomor ID Pelanggan harus diisi.')
                ->withInput();
        }

        // Simpan data ke session untuk sementara
        session(['single_checkout' => [
            'product_id' => $product->id,
            'nominal_id' => $nominal->id,
            'form_data' => $formData,
            'quantity' => 1
        ]]);

        // Redirect ke halaman checkout (index)
        return redirect()->route('checkout.index');
    }

    /**
     * Halaman checkout utama (handle single product & cart)
     */
    public function index(Request $request)
    {
        // Ambil data cart dari session
        $cartItems = session('cart', []);

        // Jika cart kosong, redirect ke cart
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        } //ini aku ga pake cart ya

        // Hitung total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Cek apakah single product
        $singleProduct = count($cartItems) === 1;

        // Cek apakah butuh recipient number
        $needsRecipient = false;
        foreach ($cartItems as $item) {
            if (in_array($item['type'], ['digiflazz', 'transfer'])) {
                $needsRecipient = true;
                break;
            }
        }

        return view('customer.pages.checkout', compact('cartItems', 'total', 'singleProduct', 'needsRecipient'));
    }

    /**
     * Proses checkout (menyimpan transaction)
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'recipient_numbers' => 'sometimes|array',
            'recipient_numbers.*' => 'required_if:needs_recipient,true|string|max:20'
        ]);

        // Ambil data cart
        $singleCheckout = session('single_checkout');
        $cart = session('cart', []);

        if (!$singleCheckout && empty($cart)) {
            return redirect()->route('products.index')
                ->with('error', 'Tidak ada item untuk diproses.');
        }

        try {
            // Generate invoice number
            $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(time()), 0, 6));

            // Buat transaction
            $transaction = Trancsaction::create([
                'invoice' => $invoice,
                'user_id' => auth()->id(),
                'amount' => 0, // akan diupdate nanti
                'total_paid' => 0,
                'payment_method' => 'qris',
                'payment_provider' => 'tokopay',
                'status' => 'pending',
                'expired_at' => now()->addHours(2)
            ]);

            $totalAmount = 0;

            if ($singleCheckout) {
                // Proses single product
                $product = Product::findOrFail($singleCheckout['product_id']);
                $nominal = ProductNominal::findOrFail($singleCheckout['nominal_id']);

                $price = $nominal->discount_price ?? $nominal->price;
                $totalAmount = $price;

                // Ambil nomor tujuan
                $recipientNo = null;
                if (in_array($product->type, ['pulsa', 'data', 'e-wallet'])) {
                    $recipientNo = $singleCheckout['form_data']['phone'] ?? null;
                } elseif ($product->type === 'pln') {
                    $recipientNo = $singleCheckout['form_data']['customer_id'] ?? null;
                }

                // Tentukan fulfillment source
                $fulfillmentSource = $this->getFulfillmentSource($product->type);

                // Buat transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_nominal_id' => $nominal->id,
                    'quantity' => 1,
                    'price' => $price,
                    'total' => $price,
                    'status' => 'pending',
                    'fulfillment_source' => $fulfillmentSource
                ]);

                // Simpan recipient number ke additional data jika ada
                if ($recipientNo) {
                    // Simpan sebagai metadata atau di field khusus
                    // Ini bisa disesuaikan dengan struktur database kamu
                }

                // Kurangi stock nominal
                $nominal->decrement('available_stock', 1);
            } else {
                // Proses cart items
                $productIds = array_keys($cart);
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

                foreach ($cart as $productId => $item) {
                    $product = $products[$productId];

                    // Ambil nomor tujuan dari form
                    $recipientNo = $validated['recipient_numbers'][$productId] ?? null;

                    $subtotal = $item['price'] * $item['quantity'];
                    $totalAmount += $subtotal;

                    // Tentukan fulfillment source
                    $fulfillmentSource = $this->getFulfillmentSource($product->type);

                    // Cari nominal jika ada
                    $nominalId = $item['nominal_id'] ?? null;

                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'product_nominal_id' => $nominalId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $subtotal,
                        'status' => 'pending',
                        'fulfillment_source' => $fulfillmentSource
                    ]);

                    // Kurangi stock jika ada nominal
                    if ($nominalId) {
                        ProductNominal::where('id', $nominalId)->decrement('available_stock', $item['quantity']);
                    }
                }
            }

            // Update total amount
            $transaction->update([
                'amount' => $totalAmount,
                'total_paid' => $totalAmount
            ]);

            // Simpan customer data jika guest
            if (!auth()->check()) {
                session(['guest_checkout' => [
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'transaction_id' => $transaction->id
                ]]);
            }

            // Clear session data
            session()->forget(['single_checkout', 'cart']);

            // Redirect ke pembayaran
            return redirect()->route('payment.show', $transaction->invoice)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus item dari cart
     */
    public function removeFromCart($id)
    {
        // Cek apakah single checkout
        $singleCheckout = session('single_checkout');

        if ($singleCheckout && strpos($id, 'single_') === 0) {
            // Hapus single checkout
            session()->forget('single_checkout');
            return redirect()->route('products.index')
                ->with('success', 'Pesanan dibatalkan.');
        }

        // Hapus dari cart biasa
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);

            return redirect()->route('checkout.index')
                ->with('success', 'Produk dihapus dari keranjang.');
        }

        return redirect()->route('checkout.index')
            ->with('error', 'Produk tidak ditemukan.');
    }

    /**
     * Map product type untuk konsistensi
     */
    private function mapProductType($type)
    {
        $mapping = [
            'pulsa' => 'digiflazz',
            'data' => 'digiflazz',
            'e-wallet' => 'transfer',
            'pln' => 'digiflazz',
            'pdam' => 'digiflazz',
            'bpjs' => 'digiflazz',
            'game_voucher' => 'manual',
            'voucher' => 'manual',
            'other' => 'manual'
        ];

        return $mapping[$type] ?? 'manual';
    }

    /**
     * Tentukan fulfillment source berdasarkan product type
     */
    private function getFulfillmentSource($productType)
    {
        $mapping = [
            'pulsa' => 'digiflazz',
            'data' => 'digiflazz',
            'e-wallet' => 'manual', // biasanya manual transfer
            'pln' => 'digiflazz',
            'pdam' => 'digiflazz',
            'bpjs' => 'digiflazz',
            'game_voucher' => 'manual',
            'voucher' => 'manual'
        ];

        return $mapping[$productType] ?? 'manual';
    }
}

<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Support\Str;
use App\Models\Trancsaction;
use Illuminate\Http\Request;
use App\Models\ProductNominal;
use App\Models\ProviderProduct;
use App\Models\TransactionItem;
use App\Services\MidtransService;
use App\Services\TelegramService;
use App\Services\WhatsAppService;
use App\Services\DigiflazzService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class CheckoutController extends Controller
{
    protected $midtransService;
    protected $telegramService;
    protected $whatsappService;
    protected $notificationService;

    // DigiflazzService akan di-instantiate sesuai provider

    public function __construct()
    {
        $this->midtransService = new MidtransService();
        $this->telegramService = new TelegramService();
        $this->whatsappService = new WhatsAppService();
        $this->notificationService = new NotificationService();
    }

    /**
     * Step 1: Create checkout page
     */
    public function create(Request $request, $product_slug)
    {
        // Ambil data dari session jika ada
        $sessionData = session('pending_checkout', []);

        $nominal_id = $request->get('nominal_id') ?? ($sessionData['nominal_id'] ?? null);
        $phone = $request->get('phone') ?? ($sessionData['phone'] ?? null);
        $customer_id = $request->get('customer_id') ?? ($sessionData['customer_id'] ?? null);

        session()->forget('pending_checkout');

        // Cari produk
        $product = Product::where('slug', $product_slug)->firstOrFail();

        // Validasi nominal_id
        if (!$nominal_id) {
            return redirect()->back()
                ->with('error', 'Nominal tidak ditemukan');
        }

        // Cari nominal
        $nominal = $product->nominals()->findOrFail($nominal_id);

        // Validasi stok
        if (!$product->is_digiflazz && $nominal->available_stock == 0) {
            return redirect()->back()
                ->with('error', 'Maaf, stok untuk nominal ini habis');
        }

        return view('customer.pages.checkout', compact('product', 'nominal', 'phone', 'customer_id'));
    }

    /**
     * Step 2: Store transaction & initiate payment
     */
    public function store(Request $request)
    {
        try {
            Log::info('Checkout store called', $request->all());

            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);
            $nominal = ProductNominal::findOrFail($request->nominal_id);

            // Generate invoice
            $invoice = 'INV-' . strtoupper(Str::random(8)) . '-' . time();
            $amount = $nominal->discount_price ?? $nominal->price;

            DB::beginTransaction();

            // Buat transaksi
            $transaction = Trancsaction::create([
                'invoice' => $invoice,
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_method' => 'qris',
                'payment_provider' => 'midtrans',
                'status' => 'pending',
                'expired_at' => Carbon::now()->addMinutes(15),
            ]);

            // Buat transaction item
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'product_nominal_id' => $nominal->id,
                'quantity' => 1,
                'price' => $amount,
                'total' => $amount,
                'status' => 'pending',
                'fulfillment_source' => $product->is_digiflazz ? 'digiflazz' : 'manual',
                'phone' => $request->phone,
                'customer_id' => $request->customer_id,
            ]);

            // Panggil Midtrans
            $paymentResult = $this->midtransService->createQRISPayment(
                $transaction->invoice,
                $amount,
                $user->name,
                $user->email,
                $user->phone
            );

            if (!$paymentResult['success']) {
                throw new \Exception('Midtrans error: ' . ($paymentResult['message'] ?? 'Unknown error'));
            }

            // Update dengan payment data
            $transaction->update([
                'payment_url' => $paymentResult['redirect_url'] ?? null,
                'payment_payload' => json_encode($paymentResult),
            ]);

            // Kurangi stok jika manual
            if (!$product->is_digiflazz) {
                $nominal->decrement('available_stock');
            }

            DB::commit();

            Log::info('Checkout success', ['invoice' => $invoice, 'transaction_id' => $transaction->id]);

            return response()->json([
                'success' => true,
                'order_id' => $transaction->id,
                'message' => 'Pembayaran berhasil dibuat',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Step 3: Show payment page with QR
     */
    public function payment($order_id)
    {
        $transaction = Trancsaction::where('user_id', Auth::id())
            ->with(['items.product', 'items.nominal'])
            ->findOrFail($order_id);

        Log::info('Payment page accessed', [
            'transaction_id' => $transaction->id,
            'status' => $transaction->status,
            'has_payload' => !empty($transaction->payment_payload)
        ]);

        // Jika sudah paid/completed, redirect ke success
        if (in_array($transaction->status, ['paid', 'completed'])) {
            return redirect()->route('checkout.success', $order_id);
        }

        // Jika expired/cancelled, redirect ke failed
        if (in_array($transaction->status, ['expired', 'cancelled'])) {
            return redirect()->route('checkout.failed', $order_id);
        }

        // Get QR URL dari payload
        $qrUrl = null;

        if ($transaction->payment_payload) {
            $paymentData = json_decode($transaction->payment_payload, true);
            Log::info('Payment data from payload:', $paymentData);

            // Coba ambil QR URL dari beberapa sumber
            if (isset($paymentData['qr_url'])) {
                $qrUrl = $paymentData['qr_url'];
            } elseif (isset($paymentData['qr_string'])) {
                // Generate QR dari string
                $qrString = $paymentData['qr_string'];
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString);
            } elseif (isset($paymentData['redirect_url'])) {
                $qrUrl = $paymentData['redirect_url'];
            }
        }

        Log::info('Final QR URL for display: ' . ($qrUrl ? 'YES' : 'NO'));

        return view('customer.pages.payment', [
            'transaction' => $transaction,
            'qrUrl' => $qrUrl,
            'amount' => $transaction->amount,
            'product' => $transaction->items->first()->product ?? null,
        ]);
    }

    /**
     * Step 4: Check payment status (dipanggil via AJAX)
     */
    public function validatePayment(Request $request)
    {
        $order_id = $request->order_id;

        // Cari transaksi
        $transaction = Trancsaction::where('user_id', Auth::id())
            ->with(['items.product'])
            ->findOrFail($order_id);

        Log::info('Checking payment status for: ' . $transaction->invoice);

        // SIMULASI: Jika lebih dari 30 detik sejak dibuat, anggap SUCCESS (untuk testing)
        if (app()->environment('local') || app()->environment('testing')) {
            $createdAgo = now()->diffInSeconds($transaction->created_at);

            if ($createdAgo > 30 && $transaction->status === 'pending') {
                Log::info('Simulating successful payment for testing');

                DB::beginTransaction();
                try {
                    // Update transaction
                    $transaction->update([
                        'status' => 'paid',
                        'total_paid' => $transaction->amount,
                        'paid_at' => now(),
                    ]);

                    // Update item
                    $transactionItem = $transaction->items()->first();
                    $transactionItem->update(['status' => 'processing']);

                    // Process based on product type
                    $product = $transactionItem->product;

                    if ($product->is_digiflazz) {
                        $this->processDigiflazz($transactionItem);
                    } else {
                        $this->processManual($transactionItem);
                    }

                    // Send notifications
                    $this->sendNotifications($transaction);

                    DB::commit();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Pembayaran berhasil (simulasi)'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Simulation error: ' . $e->getMessage());
                }
            }
        }

        // REAL CHECK: Cek status transaksi
        switch ($transaction->status) {
            case 'paid':
            case 'completed':
                return response()->json(['status' => 'success']);

            case 'cancelled':
            case 'expired':
            case 'failed':
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Transaksi ' . $transaction->status
                ]);

            case 'processing':
                return response()->json(['status' => 'processing_error']);

            default:
                return response()->json(['status' => 'pending']);
        }
    }

    public function handleMidtransReturn(Request $request)
    {
        $orderId = $request->get('order_id');
        
        $transaction = Trancsaction::where('invoice', $orderId)->first();
        
        if (!$transaction) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan');
        }

        if ($transaction->status === 'pending') {
            try {
                $status = $this->midtransService->checkStatus($orderId);
                
                if ($status) {
                    $transactionStatus = $status->transaction_status;
                    $fraudStatus = $status->fraud_status;
                    $isPaid = false;

                    // Logika Status Midtrans
                    if ($transactionStatus == 'capture') {
                        if ($fraudStatus == 'challenge') {
                        } else if ($fraudStatus == 'accept') {
                            $isPaid = true;
                        }
                    } else if ($transactionStatus == 'settlement') {
                        $isPaid = true;
                    } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                        $transaction->update(['status' => 'cancelled']);
                    }

                    // Jika Midtrans bilang Lunas, Update DB
                    if ($isPaid) {
                        DB::beginTransaction();
                        try {
                            $transaction->update([
                                'status' => 'paid',
                                'total_paid' => $status->gross_amount,
                                'paid_at' => now(),
                                'payment_reference' => $status->transaction_id ?? null,
                            ]);

                            $item = $transaction->items()->first();
                            $item->update(['status' => 'processing']);

                            $item->load('product');

                            if ($item->product->source === 'digiflazz') {
                                $this->processDigiflazz($item);
                            } else {
                                $this->processManual($item);
                            }

                            $this->sendNotifications($transaction);

                            DB::commit();
                            
                            // PENTING: Refresh model agar status terbaru terbaca oleh baris kode di bawah
                            $transaction->refresh(); 
                            
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Auto-update return error: ' . $e->getMessage());
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Gagal cek status midtrans: ' . $e->getMessage());
            }
        }

        // Cek lagi status terbaru setelah proses di atas
        $transaction->refresh();

        if (in_array($transaction->status, ['paid', 'completed'])) {
            // SUKSES -> Ke Halaman Sukses
            return redirect()->route('checkout.success', $transaction->id);
        } else if (in_array($transaction->status, ['cancelled', 'expired', 'failed'])) {
            // GAGAL -> Ke Halaman Gagal
            return redirect()->route('checkout.failed', $transaction->id);
        }
        
        // PENDING -> Ke Halaman Payment (Hanya jika benar-benar masih pending)
        return redirect()->route('checkout.payment', $transaction->id)
            ->with('info', 'Pembayaran sedang diproses, silakan refresh halaman ini.');
    }

    /**
     * Step 5: Success page
     */
    public function success($order_id)
    {
        $transaction = Trancsaction::where('user_id', Auth::id())
            ->with(['items.product', 'items.nominal'])
            ->findOrFail($order_id);

        // Jika status masih pending, coba check ulang
        if ($transaction->status === 'pending') {
            // Cek status dari database (mungkin sudah berubah)
            $transaction->refresh();

            // Jika masih pending, redirect kembali ke payment
            if ($transaction->status === 'pending') {
                return redirect()->route('checkout.payment', $order_id)
                    ->with('info', 'Pembayaran masih diproses. Silakan tunggu...');
            }
        }

        return view('customer.pages.succes', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Step 6: Failed page
     */
    public function failed($order_id)
    {
        $transaction = Trancsaction::where('user_id', Auth::id())
            ->findOrFail($order_id);

        return view('customer.pages.failed', [
            'transaction' => $transaction
        ]);
    }

    /**
     * ============================================
     * PRIVATE METHODS
     * ============================================
     */

    /**
     * Proses produk Digiflazz
     */
    private function processDigiflazz($transactionItem)
    {
        try {
            $product = $transactionItem->product;
            $nominal = $transactionItem->nominal;
            $phone = $transactionItem->phone;

            // Cari provider Digiflazz
            $provider = Provider::where('type', 'digiflazz')
                ->where('status', 'active')
                ->first();

            if (!$provider) {
                throw new \Exception('Provider Digiflazz tidak ditemukan');
            }

            // Instantiate DigiflazzService
            $digiflazzService = new DigiflazzService($provider);

            // Cari SKU dari provider product
            $providerProduct = ProviderProduct::where('provider_id', $provider->id)
                ->where(function ($query) use ($nominal) {
                    $query->where('provider_sku', $nominal->provider_sku)
                        ->orWhere('name', 'like', '%' . $nominal->name . '%');
                })
                ->first();

            if (!$providerProduct) {
                throw new \Exception('SKU produk tidak ditemukan di Digiflazz');
            }

            // Generate ref_id
            $refId = 'DGF-' . strtoupper(Str::random(8)) . '-' . time();

            // Panggil API Digiflazz
            $result = $digiflazzService->topup(
                $providerProduct->provider_sku,
                $phone,
                $refId
            );

            // Parse response
            $responseData = $result['data'] ?? [];
            $status = $responseData['status'] ?? 'error';
            $sn = $responseData['sn'] ?? null;
            $message = $responseData['message'] ?? 'No message';

            if ($status === 'success' || $status === '1') {
                // Update transaction item
                $transactionItem->update([
                    'status' => 'completed',
                    'provider_trx_id' => $responseData['trx_id'] ?? $refId,
                    'provider_status' => 'success',
                    'provider_rc' => $responseData['rc'] ?? null,
                    'provider_message' => $message,
                    'sn' => $sn,
                    'raw_response' => json_encode($responseData),
                    'completed_at' => Carbon::now(),
                ]);

                // Update parent transaction
                $transactionItem->transaction->update([
                    'status' => 'completed',
                    'completed_at' => Carbon::now(),
                ]);

                Log::info('Digiflazz purchase successful', [
                    'transaction_id' => $transactionItem->transaction_id,
                    'ref_id' => $refId,
                    'sn' => $sn
                ]);
            } else {
                // Update sebagai failed
                $transactionItem->update([
                    'status' => 'cancelled',
                    'provider_status' => 'failed',
                    'provider_message' => $message,
                    'raw_response' => json_encode($responseData),
                ]);

                throw new \Exception('Digiflazz error: ' . $message);
            }
        } catch (\Exception $e) {
            Log::error('Digiflazz processing error: ' . $e->getMessage(), [
                'transaction_item_id' => $transactionItem->id
            ]);
            throw $e;
        }
    }

    /**
     * Proses produk Manual
     */
    private function processManual($transactionItem)
    {
        // Generate voucher code
        $voucherCode = 'VOUCH-' . strtoupper(Str::random(10));

        // Generate QR Code URL
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' .
            urlencode('VOUCHER-' . $voucherCode);

        // Update transaction item
        $transactionItem->update([
            'status' => 'completed',
            'voucher_code' => $voucherCode,
            'qr_code_url' => $qrCodeUrl,
            'expired_at' => Carbon::now()->addDays(30),
            'completed_at' => Carbon::now(),
        ]);

        // Update parent transaction
        $transactionItem->transaction->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);
    }

    /**
     * Kirim semua notifikasi
     */
    private function sendNotifications($transaction)
    {
        try {
            // 1️⃣ NOTIFIKASI IN-APP (Database)
            $this->notificationService->paymentSuccess($transaction);

            // 2️⃣ NOTIFIKASI TELEGRAM KE ADMIN
            $this->telegramService->sendNewTransaction($transaction);

            // 3️⃣ NOTIFIKASI WHATSAPP KE CUSTOMER
            $userPhone = $transaction->user->phone;
            $transactionItem = $transaction->items()->first();
            $product = $transactionItem->product;

            if ($product->source === 'digiflazz') {
                // Format WA untuk Digiflazz
                $this->sendDigiflazzWhatsApp($transaction);
            } else {
                // Format WA untuk Manual (3 pesan)
                $this->sendManualWhatsApp($transaction);
            }

            Log::info('All notifications sent successfully', [
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            Log::error('Notification error: ' . $e->getMessage());
            // Jangan throw error agar proses tetap berjalan
        }
    }

    /**
     * Kirim WhatsApp untuk produk Digiflazz
     */
    private function sendDigiflazzWhatsApp($transaction)
    {
        $transactionItem = $transaction->items()->first();

        // Pesan 1: Konfirmasi pembayaran
        $message1 = "✅ *PEMBAYARAN BERHASIL*\n\n";
        $message1 .= "Invoice: {$transaction->invoice}\n";
        $message1 .= "Produk: {$transactionItem->product->name}\n";
        $message1 .= "Nominal: {$transactionItem->nominal->name}\n";
        $message1 .= "No. Tujuan: {$transactionItem->phone}\n";
        $message1 .= "Status: Diproses otomatis\n\n";
        $message1 .= "Mohon tunggu 1-5 menit untuk pengisian.";

        $this->whatsappService->sendMessage($transaction->user->phone, $message1);

        sleep(2);

        // Pesan 2: Hasil pengisian
        if ($transactionItem->provider_status === 'success') {
            $message2 = "🎉 *PENGISIAN SUKSES*\n\n";
            $message2 .= "SN: {$transactionItem->sn}\n";
            $message2 .= "Status: Berhasil\n";
            $message2 .= "Waktu: " . now()->format('d/m/Y H:i:s') . "\n\n";
            $message2 .= "Terima kasih telah berbelanja!";

            $this->whatsappService->sendMessage($transaction->user->phone, $message2);
        } else {
            $message2 = "⚠️ *PENGISIAN GAGAL*\n\n";
            $message2 .= "Status: {$transactionItem->provider_message}\n";
            $message2 .= "Silakan hubungi admin untuk refund.\n\n";
            $message2 .= "WhatsApp Admin: 628xxxxxxxxxx";

            $this->whatsappService->sendMessage($transaction->user->phone, $message2);
        }
    }

    /**
     * Kirim WhatsApp untuk produk Manual
     */
    private function sendManualWhatsApp($transaction)
    {
        $transactionItem = $transaction->items()->first();

        $transactionItem->refresh();

        $expiredDate = $transactionItem->expired_at 
            ? $transactionItem->expired_at->format('d F Y') 
            : now()->addDays(30)->format('d F Y'); // Fallback default

        // WA 1: Kode Voucher
        $message1 = "🎫 *KODE VOUCHER ANDA*\n\n";
        $message1 .= "Invoice: {$transaction->invoice}\n";
        $message1 .= "Produk: {$transactionItem->product->name}\n";
        $message1 .= "Kode: *{$transactionItem->voucher_code}*\n\n";
        $message1 .= "Simpan kode ini untuk klaim produk.";

        $this->whatsappService->sendMessage($transaction->user->phone, $message1);

        sleep(2);

        // WA 2: QR Code
        $message2 = "📱 *QR CODE PRODUK*\n\n";
        $message2 .= "Scan QR untuk klaim produk:\n";
        $message2 .= "Link: {$transactionItem->qr_code_url}";

        $this->whatsappService->sendMessage($transaction->user->phone, $message2);

        sleep(2);

        // WA 3: Terima Kasih + Expired
        $message3 = "🎉 *TERIMA KASIH*\n\n";
        $message3 .= "Pembayaran berhasil!\n\n";
        $message3 .= "Produk berlaku hingga: *{$expiredDate}*\n\n";
        $message3 .= "Hubungi admin jika butuh bantuan.";

        $this->whatsappService->sendMessage($transaction->user->phone, $message3);
    }
}

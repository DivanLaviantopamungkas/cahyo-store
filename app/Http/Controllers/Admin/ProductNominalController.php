<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductNominal;
use App\Models\VoucherCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductNominalController extends BaseAdminController
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $productId = $request->get('product_id');
        $status = $request->get('status');

        $nominals = ProductNominal::query()
            ->with(['product.category'])
            ->when($productId, function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('product_id')
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return $this->view('nominals.index', compact('nominals', 'products', 'search', 'productId', 'status'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return $this->view('nominals.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'       => ['required', 'exists:products,id'],
            'name'             => ['required', 'string', 'max:100'],
            'provider_sku'     => ['nullable', 'string', 'max:80'],
            'price'            => ['required', 'numeric', 'min:0'],
            'discount_price'   => ['nullable', 'numeric', 'min:0'],
            'cost_price'       => ['nullable', 'numeric', 'min:0'],
            'margin'           => ['nullable', 'numeric', 'min:0'],
            'stock'            => ['required', 'integer', 'min:0'],
            'available_stock'  => ['required', 'integer', 'min:0'],
            'stock_mode'       => ['nullable', 'in:manual,provider'],
            'is_active'        => ['nullable', 'boolean'],
            'order'            => ['nullable', 'integer', 'min:0'],
        ]);

        // Validasi manual untuk memastikan discount_price < price jika ada
        if (isset($data['discount_price']) && $data['discount_price'] > 0 && $data['discount_price'] >= $data['price']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['discount_price' => 'Harga diskon harus lebih kecil dari harga normal.']);
        }

        // Validasi manual untuk memastikan available_stock <= stock
        if ($data['available_stock'] > $data['stock']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['available_stock' => 'Stok tersedia tidak boleh lebih besar dari total stok.']);
        }

        // Hitung margin jika kosong tapi ada cost_price
        if (empty($data['margin']) && isset($data['cost_price']) && $data['cost_price'] > 0) {
            $data['margin'] = (($data['price'] - $data['cost_price']) / $data['price']) * 100;
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        ProductNominal::create($data);

        return redirect()
            ->route('admin.nominals.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Nominal berhasil ditambahkan.'
            ]);
    }

    public function show(ProductNominal $nominal)
    {
        $nominal->load(['product.category', 'voucherCodes' => function ($query) {
            $query->latest()->take(10);
        }]);

        // Statistik untuk nominal ini
        $stats = [
            'total_vouchers' => $nominal->voucherCodes()->count(),
            'available_vouchers' => $nominal->voucherCodes()->where('status', 'available')->count(),
            'reserved_vouchers' => $nominal->voucherCodes()->where('status', 'reserved')->count(),
            'sold_vouchers' => $nominal->voucherCodes()->where('status', 'sold')->count(),
            'expired_vouchers' => $nominal->voucherCodes()->where('status', 'expired')->count(),
            'total_revenue' => $nominal->voucherCodes()
                ->where('status', 'sold')
                ->count() * $nominal->price,
        ];

        return $this->view('nominals.show', compact('nominal', 'stats'));
    }

    public function edit(ProductNominal $nominal)
    {
        $products = Product::orderBy('name')->get();
        $allNominals = ProductNominal::with('product')->orderBy('name')->get();

        return view('admin.voucher-codes.edit', compact(
            'voucherCode',
            'products',
            'allNominals'
        ));
    }

    public function update(Request $request, ProductNominal $nominal)
    {
        $data = $request->validate([
            'product_id'       => ['sometimes', 'required', 'exists:products,id'],
            'name'             => ['sometimes', 'required', 'string', 'max:100'],
            'provider_sku'     => ['nullable', 'string', 'max:80'],
            'price'            => ['sometimes', 'required', 'numeric', 'min:0'],
            'discount_price'   => ['nullable', 'numeric', 'min:0'],
            'cost_price'       => ['nullable', 'numeric', 'min:0'],
            'margin'           => ['nullable', 'numeric', 'min:0'],
            'stock'            => ['sometimes', 'required', 'integer', 'min:0'],
            'available_stock'  => ['sometimes', 'required', 'integer', 'min:0'],
            'stock_mode'       => ['nullable', 'in:manual,provider'],
            'is_active'        => ['nullable', 'boolean'],
            'order'            => ['nullable', 'integer', 'min:0'],
        ]);

        // Validasi manual untuk memastikan discount_price < price jika ada
        if (isset($data['discount_price']) && $data['discount_price'] > 0 && $data['discount_price'] >= $data['price']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['discount_price' => 'Harga diskon harus lebih kecil dari harga normal.']);
        }

        // Validasi manual untuk memastikan available_stock <= stock
        if (isset($data['available_stock']) && isset($data['stock']) && $data['available_stock'] > $data['stock']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['available_stock' => 'Stok tersedia tidak boleh lebih besar dari total stok.']);
        }

        // Hitung margin jika kosong tapi ada cost_price
        if (empty($data['margin']) && isset($data['cost_price']) && $data['cost_price'] > 0) {
            $data['margin'] = (($data['price'] - $data['cost_price']) / $data['price']) * 100;
        }

        $nominal->update($data);

        return redirect()
            ->route('admin.nominals.edit', $nominal)
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Nominal berhasil diupdate.'
            ]);
    }

    public function destroy(ProductNominal $nominal)
    {
        // Cek apakah nominal memiliki voucher codes
        if ($nominal->voucherCodes()->exists()) {
            return redirect()
                ->route('admin.nominals.index')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Tidak dapat menghapus nominal yang masih memiliki voucher codes.'
                ]);
        }

        $nominal->delete();

        return redirect()
            ->route('admin.nominals.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Nominal berhasil dihapus.'
            ]);
    }

    // Custom Methods sesuai route
    public function toggleStatus(ProductNominal $nominal)
    {
        $nominal->update(['is_active' => !$nominal->is_active]);

        $status = $nominal->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.nominals.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => "Nominal berhasil $status."
            ]);
    }

    public function orderUp(ProductNominal $nominal)
    {
        $previous = ProductNominal::where('product_id', $nominal->product_id)
            ->where('order', '<', $nominal->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous) {
            $temp = $nominal->order;
            $nominal->order = $previous->order;
            $previous->order = $temp;

            $nominal->save();
            $previous->save();
        }

        return redirect()->back();
    }

    public function orderDown(ProductNominal $nominal)
    {
        $next = ProductNominal::where('product_id', $nominal->product_id)
            ->where('order', '>', $nominal->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($next) {
            $temp = $nominal->order;
            $nominal->order = $next->order;
            $next->order = $temp;

            $nominal->save();
            $next->save();
        }

        return redirect()->back();
    }

    public function bulkAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids', []);

        if (empty($ids)) {
            return redirect()->back()
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Tidak ada nominal yang dipilih.'
                ]);
        }

        switch ($action) {
            case 'activate':
                ProductNominal::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Nominals berhasil diaktifkan.';
                break;

            case 'deactivate':
                ProductNominal::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Nominals berhasil dinonaktifkan.';
                break;

            case 'delete':
                // Cek apakah ada yang punya voucher codes
                $hasVouchers = ProductNominal::whereIn('id', $ids)
                    ->whereHas('voucherCodes')
                    ->exists();

                if ($hasVouchers) {
                    return redirect()->back()
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Gagal!',
                            'message' => 'Tidak dapat menghapus nominal yang masih memiliki voucher codes.'
                        ]);
                }

                ProductNominal::whereIn('id', $ids)->delete();
                $message = 'Nominals berhasil dihapus.';
                break;

            default:
                $message = 'Aksi tidak dikenali.';
                break;
        }

        return redirect()->back()
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => $message
            ]);
    }
}

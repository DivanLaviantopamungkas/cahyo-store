<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductNominal;
use App\Models\VoucherCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VoucherCodeController extends BaseAdminController
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $productId = $request->get('product_id');
        $status = $request->get('status');

        $query = VoucherCode::query()
            ->with(['product', 'nominal']);

        $stats = [
            'available' => VoucherCode::where('status', 'available')->count(),
            'reserved'  => VoucherCode::where('status', 'reserved')->count(),
            'sold'      => VoucherCode::where('status', 'sold')->count(),
            'expired'   => VoucherCode::where('status', 'expired')->count(),
        ];

        $voucherCodes = $query
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                    ->orWhere('secret', 'like', "%{$search}%");
                });
            })
            ->when($productId, function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.voucher-codes.index', compact('voucherCodes', 'products', 'search', 'productId', 'status', 'stats'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('admin.voucher-codes.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'product_nominal_id' => ['required', 'exists:product_nominals,id'], // Ubah dari nominal_id ke product_nominal_id
            'code_method' => ['required', 'in:upload,manual'],
        ]);

        // Validasi berdasarkan metode input
        if ($request->code_method === 'upload') {
            $request->validate([
                'code_file' => ['required', 'file', 'mimes:txt', 'max:10240'], // 10MB
            ]);

            $codes = $this->processUploadedFile($request->file('code_file'));
        } else {
            $request->validate([
                'codes_input' => ['required', 'string', 'min:1'],
            ]);

            $codes = $this->processManualInput($request->codes_input);
        }

        // Validasi kode duplikat
        $this->validateDuplicateCodes($codes);

        // Simpan semua kode
        $savedCount = $this->saveVoucherCodes(
            $codes,
            $request->product_id,
            $request->product_nominal_id // Ubah ini juga
        );

        return redirect()
            ->route('admin.voucher-codes.index')
            ->with('toast', [
                'type' => 'success',
                'message' => "{$savedCount} kode voucher berhasil ditambahkan."
            ]);
    }

    private function processUploadedFile($file)
    {
        $content = file_get_contents($file->getRealPath());
        $lines = explode("\n", $content);

        return collect($lines)
            ->map(fn($line) => trim($line))
            ->filter(fn($line) => !empty($line))
            ->values();
    }

    private function processManualInput($input)
    {
        $lines = explode("\n", $input);

        return collect($lines)
            ->map(fn($line) => trim($line))
            ->filter(fn($line) => !empty($line))
            ->values();
    }

    private function validateDuplicateCodes($newCodes)
    {
        // Cek duplikat dalam input
        $duplicates = $newCodes->duplicates();
        if ($duplicates->isNotEmpty()) {
            throw ValidationException::withMessages([
                'code_method' => 'Terdapat kode duplikat dalam input: ' .
                    $duplicates->take(5)->implode(', ') . '...'
            ]);
        }

        // Cek duplikat dengan database
        $existing = VoucherCode::whereIn('code', $newCodes)->pluck('code');
        if ($existing->isNotEmpty()) {
            throw ValidationException::withMessages([
                'code_method' => 'Beberapa kode sudah ada di database: ' .
                    $existing->take(5)->implode(', ') . '...'
            ]);
        }
    }

    private function saveVoucherCodes($codes, $productId, $nominalId)
    {
        $batch = [];
        $now = now();

        foreach ($codes as $code) {
            $batch[] = [
                'product_id' => $productId,
                'product_nominal_id' => $nominalId, // Sesuaikan dengan nama field di database
                'code' => $code,
                'secret' => Str::random(16), // Generate secret otomatis
                'status' => 'available',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insert batch setiap 1000 records
            if (count($batch) >= 1000) {
                VoucherCode::insert($batch);
                $batch = [];
            }
        }

        // Insert sisa records
        if (!empty($batch)) {
            VoucherCode::insert($batch);
        }

        return count($codes);
    }

    // AJAX endpoint untuk mengambil nominals berdasarkan product
    public function getNominalsByProduct($productId)
    {
        $nominals = ProductNominal::where('product_id', $productId)
            ->orderBy('price')
            ->get()
            ->map(function ($nominal) {
                return [
                    'id' => $nominal->id,
                    'name' => $nominal->name,
                    'price' => $nominal->price,
                    'discount_price' => $nominal->discount_price,
                ];
            });

        return response()->json($nominals);
    }

    public function edit(VoucherCode $voucherCode)
    {
        $products = Product::orderBy('name')->get();
        $allNominals = ProductNominal::with('product')->orderBy('name')->get();

        return view('admin.voucher-codes.edit', compact(
            'voucherCode',
            'products',
            'allNominals'
        ));
    }

    public function update(Request $request, VoucherCode $voucherCode)
    {
        $data = $request->validate([
            'product_id'         => ['sometimes', 'required', 'exists:products,id'],
            'product_nominal_id' => ['nullable', 'exists:product_nominals,id'],
            'code'               => ['sometimes', 'required', 'string', 'max:255', 'unique:voucher_codes,code,' . $voucherCode->id],
            'secret'             => ['nullable', 'string'],
            'status'             => ['nullable', 'in:available,reserved,sold,expired'],
            'expired_at'         => ['nullable', 'date'],
        ]);

        $voucherCode->update($data);

        return redirect()
            ->route('admin.voucher-codes.edit', $voucherCode)
            ->with('toast', ['type' => 'success', 'message' => 'Voucher berhasil diupdate.']);
    }

    public function destroy(VoucherCode $voucherCode)
    {
        $voucherCode->delete();

        return redirect()
            ->route('admin.voucher-codes.index')
            ->with('toast', ['type' => 'success', 'message' => 'Voucher berhasil dihapus.']);
    }

    public function import(Request $request)
    {
        $data = $request->validate([
            'product_id'         => ['required', 'exists:products,id'],
            'product_nominal_id' => ['nullable', 'exists:product_nominals,id'],
            'codes'              => ['required', 'string'],
        ]);

        $lines = preg_split("/\\r\\n|\\r|\\n/", $data['codes']);
        $lines = array_values(array_filter(array_map('trim', $lines)));

        $inserted = 0;
        $skipped = 0;

        foreach ($lines as $code) {
            if ($code === '') continue;

            try {
                VoucherCode::create([
                    'product_id' => $data['product_id'],
                    'product_nominal_id' => $data['product_nominal_id'] ?? null,
                    'code' => $code,
                    'status' => 'available',
                ]);
                $inserted++;
            } catch (\Throwable $e) {
                $skipped++;
            }
        }

        return redirect()
            ->route('admin.voucher-codes.index')
            ->with('toast', ['type' => 'success', 'message' => "Import selesai. Inserted: {$inserted}, Skipped: {$skipped}"]);
    }

    public function reserve(VoucherCode $voucherCode)
    {
        if ($voucherCode->status !== 'available') {
            return redirect()
                ->back()
                ->with('toast', ['type' => 'error', 'message' => 'Voucher tidak dalam status available.']);
        }

        $voucherCode->update(['status' => 'reserved']);

        return redirect()
            ->back()
            ->with('toast', ['type' => 'success', 'message' => 'Voucher di-reserve.']);
    }

    public function unreserve(VoucherCode $voucherCode)
    {
        if ($voucherCode->status !== 'reserved') {
            return redirect()
                ->back()
                ->with('toast', ['type' => 'error', 'message' => 'Voucher tidak dalam status reserved.']);
        }

        $voucherCode->update(['status' => 'available']);

        return redirect()
            ->back()
            ->with('toast', ['type' => 'success', 'message' => 'Reserve dibatalkan.']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductNominal;
use App\Models\VoucherCode;
use App\Models\Provider;
use App\Models\ProviderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends BaseAdminController
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $categoryId = $request->get('category_id');
        $status = $request->get('status');

        $products = Product::query()
            ->with(['category', 'nominals'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('order')->get();

        // Tambahkan ini untuk mendapatkan semua produk (untuk filter jika diperlukan)
        $allProducts = Product::orderBy('name')->get(['id', 'name']);

        return $this->view('products.index', compact(
            'products',
            'categories',
            'allProducts', // Tambahkan ini
            'search',
            'categoryId',
            'status'
        ));
    }

    /**
     * Show form for creating manual product
     */
    public function createManual()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('admin.products.create-manual', compact('categories'));
    }

    /**
     * Store manual product
     */
    public function storeManual(Request $request)
    {
        // Validation rules for manual product
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:single,multiple'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],

            // Validasi untuk nominals
            'nominals' => ['required', 'array', 'min:1'],
            'nominals.*.name' => ['required', 'string', 'max:255'],
            'nominals.*.provider_sku' => ['nullable', 'string', 'max:255'],
            'nominals.*.price' => ['required', 'numeric', 'min:0'],
            'nominals.*.discount_price' => ['nullable', 'numeric', 'min:0'],
            'nominals.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'nominals.*.margin' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nominals.*.stock' => ['required', 'integer', 'min:0'],
            'nominals.*.available_stock' => ['required', 'integer', 'min:0'],
            'nominals.*.stock_mode' => ['nullable', 'in:manual,provider'],
            'nominals.*.is_active' => ['nullable', 'boolean'],
            'nominals.*.order' => ['nullable', 'integer', 'min:0'],
        ];

        $validated = $request->validate($rules);

        // Validasi tambahan untuk single product
        if ($validated['type'] === 'single' && count($validated['nominals']) > 1) {
            return back()->withInput()->with('error', 'Produk single hanya boleh memiliki 1 nominal');
        }

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        DB::beginTransaction();

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imagePath = $this->uploadImage($request->file('image'), $validated['name']);
            }

            // Prepare product data
            $productData = [
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'image' => $imagePath,
                'source' => 'manual',
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured', false),
                'order' => $validated['order'] ?? 0,
            ];

            // Create product
            $product = Product::create($productData);

            // Create nominals
            $nominalData = [];
            foreach ($validated['nominals'] as $index => $nominal) {

                if (isset($nominal['discount_price']) && $nominal['discount_price'] >= $nominal['price']) {
                    throw new \Exception('Harga diskon harus lebih kecil dari harga normal pada nominal: ' . $nominal['name']);
                }

                if ($nominal['available_stock'] > $nominal['stock']) {
                    throw new \Exception('Stok tersedia tidak boleh lebih besar dari total stok pada nominal: ' . $nominal['name']);
                }

                $nominalData[] = [
                    'product_id' => $product->id,
                    'name' => $nominal['name'],
                    'provider_sku' => $nominal['provider_sku'] ?? null,
                    'price' => $nominal['price'],
                    'discount_price' => $nominal['discount_price'] ?? null,
                    'cost_price' => $nominal['cost_price'] ?? null,
                    'margin' => $nominal['margin'] ?? null,
                    'stock' => $nominal['stock'],
                    'available_stock' => $nominal['available_stock'],
                    'stock_mode' => $nominal['stock_mode'] ?? 'manual',
                    'is_active' => array_key_exists('is_active', $nominal) ? (bool) $nominal['is_active'] : true,
                    'order' => $nominal['order'] ?? $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // insert ke tabel yang benar
            DB::table('product_nominals')->insert($nominalData);

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Produk manual beserta nominals berhasil dibuat.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus gambar jika ada error
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Gagal menambahkan produk: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Show form for creating digiflazz product
     */
    public function createDigiflazz()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $providers = Provider::where('is_active', true)
            ->where('code', 'digiflazz')
            ->orderBy('name')
            ->get();

        return view('admin.products.create-digiflazz', compact('categories', 'providers'));
    }

    /**
     * Store digiflazz product
     */
    public function storeDigiflazz(Request $request)
    {
        // Validation rules for Digiflazz product
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'provider_id' => ['required', 'exists:providers,id'],
            'provider_sku' => ['required', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:single,multiple'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],

            // Validasi untuk nominals
            'nominals' => ['required', 'array', 'min:1'],
            'nominals.*.name' => ['required', 'string', 'max:255'],
            'nominals.*.provider_sku' => ['nullable', 'string', 'max:255'],
            'nominals.*.price' => ['required', 'numeric', 'min:0'],
            'nominals.*.discount_price' => ['nullable', 'numeric', 'min:0'],
            'nominals.*.cost_price' => ['required', 'numeric', 'min:0'],
            'nominals.*.margin' => ['required', 'numeric', 'min:0', 'max:100'],
            'nominals.*.stock' => ['required', 'integer', 'min:0'],
            'nominals.*.available_stock' => ['required', 'integer', 'min:0'],
            'nominals.*.stock_mode' => ['nullable', 'in:manual,provider'],
            'nominals.*.is_active' => ['nullable', 'boolean'],
            'nominals.*.order' => ['nullable', 'integer', 'min:0'],
        ];

        $validated = $request->validate($rules);

        // Validasi tambahan untuk single product
        if ($validated['type'] === 'single' && count($validated['nominals']) > 1) {
            return back()->withInput()->with('error', 'Produk single hanya boleh memiliki 1 nominal');
        }

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $name = $validated['name'] ?? 'Product from Digiflazz';
            $validated['slug'] = Str::slug($name);
        }

        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        DB::beginTransaction();

        try {
            // Get provider info
            $provider = Provider::findOrFail($validated['provider_id']);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $name = $validated['name'] ?? 'Digiflazz Product';
                $imagePath = $this->uploadImage($request->file('image'), $name);
            }

            // Prepare product data
            $productData = [
                'category_id' => $validated['category_id'],
                'name' => $validated['name'] ?? $provider->name . ' Product',
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'image' => $imagePath,
                'provider_id' => $provider->id,
                'provider_sku' => $validated['provider_sku'],
                'source' => 'digiflazz',
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured', false),
                'order' => $validated['order'] ?? 0,
            ];

            // Create product
            $product = Product::create($productData);

            // Create nominals
            $nominalData = [];
            foreach ($validated['nominals'] as $index => $nominal) {
                // Validasi discount_price < price
                if (isset($nominal['discount_price']) && $nominal['discount_price'] >= $nominal['price']) {
                    throw new \Exception('Harga diskon harus lebih kecil dari harga normal pada nominal: ' . $nominal['name']);
                }

                // Validasi available_stock <= stock
                if ($nominal['available_stock'] > $nominal['stock']) {
                    throw new \Exception('Stok tersedia tidak boleh lebih besar dari total stok pada nominal: ' . $nominal['name']);
                }

                $nominalData[] = [
                    'product_id' => $product->id,
                    'name' => $nominal['name'],
                    'provider_sku' => $nominal['provider_sku'] ?? $validated['provider_sku'],
                    'price' => $nominal['price'],
                    'discount_price' => $nominal['discount_price'] ?? null,
                    'cost_price' => $nominal['cost_price'],
                    'margin' => $nominal['margin'],
                    'stock' => $nominal['stock'],
                    'available_stock' => $nominal['available_stock'],
                    'stock_mode' => $nominal['stock_mode'] ?? 'provider',
                    'is_active' => isset($nominal['is_active']) ? (bool)$nominal['is_active'] : true,
                    'order' => $nominal['order'] ?? $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert semua nominals sekaligus
            DB::table('product_nominals')->insert($nominalData);

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Produk Digiflazz beserta nominals berhasil diimport.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus gambar jika ada error
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Gagal mengimport produk: ' . $e->getMessage()
                ]);
        }
    }
    /**
     * Upload image
     */
    private function uploadImage($file, $productName)
    {
        $filename = time() . '_' . Str::slug($productName) . '.' . $file->getClientOriginalExtension();
        $path = 'images/products';

        // Simpan di storage/public
        $file->storeAs($path, $filename, 'public');

        // Return path yang bisa diakses via asset()
        return 'storage/' . $path . '/' . $filename;
    }

    /**
     * Download image from URL
     */
    private function downloadImageFromUrl(string $url, string $productName): ?string
    {
        try {
            $imageData = file_get_contents($url);
            if ($imageData === false) {
                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $filename = time() . '_' . Str::slug($productName) . '.' . $extension;
            $path = 'images/products';

            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            // Simpan di storage
            Storage::disk('public')->put($path . '/' . $filename, $imageData);

            // Return path untuk asset()
            return 'storage/' . $path . '/' . $filename;
        } catch (\Exception $e) {
            Log::error('Image download error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get provider products for dropdown (AJAX)
     */
    public function getProviderProducts(Provider $provider)
    {
        try {
            // Cek auth
            if (!auth('admin')->check()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Ambil data dari provider_products
            $products = $provider->providerProducts()
                ->where('is_available', true)
                ->orderBy('name')
                ->select([
                    'id',
                    'provider_sku', // Kolom yang benar
                    'name',
                    'category',
                    'brand',
                    'provider_price', // Kolom yang benar
                    'details'
                ])
                ->get()
                ->map(function ($product) {
                    // Parse details JSON
                    $details = [];
                    if ($product->details && is_string($product->details)) {
                        try {
                            $details = json_decode($product->details, true);
                        } catch (\Exception $e) {
                            $details = [];
                        }
                    }

                    return [
                        'sku' => $product->provider_sku, // Mapping ke 'sku' untuk frontend
                        'name' => $product->name,
                        'category' => $product->category ?: 'Umum',
                        'brand' => $product->brand ?: '-',
                        'price' => (int) $product->provider_price, // Mapping ke 'price'
                        'description' => $details['description'] ?? null,
                        'details' => $details
                    ];
                })
                ->toArray();

            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Error loading provider products: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Gagal memuat produk. Silakan coba lagi.'
            ], 500);
        }
    }

    public function getAllDigiflazzProducts()
    {
        try {
            $cacheKey = 'digiflazz_all_products_v2';

            $products = Cache::remember($cacheKey, 300, function () {
                // Ambil semua provider yang Digiflazz
                $providers = Provider::where('is_active', true)
                    ->where('code', 'digiflazz')
                    ->pluck('id');

                if ($providers->isEmpty()) {
                    return [];
                }

                return ProviderProduct::whereIn('provider_id', $providers)
                    ->where('is_available', true)
                    ->select([
                        'provider_id',
                        'provider_sku as sku',
                        'name',
                        'category',
                        'brand',
                        'provider_price as price',
                        'description',
                        'details'
                    ])
                    ->orderBy('category')
                    ->orderBy('name')
                    ->limit(1000) // Batasi untuk performa
                    ->get()
                    ->map(function ($product) {
                        return [
                            'provider_id' => $product->provider_id,
                            'sku' => $product->sku,
                            'name' => $product->name ?: 'Tanpa Nama',
                            'category' => $product->category ?: 'Umum',
                            'brand' => $product->brand ?: '-',
                            'price' => (int) $product->price,
                            'description' => $product->description,
                            'details' => $product->details ? json_decode($product->details, true) : []
                        ];
                    })
                    ->toArray();
            });

            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Error loading all Digiflazz products: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'nominals' => function ($query) {
            $query->orderBy('order')->orderBy('name');
        }]);

        // Ambil statistik real untuk produk ini
        $stats = [
            'total_nominals' => $product->nominals()->count(),
            'total_vouchers' => VoucherCode::where('product_id', $product->id)->count(),
            'sold_vouchers' => VoucherCode::where('product_id', $product->id)
                ->where('status', 'sold')
                ->count(),
            'total_revenue' => VoucherCode::where('voucher_codes.product_id', $product->id)
                ->where('voucher_codes.status', 'sold')
                ->join('product_nominals', 'voucher_codes.product_nominal_id', '=', 'product_nominals.id')
                ->sum('product_nominals.price'),
        ];

        // Ambil recent voucher codes untuk produk ini
        $recentVouchers = VoucherCode::where('product_id', $product->id)
            ->with('productNominal')
            ->latest()
            ->take(5)
            ->get();

        return $this->view('products.show', compact('product', 'stats', 'recentVouchers'));
    }

    public function edit(Product $product)
    {
        // Load product with product_nominals ordered by order and name
        $product->load(['product_nominals' => function ($query) {
            $query->orderBy('order')
                ->orderBy('name')
                ->withCount(['voucherCodes as real_stock' => function ($q) {
                    $q->where('status', 'available');
                }]);
        }]);

        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();

        return $this->view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Validasi produk
        $productRules = [
            'category_id'  => ['required', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:150'],
            'slug'         => ['nullable', 'string', 'max:170', 'unique:products,slug,' . $product->id],
            'description'  => ['nullable', 'string'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'type'         => ['required', 'in:single,multiple'],
            'is_active'    => ['nullable', 'boolean'],
            'is_featured'  => ['nullable', 'boolean'],
            'order'        => ['nullable', 'integer', 'min:0'],
        ];

        // Validasi untuk product_nominals
        $nominalRules = [
            'nominals' => ['required', 'array', 'min:1'],
            'nominals.*.id' => ['nullable', 'exists:product_nominals,id,product_id,' . $product->id],
            'nominals.*.name' => ['required', 'string', 'max:255'],
            'nominals.*.provider_sku' => ['nullable', 'string', 'max:255'],
            'nominals.*.price' => ['required', 'numeric', 'min:0'],
            'nominals.*.discount_price' => ['nullable', 'numeric', 'min:0'],
            'nominals.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'nominals.*.margin' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nominals.*.stock_mode' => ['nullable', 'in:manual,provider'],
            'nominals.*.is_active' => ['nullable', 'boolean'],
            'nominals.*.order' => ['nullable', 'integer', 'min:0'],
        ];

        // Gabungkan semua rules
        $rules = array_merge($productRules, $nominalRules);
        $validated = $request->validate($rules);

        // Validasi tambahan untuk single product
        if ($validated['type'] === 'single' && count($validated['nominals']) > 1) {
            return back()->withInput()->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Produk single hanya boleh memiliki 1 nominal'
            ]);
        }

        // Handle image
        $imagePath = $product->image;

        if ($request->has('remove_image') && $request->remove_image == '1') {
            // Hapus gambar lama dari storage
            if ($product->image) {
                $oldImagePath = str_replace('storage/', '', $product->image);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }
            $imagePath = null;
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                $oldImagePath = str_replace('storage/', '', $product->image);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            // Upload gambar baru
            $imagePath = $this->uploadImage($request->file('image'), $validated['name']);
        }

        DB::beginTransaction();

        try {
            // Update product data
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'type' => $validated['type'],
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured', false),
                'order' => $validated['order'] ?? $product->order,
            ]);

            // Handle product_nominals
            $existingNominalIds = $product->product_nominals->pluck('id')->toArray();
            $updatedNominalIds = [];

            foreach ($validated['nominals'] as $index => $nominal) {
                // Validasi discount_price < price
                if (isset($nominal['discount_price']) && $nominal['discount_price'] >= $nominal['price']) {
                    throw new \Exception('Harga diskon harus lebih kecil dari harga normal pada nominal: ' . $nominal['name']);
                }

                $nominalData = [
                    'product_id' => $product->id,
                    'name' => $nominal['name'],
                    'provider_sku' => $nominal['provider_sku'] ?? null,
                    'price' => $nominal['price'],
                    'discount_price' => $nominal['discount_price'] ?? null,
                    'cost_price' => $nominal['cost_price'] ?? null,
                    'margin' => $nominal['margin'] ?? null,
                    'stock' => 0,
                    'available_stock' => 0,
                    'stock_mode' => $nominal['stock_mode'] ?? 'manual',
                    'is_active' => isset($nominal['is_active']) ? (bool)$nominal['is_active'] : true,
                    'order' => $nominal['order'] ?? $index,
                ];

                if (isset($nominal['id']) && in_array($nominal['id'], $existingNominalIds)) {
                    // Update existing nominal
                    $product->product_nominals()->where('id', $nominal['id'])->update($nominalData);
                    $updatedNominalIds[] = $nominal['id'];
                } else {
                    // Create new nominal
                    $newNominal = $product->product_nominals()->create($nominalData);
                    $updatedNominalIds[] = $newNominal->id;
                }
            }

            // Delete product_nominals yang tidak ada di updated list
            $nominalsToDelete = array_diff($existingNominalIds, $updatedNominalIds);
            if (!empty($nominalsToDelete)) {
                $product->product_nominals()->whereIn('id', $nominalsToDelete)->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Produk berhasil diperbarui.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Gagal memperbarui produk: ' . $e->getMessage()
                ]);
        }
    }


    public function destroy(Product $product)
    {
        // Cek apakah produk memiliki nominals atau voucher codes
        if ($product->nominals()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Tidak dapat menghapus produk yang masih memiliki nominals.'
                ]);
        }

        if ($product->voucherCodes()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Tidak dapat menghapus produk yang masih memiliki voucher codes.'
                ]);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Produk berhasil dihapus.'
            ]);
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status produk berhasil diubah',
            'is_active' => $product->is_active
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return response()->json([
            'success' => true,
            'message' => 'Featured status berhasil diubah',
            'is_featured' => $product->is_featured
        ]);
    }

    /**
     * Move product order up
     */
    public function orderUp(Product $product)
    {
        $prevProduct = Product::where('order', '<', $product->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($prevProduct) {
            $tempOrder = $product->order;
            $product->order = $prevProduct->order;
            $prevProduct->order = $tempOrder;

            $product->save();
            $prevProduct->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Move product order down
     */
    public function orderDown(Product $product)
    {
        $nextProduct = Product::where('order', '>', $product->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextProduct) {
            $tempOrder = $product->order;
            $product->order = $nextProduct->order;
            $nextProduct->order = $tempOrder;

            $product->save();
            $nextProduct->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk yang dipilih'
            ]);
        }

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Produk berhasil diaktifkan';
                break;

            case 'deactivate':
                Product::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Produk berhasil dinonaktifkan';
                break;

            case 'feature':
                Product::whereIn('id', $ids)->update(['is_featured' => true]);
                $message = 'Produk berhasil ditandai sebagai featured';
                break;

            case 'unfeature':
                Product::whereIn('id', $ids)->update(['is_featured' => false]);
                $message = 'Produk berhasil dihapus dari featured';
                break;

            case 'delete':
                // Check if any product has nominals or vouchers
                $productsWithDependencies = Product::whereIn('id', $ids)
                    ->where(function ($query) {
                        $query->has('nominals')
                            ->orHas('voucherCodes');
                    })
                    ->count();

                if ($productsWithDependencies > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Beberapa produk memiliki nominals atau voucher codes dan tidak dapat dihapus'
                    ]);
                }

                Product::whereIn('id', $ids)->delete();
                $message = 'Produk berhasil dihapus';
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi tidak valid'
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get price range for product
     */
    private function getPriceRange(Product $product)
    {
        if ($product->nominals()->exists()) {
            $minPrice = $product->nominals()->min('price');
            $maxPrice = $product->nominals()->max('price');

            return [
                'min' => $minPrice,
                'max' => $maxPrice,
                'formatted' => 'Rp ' . number_format($minPrice) . ' - Rp ' . number_format($maxPrice)
            ];
        }

        return null;
    }

    /**
     * Import all products from Digiflazz provider
     */
    public function importAllDigiflazz(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'margin' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:single,multiple',
            'auto_create_category' => 'nullable|boolean', // Optional flag
        ]);

        try {
            $provider = Provider::findOrFail($request->provider_id);

            // Check if provider is Digiflazz
            if ($provider->code !== 'digiflazz') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya provider Digiflazz yang didukung'
                ], 400);
            }

            // Get selected categories if any
            $selectedCategories = [];
            if ($request->has('selected_categories')) {
                $selectedCategories = json_decode($request->selected_categories, true) ?? [];
            }

            // Query products
            $productsQuery = ProviderProduct::where('provider_id', $provider->id)
                ->where('is_available', true);

            // Filter by selected categories if provided
            if (!empty($selectedCategories)) {
                $productsQuery->whereIn('category', $selectedCategories);
            }

            $products = $productsQuery->orderBy('brand')
                ->orderBy('provider_price')
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada produk tersedia di provider ini. Silakan sinkronisasi terlebih dahulu.'
                ], 400);
            }

            $importedCount = 0;
            $failedCount = 0;
            $failedProducts = [];
            $margin = floatval($request->margin);

            // Auto create categories tracking
            $autoCreatedCategories = [];
            $categoryOrder = 999; // Order untuk kategori baru

            DB::beginTransaction();

            if ($request->type === 'single') {
                // Import each product as single product
                foreach ($products as $product) {
                    try {
                        // Check if product already exists
                        $existingProduct = Product::where('provider_sku', $product->provider_sku)
                            ->where('provider_id', $provider->id)
                            ->first();

                        if ($existingProduct) {
                            $failedProducts[] = "{$product->name} - Sudah ada";
                            $failedCount++;
                            continue;
                        }

                        // Auto-create or find category
                        $category = null;
                        if ($request->auto_create_category && $product->category) {
                            $category = Category::findOrCreate(
                                $product->category,
                                $categoryOrder--
                            );
                            $autoCreatedCategories[$product->category] = true;
                        } else {
                            // Gunakan kategori yang dipilih atau default
                            if ($request->category_id) {
                                $category = Category::find($request->category_id);
                            }
                            if (!$category) {
                                $category = Category::findOrCreate('Digital Products', 0);
                            }
                        }

                        if (!$category) {
                            throw new \Exception('Kategori tidak ditemukan');
                        }

                        // Parse details for image URL
                        $details = [];
                        $imageUrl = null;
                        if ($product->details && is_string($product->details)) {
                            try {
                                $details = json_decode($product->details, true, 512, JSON_THROW_ON_ERROR);
                                // Get image URL from Digiflazz details
                                $imageUrl = $details['icon_url'] ??
                                    ($details['images'][0] ??
                                        ($details['icon'] ??
                                            ($details['image'] ?? null)));
                            } catch (\Exception $e) {
                                $details = [];
                            }
                        }

                        // Download image from Digiflazz
                        $imagePath = null;
                        if ($imageUrl) {
                            $imagePath = $this->downloadDigiflazzImage($imageUrl, $product->name);
                        }

                        // Generate unique slug
                        $baseSlug = Str::slug($product->name);
                        $slug = $baseSlug;
                        $counter = 1;
                        while (Product::where('slug', $slug)->exists()) {
                            $slug = $baseSlug . '-' . $counter;
                            $counter++;
                        }

                        // Calculate selling price
                        $costPrice = floatval($product->provider_price);
                        if ($margin >= 100) {
                            throw new \Exception('Margin tidak boleh 100% atau lebih');
                        }

                        if ($costPrice > 0 && $margin > 0) {
                            $sellingPrice = $costPrice / (1 - ($margin / 100));
                            $finalPrice = ceil($sellingPrice / 100) * 100;
                        } else {
                            $finalPrice = $costPrice;
                        }

                        // Create product
                        $newProduct = Product::create([
                            'category_id' => $category->id,
                            'name' => $product->name,
                            'slug' => $slug,
                            'description' => $product->description ?: ($details['description'] ?? "Produk {$product->name}"),
                            'type' => 'single',
                            'image' => $imagePath,
                            'provider_id' => $provider->id,
                            'provider_sku' => $product->provider_sku,
                            'source' => 'digiflazz',
                            'is_active' => true,
                            'is_featured' => false,
                            'order' => 0,
                            'meta_data' => $product->details ? json_encode($product->details) : null,
                        ]);

                        // Create single nominal
                        ProductNominal::create([
                            'product_id' => $newProduct->id,
                            'name' => $product->name,
                            'provider_sku' => $product->provider_sku,
                            'price' => $finalPrice,
                            'cost_price' => $costPrice,
                            'margin' => $margin,
                            'discount_price' => null,
                            'stock' => 0, // Atau null
                            'available_stock' => null, // NULL untuk provider!
                            'stock_mode' => 'provider', // Pastikan ini 'provider'
                            'is_active' => true,
                            'order' => 0,
                            'meta_data' => $product->details ? json_encode($product->details) : null,
                        ]);

                        $importedCount++;
                        Log::info("Imported product: {$product->name}, Category: {$category->name}");
                    } catch (\Exception $e) {
                        $failedProducts[] = "{$product->name} - " . substr($e->getMessage(), 0, 50);
                        $failedCount++;
                        Log::error("Failed to import product {$product->name}: " . $e->getMessage());
                    }
                }
            } else {
                // Import grouped by brand/category
                $groupedProducts = [];

                // Group products by brand and category
                foreach ($products as $product) {
                    $key = ($product->brand ?: 'default') . '|' . ($product->category ?: 'uncategorized');
                    if (!isset($groupedProducts[$key])) {
                        $groupedProducts[$key] = [];
                    }
                    $groupedProducts[$key][] = $product;
                }

                foreach ($groupedProducts as $key => $brandProducts) {
                    try {
                        list($brand, $categoryName) = explode('|', $key);

                        // Use first product as base
                        $baseProduct = $brandProducts[0];

                        // Auto-create or find category
                        $category = null;
                        if ($request->auto_create_category && $categoryName && $categoryName !== 'uncategorized') {
                            $category = Category::findOrCreate(
                                $categoryName,
                                $categoryOrder--
                            );
                            $autoCreatedCategories[$categoryName] = true;
                        } else {
                            // Gunakan kategori yang dipilih atau default
                            if ($request->category_id) {
                                $category = Category::find($request->category_id);
                            }
                            if (!$category) {
                                $category = Category::findOrCreate('Digital Products', 0);
                            }
                        }

                        if (!$category) {
                            throw new \Exception('Kategori tidak ditemukan');
                        }

                        // Generate product name
                        $productName = $brand !== 'default' ? $brand : $baseProduct->name;
                        if ($brand === 'default') {
                            // Try to extract brand from product name
                            if (preg_match('/(pulsa|data|paket|token)/i', $baseProduct->name)) {
                                $productName = $categoryName !== 'uncategorized' ? $categoryName : 'Produk Digital';
                            }
                        }

                        // Generate unique product name
                        $productName = trim($productName);
                        if (empty($productName)) {
                            $productName = 'Produk ' . $categoryName;
                        }

                        // Check if product already exists (by similar name)
                        $existingProduct = Product::where('name', 'like', "%{$productName}%")
                            ->where('provider_id', $provider->id)
                            ->where('category_id', $category->id)
                            ->first();

                        if ($existingProduct) {
                            $failedProducts[] = "{$productName} - Sudah ada";
                            $failedCount++;
                            continue;
                        }

                        // Get image from first product
                        $imagePath = null;
                        $details = [];
                        if ($baseProduct->details && is_string($baseProduct->details)) {
                            try {
                                $details = json_decode($baseProduct->details, true, 512, JSON_THROW_ON_ERROR);
                                $imageUrl = $details['icon_url'] ??
                                    ($details['images'][0] ??
                                        ($details['icon'] ??
                                            ($details['image'] ?? null)));
                                if ($imageUrl) {
                                    $imagePath = $this->downloadDigiflazzImage($imageUrl, $productName);
                                }
                            } catch (\Exception $e) {
                                $details = [];
                            }
                        }

                        // Generate unique slug
                        $baseSlug = Str::slug($productName);
                        $slug = $baseSlug;
                        $counter = 1;
                        while (Product::where('slug', $slug)->exists()) {
                            $slug = $baseSlug . '-' . $counter;
                            $counter++;
                        }

                        // Create product
                        $newProduct = Product::create([
                            'category_id' => $category->id,
                            'name' => $productName,
                            'slug' => $slug,
                            'description' => "Berbagai pilihan nominal {$productName}",
                            'type' => 'multiple',
                            'image' => $imagePath,
                            'provider_id' => $provider->id,
                            'provider_sku' => 'MULTIPLE-' . Str::slug($productName),
                            'source' => 'digiflazz',
                            'is_active' => true,
                            'is_featured' => false,
                            'order' => 0,
                            'meta_data' => null,
                        ]);

                        // Create multiple nominals
                        foreach ($brandProducts as $index => $product) {
                            $costPrice = floatval($product->provider_price);

                            if ($costPrice > 0 && $margin > 0) {
                                $sellingPrice = $costPrice / (1 - ($margin / 100));
                                $finalPrice = ceil($sellingPrice / 100) * 100;
                            } else {
                                $finalPrice = $costPrice;
                            }

                            ProductNominal::create([
                                'product_id' => $newProduct->id,
                                'name' => $product->name,
                                'provider_sku' => $product->provider_sku,
                                'price' => $finalPrice,
                                'cost_price' => $costPrice,
                                'margin' => $margin,
                                'discount_price' => null,
                                'stock' => 0, // Atau null
                                'available_stock' => null, // NULL untuk provider!
                                'stock_mode' => 'provider', // Pastikan ini 'provider'
                                'is_active' => true,
                                'order' => 0,
                                'meta_data' => $product->details ? json_encode($product->details) : null,
                            ]);
                        }

                        $importedCount++;
                        Log::info("Imported grouped product: {$productName}, Category: {$category->name}");
                    } catch (\Exception $e) {
                        $failedProducts[] = "{$key} - " . substr($e->getMessage(), 0, 50);
                        $failedCount++;
                        Log::error("Failed to import grouped products for {$key}: " . $e->getMessage());
                    }
                }
            }

            DB::commit();

            $message = "Berhasil mengimport {$importedCount} produk" .
                ($failedCount > 0 ? " ({$failedCount} gagal)" : "");

            // Tambahkan informasi kategori yang dibuat otomatis
            if (!empty($autoCreatedCategories)) {
                $message .= ". Kategori yang dibuat otomatis: " . implode(', ', array_keys($autoCreatedCategories));
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported_count' => $importedCount,
                'failed_count' => $failedCount,
                'auto_created_categories' => array_keys($autoCreatedCategories),
                'failed_products' => array_slice($failedProducts, 0, 10), // Limit to 10
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import all Digiflazz products failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport produk: ' . $e->getMessage()
            ], 500);
        }
    }

    private function downloadDigiflazzImage($imageUrl, $productName): ?string
    {
        try {
            if (empty($imageUrl)) {
                return null;
            }

            // Generate filename
            $filename = time() . '_' . Str::slug($productName) . '.png';
            $path = 'storage/images/products';

            // Create directory if not exists
            if (!file_exists(public_path('images/products'))) {
                mkdir(public_path('images/products'), 0777, true);
            }

            // Download image using cURL or file_get_contents
            $imageData = @file_get_contents($imageUrl);

            if ($imageData === false) {
                // Try with cURL if file_get_contents fails
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $imageUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                $imageData = curl_exec($ch);
                curl_close($ch);
            }

            if ($imageData === false) {
                Log::warning("Failed to download image from URL: {$imageUrl}");
                return null;
            }

            // Save image
            $fullPath = "{$path}/{$filename}";
            $savePath = public_path("images/products/{$filename}");

            if (file_put_contents($savePath, $imageData)) {
                return "images/products/{$filename}";
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Image download error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get categories from provider products
     */
    public function getProviderCategories(Provider $provider)
    {
        try {
            $categories = ProviderProduct::where('provider_id', $provider->id)
                ->whereNotNull('category')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category')
                ->map(function ($category) {
                    return [
                        'value' => $category,
                        'label' => ucfirst($category),
                    ];
                })
                ->values()
                ->toArray();

            return response()->json($categories);
        } catch (\Exception $e) {
            Log::error('Error loading provider categories: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}

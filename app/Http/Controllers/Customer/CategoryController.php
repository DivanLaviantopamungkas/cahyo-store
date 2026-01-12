<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductNominal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // GET /kategori
    public function index(Request $request)
    {
        $q    = trim((string) $request->query('q', ''));
        $sort = (string) $request->query('sort', 'default'); // default | az | za

        $categoriesQuery = Category::query()
            ->where('is_active', 1)
            ->withCount([
                'products as products_count' => function ($sub) {
                    $sub->where('is_active', 1);
                }
            ]);

        if ($q !== '') {
            $categoriesQuery->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        if ($sort === 'az') {
            $categoriesQuery->orderBy('name');
        } elseif ($sort === 'za') {
            $categoriesQuery->orderByDesc('name');
        } else {
            $categoriesQuery->orderBy('order');
        }

        $categories = $categoriesQuery->get(['id', 'name', 'slug', 'icon', 'color', 'order']);

        // Produk terlaris/featured
        $popularProducts = Product::query()
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('order')
            ->limit(8)
            ->get(['id', 'category_id', 'name', 'slug', 'image', 'type']);

        return view('customer.pages.category', compact('categories', 'popularProducts', 'q', 'sort'));
    }

    // GET /kategori/{slug}
    public function show(string $slug)
    {
        // 1. Cari kategori berdasarkan slug
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        // 2. Cari produk dalam kategori ini
        // Tentukan nama kolom yang benar
        $categoryColumn = Schema::hasColumn('products', 'category_id') ? 'category_id' : 'categoryid';

        $products = Product::query()
            ->where($categoryColumn, $category->id)
            ->where('is_active', 1)
            ->orderBy('is_featured', 'desc')
            ->orderBy('order')
            ->paginate(12);

        // 3. Hitung harga minimum untuk setiap produk
        $productIds = $products->pluck('id');

        $minPrices = ProductNominal::query()
            ->selectRaw('product_id, MIN(COALESCE(discount_price, price)) as min_price')
            ->whereIn('product_id', $productIds)
            ->where('is_active', 1)
            ->groupBy('product_id')
            ->pluck('min_price', 'product_id');

        // 4. Tambahkan harga minimum ke setiap produk
        $products->each(function ($product) use ($minPrices) {
            $product->min_price = (float) ($minPrices[$product->id] ?? 0);
        });

        // 5. Kembalikan view yang BENAR untuk kategori
        // BUKAN 'product-detail.blade.php' tapi 'category-show.blade.php'
        return view('customer.pages.category-show', [
            'category' => $category,
            'products' => $products
        ]);
    }

    // GET /kategori/{slug}/produk  (list produk per kategori)
    public function products(string $slug)
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $products = Product::query()
            ->where('is_active', 1)
            ->where('categoryid', $category->id)
            ->orderBy('order')
            ->paginate(24, ['id', 'category_id', 'name', 'slug', 'image', 'type']);

        return view('customer.pages.category-products', compact('category', 'products'));
    }
}

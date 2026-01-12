<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductNominal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    // Method untuk produk MANUAL (hanya nominal)
    public function showManual(string $slug)
    {
        return $this->getProductDetail($slug, 'manual');
    }

    // Method untuk produk DIGIFLAZZ (nomor + nominal)
    public function showDigiflazz(string $slug)
    {
        return $this->getProductDetail($slug, 'digiflazz');
    }

    /**
     * Helper method untuk mengambil data produk
     */
    private function getProductDetail(string $slug, string $source)
    {
        // Ambil produk berdasarkan slug dan source
        $product = Product::query()
            ->where('slug', $slug)
            ->where('source', $source)
            ->where('is_active', 1)
            ->firstOrFail();

        // Ambil nominals
        $nominals = ProductNominal::query()
            ->where('product_id', $product->id)
            ->where('is_active', 1)
            ->orderBy('order')
            ->get(['id', 'name', 'price', 'discount_price', 'available_stock']);

        // Ambil related products (maksimal 4 produk)
        $categoryColumn = $this->getCategoryColumnName();

        $relatedProducts = Product::query()
            ->where('is_active', 1)
            ->where($categoryColumn, $product->{$categoryColumn})
            ->where('id', '!=', $product->id)
            ->where('source', $source) // Hanya produk dengan source yang sama
            ->orderBy('order')
            ->limit(4)
            ->get(['id', 'name', 'slug', 'image', 'type', 'source']);

        // Hitung harga minimum untuk related products
        $minPrices = ProductNominal::query()
            ->selectRaw('product_id, MIN(COALESCE(discount_price, price)) as min_price')
            ->whereIn('product_id', $relatedProducts->pluck('id'))
            ->where('is_active', 1)
            ->groupBy('product_id')
            ->pluck('min_price', 'product_id');

        $relatedProducts->each(function ($product) use ($minPrices) {
            $product->min_price = (float) ($minPrices[$product->id] ?? 0);
        });

        // Tentukan view berdasarkan source
        $view = $source === 'digiflazz'
            ? 'customer.pages.digiflazz-detail'
            : 'customer.pages.manual-detail';

        return view($view, compact('product', 'nominals', 'relatedProducts'));
    }

    /**
     * Get category column name
     */
    private function getCategoryColumnName(): string
    {
        if (Schema::hasColumn('products', 'categoryid')) {
            return 'categoryid';
        }
        return 'category_id';
    }
}

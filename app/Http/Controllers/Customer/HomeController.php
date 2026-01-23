<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $categoriesWithProducts = Category::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->with(['products' => function ($query) {
                $query->where('is_active', 1)
                    ->orderBy('order');
            }])
            ->get();

        return view('customer.pages.home', [
            'categories' => $categoriesWithProducts,
        ]);
    }
}

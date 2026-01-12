<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerControllers extends Controller
{
    public function home()
    {
        return view('customer.pages.home');
    }

    public function category()
    {
        return view('customer.pages.category');
    }

    public function productDetail($slug)
    {
        return view('customer.pages.product-detail');
    }

    public function checkout()
    {
        return view('customer.pages.checkout');
    }

    public function orders()
    {
        return view('customer.pages.orders');
    }

    public function notifications()
    {
        return view('customer.pages.notifications');
    }

    public function help()
    {
        return view('customer.pages.help');
    }

    public function profile()
    {
        return view('customer.pages.profile');
    }
}

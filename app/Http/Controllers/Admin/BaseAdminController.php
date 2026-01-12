<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseAdminController extends Controller
{
    protected function view(string $path, array $data = [])
    {
        return view('admin.' . $path, $data);
    }
}

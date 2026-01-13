<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Trancsaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $transactions = Trancsaction::where('user_id', auth()->id())
            ->with(['items.product', 'items.nominal'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => Trancsaction::where('user_id', auth()->id())->count(),
            'pending' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'completed' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['completed', 'paid'])
                ->count(),
            'failed' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['cancelled', 'expired', 'failed'])
                ->count(),
            'total_spent' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['completed', 'paid'])
                ->sum('amount'),
        ];

        return view('customer.pages.orders', compact('transactions', 'stats'));
    }
}

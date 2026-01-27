<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Models\User;
use App\Models\VoucherCode;
use App\Models\Product;
use App\Models\ProductNominal;
use App\Models\Trancsaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        $stats = [
            'total_transactions' => Trancsaction::count(),
            'total_members'      => User::count(),
            'total_vouchers'     => VoucherCode::count(),
            'pending_orders'     => Trancsaction::whereIn('status', ['pending', 'processing'])->count(),
            'total_revenue'      => Trancsaction::where('status', 'completed')->sum('total_paid'),
        ];

        $period = request()->get('chart_period', '7');
        $chart = $this->getSalesChart($period);

        $recentTransactions = Trancsaction::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chart', 'recentTransactions'));
    }

    private function getSalesChart($period = '7')
    {
        $now = Carbon::now();
        
        if ($period == '30') {
            $startDate = $now->copy()->subDays(29)->startOfDay();
            $daysCount = 30;
        } elseif ($period == 'month') {
            $startDate = $now->copy()->startOfMonth();
            $daysCount = $now->day;
        } else {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $daysCount = 7;
        }

        $endDate = $now->copy()->endOfDay();

        $transactions = Trancsaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_paid) as total')
            ])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $values = [];
        $revenues = [];

        for ($i = $daysCount - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            $labels[] = $date->format('D, d M'); 
            
            if (isset($transactions[$dateStr])) {
                $values[] = (int)$transactions[$dateStr]->count;
                $revenues[] = (float)$transactions[$dateStr]->total;
            } else {
                $values[] = 0;
                $revenues[] = 0;
            }
        }

        return [
            'labels'             => $labels,
            'values'             => $values,
            'revenues'           => $revenues,
            'total_sales_amount' => array_sum($revenues),
            'total_tx_count'     => array_sum($values),
        ];
    }

    // Additional method for real-time updates (optional)
    public function chartData()
    {
        $period = request()->get('period', '7');
        $chart = $this->getSalesChart($period);
        
        return response()->json($chart);
    }
}
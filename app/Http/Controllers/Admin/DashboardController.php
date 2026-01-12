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
        // Total stats (with caching for better performance)
        $stats = [
            'total_transactions' => Trancsaction::count(),
            'total_members'      => User::count(),
            'total_vouchers'     => VoucherCode::count(),
            'pending_orders'     => Trancsaction::whereIn('status', ['pending', 'processing'])->count(),
            'total_revenue'      => Trancsaction::where('status', 'completed')->sum('total_paid'),
        ];

        // Chart data based on selected period
        $period = request()->get('chart_period', '7');
        $chart = $this->getSalesChart($period);

        // Recent transactions
        $recentTransactions = Trancsaction::with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        return $this->view('dashboard', compact('stats', 'chart', 'recentTransactions'));
    }

    private function getSalesChart($period = '7')
    {
        $now = Carbon::now();
        
        switch ($period) {
            case '30':
                $startDate = $now->copy()->subDays(29)->startOfDay();
                $groupBy = 'DATE(created_at)';
                $dateFormat = 'D';
                break;
                
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $groupBy = 'DATE(created_at)';
                $dateFormat = 'j M';
                break;
                
            default: // 7 days
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $groupBy = 'DATE(created_at)';
                $dateFormat = 'D';
                break;
        }

        $endDate = $now->copy()->endOfDay();

        // Get transaction data
        $transactions = Trancsaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select([
                DB::raw($groupBy . ' as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_paid) as total')
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Generate labels and values
        $labels = [];
        $values = [];
        $revenues = [];
        $maxValue = 1; // Minimum 1 to avoid division by zero

        $periodDays = $period == 'month' 
            ? $startDate->diffInDays($endDate) + 1 
            : ($period == '30' ? 30 : 7);

        for ($i = $periodDays - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            $labels[] = $date->format($dateFormat);
            
            if (isset($transactions[$dateStr])) {
                $transactionCount = $transactions[$dateStr]->count;
                $values[] = $transactionCount;
                $revenues[] = $transactions[$dateStr]->total;
                $maxValue = max($maxValue, $transactionCount);
            } else {
                $values[] = 0;
                $revenues[] = 0;
            }
        }

        // Normalize values for chart height
        $normalizedValues = array_map(function ($value) use ($maxValue) {
            return $maxValue > 0 ? round(($value / $maxValue) * 100) : 0;
        }, $values);

        return [
            'labels'            => $labels,
            'values'            => $values,
            'revenues'          => $revenues,
            'normalized_values' => $normalizedValues,
            'total_sales'       => $transactions->sum('total'),
            'total_transactions' => $transactions->sum('count'),
            'period'           => $period
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
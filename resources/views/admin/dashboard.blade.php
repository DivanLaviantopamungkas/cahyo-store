@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Analisis sistem hari ini')

@section('content')
    <div class="space-y-8 pb-12">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @php
                $statsItems = [
                    [
                        'label' => 'Transaksi', 
                        'value' => $stats['total_transactions'], 
                        'icon' => 'icon-credit-card', 
                        'color' => 'emerald', 
                        'bg' => 'from-emerald-500/20'
                    ],
                    [
                        'label' => 'Member', 
                        'value' => $stats['total_members'], 
                        'icon' => 'icon-users', 
                        'color' => 'violet', 
                        'bg' => 'from-violet-500/20'
                    ],
                    [
                        'label' => 'Voucher', 
                        'value' => $stats['total_vouchers'], 
                        'icon' => 'icon-ticket', 
                        'color' => 'rose', 
                        'bg' => 'from-rose-500/20'
                    ],
                    [
                        'label' => 'Pending', 
                        'value' => $stats['pending_orders'], 
                        'icon' => 'icon-clock', 
                        'color' => 'sky', 
                        'bg' => 'from-sky-500/20'
                    ],
                ];
            @endphp

            @foreach($statsItems as $item)
                <div class="group relative overflow-hidden bg-white dark:bg-slate-800 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-gradient-to-br {{ $item['bg'] }} to-transparent rounded-full blur-2xl"></div>
                    <div class="relative">
                        <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-{{ $item['color'] }}-50 dark:bg-{{ $item['color'] }}-900/30 text-{{ $item['color'] }}-600">
                            <svg class="w-7 h-7"><use href="#{{ $item['icon'] }}"></use></svg>
                        </div>
                        <div class="mt-4">
                            <p class="text-[10px] uppercase tracking-widest font-black text-slate-400">{{ $item['label'] }}</p>
                            <p class="text-3xl font-black text-slate-800 dark:text-white mt-1 leading-none">{{ number_format($item['value']) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
                <div class="p-8 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-xl text-slate-800 dark:text-white tracking-tight">Analisis Penjualan</h3>
                        <p class="text-xs text-slate-400 font-medium">Tren pendapatan toko anda</p>
                    </div>
                    <select class="text-xs font-bold rounded-2xl border-none bg-slate-100 dark:bg-slate-900 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 cursor-pointer"
                        onchange="window.location.href = '?chart_period=' + this.value">
                        <option value="7" {{ request('chart_period') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ request('chart_period') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="month" {{ request('chart_period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>

                <div class="px-8 pb-4 flex-1">
                    <div class="relative h-72 w-full">
                        <canvas id="salesChart"></canvas>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-slate-50 dark:border-slate-700/50">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Sales</span>
                            <span class="text-2xl font-black text-slate-800 dark:text-white">
                                {{ number_format($chart['total_tx_count']) }} <span class="text-xs font-medium text-slate-400">Pcs</span>
                            </span>
                        </div>
                        <div class="flex flex-col text-right">
                            <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Revenue</span>
                            <span class="text-2xl font-black text-emerald-600">
                                Rp{{ number_format($chart['total_sales_amount'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm flex flex-col overflow-hidden">
                <div class="p-8 border-b border-slate-50 dark:border-slate-700/50 flex items-center justify-between">
                    <h3 class="font-black text-lg text-slate-800 dark:text-white">Aktivitas</h3>
                    <a href="{{ route('admin.transactions.index') }}" class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-colors">
                        <svg class="w-5 h-5"><use href="#icon-chevron-right"></use></svg>
                    </a>
                </div>
                <div class="p-4 space-y-2 flex-1 overflow-y-auto max-h-[500px] scrollbar-hide">
                    @forelse($recentTransactions as $transaction)
                        <div class="flex items-center p-4 rounded-3xl hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-all border border-transparent active:scale-[0.98]">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center font-black text-slate-500 dark:text-slate-300">
                                {{ substr($transaction->user->name ?? 'G', 0, 1) }}
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-black text-slate-800 dark:text-white truncate max-w-[120px]">
                                    {{ $transaction->invoice_number ?? 'INV-' . $transaction->id }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-tight">
                                    {{ $transaction->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-slate-800 dark:text-white">
                                    Rp{{ number_format($transaction->total_paid/1000, 0) }}k
                                </p>
                                <div class="mt-1">
                                    <span class="text-[8px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest {{ $transaction->status == 'completed' || $transaction->status == 'paid' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                        {{ $transaction->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center text-slate-400 font-bold">Belum ada transaksi</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="font-black text-lg text-slate-800 dark:text-white px-2 tracking-tight">Navigasi Cepat</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $quickActions = [
                        [
                            'route' => 'admin.voucher-codes.create', 
                            'title' => 'Voucher', 
                            'subtitle' => 'Buat kode baru', 
                            'icon' => 'icon-ticket', 
                            'color' => 'emerald'
                        ],
                        [
                            'route' => 'admin.products.create.manual', 
                            'title' => 'Produk', 
                            'subtitle' => 'Input manual', 
                            'icon' => 'icon-shopping-bag', 
                            'color' => 'violet'
                        ],
                        [
                            'route' => 'admin.products.create.digiflazz', 
                            'title' => 'Digiflazz', 
                            'subtitle' => 'Import produk', 
                            'icon' => 'icon-credit-card', 
                            'color' => 'sky'
                        ],
                        [
                            'route' => 'admin.broadcasts.create', 
                            'title' => 'Broadcast', 
                            'subtitle' => 'Kirim pesan', 
                            'icon' => 'icon-megaphone', 
                            'color' => 'rose'
                        ],
                    ];
                @endphp

                @foreach($quickActions as $act)
                    <a href="{{ route($act['route']) }}" class="group bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm flex flex-col items-start space-y-4 transition-all hover:border-{{ $act['color'] }}-400 hover:shadow-lg active:scale-95">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $act['color'] }}-50 dark:bg-{{ $act['color'] }}-900/30 flex items-center justify-center text-{{ $act['color'] }}-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6"><use href="#{{ $act['icon'] }}"></use></svg>
                        </div>
                        <div>
                            <p class="font-black text-slate-800 dark:text-white leading-tight">{{ $act['title'] }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tight">{{ $act['subtitle'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { 
            display: none; 
        }

        .scrollbar-hide { 
            -ms-overflow-style: none; 
            scrollbar-width: none; 
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chart['labels']),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($chart['revenues']),
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: '#10b981',
                        borderWidth: 4,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { size: 12, weight: 'bold' },
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, weight: 'bold' },
                                color: '#94a3b8'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
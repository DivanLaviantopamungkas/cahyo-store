@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-admin.card
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-900/10 border-emerald-200 dark:border-emerald-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">Total Transaksi</p>
                        <p class="text-3xl font-bold text-emerald-800 dark:text-emerald-200 mt-2">
                            {{ number_format($stats['total_transactions']) }}
                        </p>
                    </div>
                    <div class="p-3 rounded-2xl bg-emerald-500/10">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400">
                            <use href="#icon-credit-card"></use>
                        </svg>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-violet-50 to-violet-100 dark:from-violet-900/20 dark:to-violet-900/10 border-violet-200 dark:border-violet-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-violet-700 dark:text-violet-300 font-medium">Total Member</p>
                        <p class="text-3xl font-bold text-violet-800 dark:text-violet-200 mt-2">
                            {{ number_format($stats['total_members']) }}
                        </p>
                    </div>
                    <div class="p-3 rounded-2xl bg-violet-500/10">
                        <svg class="w-8 h-8 text-violet-600 dark:text-violet-400">
                            <use href="#icon-users"></use>
                        </svg>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/20 dark:to-rose-900/10 border-rose-200 dark:border-rose-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-rose-700 dark:text-rose-300 font-medium">Total Voucher</p>
                        <p class="text-3xl font-bold text-rose-800 dark:text-rose-200 mt-2">
                            {{ number_format($stats['total_vouchers']) }}
                        </p>
                    </div>
                    <div class="p-3 rounded-2xl bg-rose-500/10">
                        <svg class="w-8 h-8 text-rose-600 dark:text-rose-400">
                            <use href="#icon-ticket"></use>
                        </svg>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-sky-50 to-sky-100 dark:from-sky-900/20 dark:to-sky-900/10 border-sky-200 dark:border-sky-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-sky-700 dark:text-sky-300 font-medium">Pending Orders</p>
                        <p class="text-3xl font-bold text-sky-800 dark:text-sky-200 mt-2">
                            {{ number_format($stats['pending_orders']) }}
                        </p>
                    </div>
                    <div class="p-3 rounded-2xl bg-sky-500/10">
                        <svg class="w-8 h-8 text-sky-600 dark:text-sky-400">
                            <use href="#icon-clock"></use>
                        </svg>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <!-- Chart & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sales Chart -->
            <x-admin.card class="lg:col-span-2">
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-slate-800 dark:text-white">Statistik Penjualan</h3>
                        <select
                            class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-transparent focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            x-data="{}" @change="window.location.href = '?chart_period=' + $event.target.value">
                            <option value="7" {{ request('chart_period', '7') == '7' ? 'selected' : '' }}>7 Hari
                                Terakhir</option>
                            <option value="30" {{ request('chart_period') == '30' ? 'selected' : '' }}>30 Hari Terakhir
                            </option>
                            <option value="month" {{ request('chart_period') == 'month' ? 'selected' : '' }}>Bulan Ini
                            </option>
                        </select>
                    </div>
                </x-slot>

                <div x-data="{
                    labels: {{ Illuminate\Support\Js::from($chart['labels']) }},
                    values: {{ Illuminate\Support\Js::from($chart['values']) }},
                    normalizedValues: {{ Illuminate\Support\Js::from($chart['normalized_values']) }},
                    hoveredIndex: null,
                    tooltipPosition: '50%'
                }" class="relative h-64">
                    <div class="absolute inset-0 flex items-end space-x-1">
                        <template x-for="(value, index) in values" :key="index">
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-3/4 bg-gradient-to-t from-emerald-500 to-emerald-400 rounded-t-lg transition-all duration-300 hover:opacity-90 cursor-pointer"
                                    :style="`height: ${normalizedValues[index]}%`"
                                    :class="{ 'opacity-90': hoveredIndex === index }"
                                    @mouseenter="hoveredIndex = index; tooltipPosition = `${(index / values.length) * 100}%`"
                                    @mouseleave="hoveredIndex = null"></div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-2" x-text="labels[index]"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Tooltip -->
                    <template x-if="hoveredIndex !== null">
                        <div class="absolute z-10 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-lg p-3 min-w-[120px]"
                            :style="`left: ${tooltipPosition}; transform: translateX(-50%); bottom: calc(70% + 10px)`">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-slate-800 dark:text-white"
                                    x-text="`${values[hoveredIndex].toLocaleString('id-ID')}`"></span>
                                <span class="text-xs text-slate-500 ml-2">transaksi</span>
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400" x-text="labels[hoveredIndex]"></div>
                            @if (isset($chart['revenues']))
                                <div class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1"
                                    x-text="`Rp ${new Intl.NumberFormat('id-ID').format({{ $chart['revenues'][0] }}[hoveredIndex] || 0)}`">
                                </div>
                            @endif
                        </div>
                    </template>

                    <!-- No Data Message -->
                    <template x-if="values.every(v => v === 0)">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p class="text-slate-500 dark:text-slate-400 mt-2">Tidak ada data transaksi</p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Chart Summary -->
                <div class="flex items-center justify-between mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total Transaksi</p>
                        <p class="text-2xl font-bold text-slate-800 dark:text-white">
                            {{ number_format($chart['total_transactions'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($chart['total_sales'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </x-admin.card>

            <!-- Recent Transactions -->
            <x-admin.card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-slate-800 dark:text-white">Transaksi Terbaru</h3>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $recentTransactions->count() }} transaksi
                        </span>
                    </div>
                </x-slot>

                <div class="space-y-4">
                    @forelse($recentTransactions as $transaction)
                        <div
                            class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div>
                                <p class="font-medium text-sm text-slate-800 dark:text-white">
                                    {{ $transaction->invoice_number ?? 'INV-' . $transaction->id }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ $transaction->user->name ?? 'Guest' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <x-admin.badge :color="$transaction->status == 'paid'
                                    ? 'green'
                                    : ($transaction->status == 'pending'
                                        ? 'yellow'
                                        : 'red')" size="sm">
                                    {{ ucfirst($transaction->status) }}
                                </x-admin.badge>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $transaction->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-slate-500 dark:text-slate-400 mt-2">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>

                <x-slot name="footer">
                    <a href="{{ route('admin.transactions.index') }}"
                        class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 text-sm font-medium flex items-center justify-center group">
                        Lihat semua transaksi
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform">
                            <use href="#icon-chevron-right"></use>
                        </svg>
                    </a>
                </x-slot>
            </x-admin.card>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.voucher-codes.create') }}"
                class="group p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div
                        class="p-2 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400">
                            <use href="#icon-plus"></use>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-slate-800 dark:text-white">Tambah Voucher</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Import atau buat manual</p>
                    </div>
                </div>
            </a>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Tambah Produk Manual -->
                <a href="{{ route('admin.products.create.manual') }}"
                    class="group p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center">
                        <div
                            class="p-2 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400">
                                <use href="#icon-edit"></use>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-slate-800 dark:text-white">Tambah Manual</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Buat produk dengan nominal sendiri
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Import dari Digiflazz -->
                <a href="{{ route('admin.products.create.digiflazz') }}"
                    class="group p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center">
                        <div
                            class="p-2 rounded-xl bg-blue-100 dark:bg-blue-900/30 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400">
                                <use href="#icon-download"></use>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-slate-800 dark:text-white">Import Digiflazz</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Import produk dari provider
                                Digiflazz</p>
                        </div>
                    </div>
                </a>
            </div>

            <a href="{{ route('admin.broadcasts.create') }}"
                class="group p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-rose-300 dark:hover:border-rose-600 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div
                        class="p-2 rounded-xl bg-rose-100 dark:bg-rose-900/30 group-hover:bg-rose-200 dark:group-hover:bg-rose-800/50 transition-colors">
                        <svg class="w-6 h-6 text-rose-600 dark:text-rose-400">
                            <use href="#icon-megaphone"></use>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-slate-800 dark:text-white">Buat Broadcast</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kirim pesan ke member</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

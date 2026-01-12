@extends('admin.layouts.app')

@section('title', 'Transaksi')
@section('breadcrumb', 'Semua Transaksi')

@section('content')
    <div class="space-y-4 md:space-y-6">
        <!-- Search & Filter -->
        <x-admin.card>
            <div class="flex flex-col gap-4">
                <form method="GET" action="{{ route('admin.transactions.index') }}" class="w-full">
                    <div class="flex flex-col md:flex-row gap-3 md:items-center">
                        <!-- Search Input -->
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400">
                                    <use href="#icon-magnifying-glass"></use>
                                </svg>
                            </div>
                            <input type="search" name="q" value="{{ $search }}"
                                placeholder="Cari invoice atau nama customer..."
                                class="block w-full pl-10 pr-4 py-2 rounded-xl md:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm md:text-base">
                        </div>

                        <!-- Filter Controls -->
                        <div class="flex flex-col sm:flex-row gap-2 md:items-center">
                            <select name="status"
                                class="w-full sm:w-auto text-sm rounded-xl md:rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-3 py-2">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processing
                                </option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="flex-1 sm:flex-none px-4 py-2 rounded-xl md:rounded-2xl bg-emerald-500 text-white font-medium hover:bg-emerald-600 transition-colors text-sm md:text-base">
                                    <span class="hidden sm:inline">Filter</span>
                                    <svg class="w-5 h-5 sm:hidden mx-auto">
                                        <use href="#icon-funnel"></use>
                                    </svg>
                                </button>

                                @if ($status || $search)
                                    <a href="{{ route('admin.transactions.index') }}"
                                        class="flex-1 sm:flex-none px-4 py-2 rounded-xl md:rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors text-sm md:text-base text-center">
                                        <span class="hidden sm:inline">Reset</span>
                                        <svg class="w-5 h-5 sm:hidden mx-auto">
                                            <use href="#icon-arrow-path"></use>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </x-admin.card>

        <!-- Status Tabs -->
        <div class="overflow-x-auto pb-2 -mx-4 px-4">
            <div class="flex space-x-2 min-w-max">
                @php
                    // Jika query count terlalu berat, bisa di-cache atau dihitung di controller
                    try {
                        $statusCounts = [
                            'all' => App\Models\Trancsaction::count(),
                            'pending' => App\Models\Trancsaction::where('status', 'pending')->count(),
                            'paid' => App\Models\Trancsaction::where('status', 'paid')->count(),
                            'processing' => App\Models\Trancsaction::where('status', 'processing')->count(),
                            'completed' => App\Models\Trancsaction::where('status', 'completed')->count(),
                            'expired' => App\Models\Trancsaction::where('status', 'expired')->count(),
                            'cancelled' => App\Models\Trancsaction::where('status', 'cancelled')->count(),
                        ];
                    } catch (Exception $e) {
                        $statusCounts = [
                            'all' => 0,
                            'pending' => 0,
                            'paid' => 0,
                            'processing' => 0,
                            'completed' => 0,
                            'expired' => 0,
                            'cancelled' => 0,
                        ];
                    }
                @endphp

                <a href="{{ route('admin.transactions.index') }}"
                    class="px-3 py-2 rounded-xl font-medium whitespace-nowrap transition-colors text-sm md:text-base md:rounded-2xl md:px-4 {{ !$status ? 'bg-emerald-500 text-white' : 'border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    Semua ({{ $statusCounts['all'] }})
                </a>

                @foreach (['pending', 'paid', 'processing', 'completed', 'expired', 'cancelled'] as $tabStatus)
                    <a href="{{ route('admin.transactions.index', ['status' => $tabStatus]) }}"
                        class="px-3 py-2 rounded-xl font-medium whitespace-nowrap transition-colors text-sm md:text-base md:rounded-2xl md:px-4 {{ $status === $tabStatus ? 'bg-emerald-500 text-white' : 'border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                        {{ ucfirst($tabStatus) }} ({{ $statusCounts[$tabStatus] }})
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Transactions Table -->
        <x-admin.card class="!p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap md:px-6 md:py-3">
                                Invoice</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap md:px-6 md:py-3">
                                Customer</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap md:px-6 md:py-3">
                                Produk</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap md:px-6 md:py-3">
                                Total</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap md:px-6 md:py-3">
                                Status</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap md:px-6 md:py-3">
                                Tanggal</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap md:px-6 md:py-3">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($transactions as $transaction)
                            @php
                                $statusColors = [
                                    'pending' => 'yellow',
                                    'paid' => 'blue',
                                    'processing' => 'purple',
                                    'completed' => 'green',
                                    'expired' => 'gray',
                                    'cancelled' => 'red',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <!-- Invoice Column -->
                                <td class="px-4 py-3 whitespace-nowrap md:px-6 md:py-4">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $transaction->invoice }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            {{ $transaction->payment_method ?? 'N/A' }}</div>
                                    </div>
                                </td>

                                <!-- Customer Column -->
                                <td class="px-4 py-3 whitespace-nowrap md:px-6 md:py-4">
                                    <div class="flex flex-col">
                                        <div
                                            class="text-sm text-slate-900 dark:text-white truncate max-w-[120px] md:max-w-none">
                                            {{ $transaction->user->name ?? 'Guest' }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            {{ $transaction->user->phone ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Product Column -->
                                <td class="px-4 py-3 md:px-6 md:py-4">
                                    <div class="flex flex-col">
                                        @if ($transaction->items && $transaction->items->isNotEmpty())
                                            <div
                                                class="text-sm text-slate-900 dark:text-white truncate max-w-[100px] md:max-w-[150px]">
                                                {{ $transaction->items->first()->product->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                {{ $transaction->items->first()->nominal->name ?? ($transaction->items->first()->voucherCode->code ?? '') }}
                                            </div>
                                        @else
                                            <div class="text-sm text-slate-500">No items</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Total Column -->
                                <td class="px-4 py-3 whitespace-nowrap md:px-6 md:py-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-4 py-3 whitespace-nowrap md:px-6 md:py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($transaction->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                    @case('paid') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                    @case('processing') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                                    @case('completed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 @break
                                    @case('expired') bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-200 @break
                                    @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                    @default bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-200
                                @endswitch">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>

                                <!-- Date Column -->
                                <td class="px-4 py-3 whitespace-nowrap md:px-6 md:py-4">
                                    <div class="flex flex-col">
                                        <div class="text-sm text-slate-900 dark:text-white">
                                            {{ $transaction->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $transaction->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-4 py-3 whitespace-nowrap md:px-6 md:py-4">
                                    <div class="flex items-center space-x-1 md:space-x-2">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}"
                                            class="p-1.5 rounded-lg text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 hover:bg-sky-50 dark:hover:bg-sky-900/30 transition-colors"
                                            title="Detail">
                                            <svg class="w-4 h-4 md:w-5 md:h-5">
                                                <use href="#icon-eye"></use>
                                            </svg>
                                        </a>

                                        @if ($transaction->status == 'paid')
                                            <form action="{{ route('admin.transactions.mark-processing', $transaction) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="p-1.5 rounded-lg text-xs text-violet-600 dark:text-violet-400 hover:text-violet-800 dark:hover:text-violet-300 hover:bg-violet-50 dark:hover:bg-violet-900/30 transition-colors"
                                                    title="Mark as Processing"
                                                    onclick="return confirm('Set transaksi ke processing?')">
                                                    <span class="hidden md:inline">Processing</span>
                                                    <svg class="w-4 h-4 md:hidden">
                                                        <use href="#icon-play"></use>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($transaction->status == 'processing')
                                            <form action="{{ route('admin.transactions.mark-completed', $transaction) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="p-1.5 rounded-lg text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors"
                                                    title="Mark as Completed"
                                                    onclick="return confirm('Set transaksi ke completed?')">
                                                    <span class="hidden md:inline">Complete</span>
                                                    <svg class="w-4 h-4 md:hidden">
                                                        <use href="#icon-check"></use>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center md:px-6 md:py-12">
                                    <div class="text-slate-400 dark:text-slate-500">
                                        <svg class="w-12 h-12 mx-auto mb-3 md:mb-4 opacity-50">
                                            <use href="#icon-document-text"></use>
                                        </svg>
                                        <p class="text-base md:text-lg font-medium">Tidak ada transaksi ditemukan</p>
                                        @if ($status || $search)
                                            <p class="mt-1 md:mt-2 text-sm">Coba dengan filter yang berbeda</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        <!-- Pagination -->
        @if ($transactions->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center sm:text-left">
                    Menampilkan
                    <span class="font-medium">{{ $transactions->firstItem() ?? 0 }}</span>
                    hingga
                    <span class="font-medium">{{ $transactions->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-medium">{{ $transactions->total() }}</span>
                    transaksi
                </p>

                <div class="flex items-center space-x-1">
                    @if ($transactions->onFirstPage())
                        <span
                            class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-400 dark:text-slate-500 text-sm cursor-not-allowed">Sebelumnya</span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}"
                            class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm transition-colors">Sebelumnya</a>
                    @endif

                    <div class="flex items-center space-x-1">
                        @foreach ($transactions->getUrlRange(1, min(5, $transactions->lastPage())) as $page => $url)
                            @if ($page == $transactions->currentPage())
                                <span
                                    class="px-3 py-1.5 rounded-xl bg-emerald-500 text-white text-sm font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($transactions->lastPage() > 5)
                            <span class="px-2 text-slate-500">...</span>
                            <a href="{{ $transactions->url($transactions->lastPage()) }}"
                                class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm transition-colors">{{ $transactions->lastPage() }}</a>
                        @endif
                    </div>

                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}"
                            class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm transition-colors">Selanjutnya</a>
                    @else
                        <span
                            class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-400 dark:text-slate-500 text-sm cursor-not-allowed">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @elseif($transactions->total() > 0)
            <div class="text-center">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Menampilkan semua {{ $transactions->total() }} transaksi
                </p>
            </div>
        @endif
    </div>
@endsection

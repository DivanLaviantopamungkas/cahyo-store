@extends('admin.layouts.app')

@section('title', 'Voucher Codes')
@section('breadcrumb', 'Semua Voucher Codes')

@section('actions')
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.voucher-codes.create') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-4 h-4 mr-2">
                <use href="#icon-plus"></use>
            </svg>
            Tambah Voucher
        </a>
        <a href="{{ route('admin.voucher-codes.import') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl border border-violet-300 dark:border-violet-600 text-violet-600 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/20 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2">
                <use href="#icon-arrow-down-tray"></use>
            </svg>
            Import
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Search & Filter -->
        <x-admin.card>
            <form action="{{ route('admin.voucher-codes.index') }}" method="GET" id="filterForm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400">
                                <use href="#icon-magnifying-glass"></use>
                            </svg>
                        </div>
                        <input type="search" name="q" value="{{ old('q', $search) }}"
                            placeholder="Cari voucher code..."
                            class="block w-full pl-10 pr-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <!-- Product Filter -->
                        <select name="product_id"
                            class="w-full sm:w-auto text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent py-2">
                            <option value="">Semua Produk</option>
                            @if (isset($products) && $products->count() > 0)
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                        <!-- Status Filter -->
                        <select name="status"
                            class="w-full sm:w-auto text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent py-2">
                            <option value="">Semua Status</option>
                            <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="reserved" {{ $status == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            <option value="sold" {{ $status == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="expired" {{ $status == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>

                        <div class="flex items-center space-x-2">
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-medium transition-colors whitespace-nowrap">
                                Filter
                            </button>

                            @if ($search || $status || $productId)
                                <a href="{{ route('admin.voucher-codes.index') }}"
                                    class="px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-2xl font-medium transition-colors whitespace-nowrap">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </x-admin.card>

        <!-- Stats - Gunakan $stats dari controller -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-admin.card
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-900/10 border-emerald-200 dark:border-emerald-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-emerald-800 dark:text-emerald-200">{{ $stats['available'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium mt-1">Available</p>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-900/10 border-amber-200 dark:border-amber-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-amber-800 dark:text-amber-200">{{ $stats['reserved'] ?? 0 }}</p>
                    <p class="text-sm text-amber-700 dark:text-amber-300 font-medium mt-1">Reserved</p>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-violet-50 to-violet-100 dark:from-violet-900/20 dark:to-violet-900/10 border-violet-200 dark:border-violet-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-violet-800 dark:text-violet-200">{{ $stats['sold'] ?? 0 }}</p>
                    <p class="text-sm text-violet-700 dark:text-violet-300 font-medium mt-1">Sold</p>
                </div>
            </x-admin.card>

            <x-admin.card
                class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/20 dark:to-slate-900/10 border-slate-200 dark:border-slate-800">
                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-800 dark:text-slate-200">{{ $stats['expired'] ?? 0 }}</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium mt-1">Expired</p>
                </div>
            </x-admin.card>
        </div>

        <!-- Voucher Codes Table -->
        <x-admin.card class="overflow-hidden">
            <div class="overflow-x-auto -mx-6 md:mx-0">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Produk & Nominal
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Code & Secret
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Expired
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($voucherCodes as $voucher)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $voucher->product->name ?? '-' }}
                                    </div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">
                                        {{ $voucher->nominal->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-sm font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                                        {{ $voucher->code }}
                                    </code>
                                    @if ($voucher->secret)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                            Secret: {{ $voucher->secret }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($voucher->status == 'available')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200">
                                            Available
                                        </span>
                                    @elseif($voucher->status == 'reserved')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200">
                                            Reserved
                                        </span>
                                    @elseif($voucher->status == 'sold')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 dark:bg-violet-900/30 text-violet-800 dark:text-violet-200">
                                            Sold
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-200">
                                            Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        @if ($voucher->expired_at)
                                            {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y') }}
                                            @if (\Carbon\Carbon::parse($voucher->expired_at)->isPast())
                                                <span
                                                    class="text-xs text-rose-600 dark:text-rose-400 block">(Expired)</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.voucher-codes.show', $voucher) }}"
                                            class="text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 p-1 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/30"
                                            title="Detail">
                                            <svg class="w-5 h-5">
                                                <use href="#icon-eye"></use>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.voucher-codes.edit', $voucher) }}"
                                            class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 p-1 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/30"
                                            title="Edit">
                                            <svg class="w-5 h-5">
                                                <use href="#icon-pencil"></use>
                                            </svg>
                                        </a>

                                        @if ($voucher->status == 'available')
                                            <form action="{{ route('admin.voucher-codes.reserve', $voucher) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 p-1 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/30"
                                                    title="Reserve">
                                                    <svg class="w-5 h-5">
                                                        <use href="#icon-clock"></use>
                                                    </svg>
                                                </button>
                                            </form>
                                        @elseif($voucher->status == 'reserved')
                                            <form action="{{ route('admin.voucher-codes.unreserve', $voucher) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 p-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/30"
                                                    title="Unreserve">
                                                    <svg class="w-5 h-5">
                                                        <use href="#icon-x-circle"></use>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.voucher-codes.destroy', $voucher) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 p-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/30"
                                                title="Hapus">
                                                <svg class="w-5 h-5">
                                                    <use href="#icon-trash"></use>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto">
                                            <use href="#icon-ticket"></use>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-slate-700 dark:text-slate-300">
                                            Belum ada voucher codes
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            Mulai dengan membuat atau import voucher codes.
                                        </p>
                                        <div
                                            class="mt-6 flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                                            <a href="{{ route('admin.voucher-codes.create') }}"
                                                class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                                <svg class="w-4 h-4 mr-2">
                                                    <use href="#icon-plus"></use>
                                                </svg>
                                                Tambah Voucher
                                            </a>
                                            <a href="{{ route('admin.voucher-codes.import') }}"
                                                class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                                                <svg class="w-4 h-4 mr-2">
                                                    <use href="#icon-arrow-down-tray"></use>
                                                </svg>
                                                Import
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($voucherCodes->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <!-- Jumlah Data -->
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            @if ($voucherCodes->total() > 0)
                                Menampilkan
                                <span
                                    class="font-medium text-slate-700 dark:text-slate-300">{{ $voucherCodes->firstItem() }}</span>
                                hingga
                                <span
                                    class="font-medium text-slate-700 dark:text-slate-300">{{ $voucherCodes->lastItem() }}</span>
                                dari total
                                <span
                                    class="font-medium text-slate-700 dark:text-slate-300">{{ $voucherCodes->total() }}</span>
                                voucher
                            @endif
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center space-x-2">
                            {{ $voucherCodes->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </x-admin.card>
    </div>

    @push('scripts')
        <script>
            // Auto submit form dengan debounce
            let searchTimeout;
            const searchInput = document.querySelector('input[name="q"]');
            const filterForm = document.getElementById('filterForm');

            if (searchInput && filterForm) {
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500);
                });
            }

            // Auto submit saat select berubah
            const selects = filterForm.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        </script>
    @endpush
@endsection

@extends('admin.layouts.app')

@section('title', 'Voucher Code Detail')
@section('breadcrumb')
    <a href="{{ route('admin.voucher-codes.index') }}">Voucher Codes</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Detail</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        @if($voucherCode->status == 'available')
        <form action="{{ route('admin.voucher-codes.reserve', $voucherCode->id) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                Reserve
            </button>
        </form>
        @elseif($voucherCode->status == 'reserved')
        <form action="{{ route('admin.voucher-codes.unreserve', $voucherCode->id) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                Unreserve
            </button>
        </form>
        @endif

        <a href="{{ route('admin.voucher-codes.edit', $voucherCode->id) }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit
        </a>
        <a href="{{ route('admin.voucher-codes.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Voucher Info -->
    <div class="lg:col-span-2">
        <x-admin.card>
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Voucher Code</h2>
                        <div class="mt-2">
                            <code class="text-xl font-mono bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-xl">{{ $voucherCode->code }}</code>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'available' => 'green',
                                'reserved' => 'yellow',
                                'sold' => 'violet',
                                'expired' => 'gray'
                            ];
                        @endphp
                        <x-admin.badge :color="$statusColors[$voucherCode->status]" size="lg">
                            {{ ucfirst($voucherCode->status) }}
                        </x-admin.badge>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Produk</p>
                        <p class="text-lg font-bold text-slate-800 dark:text-white mt-2">Game Online</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kategori: Game</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Nominal</p>
                        <p class="text-lg font-bold text-slate-800 dark:text-white mt-2">100 Diamond</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Rp 100,000</p>
                    </div>
                </div>

                <!-- Voucher Details -->
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                    <h3 class="font-semibold text-emerald-800 dark:text-emerald-300 mb-4">Informasi Voucher</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Secret</span>
                            <code class="font-mono text-emerald-800 dark:text-emerald-300">{{ $voucherCode->secret ?: '—' }}</code>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Expired At</span>
                            <span class="text-emerald-800 dark:text-emerald-300">
                                {{ $voucherCode->expired_at ? $voucherCode->expired_at->format('d M Y, H:i') : 'Tidak ada' }}
                            </span>
                        </div>
                        @if($voucherCode->sold_to)
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Sold To</span>
                            <span class="text-emerald-800 dark:text-emerald-300">Member #{{ $voucherCode->sold_to }}</span>
                        </div>
                        @endif
                        @if($voucherCode->sold_at)
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Sold At</span>
                            <span class="text-emerald-800 dark:text-emerald-300">{{ $voucherCode->sold_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>

    <!-- Actions & History -->
    <div class="space-y-6">
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Aksi</h3>
            <div class="space-y-3">
                @if($voucherCode->status == 'available')
                <form action="{{ route('admin.voucher-codes.reserve', $voucherCode->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                        Reserve Voucher
                    </button>
                </form>
                @elseif($voucherCode->status == 'reserved')
                <form action="{{ route('admin.voucher-codes.unreserve', $voucherCode->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                        Unreserve Voucher
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.voucher-codes.edit', $voucherCode->id) }}" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-emerald-300 dark:border-emerald-600 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-pencil"></use></svg>
                    Edit Voucher
                </a>

                <form action="{{ route('admin.voucher-codes.destroy', $voucherCode->id) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-rose-300 dark:border-rose-600 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2"><use href="#icon-trash"></use></svg>
                        Hapus Voucher
                    </button>
                </form>
            </div>
        </x-admin.card>

        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dibuat</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $voucherCode->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Terakhir Diupdate</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $voucherCode->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </x-admin.card>

        @if($voucherCode->status == 'sold')
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi Penjualan</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Invoice</p>
                    <p class="text-sm text-slate-800 dark:text-white">INV-20240001</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Member</p>
                    <p class="text-sm text-slate-800 dark:text-white">John Doe</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Harga</p>
                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Rp 100,000</p>
                </div>@extends('admin.layouts.app')

@section('title', 'Voucher Code Detail')
@section('breadcrumb')
    <a href="{{ route('admin.voucher-codes.index') }}">Voucher Codes</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Detail</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        @if($voucherCode->status == 'available')
        <form action="{{ route('admin.voucher-codes.reserve', $voucherCode) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                Reserve
            </button>
        </form>
        @elseif($voucherCode->status == 'reserved')
        <form action="{{ route('admin.voucher-codes.unreserve', $voucherCode) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                Unreserve
            </button>
        </form>
        @endif

        <a href="{{ route('admin.voucher-codes.edit', $voucherCode) }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit
        </a>
        <a href="{{ route('admin.voucher-codes.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-arrow-left"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Voucher Info -->
    <div class="lg:col-span-2">
        <x-admin.card>
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Voucher Code</h2>
                        <div class="mt-2">
                            <code class="text-xl font-mono bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-xl">{{ $voucherCode->code }}</code>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'available' => 'green',
                                'reserved' => 'yellow',
                                'sold' => 'violet',
                                'expired' => 'gray'
                            ];
                        @endphp
                        <x-admin.badge :color="$statusColors[$voucherCode->status]" size="lg">
                            {{ ucfirst($voucherCode->status) }}
                        </x-admin.badge>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Produk</p>
                        <p class="text-lg font-bold text-slate-800 dark:text-white mt-2">
                            {{ $voucherCode->product->name ?? '-' }}
                        </p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            ID: {{ $voucherCode->product_id }}
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Nominal</p>
                        <p class="text-lg font-bold text-slate-800 dark:text-white mt-2">
                            {{ $voucherCode->nominal->name ?? '-' }}
                        </p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            ID: {{ $voucherCode->product_nominal_id ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Voucher Details -->
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                    <h3 class="font-semibold text-emerald-800 dark:text-emerald-300 mb-4">Informasi Voucher</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Secret</span>
                            <code class="font-mono text-emerald-800 dark:text-emerald-300">
                                {{ $voucherCode->secret ?: '—' }}
                            </code>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Expired At</span>
                            <span class="text-emerald-800 dark:text-emerald-300">
                                @if($voucherCode->expired_at)
                                    {{ \Carbon\Carbon::parse($voucherCode->expired_at)->format('d M Y, H:i') }}
                                    @if(\Carbon\Carbon::parse($voucherCode->expired_at)->isPast())
                                        <span class="text-xs text-rose-600 dark:text-rose-400 ml-2">(Expired)</span>
                                    @endif
                                @else
                                    Tidak ada
                                @endif
                            </span>
                        </div>
                        @if($voucherCode->soldTo)
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Sold To</span>
                            <span class="text-emerald-800 dark:text-emerald-300">
                                @if($voucherCode->soldTo->name)
                                    {{ $voucherCode->soldTo->name }}
                                @else
                                    Member #{{ $voucherCode->sold_to }}
                                @endif
                            </span>
                        </div>
                        @endif
                        @if($voucherCode->sold_at)
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-700 dark:text-emerald-400">Sold At</span>
                            <span class="text-emerald-800 dark:text-emerald-300">
                                {{ \Carbon\Carbon::parse($voucherCode->sold_at)->format('d M Y, H:i') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>

    <!-- Actions & History -->
    <div class="space-y-6">
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Aksi</h3>
            <div class="space-y-3">
                @if($voucherCode->status == 'available')
                <form action="{{ route('admin.voucher-codes.reserve', $voucherCode) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                        Reserve Voucher
                    </button>
                </form>
                @elseif($voucherCode->status == 'reserved')
                <form action="{{ route('admin.voucher-codes.unreserve', $voucherCode) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                        Unreserve Voucher
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.voucher-codes.edit', $voucherCode) }}" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-emerald-300 dark:border-emerald-600 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-pencil"></use></svg>
                    Edit Voucher
                </a>

                <form action="{{ route('admin.voucher-codes.destroy', $voucherCode) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-rose-300 dark:border-rose-600 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2"><use href="#icon-trash"></use></svg>
                        Hapus Voucher
                    </button>
                </form>
            </div>
        </x-admin.card>

        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dibuat</p>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ $voucherCode->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Terakhir Diupdate</p>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ $voucherCode->updated_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">ID</p>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ $voucherCode->id }}
                    </p>
                </div>
            </div>
        </x-admin.card>

        @if($voucherCode->status == 'sold' && $voucherCode->soldTo)
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi Penjualan</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dibeli Oleh</p>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ $voucherCode->soldTo->name ?? 'Member #' . $voucherCode->sold_to }}
                    </p>
                </div>
                @if($voucherCode->soldTo->email)
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Email</p>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ $voucherCode->soldTo->email }}
                    </p>
                </div>
                @endif
                @if($voucherCode->soldTo->phone)
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Telepon</p>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ $voucherCode->soldTo->phone }}
                    </p>
                </div>
                @endif
                @if($voucherCode->sold_at)
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tanggal Penjualan</p>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($voucherCode->sold_at)->format('d M Y, H:i') }}
                    </p>
                </div>
                @endif
            </div>
        </x-admin.card>
        @endif
    </div>
</div>
@endsection
            </div>
        </x-admin.card>
        @endif
    </div>
</div>
@endsection

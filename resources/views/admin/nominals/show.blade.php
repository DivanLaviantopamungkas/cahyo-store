@extends('admin.layouts.app')

@section('title', $nominal->name)
@section('breadcrumb')
    <a href="{{ route('admin.nominals.index') }}">Nominals</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <a href="{{ route('admin.products.show', $nominal->product_id) }}">{{ $nominal->product->name }}</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Detail</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.nominals.edit', $nominal->id) }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit
        </a>
        <a href="{{ route('admin.nominals.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Nominal Info -->
    <div class="lg:col-span-2">
        <x-admin.card>
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $nominal->name }}</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Produk: {{ $nominal->product->name }} - {{ $nominal->product->category->name }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-admin.badge :color="$nominal->is_active ? 'green' : 'red'" size="sm">
                            {{ $nominal->is_active ? 'Aktif' : 'Nonaktif' }}
                        </x-admin.badge>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                        <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">Harga Normal</p>
                        <p class="text-3xl font-bold text-emerald-800 dark:text-emerald-200 mt-2">Rp {{ number_format($nominal->price) }}</p>
                    </div>

                    @if($nominal->discount_price && $nominal->discount_price > 0)
                    <div class="p-4 rounded-2xl bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800">
                        <p class="text-sm text-violet-700 dark:text-violet-300 font-medium">Harga Diskon</p>
                        <p class="text-3xl font-bold text-violet-800 dark:text-violet-200 mt-2">Rp {{ number_format($nominal->discount_price) }}</p>
                        @if($nominal->price > $nominal->discount_price)
                        <p class="text-xs text-violet-600 dark:text-violet-400 mt-1">
                            Hemat Rp {{ number_format($nominal->price - $nominal->discount_price) }}
                            ({{ round(($nominal->price - $nominal->discount_price) / $nominal->price * 100) }}%)
                        </p>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Stock Info -->
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                    <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi Stok</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Total Stok</p>
                            <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $nominal->stock }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Stok Tersedia</p>
                            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $nominal->available_stock }}</p>
                        </div>
                    </div>

                    <!-- Stock Progress -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400 mb-1">
                            <span>Penggunaan Stok</span>
                            @if($nominal->stock > 0)
                            <span>{{ round(($nominal->stock - $nominal->available_stock) / $nominal->stock * 100) }}%</span>
                            @else
                            <span>0%</span>
                            @endif
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            @if($nominal->stock > 0)
                            <div
                                class="bg-emerald-500 h-2 rounded-full"
                                style="width: {{ ($nominal->stock - $nominal->available_stock) / $nominal->stock * 100 }}%"
                            ></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card>

        <!-- Voucher Codes -->
        <x-admin.card class="mt-6">
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800 dark:text-white">Voucher Codes Terbaru</h3>
                    <a href="{{ route('admin.voucher-codes.create') }}?product_nominal={{ $nominal->id }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium">
                        <svg class="w-4 h-4 inline mr-1"><use href="#icon-plus"></use></svg>
                        Tambah
                    </a>
                </div>
            </x-slot>

            @if($nominal->voucherCodes->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-3"><use href="#icon-document-text"></use></svg>
                    <p class="text-slate-500 dark:text-slate-400">Belum ada voucher code untuk nominal ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Expired</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($nominal->voucherCodes as $voucher)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-4 py-3">
                                    <code class="text-sm font-mono text-slate-800 dark:text-white">{{ $voucher->code }}</code>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $badgeColors = [
                                            'available' => 'green',
                                            'reserved' => 'yellow',
                                            'sold' => 'blue',
                                            'expired' => 'red'
                                        ];
                                        $statusLabels = [
                                            'available' => 'Available',
                                            'reserved' => 'Reserved',
                                            'sold' => 'Sold',
                                            'expired' => 'Expired'
                                        ];
                                    @endphp
                                    <x-admin.badge :color="$badgeColors[$voucher->status] ?? 'gray'" size="sm">
                                        {{ $statusLabels[$voucher->status] ?? $voucher->status }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-800 dark:text-white">
                                    {{ $voucher->expired_at ? $voucher->expired_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-2">
                                        @if($voucher->status === 'available')
                                        <form action="{{ route('admin.voucher-codes.reserve', $voucher->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300">
                                                Reserve
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('admin.voucher-codes.show', $voucher->id) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-admin.card>
    </div>

    <!-- Stats & Actions -->
    <div class="space-y-6">
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Statistik</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Total Voucher</span>
                    <span class="font-semibold text-slate-800 dark:text-white">{{ $stats['total_vouchers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Available</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $stats['available_vouchers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Reserved</span>
                    <span class="font-semibold text-slate-800 dark:text-white">{{ $stats['reserved_vouchers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Sold</span>
                    <span class="font-semibold text-slate-800 dark:text-white">{{ $stats['sold_vouchers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Expired</span>
                    <span class="font-semibold text-slate-800 dark:text-white">{{ $stats['expired_vouchers'] }}</span>
                </div>
                <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Total Revenue</span>
                        <span class="font-bold text-lg text-emerald-600 dark:text-emerald-400">Rp {{ number_format($stats['total_revenue']) }}</span>
                    </div>
                </div>
            </div>
        </x-admin.card>

        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Produk</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $nominal->product->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Kategori</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $nominal->product->category->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Urutan</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $nominal->order }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dibuat</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $nominal->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Terakhir Diupdate</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $nominal->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </x-admin.card>

        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.voucher-codes.create') }}?product_nominal={{ $nominal->id }}" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-plus"></use></svg>
                    Tambah Voucher
                </a>
                <a href="{{ route('admin.nominals.edit', $nominal->id) }}" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-pencil"></use></svg>
                    Edit Nominal
                </a>
                <form action="{{ route('admin.nominals.toggle-status', $nominal->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-4 py-3 rounded-2xl border {{ $nominal->is_active ? 'border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' : 'border-emerald-300 dark:border-emerald-700 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20' }} font-medium transition-colors">
                        <svg class="w-5 h-5 inline mr-2"><use href="#icon-{{ $nominal->is_active ? 'x' : 'check' }}"></use></svg>
                        {{ $nominal->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </x-admin.card>
    </div>
</div>
@endsection

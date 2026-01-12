@extends('admin.layouts.app')

@section('title', $product->name)
@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}">Produk</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Detail</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-arrow-left"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Product Info -->
    <div class="lg:col-span-2">
        <x-admin.card>
            <div class="flex flex-col md:flex-row md:items-start space-y-6 md:space-y-0 md:space-x-6">
                <!-- Product Image -->
                <div class="flex-shrink-0">
                    @if($product->image && file_exists(public_path($product->image)))
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-48 h-48 object-cover rounded-2xl">
                    @else
                    <div class="w-48 h-48 rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white opacity-80"><use href="#icon-shopping-bag"></use></svg>
                        @if($product->image && !file_exists(public_path($product->image)))
                        <div class="absolute bottom-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                            File tidak ditemukan
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $product->name }}</h2>
                            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ $product->slug }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($product->is_featured)
                            <x-admin.badge color="yellow" size="sm">Featured</x-admin.badge>
                            @endif
                            <x-admin.badge :color="$product->is_active ? 'green' : 'red'" size="sm">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </x-admin.badge>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mt-4">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Kategori</p>
                        <div class="flex items-center mt-1">
                            <div class="p-2 rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400"><use href="#icon-tag"></use></svg>
                            </div>
                            <span class="ml-2 text-slate-800 dark:text-white">
                                {{ $product->category->name ?? 'Tidak ada kategori' }}
                            </span>
                        </div>
                    </div>

                    <!-- Product Type -->
                    <div class="mt-4">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Tipe Produk</p>
                        <x-admin.badge color="blue" size="sm" class="mt-1">
                            {{ $product->type == 'single' ? 'Single' : 'Multiple' }}
                        </x-admin.badge>
                    </div>

                    <!-- Price Range -->
                    <div class="mt-6">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Range Harga</p>
                        <div class="mt-2">
                            @if($product->nominals->count() > 0)
                                @php
                                    $minPrice = $product->nominals->min('price');
                                    $maxPrice = $product->nominals->max('price');
                                @endphp
                                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($minPrice) }} - Rp {{ number_format($maxPrice) }}
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    {{ $product->nominals->count() }} nominal tersedia
                                </p>
                            @else
                                <p class="text-lg text-slate-400 dark:text-slate-500">—</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    Belum ada nominal
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($product->description)
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                <h3 class="font-semibold text-slate-800 dark:text-white mb-3">Deskripsi</h3>
                <p class="text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $product->description }}</p>
            </div>
            @endif
        </x-admin.card>

        <!-- Recent Voucher Codes -->
        @if(isset($recentVouchers) && $recentVouchers->count() > 0)
        <x-admin.card class="mt-6">
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800 dark:text-white">Voucher Terbaru</h3>
                    <a href="{{ route('admin.voucher-codes.index') }}?product={{ $product->id }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium">
                        Lihat semua
                        <svg class="w-4 h-4 inline ml-1"><use href="#icon-chevron-right"></use></svg>
                    </a>
                </div>
            </x-slot>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($recentVouchers as $voucher)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-4 py-3 text-sm text-slate-800 dark:text-white font-mono">{{ $voucher->code }}</td>
                            <td class="px-4 py-3 text-sm text-slate-800 dark:text-white">
                                @if($voucher->productNominal)
                                    Rp {{ number_format($voucher->productNominal->price) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'available' => 'green',
                                        'sold' => 'emerald',
                                        'used' => 'blue',
                                        'expired' => 'red',
                                        'blocked' => 'rose'
                                    ];
                                    $statusLabels = [
                                        'available' => 'Tersedia',
                                        'sold' => 'Terjual',
                                        'used' => 'Terpakai',
                                        'expired' => 'Kadaluarsa',
                                        'blocked' => 'Diblokir'
                                    ];
                                @endphp
                                <x-admin.badge :color="$statusColors[$voucher->status] ?? 'gray'" size="sm">
                                    {{ $statusLabels[$voucher->status] ?? $voucher->status }}
                                </x-admin.badge>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-800 dark:text-white">{{ $voucher->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-admin.card>
        @endif
    </div>

    <!-- Product Stats & Actions -->
    <div class="space-y-6">
        <!-- Statistics Card -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Statistik</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Total Nominal</span>
                    <span class="font-semibold text-slate-800 dark:text-white">{{ $stats['total_nominals'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Total Voucher</span>
                    <span class="font-semibold text-slate-800 dark:text-white">{{ $stats['total_vouchers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Voucher Terjual</span>
                    <span class="font-semibold text-slate-800 dark:text-white">{{ $stats['sold_vouchers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Pendapatan</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($stats['total_revenue']) }}</span>
                </div>
            </div>
        </x-admin.card>

        <!-- Product Info Card -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi Produk</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Kategori</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $product->category->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tipe</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $product->type == 'single' ? 'Single' : 'Multiple' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Urutan</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $product->order }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Status</p>
                    <div class="flex items-center mt-1">
                        @if($product->is_active)
                            <x-admin.badge color="green" size="sm">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge color="red" size="sm">Nonaktif</x-admin.badge>
                        @endif
                        @if($product->is_featured)
                            <x-admin.badge color="yellow" size="sm" class="ml-2">Featured</x-admin.badge>
                        @endif
                    </div>
                </div>
            </div>
        </x-admin.card>

        <!-- Timestamps Card -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Timestamp</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dibuat</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $product->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Terakhir Diupdate</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </x-admin.card>

        <!-- Quick Actions Card -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.voucher-codes.create') }}?product={{ $product->id }}" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-ticket"></use></svg>
                    Tambah Voucher
                </a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-pencil"></use></svg>
                    Edit Produk
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-rose-300 dark:border-rose-600 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2"><use href="#icon-trash"></use></svg>
                        Hapus Produk
                    </button>
                </form>
            </div>
        </x-admin.card>
    </div>
</div>
@endsection
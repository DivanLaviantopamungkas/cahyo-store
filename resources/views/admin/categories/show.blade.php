@extends('admin.layouts.app')

@section('title', $category->name)
@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}">Kategori</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Detail</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit
        </a>
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2 transform rotate-180"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Category Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Category Details -->
        <x-admin.card>
            <div class="flex flex-col md:flex-row md:items-start gap-6">
                <!-- Icon/Color Display -->
                <div class="flex-shrink-0">
                    <div class="p-6 rounded-2xl flex items-center justify-center" 
                         style="background-color: {{ $category->color ? $category->color . '20' : '#10b98120' }}; color: {{ $category->color ?: '#10b981' }};">
                        @if($category->icon)
                        <div class="text-4xl">{{ $category->icon }}</div>
                        @else
                        <svg class="w-16 h-16"><use href="#icon-tag"></use></svg>
                        @endif
                    </div>
                </div>
                
                <!-- Category Info -->
                <div class="flex-1">
                    <!-- Header -->
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $category->name }}</h1>
                            <div class="flex items-center mt-2 space-x-4">
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    <span class="font-mono">/{{ $category->slug }}</span>
                                </div>
                                <div class="flex items-center">
                                    @if($category->is_active)
                                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></div>
                                        Aktif
                                    </div>
                                    @else
                                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></div>
                                        Nonaktif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="p-2 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                                        title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if($category->is_active)
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400">
                                        <use href="#icon-eye-slash"></use>
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                                        <use href="#icon-eye"></use>
                                    </svg>
                                    @endif
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.categories.destroy', $category) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300">
                                    <svg class="w-5 h-5">
                                        <use href="#icon-trash"></use>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    @if($category->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Deskripsi</h3>
                        <p class="text-slate-600 dark:text-slate-400">{{ $category->description }}</p>
                    </div>
                    @endif
                    
                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="bg-slate-50 dark:bg-slate-800/30 rounded-xl p-4">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Urutan</div>
                            <div class="text-xl font-bold text-slate-800 dark:text-white">{{ $category->order }}</div>
                        </div>
                        
                        <div class="bg-slate-50 dark:bg-slate-800/30 rounded-xl p-4">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Warna</div>
                            <div class="flex items-center space-x-2">
                                <div class="h-6 w-6 rounded-full border-2 border-slate-300 dark:border-slate-600" 
                                     style="background-color: {{ $category->color ?: '#10b981' }};"></div>
                                <span class="text-sm font-medium text-slate-800 dark:text-white font-mono">
                                    {{ $category->color ?: '#10b981' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 dark:bg-slate-800/30 rounded-xl p-4">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Icon</div>
                            <div class="text-xl font-medium text-slate-800 dark:text-white">
                                {{ $category->icon ?: 'Tidak ada' }}
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 dark:bg-slate-800/30 rounded-xl p-4">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">ID</div>
                            <div class="text-sm font-mono text-slate-800 dark:text-white">{{ $category->id }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.card>

        <!-- Products in this Category -->
        <x-admin.card>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800 dark:text-white">Produk dalam Kategori</h3>
                    <span class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $products->count() }} produk
                    </span>
                </div>
            </x-slot>
            
            @if($products->count() > 0)
            <div class="space-y-3">
                @foreach($products as $product)
                <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                    <div class="flex items-center space-x-4">
                        <!-- Product Icon/Image -->
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center flex-shrink-0">
                            @if($product->icon)
                            <span class="text-xl text-white">{{ $product->icon }}</span>
                            @else
                            <svg class="w-6 h-6 text-white"><use href="#icon-gift"></use></svg>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div>
                            <div class="flex items-center space-x-2">
                                <p class="font-medium text-slate-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    {{ $product->name }}
                                </p>
                                @if(!$product->is_active)
                                <span class="text-xs px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-full">
                                                    Nonaktif
                                                </span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-4 mt-1">
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    @if($product->nominal_count > 0)
                                    {{ $product->nominal_count }} nominal
                                    @else
                                    Belum ada nominal
                                    @endif
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ $product->total_vouchers }} voucher
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ $product->sold_vouchers }} terjual
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Product Status -->
                        @if($product->is_active)
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></div>
                            Aktif
                        </div>
                        @else
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></div>
                            Nonaktif
                        </div>
                        @endif
                        
                        <!-- View Product -->
                        <a href="{{ route('admin.products.show', $product) }}" 
                           class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors"
                           title="Lihat Detail">
                            <svg class="w-5 h-5">
                                <use href="#icon-chevron-right"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Belum ada produk
                </h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6">
                    Tambahkan produk pertama dalam kategori ini
                </p>
                <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                   class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-colors">
                    <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                    Tambah Produk
                </a>
            </div>
            @endif
            
            @if($products->count() > 0)
            <x-slot name="footer">
                <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" 
                   class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 text-sm font-medium flex items-center justify-center group">
                    Lihat semua produk ({{ $stats['total_products'] }})
                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform">
                        <use href="#icon-chevron-right"></use>
                    </svg>
                </a>
            </x-slot>
            @endif
        </x-admin.card>
    </div>

    <!-- Category Stats & Actions -->
    <div class="space-y-6">
        <!-- Statistics Card -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-6">Statistik</h3>
            <div class="space-y-5">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Total Produk</span>
                        <span class="text-xl font-bold text-slate-800 dark:text-white">{{ $stats['total_products'] }}</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" 
                             style="width: {{ $stats['total_products'] > 0 ? '100%' : '0%' }}"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Produk Aktif</span>
                        <span class="text-xl font-bold text-slate-800 dark:text-white">{{ $stats['active_products'] }}</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" 
                             style="width: {{ $stats['total_products'] > 0 ? ($stats['active_products'] / $stats['total_products'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Total Voucher</span>
                        <span class="text-xl font-bold text-slate-800 dark:text-white">{{ $stats['total_vouchers'] }}</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-violet-500 h-2 rounded-full" 
                             style="width: {{ $stats['total_vouchers'] > 0 ? min(100, $stats['total_vouchers'] / 10) : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Terjual Bulan Ini</span>
                        <span class="text-xl font-bold text-slate-800 dark:text-white">{{ $stats['sold_this_month'] }}</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" 
                             style="width: {{ $stats['total_vouchers'] > 0 ? ($stats['sold_this_month'] / $stats['total_vouchers'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </x-admin.card>

        <!-- Quick Actions -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.products.create.manual') }}?category={{ $category->id }}" 
                   class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 group">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform">
                        <use href="#icon-plus-circle"></use>
                    </svg>
                    Tambah Produk
                </a>
                
                <a href="{{ route('admin.categories.edit', $category) }}" 
                   class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors group">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform">
                        <use href="#icon-pencil-square"></use>
                    </svg>
                    Edit Kategori
                </a>
                
                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform">
                            <use href="{{ $category->is_active ? '#icon-eye-slash' : '#icon-eye' }}"></use>
                        </svg>
                        {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </x-admin.card>

        <!-- Category Information -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi Kategori</h3>
            <div class="space-y-4">
                <div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Dibuat</div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-slate-400 mr-2"><use href="#icon-calendar"></use></svg>
                        <div class="text-sm text-slate-800 dark:text-white">
                            {{ $category->created_at->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-6">
                        {{ $category->created_at->format('H:i') }} WIB
                    </div>
                </div>
                
                <div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Terakhir Diupdate</div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-slate-400 mr-2"><use href="#icon-clock"></use></svg>
                        <div class="text-sm text-slate-800 dark:text-white">
                            {{ $category->updated_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-6">
                        {{ $category->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
                
                @if($category->description)
                <div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Deskripsi</div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-3">
                        {{ $category->description }}
                    </p>
                </div>
                @endif
            </div>
        </x-admin.card>

        <!-- Danger Zone -->
        <x-admin.card class="border border-rose-200 dark:border-rose-800/50 bg-rose-50 dark:bg-rose-900/10">
            <h3 class="font-semibold text-rose-800 dark:text-rose-300 mb-4">Zona Berbahaya</h3>
            <p class="text-sm text-rose-600 dark:text-rose-400 mb-4">
                Aksi ini tidak dapat dibatalkan. Hapus kategori hanya jika sudah tidak digunakan.
            </p>
            
            @if($stats['total_products'] > 0)
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 mb-4">
                <div class="flex items-center text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0"><use href="#icon-exclamation-triangle"></use></svg>
                    <div class="text-sm">
                        <div class="font-medium">Kategori memiliki {{ $stats['total_products'] }} produk</div>
                        <div class="text-xs mt-1">Hapus atau pindahkan produk terlebih dahulu sebelum menghapus kategori</div>
                    </div>
                </div>
            </div>
            @endif
            
            <form action="{{ route('admin.categories.destroy', $category) }}" 
                  method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Aksi ini tidak dapat dibatalkan.')"
                  class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        @if($stats['total_products'] > 0) disabled @endif
                        class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed @if($stats['total_products'] == 0) hover:shadow-xl @endif">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-trash"></use></svg>
                    Hapus Kategori
                </button>
            </form>
        </x-admin.card>
    </div>
</div>
@endsection

@push('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
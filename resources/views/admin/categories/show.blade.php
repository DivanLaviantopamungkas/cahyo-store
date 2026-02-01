@extends('admin.layouts.app')

@section('title', $category->name)

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}" class="hover:text-emerald-500 transition-colors">Kategori</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300 italic">Informasi Detail</span>
@endsection

@section('actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.edit', $category) }}" 
           class="inline-flex items-center px-6 py-2.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit Kategori
        </a>

        <a href="{{ route('admin.categories.index') }}" 
           class="inline-flex items-center px-6 py-2.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 shadow-sm">
            <svg class="w-4 h-4 mr-2 transform rotate-180"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-24">
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden group">
                <div class="p-8 lg:p-10 flex flex-col md:flex-row items-center md:items-start gap-8">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-[2.5rem] flex items-center justify-center text-5xl shadow-2xl transition-transform group-hover:rotate-12 duration-500" 
                            style="background-color: {{ $category->color ? $category->color . '15' : '#10b98115' }}; 
                                    border: 2px solid {{ $category->color ? $category->color . '30' : '#10b98130' }};">
                            @if($category->icon)
                                {{ $category->icon }}
                            @else
                                <svg class="w-16 h-16 opacity-40" style="color: {{ $category->color ?: '#10b981' }}"><use href="#icon-tag"></use></svg>
                            @endif
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-700 flex items-center justify-center shadow-lg">
                            <span class="text-xs font-black text-emerald-500">#{{ $category->order }}</span>
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">{{ $category->name }}</h1>
                                <div class="flex items-center justify-center md:justify-start gap-4 mt-3">
                                    <span class="px-4 py-1 rounded-full bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">
                                        /{{ $category->slug }}
                                    </span>
                                    @if($category->is_active)
                                        <span class="flex items-center px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></div> Aktif
                                        </span>
                                    @else
                                        <span class="flex items-center px-3 py-1 rounded-full bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest border border-rose-500/20">
                                            <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-2"></div> Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-3 rounded-2xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-emerald-500 hover:text-white transition-all shadow-sm active:scale-90" title="Toggle Status">
                                        <svg class="w-5 h-5"><use href="{{ $category->is_active ? '#icon-eye-slash' : '#icon-eye' }}"></use></svg>
                                    </button>
                                </form>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="p-3 rounded-2xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-sky-500 hover:text-white transition-all shadow-sm active:scale-90" title="Edit">
                                    <svg class="w-5 h-5"><use href="#icon-pencil-square"></use></svg>
                                </a>
                            </div>
                        </div>

                        @if($category->description)
                            <div class="mt-8">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi</p>
                                <div class="p-6 rounded-[1.8rem] bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 text-sm text-slate-500 dark:text-slate-400 leading-relaxed shadow-inner">
                                    "{{ $category->description }}"
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 border-t border-slate-50 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/30 font-bold text-slate-700 dark:text-white">
                    <div class="p-6 text-center border-r border-slate-50 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Produk</p>
                        <p class="text-sm uppercase tracking-tight">{{ $stats['total_products'] }}</p>
                    </div>
                    <div class="p-6 text-center border-r border-slate-50 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Color Code</p>
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $category->color ?: '#10b981' }}"></div>
                            <p class="text-sm font-mono uppercase tracking-tight">{{ $category->color ?: '#10B981' }}</p>
                        </div>
                    </div>
                    <div class="p-6 text-center border-r border-slate-50 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Urutan Menu</p>
                        <p class="text-sm uppercase tracking-tight">{{ $category->order }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">ID</p>
                        <p class="text-sm font-mono uppercase tracking-tight">{{ $category->id }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-violet-500/10 text-violet-500 flex items-center justify-center">
                            <svg class="w-6 h-6"><use href="#icon-gift"></use></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Katalog Produk</h3>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase bg-white dark:bg-slate-800 px-3 py-1 rounded-full border border-slate-100 dark:border-slate-700 shadow-sm">
                        {{ $products->count() }} Terdaftar
                    </span>
                </div>
                
                <div class="p-4 space-y-3">
                    @forelse($products as $product)
                        <div class="flex flex-col md:flex-row items-center justify-between p-5 rounded-[2rem] hover:bg-slate-50 dark:hover:bg-slate-900/50 border border-transparent hover:border-slate-100 dark:hover:border-slate-700 transition-all group">
                            <div class="flex items-center gap-5 w-full md:w-auto">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform duration-500">
                                    {{ $product->icon ?: '📦' }}
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-3">
                                        <h4 class="font-black text-slate-800 dark:text-white uppercase text-sm tracking-tight group-hover:text-emerald-500 transition-colors">
                                            {{ $product->name }}
                                        </h4>
                                        @if(!$product->is_active)
                                            <span class="px-2 py-0.5 rounded-lg bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-widest border border-rose-500/10">Off</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2">
                                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            <svg class="w-3 h-3 mr-1 opacity-50"><use href="#icon-list-bullet"></use></svg>
                                            {{ $product->nominal_count }} Nominal
                                        </div>
                                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            <svg class="w-3 h-3 mr-1 opacity-50"><use href="#icon-ticket"></use></svg>
                                            {{ $product->total_vouchers }} Voucher
                                        </div>
                                        <div class="flex items-center text-[10px] font-bold text-emerald-500/70 uppercase tracking-widest">
                                            <svg class="w-3 h-3 mr-1"><use href="#icon-check-circle"></use></svg>
                                            {{ $product->sold_vouchers }} Terjual
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 mt-4 md:mt-0 w-full md:w-auto justify-between md:justify-end">
                                <div class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $product->is_active ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-slate-500/10 text-slate-500 border border-slate-500/20' }}">
                                    {{ $product->is_active ? 'Status: Aktif' : 'Status: Nonaktif' }}
                                </div>
                                <a href="{{ route('admin.products.show', $product) }}" 
                                class="w-10 h-10 rounded-full flex items-center justify-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-emerald-500 hover:border-emerald-500 shadow-sm transition-all active:scale-90">
                                    <svg class="w-5 h-5"><use href="#icon-chevron-right"></use></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-300 dark:text-slate-600"><use href="#icon-photo"></use></svg>
                            </div>
                            <h3 class="text-base font-black text-slate-400 uppercase tracking-widest mb-8">Belum ada produk</h3>
                            <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                            class="inline-flex items-center px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg> Tambah Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
                
                @if($products->count() > 0)
                    <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-50 dark:border-slate-700">
                        <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" 
                        class="flex items-center justify-center gap-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-emerald-500 transition-colors group">
                            Eksplor Semua ({{ $stats['total_products'] }}) Produk
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-2"><use href="#icon-arrow-right"></use></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] mb-8">Statistik Kategori</h3>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Produk</span>
                            <span class="text-xs font-black text-slate-700 dark:text-white">{{ $stats['total_products'] }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Produk Aktif</span>
                            <span class="text-xs font-black text-emerald-500">{{ $stats['active_products'] }} / {{ $stats['total_products'] }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden">
                            @php $activePercent = $stats['total_products'] > 0 ? ($stats['active_products'] / $stats['total_products'] * 100) : 0; @endphp
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $activePercent }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Voucher</span>
                            <span class="text-xs font-black text-violet-500">{{ $stats['total_vouchers'] }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden">
                            <div class="h-full bg-violet-500 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Terjual Bulan Ini</span>
                            <span class="text-xs font-black text-amber-500">{{ $stats['sold_this_month'] }} Unit</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden">
                            @php $soldPercent = $stats['total_vouchers'] > 0 ? ($stats['sold_this_month'] / $stats['total_vouchers'] * 100) : 0; @endphp
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-1000" style="width: {{ $soldPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm space-y-3">
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] mb-2 ml-2">Aksi Cepat</h3>
                
                <a href="{{ route('admin.products.create.manual') }}?category={{ $category->id }}" 
                class="flex items-center justify-between w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 hover:bg-emerald-500 hover:text-white transition-all duration-300 group shadow-inner">
                    <span class="text-[10px] font-black uppercase tracking-widest">Tambah Produk</span>
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform"><use href="#icon-plus-circle"></use></svg>
                </a>
                
                <a href="{{ route('admin.categories.edit', $category) }}" 
                class="flex items-center justify-between w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 hover:bg-sky-500 hover:text-white transition-all duration-300 group shadow-inner">
                    <span class="text-[10px] font-black uppercase tracking-widest">Edit Kategori</span>
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform"><use href="#icon-pencil-square"></use></svg>
                </a>

                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" 
                    class="flex items-center justify-between w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 hover:bg-amber-500 hover:text-white transition-all duration-300 group shadow-inner">
                        <span class="text-[10px] font-black uppercase tracking-widest">
                            {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </span>
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform">
                            <use href="{{ $category->is_active ? '#icon-eye-slash' : '#icon-eye' }}"></use>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] mb-6">Log Aktivitas</h3>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-slate-700 shadow-sm">
                            <svg class="w-4 h-4"><use href="#icon-calendar"></use></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Dibuat Pada</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $category->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-slate-700 shadow-sm">
                            <svg class="w-4 h-4"><use href="#icon-clock"></use></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Terakhir Diupdate</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $category->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-rose-500/5 dark:bg-rose-500/10 rounded-[2.5rem] p-8 border border-rose-500/20 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-rose-500"><use href="#icon-exclamation-triangle"></use></svg>
                    <h3 class="text-[11px] font-black text-rose-500 uppercase tracking-[0.2em]">Danger Zone</h3>
                </div>
                <p class="text-[10px] font-bold text-slate-500 dark:text-rose-300/60 leading-relaxed mb-6">
                    Penghapusan kategori hanya diperbolehkan jika kategori tidak memiliki produk aktif di dalamnya.
                </p>
                
                @if($stats['total_products'] > 0)
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-rose-500/20 text-rose-500 flex items-center gap-3 mb-6">
                        <svg class="w-5 h-5 flex-shrink-0 animate-bounce"><use href="#icon-lock-closed"></use></svg>
                        <p class="text-[9px] font-black uppercase leading-tight">Gagal: Masih terdapat {{ $stats['total_products'] }} produk terikat.</p>
                    </div>
                @endif
                
                <form action="{{ route('admin.categories.destroy', $category) }}" 
                    method="POST" 
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Aksi ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            @if($stats['total_products'] > 0) disabled @endif
                            class="w-full py-4 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-rose-500/30 transition-all active:scale-95 disabled:opacity-30 disabled:grayscale disabled:cursor-not-allowed">
                        Hapus Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
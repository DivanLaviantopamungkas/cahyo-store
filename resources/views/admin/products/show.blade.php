@extends('admin.layouts.app')

@section('title', $product->name)

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Informasi Detail</span>
@endsection

@section('actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.edit', $product->id) }}" 
           class="inline-flex items-center px-6 py-2.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit Produk
        </a>

        <a href="{{ route('admin.products.index') }}" 
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
                <div class="p-8 lg:p-10 flex flex-col md:flex-row items-center md:items-start gap-10">
                    <div class="relative shrink-0">
                        <div class="w-48 h-48 rounded-[2.5rem] overflow-hidden bg-slate-50 dark:bg-slate-900 border-4 border-white dark:border-slate-700 shadow-2xl flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                            @if($product->image && file_exists(public_path($product->image)))
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-4">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white">
                                    <svg class="w-20 h-20 opacity-40 transition-transform group-hover:rotate-12 duration-500"><use href="#icon-shopping-bag"></use></svg>
                                </div>
                            @endif
                        </div>
                        
                        @if($product->is_featured)
                            <div class="absolute -top-3 -right-3 w-12 h-12 bg-amber-400 rounded-full flex items-center justify-center shadow-lg animate-bounce">
                                <svg class="w-6 h-6 text-white"><use href="#icon-star"></use></svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <div class="space-y-4">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter">{{ $product->name }}</h1>
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-3">
                                        <span class="px-4 py-1 rounded-full bg-slate-100 dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest border border-slate-200 dark:border-slate-700">
                                            {{ $product->slug }}
                                        </span>

                                        @if($product->is_active)
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
                            </div>

                            <div class="p-6 rounded-[2rem] bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 shadow-inner">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Estimasi Range Harga</p>

                                @if($product->nominals->count() > 0)
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-y-2">
                                        <div class="flex flex-col items-center md:items-start">
                                            <span class="text-2xl md:text-3xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">
                                                Rp {{ number_format($product->nominals->min('price'), 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <div class="px-4 flex items-center">
                                            <span class="bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-tighter shadow-sm">
                                                s/d
                                            </span>
                                        </div>

                                        <div class="flex flex-col items-center md:items-start">
                                            <span class="text-2xl md:text-3xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">
                                                Rp {{ number_format($product->nominals->max('price'), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-center md:justify-start gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-tight">Total {{ $product->nominals->count() }} Pilihan Nominal Tersedia</p>
                                    </div>
                                @else
                                    <span class="text-xl font-black text-slate-300 italic uppercase tracking-widest">Belum Ada Nominal</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 border-t border-slate-50 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/30">
                    <div class="p-6 text-center border-r border-slate-50 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase">{{ $product->category->name ?? '—' }}</p>
                    </div>

                    <div class="p-6 text-center border-r border-slate-50 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tipe Produk</p>
                        <p class="text-sm font-black text-violet-500 uppercase">{{ $product->type == 'single' ? 'Single' : 'Multiple' }}</p>
                    </div>

                    <div class="p-6 text-center border-r border-slate-50 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Urutan Tampil</p>
                        <p class="text-xl font-black text-emerald-500">#{{ $product->order }}</p>
                    </div>

                    <div class="p-6 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Provider</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white uppercase">{{ $product->source ?? 'Manual' }}</p>
                    </div>
                </div>
            </div>

            @if($product->description)
                <div class="bg-white dark:bg-slate-800 rounded-[3rem] p-8 lg:p-10 border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl"></div>

                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-[2px] bg-emerald-500 mr-3"></span>
                        Deskripsi Produk
                    </h3>

                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium whitespace-pre-line bg-slate-50 dark:bg-slate-900/50 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-inner">
                        "{{ $product->description }}"
                    </p>
                </div>
            @endif

            @if(isset($recentVouchers) && $recentVouchers->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-50 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                <svg class="w-6 h-6"><use href="#icon-ticket"></use></svg>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Voucher Terbaru</h3>
                        </div>

                        <a href="{{ route('admin.voucher-codes.index') }}?product={{ $product->id }}" class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest hover:underline">
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/30">
                                    <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Kode</th>
                                    <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Nominal</th>
                                    <th class="px-8 py-4 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                    <th class="px-8 py-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Tanggal</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                                @foreach($recentVouchers as $voucher)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors">
                                        <td class="px-8 py-5 text-sm font-mono font-black text-slate-700 dark:text-white tracking-tighter">{{ $voucher->code }}</td>
                                        <td class="px-8 py-5 text-sm font-black text-slate-700 dark:text-slate-300">
                                            Rp {{ $voucher->productNominal ? number_format($voucher->productNominal->price, 0, ',', '.') : '—' }}
                                        </td>
                                        <td class="px-8 py-5">
                                            @php
                                                $vStatus = [
                                                    'available' => ['clr' => 'emerald', 'lbl' => 'Tersedia'],
                                                    'sold' => ['clr' => 'blue', 'lbl' => 'Terjual'],
                                                    'used' => ['clr' => 'slate', 'lbl' => 'Terpakai'],
                                                    'expired' => ['clr' => 'rose', 'lbl' => 'Kadaluarsa'],
                                                    'blocked' => ['clr' => 'rose', 'lbl' => 'Blokir']
                                                ];
                                                $vS = $vStatus[$voucher->status] ?? ['clr' => 'slate', 'lbl' => $voucher->status];
                                            @endphp

                                            <span class="px-3 py-1 rounded-lg bg-{{ $vS['clr'] }}-500/10 text-{{ $vS['clr'] }}-500 text-[8px] font-black uppercase tracking-widest border border-{{ $vS['clr'] }}-500/10">
                                                {{ $vS['lbl'] }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right text-[10px] font-bold text-slate-400 uppercase">{{ $voucher->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center justify-between">
                    Statistik Performa
                    <svg class="w-4 h-4 text-emerald-500"><use href="#icon-chart-bar"></use></svg>
                </h3>
                
                <div class="grid grid-cols-1 gap-6">
                    @php
                        $perfStats = [
                            ['lbl' => 'Total Nominal', 'val' => $stats['total_nominals'], 'icon' => '#icon-list-bullet', 'clr' => 'violet'],
                            ['lbl' => 'Stok Voucher', 'val' => $stats['total_vouchers'], 'icon' => '#icon-ticket', 'clr' => 'blue'],
                            ['lbl' => 'Voucher Terjual', 'val' => $stats['sold_vouchers'], 'icon' => '#icon-check-circle', 'clr' => 'emerald'],
                        ];
                    @endphp

                    @foreach($perfStats as $pStat)
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 shadow-inner border border-slate-100 dark:border-slate-700">
                            <div class="w-12 h-12 rounded-xl bg-{{ $pStat['clr'] }}-500/10 text-{{ $pStat['clr'] }}-500 flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6"><use href="{{ $pStat['icon'] }}"></use></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $pStat['lbl'] }}</p>
                                <p class="text-xl font-black text-slate-800 dark:text-white tracking-tighter">{{ number_format($pStat['val'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-2 p-6 rounded-3xl bg-emerald-500 text-white shadow-xl shadow-emerald-500/20 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Total Pendapatan</p>
                        <p class="text-2xl font-black tracking-tighter">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm space-y-3">
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] mb-4 ml-2">Aksi Cepat</h3>
                
                <a href="{{ route('admin.voucher-codes.create') }}?product={{ $product->id }}" 
                    class="flex items-center justify-between w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 hover:bg-emerald-500 hover:text-white transition-all duration-300 group shadow-inner">
                    <span class="text-[10px] font-black uppercase tracking-widest">Tambah Stok Voucher</span>
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform"><use href="#icon-plus-circle"></use></svg>
                </a>

                <a href="{{ route('admin.products.edit', $product->id) }}" 
                    class="flex items-center justify-between w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 hover:bg-sky-500 hover:text-white transition-all duration-300 group shadow-inner">
                    <span class="text-[10px] font-black uppercase tracking-widest">Ubah Detail Produk</span>
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform"><use href="#icon-pencil-square"></use></svg>
                </a>

                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center justify-between w-full p-4 rounded-2xl bg-rose-500/5 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-300 group shadow-inner border border-rose-500/10">
                        <span class="text-[10px] font-black uppercase tracking-widest">Hapus Permanen</span>
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform"><use href="#icon-trash"></use></svg>
                    </button>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.2em] mb-6">Log Aktivitas</h3>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-slate-700 shadow-sm shrink-0">
                            <svg class="w-4 h-4"><use href="#icon-calendar"></use></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Dibuat Pada</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $product->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 border border-slate-100 dark:border-slate-700 shadow-sm shrink-0">
                            <svg class="w-4 h-4"><use href="#icon-clock"></use></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Pembaruan Terakhir</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $product->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
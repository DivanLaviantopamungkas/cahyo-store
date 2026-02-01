@extends('admin.layouts.app')

@section('title', 'Detail Voucher Code')

@section('breadcrumb')
    <a href="{{ route('admin.voucher-codes.index') }}" class="hover:text-emerald-500 transition-colors">Voucher Codes</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Detail {{ $voucherCode->id }}</span>
@endsection

@section('actions')
    <a href="{{ route('admin.voucher-codes.index') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs uppercase tracking-widest transition-all shadow-sm">
        <svg class="w-4 h-4 mr-2 transform rotate-180">
            <use href="#icon-chevron-right"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="max-w-7xl mx-auto pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden relative">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-48 h-48 text-slate-900 dark:text-white"><use href="#icon-ticket"></use></svg>
                </div>
                
                <div class="p-8 lg:p-10 relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 text-[10px] font-black text-slate-500 dark:text-slate-300 uppercase tracking-widest">
                                    {{ $voucherCode->product->name ?? 'Unknown Product' }}
                                </span>
                                @php
                                    $statusColors = [
                                        'available' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                        'reserved'  => 'bg-amber-100 text-amber-600 border-amber-200',
                                        'sold'      => 'bg-violet-100 text-violet-600 border-violet-200',
                                        'expired'   => 'bg-rose-100 text-rose-600 border-rose-200',
                                    ];
                                    $statusColor = $statusColors[$voucherCode->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <span class="px-3 py-1 rounded-lg border {{ $statusColor }} text-[10px] font-black uppercase tracking-widest">
                                    {{ $voucherCode->status }}
                                </span>
                            </div>
                            <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">
                                {{ $voucherCode->nominal->name ?? 'Nominal Tidak Tersedia' }}
                            </h2>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-200 dark:border-slate-700 p-6 flex flex-col gap-4">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kode Voucher</p>
                            <div class="flex items-center gap-3">
                                <code class="text-xl md:text-3xl font-mono font-bold text-slate-800 dark:text-white tracking-wider break-all">
                                    {{ $voucherCode->code }}
                                </code>
                                <button onclick="navigator.clipboard.writeText('{{ $voucherCode->code }}')" class="p-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-emerald-500 transition-colors" title="Copy Code">
                                    <svg class="w-5 h-5"><use href="#icon-clipboard"></use></svg>
                                </button>
                            </div>
                        </div>

                        @if($voucherCode->secret)
                        <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Secret / PIN</p>
                            <div class="flex items-center gap-3">
                                <code class="text-lg font-mono font-medium text-slate-600 dark:text-slate-300 tracking-wider">
                                    {{ $voucherCode->secret }}
                                </code>
                                <button onclick="navigator.clipboard.writeText('{{ $voucherCode->secret }}')" class="p-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-emerald-500 transition-colors" title="Copy Secret">
                                    <svg class="w-5 h-5"><use href="#icon-clipboard"></use></svg>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 p-8 shadow-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                            <svg class="w-5 h-5"><use href="#icon-calendar"></use></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Linimasa</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500">Dibuat</span>
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $voucherCode->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500">Diperbarui</span>
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $voucherCode->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-700">
                            <span class="text-xs font-bold text-slate-500">Expired</span>
                            {{-- PERBAIKAN: Menggunakan 'Tidak ada' jika null --}}
                            <span class="text-xs font-bold {{ $voucherCode->expired_at && $voucherCode->expired_at->isPast() ? 'text-rose-500' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ $voucherCode->expired_at ? $voucherCode->expired_at->format('d M Y, H:i') : 'Tidak ada' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 p-8 shadow-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-violet-500/10 text-violet-500 flex items-center justify-center">
                            <svg class="w-5 h-5"><use href="#icon-tag"></use></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Detail ID</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500">Voucher ID</span>
                            <code class="text-xs font-mono bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-slate-600 dark:text-slate-300">{{ $voucherCode->id }}</code>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500">Product ID</span>
                            <code class="text-xs font-mono bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-slate-600 dark:text-slate-300">{{ $voucherCode->product_id }}</code>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500">Nominal ID</span>
                            <code class="text-xs font-mono bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-slate-600 dark:text-slate-300">{{ $voucherCode->product_nominal_id ?? '-' }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 p-8 shadow-xl">
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6">Aksi Cepat</h3>
                <div class="space-y-3">
                    @if($voucherCode->status == 'available')
                        <form action="{{ route('admin.voucher-codes.reserve', $voucherCode->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-amber-500/20 active:scale-95 transition-all flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-clock"></use></svg>
                                Reserve Voucher
                            </button>
                        </form>
                    @elseif($voucherCode->status == 'reserved')
                        <form action="{{ route('admin.voucher-codes.unreserve', $voucherCode->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 active:scale-95 transition-all flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-check-circle"></use></svg>
                                Unreserve Voucher
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.voucher-codes.edit', $voucherCode->id) }}" class="flex items-center justify-center w-full py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
                        Edit Voucher
                    </a>

                    <form action="{{ route('admin.voucher-codes.destroy', $voucherCode->id) }}" method="POST" onsubmit="return confirm('Hapus voucher ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-4 rounded-2xl border-2 border-rose-100 dark:border-rose-900/30 text-rose-500 dark:text-rose-400 font-bold text-xs uppercase tracking-widest hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:border-rose-200 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2"><use href="#icon-trash"></use></svg>
                            Hapus Voucher
                        </button>
                    </form>
                </div>
            </div>

            @if($voucherCode->status == 'sold')
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 p-8 shadow-sm relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl"></div>
                
                <div class="flex items-center gap-4 mb-6 relative z-10">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                        <svg class="w-5 h-5"><use href="#icon-user"></use></svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Informasi Pembeli</h3>
                </div>

                <div class="space-y-5 relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Pembeli</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">
                            {{ $voucherCode->soldTo->name ?? 'Member #' . $voucherCode->sold_to }}
                        </p>
                    </div>

                    @if(optional($voucherCode->soldTo)->email)
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</p>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300 break-all">
                            {{ $voucherCode->soldTo->email }}
                        </p>
                    </div>
                    @endif

                    @if(optional($voucherCode->soldTo)->phone)
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Telepon</p>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">
                            {{ $voucherCode->soldTo->phone }}
                        </p>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu Beli</p>
                            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $voucherCode->sold_at ? \Carbon\Carbon::parse($voucherCode->sold_at)->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
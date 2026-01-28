@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')
@section('subtitle', 'Invoice #' . $transaction->invoice)

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-20 px-4 lg:px-0">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <a href="{{ route('admin.transactions.index') }}" class="group flex items-center gap-3 text-slate-500 hover:text-emerald-500 transition-all font-black text-[10px] uppercase tracking-[0.2em]">
            <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 group-hover:bg-emerald-50 group-hover:border-emerald-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </div>
            Back to Dashboard
        </a>

        @php
            $statusBadge = match($transaction->status) {
                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                'paid' => 'bg-blue-100 text-blue-700 border-blue-200',
                'processing' => 'bg-violet-100 text-violet-700 border-violet-200',
                'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                default => 'bg-slate-100 text-slate-500 border-slate-200',
            };
        @endphp
        <div class="flex items-center gap-3 px-6 py-3 bg-white dark:bg-slate-800 rounded-[1.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status:</span>
            <span class="px-4 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $statusBadge }}">
                {{ $transaction->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Daftar Produk</h3>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ count($transaction->items) }} Item</span>
                </div>
                <div class="p-8 divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($transaction->items as $item)
                        <div class="py-6 flex items-center gap-6 first:pt-0 last:pb-0">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-slate-50 dark:bg-slate-900 flex items-center justify-center border border-slate-100 dark:border-slate-700">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $item->product->name ?? 'Digital Voucher' }}</p>
                                <p class="text-[10px] font-bold text-emerald-500 mt-1 uppercase tracking-widest">{{ $item->nominal->name ?? 'Code: ' . ($item->voucherCode->code ?? '-') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-slate-800 dark:text-white">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Qty: 1</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-300 font-black uppercase tracking-widest text-xs">Produk tidak ditemukan</div>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 border-l-4 border-emerald-500 pl-3">Pelanggan</p>
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</span>
                            <span class="text-base font-black text-slate-800 dark:text-white uppercase leading-tight">{{ $transaction->user->name ?? 'Guest User' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kontak</span>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300 leading-tight">{{ $transaction->user->phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 border-l-4 border-emerald-500 pl-3">Metode Bayar</p>
                    <div class="flex items-center gap-5">
                        <div class="p-4 bg-emerald-500/10 text-emerald-600 rounded-2xl border border-emerald-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-slate-800 dark:text-white uppercase leading-none">{{ strtoupper(str_replace('_', ' ', $transaction->payment_method ?? 'Unknown')) }}</p> {{-- FIX --}}
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1.5">Sistem Automatis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-slate-800 p-8 lg:p-10 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-xl lg:sticky lg:top-24">
                <div class="text-center space-y-8">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400 mb-3">Total Bayar</p>
                        <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter leading-tight">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</h2> {{-- FIX --}}
                        <div class="w-12 h-1 bg-emerald-500/30 mx-auto mt-6 rounded-full"></div>
                    </div>

                    <div class="space-y-4">
                        @if ($transaction->status == 'paid')
                            <form action="{{ route('admin.transactions.mark-processing', $transaction) }}" method="POST">
                                @csrf 
                                <button type="submit" onclick="return confirm('Proses pesanan ini?')"
                                    class="w-full bg-slate-900 dark:bg-emerald-500 text-white py-5 rounded-[1.8rem] font-black uppercase tracking-widest text-[11px] shadow-lg shadow-emerald-500/20 hover:scale-[1.02] active:scale-95 transition-all">
                                    Set Processing
                                </button>
                            </form>
                        @endif

                        @if ($transaction->status == 'processing')
                            <form action="{{ route('admin.transactions.mark-completed', $transaction) }}" method="POST">
                                @csrf 
                                <button type="submit" onclick="return confirm('Selesaikan pesanan ini?')"
                                    class="w-full bg-emerald-600 text-white py-5 rounded-[1.8rem] font-black uppercase tracking-widest text-[11px] shadow-lg shadow-emerald-600/20 hover:scale-[1.02] active:scale-95 transition-all">
                                    Finalize Order
                                </button>
                            </form>
                        @endif

                        <button type="button" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-300 py-5 rounded-[1.8rem] font-black uppercase tracking-widest text-[11px] border border-slate-200 dark:border-slate-700 hover:bg-slate-100 transition-all">
                            Print Invoice
                        </button>
                    </div>

                    <div class="pt-8 border-t border-slate-50 dark:border-slate-700 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Time Log</p>
                        <p class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase tracking-wide">
                            {{ $transaction->created_at->format('d M Y • H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
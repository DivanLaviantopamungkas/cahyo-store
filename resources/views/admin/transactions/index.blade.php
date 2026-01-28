@extends('admin.layouts.app')

@section('title', 'Aktivitas Transaksi')
@section('subtitle', 'Pantau arus kas dan status pesanan secara real-time')

@section('content')
    <div class="space-y-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @php
                $statConfig = [
                    'pending'    => ['color' => 'amber', 'label' => 'Menunggu', 'icon' => 'icon-clock'],
                    'paid'       => ['color' => 'blue', 'label' => 'Terbayar', 'icon' => 'icon-credit-card'],
                    'processing' => ['color' => 'violet', 'label' => 'Diproses', 'icon' => 'icon-play'],
                    'completed'  => ['color' => 'emerald', 'label' => 'Selesai', 'icon' => 'icon-check-circle'],
                ];
                $counts = [
                    'pending'    => \App\Models\Trancsaction::where('status', 'pending')->count(),
                    'paid'       => \App\Models\Trancsaction::whereIn('status', ['paid', 'completed'])->count(),
                    'processing' => \App\Models\Trancsaction::where('status', 'processing')->count(),
                    'completed'  => \App\Models\Trancsaction::where('status', 'completed')->count(),
                ];
            @endphp

            @foreach($statConfig as $key => $cfg)
            <div class="relative group">
                <div class="absolute inset-0 bg-{{ $cfg['color'] }}-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity rounded-[2rem]"></div>
                <div class="relative bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm transition-all group-hover:-translate-y-1">
                    <div class="flex flex-col gap-4">
                        <div class="w-10 h-10 rounded-xl bg-{{ $cfg['color'] }}-50 dark:bg-{{ $cfg['color'] }}-500/10 flex items-center justify-center text-{{ $cfg['color'] }}-500">
                            <svg class="w-6 h-6"><use href="#{{ $cfg['icon'] }}"></use></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 mb-1">{{ $cfg['label'] }}</p>
                            <p class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($counts[$key]) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl p-3 lg:p-4 rounded-[2.5rem] border border-white/20 dark:border-slate-700/50 shadow-2xl shadow-slate-200/50 dark:shadow-none">
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="h-5 w-5"><use href="#icon-magnifying-glass"></use></svg>
                    </div>
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari ID Invoice atau nama pelanggan..."
                        class="block w-full pl-12 pr-4 py-4 rounded-[1.8rem] border-none bg-white dark:bg-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-sm">
                </div>

                <div class="flex gap-2">
                    <select name="status" onchange="this.form.submit()" class="flex-1 lg:w-48 text-xs font-black uppercase tracking-wider rounded-[1.8rem] border-none bg-white dark:bg-slate-900 py-4 px-6 focus:ring-2 focus:ring-emerald-500/50 cursor-pointer shadow-sm">
                        <option value="">Semua Status</option>
                        @foreach(['pending', 'paid', 'processing', 'completed', 'expired', 'cancelled'] as $st)
                            <option value="{{ $st }}" {{ $status == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="hidden lg:block px-8 rounded-[1.8rem] bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase tracking-widest text-[10px] hover:shadow-xl hover:shadow-emerald-500/20 transition-all active:scale-95">
                        Filter
                    </button>
                    
                    @if($search || $status)
                        <a href="{{ route('admin.transactions.index') }}" 
                           class="flex items-center gap-2 px-6 rounded-[1.8rem] bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-200 transition-all shadow-sm border border-rose-200 dark:border-rose-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest hidden sm:inline">Reset</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-50 dark:border-slate-700">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Transaction ID</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Customer</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Amount</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/80 transition-all group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $transaction->status == 'completed' ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                    <div>
                                        <p class="font-black text-slate-800 dark:text-white text-sm">#{{ $transaction->invoice }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $transaction->created_at->format('M d, Y • H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $transaction->user->name ?? 'Guest User' }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-500',
                                        'paid' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-500',
                                        'processing' => 'bg-violet-100 text-violet-700 dark:bg-violet-500/10 dark:text-violet-500',
                                        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-500',
                                        'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-500',
                                    ];
                                @endphp
                                <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $statusClasses[$transaction->status] ?? 'bg-slate-100 text-slate-500' }}">
                                    {{ $transaction->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-slate-800 dark:text-white">
                                <p class="text-sm">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                <p class="text-[9px] text-slate-400 uppercase tracking-tighter">{{ strtoupper(str_replace('_', ' ', $transaction->payment_method ?? 'Unknown')) }}</p>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="p-2.5 bg-slate-100 dark:bg-slate-900 rounded-xl text-slate-400 hover:text-emerald-500 transition-all border border-slate-100 dark:border-slate-700 inline-block shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="5" class="py-24 text-center text-slate-300 font-black uppercase tracking-[0.3em]">No Transactions Found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="lg:hidden p-4 space-y-4">
                @foreach($transactions as $transaction)
                <div class="bg-slate-50/50 dark:bg-slate-900/50 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-700 flex flex-col gap-4 shadow-inner">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-slate-500 uppercase font-black text-[9px] tracking-widest">
                             {{ strtoupper(str_replace('_', ' ', $transaction->payment_method ?? 'PAY')) }}
                        </div>
                        <span class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $statusClasses[$transaction->status] ?? 'bg-slate-100' }}">
                            {{ $transaction->status }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">#{{ $transaction->invoice }}</p>
                        <h3 class="font-black text-slate-800 dark:text-white text-base leading-tight">{{ $transaction->user->name ?? 'Guest User' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $transaction->created_at->format('d M Y • H:i') }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-700">
                        <div>
                            <p class="text-lg font-black text-slate-800 dark:text-white leading-none mb-1">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ strtoupper(str_replace('_', ' ', $transaction->payment_method ?? 'Transfer')) }}</p>
                        </div>
                        <div class="flex gap-2">
                             @if ($transaction->status == 'paid')
                                <form action="{{ route('admin.transactions.mark-processing', $transaction) }}" method="POST">
                                    @csrf 
                                    <button class="p-3 bg-violet-500 text-white rounded-2xl shadow-lg shadow-violet-500/30 active:scale-90 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.transactions.show', $transaction) }}" class="p-3 bg-white dark:bg-slate-800 rounded-2xl text-slate-600 dark:text-slate-400 shadow-sm border border-slate-100 dark:border-slate-700 active:scale-90 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="p-8 bg-slate-50/30 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
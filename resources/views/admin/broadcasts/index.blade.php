@extends('admin.layouts.app')

@section('title', 'Broadcast')
@section('breadcrumb', 'Semua Broadcast')

@section('actions')
    <a href="{{ route('admin.broadcasts.create') }}" class="group inline-flex items-center px-5 py-2.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-emerald-200 dark:shadow-emerald-900/20 transition-all duration-300 active:scale-95">
        <svg class="w-4 h-4 mr-2 transition-transform group-hover:rotate-12"><use href="#icon-plus"></use></svg>
        <span>Buat Broadcast</span>
    </a>
@endsection

@section('content')
    <div class="pb-12 space-y-8">
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500/20 to-violet-500/20 rounded-[2.5rem] blur opacity-25 transition duration-1000 group-hover:opacity-50"></div>
            <x-admin.card class="relative border-none bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[2rem] shadow-sm">
                <form action="{{ route('admin.broadcasts.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="relative flex-1 max-w-xl group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within/input:text-emerald-500">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within/input:text-emerald-500"><use href="#icon-magnifying-glass"></use></svg>
                        </div>
                        <input 
                            type="text" 
                            name="q"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari berdasarkan judul atau isi pesan..." 
                            class="block w-full pl-12 pr-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all shadow-inner placeholder:text-slate-400"
                        >
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-4">
                        <select name="status" class="px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 focus:border-emerald-500 outline-none transition-all cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="sent">Sent</option>
                            <option value="failed">Failed</option>
                        </select>
                        
                        <select name="target" class="px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 focus:border-emerald-500 outline-none transition-all cursor-pointer">
                            <option value="">Semua Target</option>
                            <option value="all">All Members</option>
                            <option value="active">Active Members</option>
                            <option value="inactive">Inactive Members</option>
                        </select>
                    </div>
                </form>
            </x-admin.card>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($broadcasts as $broadcast)
                @php
                    $statusColors = [
                        'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                        'scheduled' => 'bg-blue-50 text-blue-600 border-blue-100',
                        'sending' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'sent' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
                        'cancelled' => 'bg-slate-100 text-slate-500 border-slate-200'
                    ];
                @endphp

                <x-admin.card class="group relative hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 border-slate-100 dark:border-slate-700 rounded-[2rem] overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ str_contains($statusColors[$broadcast->status] ?? 'bg-slate-500', 'emerald') ? 'bg-emerald-500' : 'bg-slate-400' }} opacity-50 transition-all group-hover:w-2"></div>
                    
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 p-2">
                        <div class="flex-1 space-y-4 px-4">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-50 dark:border-slate-700/50 pb-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="font-black text-lg text-slate-800 dark:text-white uppercase tracking-tight">{{ $broadcast->title }}</h3>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.15em] border {{ $statusColors[$broadcast->status] ?? 'bg-slate-100' }}">
                                        {{ ucfirst($broadcast->status) }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.15em] bg-violet-50 text-violet-600 border border-violet-100 dark:bg-violet-900/20">
                                        Target: {{ str_replace('_', ' ', $broadcast->target) }}
                                    </span>
                                </div>
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center">
                                    <svg class="w-3 h-3 mr-1.5 opacity-50"><use href="#icon-clock"></use></svg>
                                    {{ $broadcast->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            
                            <div class="relative">
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed line-clamp-2 italic font-medium">
                                    "{{ $broadcast->message }}"
                                </p>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-6 pt-2">
                                @if($broadcast->scheduled_at && $broadcast->status == 'scheduled')
                                <div class="flex items-center text-[11px] font-black uppercase tracking-wider text-amber-500 bg-amber-50 dark:bg-amber-900/20 px-3 py-1.5 rounded-xl border border-amber-100 dark:border-amber-800">
                                    <svg class="w-4 h-4 mr-2 animate-pulse"><use href="#icon-calendar"></use></svg>
                                    Jadwal: {{ $broadcast->scheduled_at->format('d M, H:i') }}
                                </div>
                                @endif

                                <div class="flex items-center gap-4 text-[11px] font-black uppercase tracking-widest text-slate-400">
                                    <span class="flex items-center"><svg class="w-3.5 h-3.5 mr-1.5"><use href="#icon-users"></use></svg> {{ number_format($broadcast->total_recipients ?? 0) }}</span>
                                    <span class="flex items-center text-emerald-500"><svg class="w-3.5 h-3.5 mr-1.5"><use href="#icon-check-circle"></use></svg> {{ number_format($broadcast->success_count ?? 0) }}</span>
                                    <span class="flex items-center text-rose-500"><svg class="w-3.5 h-3.5 mr-1.5"><use href="#icon-x-circle"></use></svg> {{ number_format($broadcast->failed_count ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end gap-2 bg-slate-50/50 dark:bg-slate-900/50 p-4 lg:p-2 rounded-2xl border border-slate-100 dark:border-slate-700/50 lg:border-none">
                            <a href="{{ route('admin.broadcasts.show', $broadcast->id) }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-sky-500 hover:bg-sky-500 hover:text-white shadow-sm border border-slate-200 dark:border-slate-600 transition-all active:scale-90" title="Detail">
                                <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                            </a>
                            
                            <a href="{{ route('admin.broadcasts.edit', $broadcast->id) }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-emerald-500 hover:bg-emerald-500 hover:text-white shadow-sm border border-slate-200 dark:border-slate-600 transition-all active:scale-90" title="Edit">
                                <svg class="w-5 h-5"><use href="#icon-pencil"></use></svg>
                            </a>
                            
                            @if(in_array($broadcast->status, ['draft', 'scheduled']))
                            <form action="{{ route('admin.broadcasts.send', $broadcast->id) }}" method="POST" onsubmit="return confirm('Kirim broadcast ini sekarang?')">
                                @csrf
                                <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-xl bg-violet-500 text-white hover:bg-violet-600 shadow-lg shadow-violet-200 dark:shadow-violet-900/30 transition-all active:scale-90" title="Send Now">
                                    <svg class="w-5 h-5"><use href="#icon-megaphone"></use></svg>
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('admin.broadcasts.destroy', $broadcast->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus broadcast ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-rose-500 hover:bg-rose-500 hover:text-white shadow-sm border border-slate-200 dark:border-slate-600 transition-all active:scale-90" title="Hapus">
                                    <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </x-admin.card>
            @empty
                <div class="relative group p-1">
                    <div class="absolute -inset-1 bg-gradient-to-r from-slate-200 to-slate-100 rounded-[3rem] blur opacity-25"></div>
                    <div class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] py-20 px-6 text-center shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <svg class="w-12 h-12 text-slate-300"><use href="#icon-megaphone"></use></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Belum ada broadcast</h3>
                        <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium max-w-sm mx-auto">Mulai kampanye promosi atau pengumuman Anda dengan membuat broadcast pertama.</p>
                        <div class="mt-8">
                            <a href="{{ route('admin.broadcasts.create') }}" class="inline-flex items-center px-8 py-4 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-xl active:scale-95">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                                Buat Broadcast Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($broadcasts->hasPages())
            <div class="relative pt-10 border-t border-slate-100 dark:border-slate-800">
                {{ $broadcasts->links('admin.partials.pagination') }}
            </div>
        @endif
    </div>
@endsection
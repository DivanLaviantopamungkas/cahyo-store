@extends('admin.layouts.app')

@section('title', 'Broadcast')
@section('breadcrumb', 'Semua Broadcast')

@section('actions')
    <a href="{{ route('admin.broadcasts.create') }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
        <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
        Buat Broadcast
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Search & Filter -->
    <x-admin.card>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400"><use href="#icon-magnifying-glass"></use></svg>
                </div>
                <input 
                    type="search" 
                    placeholder="Cari broadcast..." 
                    class="block w-full pl-10 pr-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                >
            </div>
            
            <div class="flex items-center space-x-3">
                <select class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="sent">Sent</option>
                    <option value="failed">Failed</option>
                </select>
                
                <select class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Semua Target</option>
                    <option value="all">All Members</option>
                    <option value="active">Active Members</option>
                    <option value="inactive">Inactive Members</option>
                </select>
            </div>
        </div>
    </x-admin.card>

    <!-- Broadcasts List -->
    <div class="grid grid-cols-1 gap-4">
        @for($i = 1; $i <= 5; $i++)
        @php
            $statuses = ['draft', 'scheduled', 'sent', 'failed'];
            $status = $statuses[array_rand($statuses)];
            $statusColors = [
                'draft' => 'gray',
                'scheduled' => 'blue',
                'sent' => 'green',
                'failed' => 'red'
            ];
            $targets = ['all', 'active', 'inactive'];
            $target = $targets[array_rand($targets)];
        @endphp
        <x-admin.card class="hover:shadow-lg transition-shadow duration-300">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Broadcast Contoh {{ $i }}</h3>
                            <div class="flex items-center space-x-3 mt-2">
                                <x-admin.badge :color="$statusColors[$status]" size="sm">
                                    {{ ucfirst($status) }}
                                </x-admin.badge>
                                <x-admin.badge color="purple" size="sm">
                                    Target: {{ ucfirst($target) }}
                                </x-admin.badge>
                            </div>
                        </div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            {{ now()->subDays($i)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    
                    <p class="mt-3 text-slate-600 dark:text-slate-400 line-clamp-2">
                        Ini adalah contoh pesan broadcast untuk member. Pesan ini berisi informasi penting atau promo yang sedang berjalan.
                    </p>
                    
                    @if($status == 'scheduled')
                    <div class="mt-3 flex items-center text-sm text-amber-600 dark:text-amber-400">
                        <svg class="w-4 h-4 mr-1"><use href="#icon-clock"></use></svg>
                        Scheduled: {{ now()->addDays($i)->format('d M Y, H:i') }}
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.broadcasts.show', $i) }}" class="text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 p-2 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/30" title="Detail">
                        <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                    </a>
                    <a href="{{ route('admin.broadcasts.edit', $i) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 p-2 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/30" title="Edit">
                        <svg class="w-5 h-5"><use href="#icon-pencil"></use></svg>
                    </a>
                    
                    @if(in_array($status, ['draft', 'scheduled']))
                    <form action="{{ route('admin.broadcasts.send', $i) }}" method="POST" onsubmit="return confirm('Kirim broadcast ini sekarang?')">
                        @csrf
                        <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 p-2 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/30" title="Send Now">
                            <svg class="w-5 h-5"><use href="#icon-megaphone"></use></svg>
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('admin.broadcasts.destroy', $i) }}" method="POST" class="inline" onsubmit="return confirm('Hapus broadcast ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 p-2 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/30" title="Hapus">
                            <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                        </button>
                    </form>
                </div>
            </div>
        </x-admin.card>
        @endfor
    </div>

    <!-- Empty State -->
    <div class="text-center py-12 hidden">
        <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto"><use href="#icon-megaphone"></use></svg>
        <h3 class="mt-4 text-lg font-medium text-slate-700 dark:text-slate-300">Belum ada broadcast</h3>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Mulai dengan membuat broadcast pertama Anda.</p>
        <div class="mt-6">
            <a href="{{ route('admin.broadcasts.create') }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                Buat Broadcast
            </a>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">Menampilkan 1 hingga 5 dari 25 broadcast</p>
        <div class="flex space-x-2">
            <button class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50">Sebelumnya</button>
            <button class="px-3 py-1 rounded-xl bg-emerald-500 text-white">1</button>
            <button class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">2</button>
            <button class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">3</button>
            <button class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">4</button>
            <button class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Selanjutnya</button>
        </div>
    </div>
</div>
@endsection
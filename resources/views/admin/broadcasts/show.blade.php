@extends('admin.layouts.app')

@section('title', $broadcast->title)
@section('breadcrumb')
    <a href="{{ route('admin.broadcasts.index') }}">Broadcast</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Detail</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        @if(in_array($broadcast->status, ['draft', 'scheduled']))
        <form action="{{ route('admin.broadcasts.send', $broadcast->id) }}" method="POST" onsubmit="return confirm('Kirim broadcast ini sekarang?')">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                <svg class="w-4 h-4 mr-2"><use href="#icon-megaphone"></use></svg>
                Kirim Sekarang
            </button>
        </form>
        @endif
        
        <a href="{{ route('admin.broadcasts.edit', $broadcast->id) }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-4 h-4 mr-2"><use href="#icon-pencil"></use></svg>
            Edit
        </a>
        <a href="{{ route('admin.broadcasts.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Broadcast Info -->
    <div class="lg:col-span-2">
        <x-admin.card>
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $broadcast->title }}</h2>
                        <div class="flex items-center space-x-3 mt-2">
                            @php
                                $statusColors = [
                                    'draft' => 'gray',
                                    'scheduled' => 'blue',
                                    'sent' => 'green',
                                    'failed' => 'red'
                                ];
                                $targetColors = [
                                    'all' => 'purple',
                                    'active' => 'green',
                                    'inactive' => 'red'
                                ];
                            @endphp
                            <x-admin.badge :color="$statusColors[$broadcast->status]" size="sm">
                                {{ ucfirst($broadcast->status) }}
                            </x-admin.badge>
                            <x-admin.badge :color="$targetColors[$broadcast->target]" size="sm">
                                Target: {{ ucfirst($broadcast->target) }}
                            </x-admin.badge>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Dibuat</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $broadcast->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Message -->
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                    <h3 class="font-semibold text-slate-800 dark:text-white mb-3">Isi Pesan</h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <p class="whitespace-pre-line text-slate-700 dark:text-slate-300">{{ $broadcast->message }}</p>
                    </div>
                </div>

                <!-- Schedule Info -->
                @if($broadcast->scheduled_at || $broadcast->status == 'sent')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($broadcast->scheduled_at)
                    <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                        <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Jadwal Pengiriman</p>
                        <p class="text-lg font-bold text-blue-800 dark:text-blue-200 mt-2">{{ $broadcast->scheduled_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            {{ $broadcast->scheduled_at->isFuture() ? 'Akan dikirim' : 'Seharusnya dikirim' }}
                        </p>
                    </div>
                    @endif
                    
                    @if($broadcast->status == 'sent')
                    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                        <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">Terkirim Pada</p>
                        <p class="text-lg font-bold text-emerald-800 dark:text-emerald-200 mt-2">{{ $broadcast->updated_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Status: Terkirim</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </x-admin.card>
    </div>

    <!-- Stats & Actions -->
    <div class="space-y-6">
        <!-- Stats -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Statistik</h3>
            <div class="space-y-4">
                @if($broadcast->status == 'sent')
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Total Dikirim</span>
                    <span class="font-semibold text-slate-800 dark:text-white">856</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Berhasil</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">842</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Gagal</span>
                    <span class="font-semibold text-rose-600 dark:text-rose-400">14</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Rate Response</span>
                    <span class="font-semibold text-slate-800 dark:text-white">12.5%</span>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-slate-500 dark:text-slate-400">Belum ada statistik</p>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Kirim broadcast untuk melihat statistik</p>
                </div>
                @endif
            </div>
        </x-admin.card>

        <!-- Actions -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Aksi</h3>
            <div class="space-y-3">
                @if(in_array($broadcast->status, ['draft', 'scheduled']))
                <form action="{{ route('admin.broadcasts.send', $broadcast->id) }}" method="POST" onsubmit="return confirm('Kirim broadcast ini sekarang?')">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                        <svg class="w-5 h-5 mr-2"><use href="#icon-megaphone"></use></svg>
                        Kirim Sekarang
                    </button>
                </form>
                @endif
                
                <a href="{{ route('admin.broadcasts.edit', $broadcast->id) }}" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-emerald-300 dark:border-emerald-600 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2"><use href="#icon-pencil"></use></svg>
                    Edit Broadcast
                </a>
                
                <form action="{{ route('admin.broadcasts.destroy', $broadcast->id) }}" method="POST" onsubmit="return confirm('Hapus broadcast ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-rose-300 dark:border-rose-600 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2"><use href="#icon-trash"></use></svg>
                        Hapus Broadcast
                    </button>
                </form>
            </div>
        </x-admin.card>

        <!-- Information -->
        <x-admin.card>
            <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Informasi</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Status</p>
                    <p class="text-sm font-medium text-slate-800 dark:text-white">{{ ucfirst($broadcast->status) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Target</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ ucfirst($broadcast->target) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Terakhir Diupdate</p>
                    <p class="text-sm text-slate-800 dark:text-white">{{ $broadcast->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </x-admin.card>
    </div>
</div>
@endsection
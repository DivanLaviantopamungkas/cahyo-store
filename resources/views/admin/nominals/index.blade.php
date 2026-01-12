@extends('admin.layouts.app')

@section('title', 'Nominals')
@section('breadcrumb', 'Semua Nominals')

@section('actions')
    <a href="{{ route('admin.nominals.create') }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
        <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
        Tambah Nominal
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Search & Filter -->
    <x-admin.card>
        <form method="GET" action="{{ route('admin.nominals.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400"><use href="#icon-magnifying-glass"></use></svg>
                </div>
                <input 
                    type="search" 
                    name="q"
                    value="{{ $search }}"
                    placeholder="Cari nominal atau produk..." 
                    class="block w-full pl-10 pr-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                >
            </div>
            
            <div class="flex items-center space-x-3">
                <select name="product_id" class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white rounded-2xl hover:bg-slate-900 dark:hover:bg-slate-600 transition-colors">
                    Filter
                </button>
                
                @if($search || $productId || $status)
                    <a href="{{ route('admin.nominals.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-2xl hover:bg-slate-300 dark:hover:bg-slate-500 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </x-admin.card>

    <!-- Nominals Table -->
    <x-admin.table>
        <x-slot name="header">
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Produk</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Harga</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Diskon</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Stok</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Urutan</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Aksi</th>
        </x-slot>

        <x-slot name="body">
            @forelse($nominals as $nominal)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center">
                            <span class="text-white font-bold">P{{ $nominal->product_id }}</span>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $nominal->product->name }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $nominal->product->category->name ?? 'Tidak Ada Kategori' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">{{ $nominal->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                    <div class="font-medium">Rp {{ number_format($nominal->price, 0, ',', '.') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                    @if($nominal->discount_price)
                        <div class="font-medium">Rp {{ number_format($nominal->discount_price, 0, ',', '.') }}</div>
                        <div class="text-xs text-emerald-600 dark:text-emerald-400">
                            {{ number_format((($nominal->price - $nominal->discount_price) / $nominal->price) * 100, 0) }}% off
                        </div>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-slate-900 dark:text-white">{{ $nominal->available_stock }}/{{ $nominal->stock }}</div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 mt-1">
                        @php
                            $percentage = $nominal->stock > 0 ? ($nominal->available_stock / $nominal->stock) * 100 : 0;
                            $color = $percentage > 50 ? 'bg-emerald-500' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-rose-500');
                        @endphp
                        <div class="{{ $color }} h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($nominal->is_active)
                        <x-admin.badge color="green" size="sm">Aktif</x-admin.badge>
                    @else
                        <x-admin.badge color="red" size="sm">Nonaktif</x-admin.badge>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">{{ $nominal->order }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.nominals.show', $nominal) }}" class="text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 p-1 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/30" title="Detail">
                            <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                        </a>
                        <a href="{{ route('admin.nominals.edit', $nominal) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 p-1 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/30" title="Edit">
                            <svg class="w-5 h-5"><use href="#icon-pencil"></use></svg>
                        </a>
                        <form action="{{ route('admin.nominals.destroy', $nominal) }}" method="POST" class="inline" onsubmit="return confirm('Hapus nominal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 p-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/30" title="Hapus">
                                <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto"><use href="#icon-currency-dollar"></use></svg>
                        <h3 class="mt-4 text-lg font-medium text-slate-700 dark:text-slate-300">Belum ada nominal</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Mulai dengan membuat nominal pertama Anda.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.nominals.create') }}" class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                                Tambah Nominal
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </x-slot>
    </x-admin.table>

    <!-- Pagination -->
    @if($nominals->hasPages())
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Menampilkan {{ $nominals->firstItem() }} hingga {{ $nominals->lastItem() }} dari {{ $nominals->total() }} entri
            </p>
            <div class="flex space-x-2">
                @if($nominals->onFirstPage())
                    <span class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-400 dark:text-slate-500 cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $nominals->previousPageUrl() }}" class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Sebelumnya</a>
                @endif

                @foreach($nominals->getUrlRange(1, $nominals->lastPage()) as $page => $url)
                    @if($page == $nominals->currentPage())
                        <span class="px-3 py-1 rounded-xl bg-emerald-500 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if($nominals->hasMorePages())
                    <a href="{{ $nominals->nextPageUrl() }}" class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Selanjutnya</a>
                @else
                    <span class="px-3 py-1 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-400 dark:text-slate-500 cursor-not-allowed">Selanjutnya</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
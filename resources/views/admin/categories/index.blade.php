@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('breadcrumb', 'Semua Kategori')

@section('actions')
    <a href="{{ route('admin.categories.create') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
        <svg class="w-4 h-4 mr-2">
            <use href="#icon-plus"></use>
        </svg>
        Tambah Kategori
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Search & Filter -->
        <x-admin.card>
            <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400">
                                <use href="#icon-magnifying-glass"></use>
                            </svg>
                        </div>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kategori..."
                            class="block w-full pl-10 pr-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>

                    <div class="flex items-center space-x-3">
                        <select name="status"
                            class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>

                        @if (request()->hasAny(['q', 'status']))
                            <a href="{{ route('admin.categories.index') }}"
                                class="px-4 py-2 text-sm rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </x-admin.card>

        <!-- Categories Table -->
        <x-admin.card>
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Gambar</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Urutan</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <!-- Nama Kategori -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($category->image)
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                                                    class="h-full w-full object-cover"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div
                                                    class="h-full w-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hidden">
                                                    <svg class="w-5 h-5">
                                                        <use href="#icon-photo"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white">
                                                    <use href="#icon-photo"></use>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                {{ $category->name }}</div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ $category->slug }}
                                            </div>
                                            @if ($category->description)
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">
                                                    {{ $category->description }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Gambar -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if ($category->image)
                                            <div class="relative group">
                                                <div
                                                    class="h-16 w-16 rounded-xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 group-hover:border-emerald-500 transition-colors">
                                                    <img src="{{ Storage::url($category->image) }}"
                                                        alt="{{ $category->name }}"
                                                        class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div
                                                        class="h-full w-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hidden">
                                                        <svg class="w-6 h-6">
                                                            <use href="#icon-photo"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <a href="{{ Storage::url($category->image) }}" target="_blank"
                                                    class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <svg class="w-5 h-5 text-white">
                                                        <use href="#icon-magnifying-glass"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                <div class="font-medium">Ukuran:</div>
                                                <div>Format: {{ pathinfo($category->image, PATHINFO_EXTENSION) }}</div>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="h-16 w-16 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-slate-400">
                                                        <use href="#icon-photo"></use>
                                                    </svg>
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                                    Tidak ada gambar
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @if ($category->is_active)
                                        <div
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Aktif
                                        </div>
                                    @else
                                        <div
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Nonaktif
                                        </div>
                                    @endif
                                </td>

                                <!-- Urutan -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $category->order }}
                                        </span>
                                        <div class="flex flex-col ml-2">
                                            <form action="{{ route('admin.categories.order-up', $category) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                                                    <svg class="w-3 h-3 text-slate-400 hover:text-emerald-500">
                                                        <use href="#icon-chevron-up"></use>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.categories.order-down', $category) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                                                    <svg class="w-3 h-3 text-slate-400 hover:text-emerald-500">
                                                        <use href="#icon-chevron-down"></use>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.categories.toggle-status', $category) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="p-1.5 text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors"
                                                title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                @if ($category->is_active)
                                                    <svg class="w-4 h-4">
                                                        <use href="#icon-eye-slash"></use>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4">
                                                        <use href="#icon-eye"></use>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="p-1.5 text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4">
                                                <use href="#icon-pencil-square"></use>
                                            </svg>
                                        </a>

                                        <!-- Detail -->
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                            class="p-1.5 text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-sky-50 dark:hover:bg-sky-900/20 rounded-lg transition-colors"
                                            title="Detail">
                                            <svg class="w-4 h-4">
                                                <use href="#icon-eye"></use>
                                            </svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4">
                                                    <use href="#icon-trash"></use>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            Tidak ada kategori ditemukan
                                        </h3>
                                        <p class="text-slate-500 dark:text-slate-400 mb-4">
                                            {{ request()->hasAny(['q', 'status']) ? 'Coba ubah filter pencarian Anda' : 'Mulai dengan menambahkan kategori baru' }}
                                        </p>
                                        @if (!request()->hasAny(['q', 'status']))
                                            <a href="{{ route('admin.categories.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-colors">
                                                <svg class="w-4 h-4 mr-2">
                                                    <use href="#icon-plus"></use>
                                                </svg>
                                                Tambah Kategori
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($categories->hasPages() || $categories->total() > 0)
                <x-slot name="footer">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <!-- Jumlah Data -->
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            @if ($categories->total() > 0)
                                Menampilkan
                                <span
                                    class="font-medium text-slate-700 dark:text-slate-300">{{ $categories->firstItem() }}</span>
                                hingga
                                <span
                                    class="font-medium text-slate-700 dark:text-slate-300">{{ $categories->lastItem() }}</span>
                                dari total
                                <span
                                    class="font-medium text-slate-700 dark:text-slate-300">{{ $categories->total() }}</span>
                                kategori
                            @endif
                        </div>

                        <!-- Pagination Links -->
                        @if ($categories->hasPages())
                            <div class="flex items-center space-x-2">
                                {{ $categories->links('components.admin.pagination') }}
                            </div>
                        @endif
                    </div>
                </x-slot>
            @endif
        </x-admin.card>
    </div>

    @push('scripts')
        <script>
            // Auto submit form dengan debounce
            let searchTimeout;
            const searchInput = document.querySelector('input[name="q"]');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 500);
                });
            }
        </script>
    @endpush
@endsection

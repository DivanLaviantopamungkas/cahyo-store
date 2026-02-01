@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('subtitle', 'Manajemen kategori produk dan layanan anda')
@section('breadcrumb', 'Semua Kategori')

@section('actions')
    <a href="{{ route('admin.categories.create') }}"
        class="inline-flex items-center px-6 py-3 rounded-[1.8rem] bg-emerald-500 hover:bg-emerald-600 text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
        <svg class="w-4 h-4 mr-2">
            <use href="#icon-plus"></use>
        </svg>
        Tambah Kategori
    </a>
@endsection

@section('content')
    <div class="space-y-8">
        <div class="bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl p-3 lg:p-4 rounded-[2.5rem] border border-white/20 dark:border-slate-700/50 shadow-2xl shadow-slate-200/50 dark:shadow-none">
            <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm" class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="h-5 w-5">
                            <use href="#icon-magnifying-glass"></use>
                        </svg>
                    </div>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kategori..."
                        class="block w-full pl-12 pr-4 py-4 rounded-[1.8rem] border-none bg-white dark:bg-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-sm">
                </div>

                <div class="flex gap-2">
                    <select name="status" onchange="this.form.submit()" 
                        class="flex-1 lg:w-48 text-xs font-black uppercase tracking-wider rounded-[1.8rem] border-none bg-white dark:bg-slate-900 py-4 px-6 focus:ring-2 focus:ring-emerald-500/50 cursor-pointer shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    @if (request()->hasAny(['q', 'status']))
                        <a href="{{ route('admin.categories.index') }}"
                            class="p-4 rounded-[1.8rem] bg-rose-500 text-white hover:rotate-90 transition-all duration-500 shadow-lg shadow-rose-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5"><use href="#icon-arrow-path"></use></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-50 dark:border-slate-700">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Kategori</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Gambar</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Urutan</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/80 transition-all group">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-800 dark:text-white group-hover:text-emerald-500 transition-colors">{{ $category->name }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $category->slug }}</span>
                                        @if ($category->description)
                                            <p class="text-[10px] text-slate-400 mt-1 line-clamp-1 max-w-xs lowercase italic">
                                                {{ $category->description }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="relative group/img w-16 h-10">
                                        @if ($category->image)
                                            <img src="{{ Storage::url($category->image) }}" class="w-full h-full object-cover rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm">
                                            <a href="{{ Storage::url($category->image) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 rounded-lg flex items-center justify-center transition-opacity">
                                                <svg class="w-4 h-4 text-white"><use href="#icon-magnifying-glass"></use></svg>
                                            </a>
                                        @else
                                            <div class="w-full h-full bg-slate-100 dark:bg-slate-900 rounded-lg flex items-center justify-center text-slate-300">
                                                <svg class="w-5 h-5"><use href="#icon-photo"></use></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ $category->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-500' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-500' }}">
                                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-black text-slate-700 dark:text-slate-300">{{ $category->order }}</span>
                                        <div class="flex flex-col">
                                            <form action="{{ route('admin.categories.order-up', $category) }}" method="POST">@csrf
                                                <button class="text-slate-300 hover:text-emerald-500 transition-colors"><svg class="w-3 h-3 rotate-180"><use href="#icon-chevron-down"></use></svg></button>
                                            </form>
                                            <form action="{{ route('admin.categories.order-down', $category) }}" method="POST">@csrf
                                                <button class="text-slate-300 hover:text-emerald-500 transition-colors"><svg class="w-3 h-3"><use href="#icon-chevron-down"></use></svg></button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categories.show', $category) }}" class="p-2.5 bg-slate-100 dark:bg-slate-900 rounded-xl text-slate-400 hover:text-sky-500 transition-all border border-slate-100 dark:border-slate-700 shadow-sm"><svg class="w-4 h-4"><use href="#icon-eye"></use></svg></a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="p-2.5 bg-slate-100 dark:bg-slate-900 rounded-xl text-slate-400 hover:text-blue-500 transition-all border border-slate-100 dark:border-slate-700 shadow-sm"><svg class="w-4 h-4"><use href="#icon-pencil-square"></use></svg></a>
                                        
                                        <button onclick="confirmDelete('{{ $category->id }}')" class="p-2.5 bg-slate-100 dark:bg-slate-900 rounded-xl text-slate-400 hover:text-rose-500 transition-all border border-slate-100 dark:border-slate-700 shadow-sm">
                                            <svg class="w-4 h-4"><use href="#icon-trash"></use></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-24 text-center text-slate-300 font-black uppercase tracking-[0.3em]">Tidak ada kategori ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="lg:hidden p-4 space-y-4">
                @foreach($categories as $category)
                <div class="bg-slate-50/50 dark:bg-slate-900/50 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-700 flex flex-col gap-4 shadow-inner">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden flex items-center justify-center">
                                @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}" class="object-cover w-full h-full">
                                @else
                                    <svg class="w-6 h-6 text-slate-300"><use href="#icon-photo"></use></svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 dark:text-white text-base leading-none mb-1">{{ $category->name }}</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Order: {{ $category->order }}</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $category->is_active ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-700">
                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.2em]">{{ $category->slug }}</span>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="p-3 bg-white dark:bg-slate-800 rounded-2xl text-slate-600 dark:text-slate-400 shadow-sm active:scale-90 transition-transform border border-slate-100 dark:border-slate-700">
                                <svg class="w-5 h-5"><use href="#icon-pencil-square"></use></svg>
                            </a>
                            <a href="{{ route('admin.categories.show', $category) }}" class="p-3 bg-white dark:bg-slate-800 rounded-2xl text-slate-600 dark:text-slate-400 shadow-sm active:scale-90 transition-transform border border-slate-100 dark:border-slate-700">
                                <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                            </a>
                            
                            <button onclick="confirmDelete('{{ $category->id }}')" class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-2xl text-rose-500 shadow-sm active:scale-90 transition-transform border border-rose-100 dark:border-rose-900/20">
                                <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="p-8 bg-slate-50/30 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em]">
                        Showing {{ $categories->firstItem() ?? 0 }}-{{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }}
                    </p>
                    <div class="flex items-center">
                        <x-admin.pagination :paginator="$categories" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" x-data="{}" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-[3rem] w-full max-w-sm p-8 shadow-2xl border border-slate-100 dark:border-slate-700 transform transition-all scale-100">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-500"><use href="#icon-trash"></use></svg>
                </div>
                <h3 class="text-xl font-black text-center text-slate-800 dark:text-white mb-2 tracking-tight leading-none uppercase">Hapus Kategori?</h3>
                <p class="text-center text-slate-500 text-sm mb-8 font-medium">Data kategori ini akan dihapus secara permanen.</p>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="closeDeleteModal()" class="py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-white font-bold text-xs uppercase tracking-widest transition-all hover:bg-slate-200 dark:hover:bg-slate-600">Batal</button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-4 rounded-2xl bg-rose-500 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-500/20 active:scale-95 transition-all hover:bg-rose-600">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
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

        function confirmDelete(id) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ route('admin.categories.destroy', '') }}/" + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endpush
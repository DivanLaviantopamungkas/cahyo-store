@extends('admin.layouts.app')

@section('title', 'Produk')
@section('subtitle', 'Manajemen daftar produk anda')

@section('actions')
    <div class="hidden lg:flex space-x-3">
        <a href="{{ route('admin.products.create.manual') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2"><use href="#icon-plus"></use></svg>
            Tambah Manual
        </a>

        <a href="{{ route('admin.products.create.digiflazz') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white font-semibold transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2"><use href="#icon-plus"></use></svg>
            Import Digiflazz
        </a>
    </div>

    <div class="lg:hidden fixed bottom-24 right-6 z-50 flex flex-col space-y-3" x-data="{ open: false }">
        <div x-show="open" x-transition class="flex flex-col space-y-3 items-end">
            <a href="{{ route('admin.products.create.digiflazz') }}" class="flex items-center px-4 py-3 rounded-2xl bg-blue-600 text-white shadow-xl">
                <span class="mr-2 text-xs font-bold uppercase">Import</span>
                <svg class="w-5 h-5"><use href="#icon-refresh"></use></svg>
            </a>

            <a href="{{ route('admin.products.create.manual') }}" class="flex items-center px-4 py-3 rounded-2xl bg-emerald-600 text-white shadow-xl">
                <span class="mr-2 text-xs font-bold uppercase">Manual</span>
                <svg class="w-5 h-5"><use href="#icon-plus"></use></svg>
            </a>
        </div>

        <button @click="open = !open" class="w-14 h-14 rounded-full bg-slate-900 dark:bg-emerald-500 text-white flex items-center justify-center shadow-2xl transition-transform" :class="open ? 'rotate-45' : ''">
            <svg class="w-8 h-8"><use href="#icon-plus"></use></svg>
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-6">
            <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400"><use href="#icon-magnifying-glass"></use></svg>
                    </div>
                    <input type="search" name="q" value="{{ $search ?? '' }}" placeholder="Cari produk..."
                        class="block w-full pl-12 pr-4 py-4 rounded-3xl border-none bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 transition-all shadow-inner">
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    <select name="category_id" class="text-xs font-bold rounded-2xl border-none bg-slate-50 dark:bg-slate-900 py-3.5 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="text-xs font-bold rounded-2xl border-none bg-slate-50 dark:bg-slate-900 py-3.5 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <button type="submit" class="col-span-2 lg:col-span-1 py-3.5 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase tracking-widest text-[10px] hover:opacity-90 active:scale-95 transition-all">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        @if ($products->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach ($products as $product)
                    <div class="flex lg:flex-col bg-white dark:bg-slate-800 rounded-[2rem] overflow-hidden border border-slate-100 dark:border-slate-700 shadow-sm group transition-all hover:shadow-md">
                        <div class="relative w-28 lg:w-full h-28 lg:h-36 shrink-0 overflow-hidden bg-slate-50 dark:bg-slate-900 border-r lg:border-r-0 lg:border-b border-slate-50 dark:border-slate-700 flex items-center justify-center">
                            @if ($product->image)
                                <img src="{{ asset($product->image) }}" class="w-full h-full object-contain p-2 lg:p-4 transition-transform group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-500 to-violet-600 text-white">
                                    <svg class="w-8 h-8 opacity-40"><use href="#icon-shopping-bag"></use></svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-1 {{ $product->is_active ? 'bg-emerald-500' : 'bg-rose-500' }} backdrop-blur-sm text-[7px] font-black text-white uppercase rounded-md shadow-sm">
                                    {{ $product->is_active ? 'Aktif' : 'Off' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 lg:p-5 flex-1 flex flex-col justify-between min-w-0">
                            <div>
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-[8px] font-black text-emerald-600 uppercase tracking-tighter truncate mr-2">{{ $product->category->name ?? 'Voucher' }}</p>
                                    <span class="text-[8px] font-bold text-slate-400 shrink-0">Ord: {{ $product->order }}</span>
                                </div>
                                <h3 class="font-black text-slate-800 dark:text-white text-sm lg:text-base leading-tight truncate mb-1 lg:mb-2">{{ $product->name }}</h3>
                                
                                @if ($product->nominals && $product->nominals->count() > 0)
                                    <div class="flex items-baseline space-x-1">
                                        <span class="text-xs lg:text-sm font-black text-slate-900 dark:text-emerald-400">
                                            Rp {{ number_format($product->nominals->min('price'), 0, ',', '.') }}
                                        </span>
                                        @if($product->nominals->count() > 1)
                                            <span class="text-[9px] text-slate-400 font-medium">~ s/d</span>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-[10px] text-slate-400 italic font-medium">Nominal Kosong</p>
                                @endif
                            </div>

                            <div class="flex items-center justify-between mt-3 lg:mt-4 pt-3 lg:pt-4 border-t border-slate-50 dark:border-slate-700">
                                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-900 rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500">
                                    {{ strtoupper($product->type) }}
                                </span>
                                
                                <div class="flex space-x-1">
                                    <a href="{{ route('admin.products.show', $product) }}" class="p-1.5 bg-slate-50 dark:bg-slate-900 rounded-lg text-slate-400 hover:text-emerald-500 transition-colors shadow-sm border border-slate-100 dark:border-slate-700">
                                        <svg class="w-4 h-4"><use href="#icon-eye"></use></svg>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 bg-slate-50 dark:bg-slate-900 rounded-lg text-slate-400 hover:text-blue-500 transition-colors shadow-sm border border-slate-100 dark:border-slate-700">
                                        <svg class="w-4 h-4"><use href="#icon-pencil"></use></svg>
                                    </a>
                                    <button onclick="confirmDelete('{{ $product->id }}')" class="p-1.5 bg-slate-50 dark:bg-slate-900 rounded-lg text-slate-400 hover:text-rose-500 transition-colors shadow-sm border border-slate-100 dark:border-slate-700">
                                        <svg class="w-4 h-4"><use href="#icon-trash"></use></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm mt-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em]">
                        Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }}
                    </p>
                    <div class="flex items-center">
                        <x-admin.pagination :paginator="$products" />
                    </div>
                </div>
            </div>
        @else
            <div class="py-20 text-center bg-white dark:bg-slate-800 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-700 shadow-sm">
                <svg class="w-20 h-20 text-slate-200 dark:text-slate-700 mx-auto mb-4"><use href="#icon-shopping-bag"></use></svg>
                <h3 class="text-xl font-black text-slate-800 dark:text-white">Ops! Produk Kosong</h3>
                <p class="text-slate-400 mt-2 text-sm">Tidak ditemukan produk dengan kriteria filter tersebut.</p>
                <a href="{{ route('admin.products.index') }}" class="mt-6 inline-block px-8 py-3 bg-emerald-500 text-white font-black rounded-2xl uppercase text-[10px] tracking-widest shadow-lg active:scale-95">Reset Filter</a>
            </div>
        @endif
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" x-data="{}" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] w-full max-w-sm p-8 shadow-2xl border border-slate-100 dark:border-slate-700">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-500"><use href="#icon-trash"></use></svg>
                </div>
                <h3 class="text-xl font-black text-center text-slate-800 dark:text-white mb-2 uppercase tracking-tight leading-none">Hapus Produk?</h3>
                <p class="text-center text-slate-500 dark:text-slate-400 text-sm mb-8 font-medium">Data yang sudah dihapus tidak dapat dikembalikan lagi.</p>
                
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="closeDeleteModal()" class="py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-white font-bold text-xs uppercase tracking-widest active:scale-95">Batal</button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 rounded-2xl bg-rose-500 text-white font-black text-xs uppercase tracking-widest shadow-lg active:scale-95 transition-all">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(productId) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ route('admin.products.destroy', ':id') }}".replace(':id', productId);
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endpush
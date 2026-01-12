@extends('admin.layouts.app')

@section('title', 'Produk')
@section('breadcrumb', 'Semua Produk')

@section('actions')
    <div class="flex space-x-3">
        <a href="{{ route('admin.products.create.manual') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-colors">
            <svg class="w-4 h-4 mr-2">
                <use href="#icon-plus"></use>
            </svg>
            Tambah Manual
        </a>
        <a href="{{ route('admin.products.create.digiflazz') }}"
            class="inline-flex items-center px-4 py-2 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors">
            <svg class="w-4 h-4 mr-2">
                <use href="#icon-refresh"></use>
            </svg>
            Import Digiflazz
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Search & Filter Form -->
        <x-admin.card>
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400">
                                <use href="#icon-magnifying-glass"></use>
                            </svg>
                        </div>
                        <input type="search" name="q" value="{{ $search ?? '' }}" placeholder="Cari produk..."
                            class="block w-full pl-10 pr-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>

                    <div class="flex items-center space-x-3">
                        <select name="category_id"
                            class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="status"
                            class="text-sm rounded-2xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>

                        <button type="submit"
                            class="px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium">
                            Filter
                        </button>

                        @if ($search || $categoryId || $status)
                            <a href="{{ route('admin.products.index') }}"
                                class="px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </x-admin.card>

        @if ($products->count() > 0)
            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <x-admin.card class="hover:shadow-lg transition-shadow duration-300">
                        <div class="relative">
                            <!-- Product Image -->
                            @if ($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                    class="w-full aspect-video rounded-xl object-cover mb-4">
                            @else
                                <div
                                    class="aspect-video rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center mb-4">
                                    <svg class="w-16 h-16 text-white opacity-80">
                                        <use href="#icon-shopping-bag"></use>
                                    </svg>
                                </div>
                            @endif

                            <!-- Featured Badge -->
                            @if ($product->is_featured)
                                <div class="absolute top-3 right-3">
                                    <x-admin.badge color="yellow" size="sm">Featured</x-admin.badge>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-3 left-3">
                                @if ($product->is_active)
                                    <x-admin.badge color="green" size="sm">Aktif</x-admin.badge>
                                @else
                                    <x-admin.badge color="red" size="sm">Nonaktif</x-admin.badge>
                                @endif
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="space-y-3">
                            <div>
                                <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $product->name }}</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kategori:
                                    {{ $product->category->name ?? '-' }}</p>
                            </div>

                            <!-- Price Range -->
                            @if ($product->nominals && $product->nominals->count() > 0)
                                <div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Harga:</p>
                                    @php
                                        $minPrice = $product->nominals->min('price');
                                        $maxPrice = $product->nominals->max('price');
                                    @endphp
                                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @if ($minPrice != $maxPrice)
                                            - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                                        @endif
                                    </p>
                                </div>
                            @else
                                <div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Harga:</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada nominal</p>
                                </div>
                            @endif

                            <!-- Product Type -->
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Tipe:</span>
                                <x-admin.badge color="blue" size="sm">
                                    {{ $product->nominals && $product->nominals->count() > 1 ? 'Multiple' : 'Single' }}
                                </x-admin.badge>
                            </div>

                            <!-- Actions -->
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-300"
                                            title="Detail">
                                            <svg class="w-5 h-5">
                                                <use href="#icon-eye"></use>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="p-2 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300"
                                            title="Edit">
                                            <svg class="w-5 h-5">
                                                <use href="#icon-pencil"></use>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300"
                                                title="Hapus">
                                                <svg class="w-5 h-5">
                                                    <use href="#icon-trash"></use>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="text-sm text-slate-500 dark:text-slate-400">
                                        Urutan: {{ $product->order }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-admin.card>
                @endforeach
            </div>

            <!-- Pagination -->
            <x-admin.card>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <!-- Jumlah Data -->
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                        @if ($products->total() > 0)
                            Menampilkan
                            <span
                                class="font-medium text-slate-700 dark:text-slate-300">{{ $products->firstItem() }}</span>
                            hingga
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $products->lastItem() }}</span>
                            dari total
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $products->total() }}</span>
                            produk
                        @endif
                    </div>

                    <!-- Pagination Links -->
                    @if ($products->hasPages())
                        <div class="flex items-center space-x-2">
                            @if (View::exists('components.admin.pagination'))
                                {{ $products->links('components.admin.pagination') }}
                            @elseif(View::exists('vendor.pagination.custom'))
                                {{ $products->links('vendor.pagination.custom') }}
                            @else
                                {{ $products->links() }}
                            @endif
                        </div>
                    @endif
                </div>
            </x-admin.card>
        @else
            <!-- Empty State -->
            <x-admin.card class="text-center py-12">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto">
                    <use href="#icon-shopping-bag"></use>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-700 dark:text-slate-300">
                    @if ($search || $categoryId || $status)
                        Produk tidak ditemukan
                    @else
                        Belum ada produk
                    @endif
                </h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    @if ($search || $categoryId || $status)
                        Coba ubah filter pencarian Anda
                    @else
                        Mulai dengan membuat produk pertama Anda.
                    @endif
                </p>
                <div class="mt-6">
                    @if ($search || $categoryId || $status)
                        <a href="{{ route('admin.products.index') }}"
                            class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium">
                            Reset Filter
                        </a>
                    @else
                        <a href="{{ route('admin.products.create') }}"
                            class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                            <svg class="w-4 h-4 mr-2">
                                <use href="#icon-plus"></use>
                            </svg>
                            Tambah Produk
                        </a>
                    @endif
                </div>
            </x-admin.card>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6">
            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 dark:bg-rose-900/30 mb-4">
                    <svg class="h-6 w-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.252 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Hapus Produk</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-500 hover:bg-rose-600 text-white">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(productId) {
                const form = document.getElementById('deleteForm');
                form.action = "{{ route('admin.products.destroy', ':id') }}".replace(':id', productId);

                const modal = document.getElementById('deleteModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeDeleteModal() {
                const modal = document.getElementById('deleteModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Close modal when clicking outside
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });

            // Update delete forms to use modal
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.type = 'button';
                        submitBtn.onclick = function() {
                            const productId = form.action.split('/').pop();
                            confirmDelete(productId);
                        };
                    }
                });
            });
        </script>
    @endpush
@endsection

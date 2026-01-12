@extends('customer.layouts.app')

@section('title', 'Kategori Produk')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Kategori Produk</h1>
                <p class="text-gray-600">Pilih kategori game atau voucher yang ingin Anda top up</p>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('categories.index') }}"
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Search Bar -->
                    <div class="relative flex-1">
                        <i class='bx bx-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari kategori..."
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <!-- Sort Options -->
                    <div class="flex items-center space-x-4">
                        <select name="sort" onchange="this.form.submit()"
                            class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="default" {{ ($sort ?? 'default') == 'default' ? 'selected' : '' }}>Urutkan:
                                Default</option>
                            <option value="az" {{ ($sort ?? 'default') == 'az' ? 'selected' : '' }}>Urutkan: A-Z
                            </option>
                            <option value="za" {{ ($sort ?? 'default') == 'za' ? 'selected' : '' }}>Urutkan: Z-A
                            </option>
                        </select>

                        <!-- Reset Filter -->
                        @if (($q ?? false) || ($sort ?? 'default') != 'default')
                            <a href="{{ route('categories.index') }}"
                                class="flex items-center border border-gray-300 rounded-lg px-4 py-3 hover:bg-gray-50 text-gray-600">
                                <i class='bx bx-refresh mr-2'></i>
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Categories Grid -->
            @if ($categories->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class='bx bx-category text-gray-400 text-4xl'></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Kategori tidak ditemukan</h3>
                    <p class="text-gray-600 mb-6">Coba gunakan kata kunci pencarian yang berbeda</p>
                    <a href="{{ route('categories.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-all">
                        <i class='bx bx-refresh mr-2'></i>
                        Tampilkan Semua Kategori
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 card-hover group">
                            <div class="p-6">
                                <!-- Icon -->
                                <div class="w-20 h-20 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300"
                                    style="background: {{ $category->color ?: '#6366f1' }}">
                                    @if ($category->icon)
                                        <i class="{{ $category->icon }} text-white text-3xl"></i>
                                    @else
                                        <i class='bx bx-category text-white text-3xl'></i>
                                    @endif
                                </div>

                                <!-- Category Info -->
                                <div class="text-center">
                                    <h3 class="font-bold text-gray-800 mb-1">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $category->products_count ?? 0 }} produk</p>
                                </div>
                            </div>

                            <!-- Hover Effect -->
                            <div class="bg-gray-50 border-t border-gray-200 p-3 text-center">
                                <span class="text-primary text-sm font-medium flex items-center justify-center">
                                    Lihat Produk
                                    <i class='bx bx-chevron-right ml-1 group-hover:translate-x-1 transition-transform'></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Popular Products Section -->
            @if (isset($popularProducts) && $popularProducts->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Terlaris</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($popularProducts as $product)
                            <div
                                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <!-- Product Image/Icon -->
                                            @if ($product->image)
                                                <div class="w-12 h-12 rounded-lg overflow-hidden mb-3">
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div
                                                    class="w-12 h-12 bg-gradient-to-br from-primary to-blue-500 rounded-lg flex items-center justify-center mb-3">
                                                    <i class='bx bx-star text-white text-xl'></i>
                                                </div>
                                            @endif
                                            <h4 class="font-bold text-gray-800">{{ $product->name }}</h4>
                                        </div>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            Featured
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-lg font-bold text-gray-800">Lihat Detail</div>
                                            <div class="text-sm text-gray-500">{{ $product->type ?? 'Digital Product' }}
                                            </div>
                                        </div>
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                            Beli
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto submit form on search input change after typing stops
        let searchTimeout;
        document.querySelector('input[name="q"]')?.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                e.target.closest('form').submit();
            }, 500);
        });
    </script>
@endpush

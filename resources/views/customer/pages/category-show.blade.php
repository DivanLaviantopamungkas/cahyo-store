@extends('customer.layouts.app')

@section('title', $category->name . ' - Kategori Produk')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex text-sm text-gray-600">
                    <a href="{{ url('/') }}" class="hover:text-primary">Beranda</a>
                    <i class='bx bx-chevron-right mx-2'></i>
                    <a href="{{ route('categories.index') }}" class="hover:text-primary">Semua Kategori</a>
                    <i class='bx bx-chevron-right mx-2'></i>
                    <span class="text-gray-800 font-medium">{{ $category->name }}</span>
                </nav>
            </div>

            <!-- Category Header -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center">
                    <!-- Category Icon -->
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center mr-4"
                        style="background: {{ $category->color ?? '#6366f1' }}">
                        @if ($category->icon)
                            <i class="{{ $category->icon }} text-white text-2xl"></i>
                        @else
                            <i class='bx bx-category text-white text-2xl'></i>
                        @endif
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $category->name }}</h1>
                        <p class="text-gray-600">
                            {{ $category->description ?? 'Semua produk dalam kategori ' . $category->name }}</p>
                        <div class="flex items-center mt-2">
                            <i class='bx bx-package text-primary mr-2'></i>
                            <span>{{ $products->total() }} produk tersedia</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @forelse ($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                        <!-- Product Image -->
                        <div class="h-48 bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center p-4">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="max-h-full max-w-full object-contain">
                            @else
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-primary to-blue-500 rounded-xl flex items-center justify-center">
                                    <i class='bx bx-diamond text-white text-3xl'></i>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>

                            <!-- Type Badge -->
                            @if ($product->type)
                                <span class="inline-block px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 mb-3">
                                    {{ ucfirst($product->type) }}
                                </span>
                            @endif

                            <!-- Price -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-lg font-bold text-primary">
                                        Rp {{ number_format($product->min_price ?? 0, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500">Mulai dari</div>
                                </div>

                                <button
                                    class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                    Beli
                                </button>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class='bx bx-package text-gray-400 text-4xl'></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum ada produk</h3>
                        <p class="text-gray-600">Tidak ada produk tersedia dalam kategori ini</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

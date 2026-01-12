@extends('customer.layouts.app')

@section('title', 'Top Up Game & Voucher Termurah')

@section('content')
    <div x-data="$store.app.init()">
        <!-- Modern Header -->
        <div class="md:hidden bg-gradient-to-r from-gray-900 to-primary sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="bg-white p-2 rounded-lg shadow-md">
                            <h1
                                class="text-lg font-bold bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent">
                                Cahyo<span class="text-primary">Store</span>
                            </h1>
                        </div>
                        <div class="h-6 w-0.5 bg-white/20"></div>
                        <p class="text-xs text-white/80 font-medium">#1 Top Up Termurah</p>
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden flex items-center space-x-3">
                        @auth
                            <!-- Logged In - Mobile -->
                            <div class="flex items-center space-x-2 text-white">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                    <span class="text-primary font-semibold text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>

                                <div class="max-w-[120px]">
                                    <div class="font-medium text-sm truncate">{{ auth()->user()->name }}</div>
                                </div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center justify-center text-red-600 hover:text-red-800 font-medium">
                                        <i class='bx bx-log-out text-lg'></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Not Logged In - Mobile (Button Masuk biru, teks putih) -->
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all text-sm">
                                Masuk
                            </a>
                        @endauth
                    </div>

                </div>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="max-w-7xl mx-auto px-3 sm:px-4">

            <!-- Hero Slider -->
            @include('customer.components.hero-slider')

            <!-- Modern Announcement Bar -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden mt-3 sm:mt-4">
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIxMCIgY3k9IjEwIiByPSIyIiBmaWxsPSJ3aGl0ZSIgZmlsbC1vcGFjaXR5PSIwLjEiLz48L3N2Zz4=')]">
                </div>
                <div class="px-3 sm:px-4 py-2.5 relative">
                    <div class="flex items-center">
                        <div
                            class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full mr-3 flex-shrink-0 border border-white/30">
                            <div class="flex items-center text-white">
                                <div class="animate-pulse mr-1">
                                    <i class='bx bx-bell text-sm'></i>
                                </div>
                                <span class="font-bold text-sm">PROMO</span>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-1">
                            <div class="flex animate-marquee whitespace-nowrap">
                                <span class="mx-6 text-white/90 flex items-center text-xs sm:text-sm">
                                    <i class='bx bx-rocket mr-1 sm:mr-2 text-sm'></i>
                                    Gratis ongkir untuk pembelian di atas Rp 50.000
                                </span>
                                <span class="mx-6 text-white/90 flex items-center text-xs sm:text-sm">
                                    <i class='bx bx-flame mr-1 sm:mr-2 text-sm'></i>
                                    Diskon 20% untuk pembelian pertama
                                </span>
                                <span class="mx-6 text-white/90 flex items-center text-xs sm:text-sm">
                                    <i class='bx bx-bolt mr-1 sm:mr-2 text-sm'></i>
                                    Proses top up instan 24/7
                                </span>
                                <span class="mx-6 text-white/90 flex items-center text-xs sm:text-sm">
                                    <i class='bx bx-gift mr-1 sm:mr-2 text-sm'></i>
                                    Dapatkan bonus item untuk pembelian tertentu
                                </span>
                                <span class="mx-6 text-white/90 flex items-center text-xs sm:text-sm">
                                    <i class='bx bx-shield-alt mr-1 sm:mr-2 text-sm'></i>
                                    Transaksi aman & terjamin
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Bar - Minimalist -->
            <div class="bg-white border-b border-gray-100 py-3">
                <div class="px-4">
                    <div class="flex items-center justify-around">
                        <!-- Mobile: icon saja, Desktop: icon + text -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mb-1">
                                <i class='bx bx-badge-check text-blue-600 text-sm'></i>
                            </div>
                            <span class="text-[10px] font-medium text-gray-700 md:hidden">RESMI</span>
                            <span class="hidden md:block text-xs text-gray-600">Resmi</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mb-1">
                                <i class='bx bx-rocket text-green-600 text-sm'></i>
                            </div>
                            <span class="text-[10px] font-medium text-gray-700 md:hidden">CEPAT</span>
                            <span class="hidden md:block text-xs text-gray-600">Cepat</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mb-1">
                                <i class='bx bx-dollar-circle text-yellow-600 text-sm'></i>
                            </div>
                            <span class="text-[10px] font-medium text-gray-700 md:hidden">MURAH</span>
                            <span class="hidden md:block text-xs text-gray-600">Murah</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mb-1">
                                <i class='bx bx-headphone text-purple-600 text-sm'></i>
                            </div>
                            <span class="text-[10px] font-medium text-gray-700 md:hidden">CS 24/7</span>
                            <span class="hidden md:block text-xs text-gray-600">CS 24/7</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="py-4 sm:py-6">
                @if ($categories->count() > 0)
                    <div class="space-y-6 sm:space-y-8">
                        @foreach ($categories as $category)
                            @if ($category->products->count() > 0)
                                <section class="group">
                                    <!-- Compact Category Header -->
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <div class="h-6 w-1 bg-gradient-to-b from-primary to-purple-600"></div>
                                                <div>
                                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                                                        {{ $category->name }}
                                                    </h2>
                                                    <p class="text-gray-500 text-xs mt-0.5">
                                                        {{ $category->products->count() }} produk tersedia
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Tombol Lihat Semua dihapus -->
                                        </div>
                                    </div>

                                    <!-- Compact Products Grid - Tampilkan SEMUA produk -->
                                    <div class="relative">
                                        <div
                                            class="grid grid-cols-3 xs:grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 xl:grid-cols-8 gap-2 sm:gap-3">
                                            @foreach ($category->products as $product)
                                                @php
                                                    if ($product->source === 'digiflazz') {
                                                        $route = route('products.digiflazz.show', $product->slug);
                                                    } else {
                                                        $route = route('products.manual.show', $product->slug);
                                                    }
                                                @endphp

                                                <a href="{{ $route }}"
                                                    class="group/product bg-white border border-gray-100 hover:border-primary/20 hover:shadow-sm transition-all duration-200 relative overflow-hidden">
                                                    <div class="p-1.5 sm:p-2">
                                                        <!-- Product Image Container - Fixed Size -->
                                                        <div class="mb-1.5 sm:mb-2 relative">
                                                            <div
                                                                class="aspect-square overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 group-hover/product:from-primary/5 group-hover/product:to-primary/10 transition-all duration-200 flex items-center justify-center">
                                                                @if ($product->image)
                                                                    <!-- Fixed size image container with object-fit -->
                                                                    <div
                                                                        class="w-full h-full flex items-center justify-center">
                                                                        <img src="{{ asset($product->image) }}"
                                                                            alt="{{ $product->name }}"
                                                                            class="max-w-full max-h-full object-contain p-1 group-hover/product:scale-105 transition-transform duration-300"
                                                                            style="width: auto; height: auto; max-width: 100%; max-height: 100%;">
                                                                    </div>
                                                                @else
                                                                    <div
                                                                        class="w-full h-full flex items-center justify-center">
                                                                        <div
                                                                            class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-primary to-purple-600 rounded-full flex items-center justify-center">
                                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white"
                                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z">
                                                                                </path>
                                                                                <path fill-rule="evenodd"
                                                                                    d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                                                    clip-rule="evenodd"></path>
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Discount Badge -->
                                                            @if ($product->discount_percent > 0)
                                                                <div class="absolute top-1 right-1">
                                                                    <div
                                                                        class="bg-gradient-to-r from-red-500 to-orange-500 text-white text-xs font-bold px-1 py-0.5 shadow-sm min-w-[36px] text-center">
                                                                        -{{ $product->discount_percent }}%
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Product Name -->
                                                        <div class="text-center">
                                                            <h3
                                                                class="text-xs font-medium text-gray-900 line-clamp-2 leading-tight group-hover/product:text-primary transition-colors min-h-[32px]">
                                                                {{ $product->name }}
                                                            </h3>
                                                            @if ($product->short_description)
                                                                <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">
                                                                    {{ $product->short_description }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </section>
                            @endif
                        @endforeach
                    </div>
                @else
                    <!-- Compact Empty State -->
                    <div class="max-w-sm mx-auto text-center py-8 sm:py-12">
                        <div class="relative mb-3">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                <i class='bx bx-package text-xl sm:text-2xl text-gray-400'></i>
                            </div>
                            <div
                                class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 w-5 h-5 sm:w-6 sm:h-6 bg-gradient-to-br from-primary/20 to-purple-600/20 rounded-full animate-pulse">
                            </div>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Belum ada produk tersedia</h3>
                        <p class="text-gray-500 text-xs sm:text-sm mb-4">Kami sedang menyiapkan produk terbaik untuk Anda
                        </p>
                        <button
                            class="bg-gradient-to-r from-primary to-purple-600 hover:from-primary-dark hover:to-purple-700 text-white font-medium px-3 py-1.5 sm:px-4 sm:py-2 rounded-md shadow hover:shadow-md transition-all duration-300 text-xs sm:text-sm">
                            <i class='bx bx-refresh mr-1'></i> Refresh Halaman
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: marquee 30s linear infinite;
            width: max-content;
        }

        @media (max-width: 640px) {
            .animate-marquee {
                animation: marquee 40s linear infinite;
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .aspect-square {
            aspect-ratio: 1 / 1;
        }

        .aspect-square img {
            object-fit: contain !important;
            max-width: 80% !important;
            max-height: 80% !important;
            margin: auto;
        }

        .aspect-square>div {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .min-h-[32px] {
            min-height: 32px;
        }
    </style>
@endsection

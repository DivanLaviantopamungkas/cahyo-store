@extends('customer.layouts.app')

@section('title', setting('site_name', 'CahyoStore'))

@section('content')
    <div x-data="$store.app.init()">
        <!-- Modern Header - Minimalist -->
        <!-- Modern Header - Simple & Elegant -->
        <div class="md:hidden bg-white sticky top-0 z-50 border-b border-gray-200/80">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="mr-3">
                            <h1 class="text-xl font-bold text-gray-900">
                                {{ setting('site_name', 'CahyoStore') }}
                            </h1>
                        </div>
                        <div class="h-4 w-px bg-gray-300"></div>
                        <span class="ml-3 text-xs text-gray-500 font-medium">
                            {{ setting('site_description', 'Top Up Termurah') }}
                        </span>
                    </div>

                    <!-- User -->
                    <div>
                        @auth
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-700 font-bold text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" title="Logout" class="p-1.5 text-gray-400 hover:text-red-500">
                                        <i class='bx bx-log-out-circle text-lg'></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-3 sm:px-4">

            @include('customer.components.hero-slider')

            <div
                class="bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden mt-3 sm:mt-4 rounded-lg shadow-sm">
                <div class="px-3 sm:px-4 py-2.5 relative">
                    <div class="flex items-center">
                        <div
                            class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full mr-3 flex-shrink-0 border border-white/30">
                            <div class="flex items-center text-white">
                                <div class="animate-pulse mr-1"><i class='bx bx-bell text-sm'></i></div>
                                <span class="font-bold text-sm">INFO</span>
                            </div>
                        </div>
                        <div class="overflow-hidden flex-1">
                            <div class="flex animate-marquee whitespace-nowrap">
                                <span class="mx-6 text-white/90 flex items-center text-xs sm:text-sm">
                                    <i class='bx bx-info-circle mr-1 sm:mr-2 text-sm'></i>
                                    {{ setting('featured_title', 'Selamat datang di ' . setting('site_name', 'CahyoStore')) }}
                                </span>
                                <span class="mx-6 text-white/90 flex items-center text-xs sm:text-sm">
                                    <i class='bx bx-gift mr-1 sm:mr-2 text-sm'></i>
                                    {{ setting('featured_description', 'Nikmati promo menarik setiap hari!') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border-b border-gray-100 py-3 rounded-lg mt-2 shadow-sm">
                <div class="px-4">
                    <div class="flex items-center justify-around">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mb-1">
                                <i class='bx bx-badge-check text-blue-600 text-sm'></i>
                            </div>
                            <span class="text-xs text-gray-600">Resmi</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mb-1">
                                <i class='bx bx-rocket text-green-600 text-sm'></i>
                            </div>
                            <span class="text-xs text-gray-600">Cepat</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mb-1">
                                <i class='bx bx-dollar-circle text-yellow-600 text-sm'></i>
                            </div>
                            <span class="text-xs text-gray-600">Murah</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mb-1">
                                <i class='bx bx-headphone text-purple-600 text-sm'></i>
                            </div>
                            <span class="text-xs text-gray-600">CS 24/7</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-4 sm:py-6">
                @if ($categories->count() > 0)
                    <div class="space-y-6 sm:space-y-8">
                        @foreach ($categories as $category)
                            @if ($category->products->count() > 0)
                                <section class="group">
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <div class="h-6 w-1 bg-gradient-to-b from-primary to-purple-600"></div>
                                                <div>
                                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                                                        {{ $category->name }}</h2>
                                                    <p class="text-gray-500 text-xs mt-0.5">
                                                        {{ $category->products->count() }} produk</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <div
                                            class="grid grid-cols-3 xs:grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 xl:grid-cols-8 gap-2 sm:gap-3">
                                            @foreach ($category->products as $product)
                                                @php
                                                    $route =
                                                        $product->source === 'digiflazz'
                                                            ? route('products.digiflazz.show', $product->slug)
                                                            : route('products.manual.show', $product->slug);
                                                @endphp
                                                <a href="{{ $route }}"
                                                    class="group/product bg-white border border-gray-100 hover:border-primary/20 hover:shadow-sm transition-all duration-200 relative overflow-hidden rounded-lg">
                                                    <div class="p-1.5 sm:p-2">
                                                        <div class="mb-1.5 sm:mb-2 relative">
                                                            <div
                                                                class="aspect-square overflow-hidden bg-gray-50 flex items-center justify-center rounded-md">
                                                                @if ($product->image)
                                                                    <img src="{{ asset($product->image) }}"
                                                                        alt="{{ $product->name }}"
                                                                        class="max-w-full max-h-full object-contain p-1">
                                                                @else
                                                                    <div
                                                                        class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                                                                        {{ substr($product->name, 0, 1) }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="text-center">
                                                            <h3
                                                                class="text-xs font-medium text-gray-900 line-clamp-2 min-h-[32px]">
                                                                {{ $product->name }}</h3>
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
                    <div class="max-w-sm mx-auto text-center py-8">
                        <i class='bx bx-package text-4xl text-gray-300 mb-2'></i>
                        <p class="text-gray-500">Belum ada produk tersedia</p>
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

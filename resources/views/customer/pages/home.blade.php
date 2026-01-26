@extends('customer.layouts.app')

@section('title', setting('site_name', 'CahyoStore'))

@section('content')
    <div x-data="$store.app.init()" class="bg-[#F4F7FA] min-h-screen">
        <div class="md:hidden bg-white sticky top-0 z-50 border-b border-gray-100/80 backdrop-blur-md">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <h1 class="text-lg font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent leading-none">
                            {{ setting('site_name', 'CahyoStore') }}
                        </h1>
                        <span class="text-[10px] text-gray-400 font-bold tracking-tight uppercase">Top Up Center</span>
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
                            <div class="flex flex-col items-end">
                                <span class="text-[9px] text-gray-400 font-bold leading-none mb-1">SALDO ANDA</span>
                                <span class="text-blue-700 font-black text-xs bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 shadow-sm">
                                    Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}
                                </span>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-xs text-white bg-blue-600 px-5 py-2 rounded-full font-black shadow-lg shadow-blue-200 active:scale-95 transition-transform">
                                MASUK
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[1200px] mx-auto px-3 sm:px-4 pb-12">
            
            <div class="pt-2 sm:pt-4">
                @include('customer.components.hero-slider')
            </div>

            <div class="bg-white/80 backdrop-blur-sm mt-3 rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex items-center">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center gap-2 flex-shrink-0 z-10 shadow-lg shadow-blue-500/20">
                    <i class='bx bxs-megaphone text-sm animate-pulse'></i>
                    <span class="text-[10px] font-black tracking-widest uppercase">Info</span>
                </div>
                <div class="overflow-hidden flex-1 py-2">
                    <div class="flex animate-marquee whitespace-nowrap">
                        <span class="mx-6 text-blue-900 text-[11px] font-bold">
                            {{ setting('featured_title', 'Selamat datang di ' . setting('site_name', 'CahyoStore')) }} — {{ setting('featured_description', 'Promo menarik setiap hari!') }}
                        </span>
                        <span class="mx-6 text-blue-900 text-[11px] font-bold">
                            🔥 Proses Instan 24 Jam — Support WhatsApp Aktif — Transaksi Aman & Terpercaya!
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-2.5 mt-4">
                @foreach([
                    ['icon' => 'bx-badge-check', 'label' => 'Resmi', 'color' => 'blue'],
                    ['icon' => 'bx-rocket', 'label' => 'Cepat', 'color' => 'green'],
                    ['icon' => 'bx-dollar-circle', 'label' => 'Murah', 'color' => 'yellow'],
                    ['icon' => 'bx-headphone', 'label' => '24/7', 'color' => 'purple']
                ] as $feat)
                    <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center group hover:bg-{{$feat['color']}}-50 transition-colors cursor-default">
                        <div class="w-10 h-10 rounded-xl bg-{{$feat['color']}}-50 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                            <i class='bx {{$feat['icon']}} text-{{$feat['color']}}-600 text-2xl'></i>
                        </div>
                        <span class="text-[10px] font-black text-gray-800 uppercase tracking-tighter">{{$feat['label']}}</span>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 space-y-12">
                @forelse ($categories as $category)
                    @if ($category->products->count() > 0)
                        <section>
                            <div class="flex items-end justify-between mb-5 px-1">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-1.5 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full shadow-lg shadow-blue-500/20"></div>
                                    <div class="flex flex-col">
                                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight leading-none">{{ $category->name }}</h2>
                                        <div class="flex items-center gap-1.5 mt-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $category->products->count() }} Pilihan Produk</p>
                                        </div>
                                    </div>
                                </div>
                                <button class="bg-blue-50 px-4 py-2 rounded-xl text-[10px] font-black text-blue-600 uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all active:scale-95 shadow-sm">
                                    Semua
                                </button>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-3 sm:gap-4">
                                @foreach ($category->products as $product)
                                    @php
                                        $route = $product->source === 'digiflazz' 
                                            ? route('products.digiflazz.show', $product->slug) 
                                            : route('products.manual.show', $product->slug);
                                    @endphp
                                    <a href="{{ $route }}" 
                                    class="group bg-white rounded-3xl border border-gray-100 hover:border-blue-400 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 flex flex-col h-full shadow-sm overflow-hidden relative">
                                        
                                        <div class="aspect-square bg-[#FBFBFC] p-4 sm:p-6 flex items-center justify-center relative">
                                            @if ($product->image)
                                                <img src="{{ asset($product->image) }}" 
                                                    alt="{{ $product->name }}" 
                                                    class="max-w-full max-h-full object-contain p-1 transform group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-500">
                                            @else
                                                <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-blue-500/30">
                                                    {{ substr($product->name, 0, 1) }}
                                                </div>
                                            @endif

                                            <div class="absolute top-3 right-3">
                                                <div class="bg-white/90 backdrop-blur-sm p-1.5 rounded-lg shadow-md border border-gray-100 group-hover:bg-yellow-400 transition-colors">
                                                    <i class='bx bxs-zap text-yellow-400 group-hover:text-white text-xs'></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 sm:p-4 flex flex-col items-center justify-center flex-grow bg-white">
                                            <h3 class="text-[12px] sm:text-xs font-black text-gray-800 line-clamp-2 min-h-[30px] sm:min-h-[32px] leading-tight text-center uppercase tracking-tight">
                                                {{ $product->name }}
                                            </h3>
                                            
                                            <div class="mt-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
                                                <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Pesan Sekarang</span>
                                                <i class='bx bx-right-arrow-alt text-blue-600'></i>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @empty
                    <div class="flex flex-col items-center justify-center py-24 bg-white rounded-[40px] border-2 border-dashed border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class='bx bx-package text-4xl text-gray-200'></i>
                        </div>
                        <p class="text-gray-400 font-black uppercase tracking-widest text-xs">Belum Ada Produk</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .animate-marquee {
            animation: marquee 35s linear infinite;
            width: max-content;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Smooth scale behavior */
        .group:active {
            transform: scale(0.97);
        }
    </style>
@endsection
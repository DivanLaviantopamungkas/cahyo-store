@extends('customer.layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <div x-data="$store.app.init()" class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Pesanan Saya</h1>
                <p class="text-gray-600">Riwayat dan status pesanan Anda</p>
            </div>

            <!-- Login Required -->
            <template x-if="!$store.app.isLoggedIn">
                <div class="max-w-2xl mx-auto">
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class='bx bx-package text-gray-400 text-4xl'></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Tidak Ada Pesanan</h3>
                        <p class="text-gray-600 mb-6">Silakan login terlebih dahulu untuk melihat riwayat pesanan</p>

                        <!-- Login Button -->
                        <button @click="$store.app.showLoginModal = true"
                            class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-medium inline-flex items-center">
                            <i class='bx bx-log-in mr-2'></i> Masuk Sekarang
                        </button>

                        <p class="text-gray-500 text-sm mt-6">
                            Belum punya akun? Login otomatis akan membuatkan akun baru untuk Anda
                        </p>
                    </div>
                </div>
            </template>

            <!-- Orders Content (Logged In) -->
            <template x-if="$store.app.isLoggedIn">
                <div class="max-w-6xl mx-auto">
                    <!-- Order Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                            <div class="text-sm text-gray-600 mb-1">Total Pesanan</div>
                            <div class="text-2xl font-bold text-gray-800">28</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                            <div class="text-sm text-gray-600 mb-1">Dalam Proses</div>
                            <div class="text-2xl font-bold text-yellow-600">2</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                            <div class="text-sm text-gray-600 mb-1">Berhasil</div>
                            <div class="text-2xl font-bold text-green-600">26</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                            <div class="text-sm text-gray-600 mb-1">Total Belanja</div>
                            <div class="text-2xl font-bold text-blue-600">Rp 2.5M</div>
                        </div>
                    </div>

                    <!-- Order Filters -->
                    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                        <div class="flex flex-wrap items-center justify-between">
                            <div class="flex space-x-2 mb-4 md:mb-0">
                                <button class="px-4 py-2 bg-primary text-white rounded-lg font-medium">
                                    Semua
                                </button>
                                <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">
                                    Dalam Proses
                                </button>
                                <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">
                                    Berhasil
                                </button>
                                <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">
                                    Dibatalkan
                                </button>
                            </div>
                            <div class="relative">
                                <input type="text" placeholder="Cari pesanan..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <i
                                    class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            </div>
                        </div>
                    </div>

                    <!-- Order List -->
                    <div class="space-y-4">
                        @php
                            $orders = [
                                [
                                    'id' => 'INV/2024/00128',
                                    'product' => 'Mobile Legends Diamonds',
                                    'image' => '👑',
                                    'color' => 'bg-gradient-to-br from-orange-400 to-red-500',
                                    'amount' => '500 Diamonds',
                                    'price' => 'Rp 150.000',
                                    'status' => 'success',
                                    'status_text' => 'Selesai',
                                    'date' => '15 Jan 2024, 14:30',
                                    'payment' => 'QRIS',
                                ],
                                [
                                    'id' => 'INV/2024/00127',
                                    'product' => 'Free Fire Diamonds',
                                    'image' => '💎',
                                    'color' => 'bg-gradient-to-br from-blue-500 to-cyan-400',
                                    'amount' => '1000 Diamonds',
                                    'price' => 'Rp 120.000',
                                    'status' => 'process',
                                    'status_text' => 'Dalam Proses',
                                    'date' => '14 Jan 2024, 20:15',
                                    'payment' => 'Bank Transfer',
                                ],
                                [
                                    'id' => 'INV/2024/00126',
                                    'product' => 'Steam Wallet Code',
                                    'image' => '🎮',
                                    'color' => 'bg-gradient-to-br from-gray-700 to-blue-800',
                                    'amount' => 'Rp 200.000',
                                    'price' => 'Rp 190.000',
                                    'status' => 'success',
                                    'status_text' => 'Selesai',
                                    'date' => '13 Jan 2024, 09:45',
                                    'payment' => 'GoPay',
                                ],
                                [
                                    'id' => 'INV/2024/00125',
                                    'product' => 'Google Play Gift Card',
                                    'image' => '📱',
                                    'color' => 'bg-gradient-to-br from-green-400 to-blue-500',
                                    'amount' => 'Rp 100.000',
                                    'price' => 'Rp 95.000',
                                    'status' => 'success',
                                    'status_text' => 'Selesai',
                                    'date' => '12 Jan 2024, 16:20',
                                    'payment' => 'OVO',
                                ],
                                [
                                    'id' => 'INV/2024/00124',
                                    'product' => 'PUBG Mobile UC',
                                    'image' => '🎯',
                                    'color' => 'bg-gradient-to-br from-yellow-500 to-red-500',
                                    'amount' => '600 UC',
                                    'price' => 'Rp 80.000',
                                    'status' => 'success',
                                    'status_text' => 'Selesai',
                                    'date' => '10 Jan 2024, 11:10',
                                    'payment' => 'QRIS',
                                ],
                            ];
                        @endphp

                        @foreach ($orders as $order)
                            <div
                                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
                                <!-- Order Header -->
                                <div class="p-4 border-b border-gray-100">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                                        <div class="mb-2 md:mb-0">
                                            <span class="font-medium text-gray-800">{{ $order['id'] }}</span>
                                            <span class="text-sm text-gray-500 ml-2">{{ $order['date'] }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-gray-600 text-sm">Pembayaran: {{ $order['payment'] }}</span>
                                            <span
                                                class="px-3 py-1 rounded-full text-sm font-medium
                                        {{ $order['status'] == 'success'
                                            ? 'bg-green-100 text-green-800'
                                            : ($order['status'] == 'process'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800') }}">
                                                {{ $order['status_text'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Content -->
                                <div class="p-4">
                                    <div class="flex items-start">
                                        <!-- Product Image -->
                                        <div
                                            class="{{ $order['color'] }} w-16 h-16 rounded-xl flex items-center justify-center mr-4">
                                            <span class="text-3xl">{{ $order['image'] }}</span>
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-800 mb-1">{{ $order['product'] }}</h3>
                                            <p class="text-gray-600 text-sm mb-2">{{ $order['amount'] }}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-lg text-gray-800">{{ $order['price'] }}</span>
                                                <div class="flex space-x-2">
                                                    <button
                                                        class="px-4 py-2 border border-primary text-primary hover:bg-primary hover:text-white rounded-lg text-sm font-medium transition-all">
                                                        Lihat Detail
                                                    </button>
                                                    @if ($order['status'] == 'process')
                                                        <button
                                                            class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm font-medium transition-all">
                                                            Lacak
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Actions -->
                                @if ($order['status'] == 'success')
                                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                                        <div class="flex justify-end space-x-3">
                                            <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                <i class='bx bx-download mr-1'></i> Download Invoice
                                            </button>
                                            <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                <i class='bx bx-refresh mr-1'></i> Beli Lagi
                                            </button>
                                            <button class="text-primary hover:text-primary-dark text-sm font-medium">
                                                <i class='bx bx-chat mr-1'></i> Bantuan
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
                        <nav class="flex items-center space-x-2">
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                                <i class='bx bx-chevron-left'></i>
                            </button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-white">1</button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">2</button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">3</button>
                            <span class="px-2 text-gray-600">...</span>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">10</button>
                            <button
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection

@extends('customer.layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4 py-6 sm:py-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pesanan Saya</h1>
                        <p class="text-gray-600 mt-1">Kelola dan lacak pesanan Anda di sini</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class='bx bx-calendar text-primary'></i>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if(!auth()->check())
                <!-- Login Required -->
                <div class="max-w-lg mx-auto">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 text-center border border-gray-200">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-primary/10 to-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class='bx bx-package text-primary text-3xl sm:text-4xl'></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Belum Login</h3>
                        <p class="text-gray-600 mb-6">Silakan login untuk melihat riwayat pesanan Anda</p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('login') }}" 
                            class="bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white px-6 py-3 rounded-xl font-medium inline-flex items-center justify-center transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class='bx bx-log-in mr-2'></i> Masuk Sekarang
                            </a>
                            <a href="{{ route('home') }}" 
                            class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl font-medium inline-flex items-center justify-center transition-all duration-300">
                                <i class='bx bx-home mr-2'></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Stats Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-700 font-medium mb-1">Total Pesanan</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-package text-blue-600'></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-yellow-700 font-medium mb-1">Dalam Proses</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-time-five text-yellow-600'></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-700 font-medium mb-1">Berhasil</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-check-circle text-green-600'></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-700 font-medium mb-1">Total Belanja</p>
                                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
                            </div>
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-credit-card text-purple-600'></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 mb-6">
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                        <button onclick="filterOrders('all')" 
                                class="filter-btn active w-full sm:w-auto px-4 py-2.5 bg-primary text-white rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 hover:shadow-md">
                            Semua
                        </button>
                        <button onclick="filterOrders('pending')" 
                                class="filter-btn w-full sm:w-auto px-4 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200">
                            <i class='bx bx-time-five mr-1'></i> Proses
                        </button>
                        <button onclick="filterOrders('completed')" 
                                class="filter-btn w-full sm:w-auto px-4 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200">
                            <i class='bx bx-check-circle mr-1'></i> Berhasil
                        </button>
                        <button onclick="filterOrders('failed')" 
                                class="filter-btn w-full sm:w-auto px-4 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200">
                            <i class='bx bx-x-circle mr-1'></i> Gagal/Batal
                        </button>
                    </div>
                </div>

                <!-- Search Box -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                    <div class="relative">
                        <i class='bx bx-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                        <input type="text" 
                            id="searchOrders" 
                            placeholder="Cari pesanan, invoice, atau produk..."
                            class="w-full pl-12 pr-4 py-3 border-0 focus:ring-2 focus:ring-primary/20 rounded-lg bg-gray-50 focus:bg-white transition-all duration-300">
                    </div>
                </div>

                <!-- Orders List -->
                <div class="space-y-4" id="ordersContainer">
                    @forelse($transactions as $transaction)
                        @php
                            $firstItem = $transaction->items->first();
                            $product = $firstItem->product ?? null;
                            $nominal = $firstItem->nominal ?? null;
                            
                            // Status badge styling
                            $statusConfig = [
                                'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'bx-time-five', 'label' => 'Menunggu Pembayaran'],
                                'paid' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'bx-credit-card', 'label' => 'Dibayar'],
                                'processing' => ['color' => 'bg-purple-100 text-purple-800', 'icon' => 'bx-refresh', 'label' => 'Diproses'],
                                'completed' => ['color' => 'bg-green-100 text-green-800', 'icon' => 'bx-check-circle', 'label' => 'Selesai'],
                                'cancelled' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'bx-x-circle', 'label' => 'Dibatalkan'],
                                'expired' => ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'bx-time', 'label' => 'Kadaluarsa'],
                                'failed' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'bx-error', 'label' => 'Gagal'],
                            ];
                            
                            $statusInfo = $statusConfig[$transaction->status] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'bx-question-mark', 'label' => $transaction->status];
                            
                            // Payment method icons
                            $paymentIcons = [
                                'qris' => 'bx-qr-scan',
                                'bank_transfer' => 'bx-building-house',
                                'credit_card' => 'bx-credit-card',
                                'gopay' => 'bxl-google',
                                'ovo' => 'bxl-mastercard',
                                'dana' => 'bxl-paypal',
                            ];
                            
                            $paymentIcon = $paymentIcons[$transaction->payment_method] ?? 'bx-credit-card';
                        @endphp

                        <div class="order-item bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                            data-status="{{ $transaction->status }}"
                            data-invoice="{{ $transaction->invoice }}"
                            data-product="{{ $product->name ?? '' }}">
                            <!-- Order Header -->
                            <div class="p-4 sm:p-6 border-b border-gray-100">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-start sm:items-center gap-3">
                                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                            <i class='bx bx-receipt text-primary text-xl'></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900">{{ $transaction->invoice }}</h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-sm text-gray-500">
                                                    <i class='bx bx-calendar mr-1'></i>
                                                    {{ $transaction->created_at->translatedFormat('d M Y, H:i') }}
                                                </span>
                                                <span class="text-gray-400">•</span>
                                                <span class="text-sm text-gray-500 flex items-center">
                                                    <i class='bx {{ $paymentIcon }} mr-1'></i>
                                                    {{ strtoupper($transaction->payment_method) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusInfo['color'] }} flex items-center gap-1.5">
                                            <i class='bx {{ $statusInfo['icon'] }}'></i>
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Content -->
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row gap-6">
                                    <!-- Product Image -->
                                    <div class="sm:w-1/6">
                                        <div class="w-20 h-20 sm:w-full sm:h-40 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center">
                                            @if($product && $product->image)
                                                <img src="{{ asset($product->image) }}" 
                                                    alt="{{ $product->name }}" 
                                                    class="w-full h-full object-contain p-2">
                                            @else
                                                <i class='bx bx-package text-gray-400 text-3xl sm:text-4xl'></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="sm:w-5/6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-lg mb-2">{{ $product->name ?? 'Produk' }}</h4>
                                                @if($nominal)
                                                    <p class="text-gray-600 mb-1">{{ $nominal->name }}</p>
                                                @endif
                                                @if($firstItem && $firstItem->phone)
                                                    <p class="text-gray-600 text-sm">
                                                        <i class='bx bx-phone mr-1'></i> {{ $firstItem->phone }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Harga Produk</span>
                                                    <span class="font-medium text-gray-900">
                                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Status</span>
                                                    <span class="font-medium {{ $transaction->status == 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                                                        {{ $statusInfo['label'] }}
                                                    </span>
                                                </div>
                                                @if($transaction->paid_at)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Waktu Bayar</span>
                                                        <span class="text-gray-900">
                                                            {{ $transaction->paid_at->translatedFormat('d M Y, H:i') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                                        
                                        <a href="{{ route('orders.show', $transaction->id) }}" 
                                            class="order-1 flex items-center justify-center gap-2 px-3 py-2.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-semibold text-xs transition-all duration-200">
                                            <i class='bx bx-show text-sm'></i> <span>Lihat Detail</span>
                                        </a>
                                        
                                        @if($transaction->status == 'completed' && $firstItem && $firstItem->voucher_code)
                                            <button onclick="copyToClipboard('{{ $firstItem->voucher_code }}')"
                                                    class="order-2 flex items-center justify-center gap-2 px-3 py-2.5 border border-green-600 text-green-600 hover:bg-green-600 hover:text-white rounded-lg font-semibold text-xs transition-all duration-200">
                                                <i class='bx bx-copy text-sm'></i> <span>Salin Voucher</span>
                                            </button>
                                        @endif
                                        
                                        @if($transaction->status == 'pending')
                                            <a href="{{ route('checkout.payment', $transaction->id) }}" 
                                                class="order-2 flex items-center justify-center gap-2 px-3 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-semibold text-xs transition-all duration-200">
                                                <i class='bx bx-credit-card text-sm'></i> <span>Lanjutkan Pembayaran</span>
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('orders.invoice', $transaction->id) }}" target="_blank"
                                            class="order-3 col-span-2 sm:col-span-1 flex items-center justify-center gap-2 px-3 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-semibold text-xs transition-all duration-200">
                                            <i class='bx bx-download text-sm'></i> <span>Invoice</span>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-12 text-center border border-gray-200">
                            <div class="w-24 h-24 bg-gradient-to-br from-gray-50 to-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class='bx bx-package text-gray-400 text-4xl'></i>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Belum Ada Pesanan</h3>
                            <p class="text-gray-600 mb-8">Anda belum melakukan transaksi apapun</p>
                            
                            <a href="{{ route('home') }}" 
                            class="inline-flex items-center justify-center bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class='bx bx-shopping-bag mr-2'></i> Mulai Belanja
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="mt-8">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <script>
        // Filter functionality
        function filterOrders(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active', 'bg-primary', 'text-white'));
            buttons.forEach(btn => {
                if (btn.textContent.includes(status === 'all' ? 'Semua' : 
                                            status === 'pending' ? 'Dalam Proses' : 
                                            status === 'completed' ? 'Berhasil' : 'Gagal/Dibatalkan')) {
                    btn.classList.add('active', 'bg-primary', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                }
            });
            
            const orders = document.querySelectorAll('.order-item');
            orders.forEach(order => {
                if (status === 'all' || order.dataset.status === status) {
                    order.style.display = 'block';
                } else {
                    order.style.display = 'none';
                }
            });
        }

        // Search functionality
        document.getElementById('searchOrders').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const orders = document.querySelectorAll('.order-item');
            
            orders.forEach(order => {
                const invoice = order.dataset.invoice.toLowerCase();
                const product = order.dataset.product.toLowerCase();
                
                if (invoice.includes(searchTerm) || product.includes(searchTerm)) {
                    order.style.display = 'block';
                } else {
                    order.style.display = 'none';
                }
            });
        });

        // Copy voucher code
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Kode voucher berhasil disalin: ' + text);
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Orders page loaded');
        });
    </script>

    <style>
        .filter-btn.active {
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
        }

        .order-item {
            transition: all 0.3s ease;
        }

        .order-item:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* orders.blade.php - tambahkan di bagian style */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
            background-color: white;
            color: #4b5563;
        }

        .pagination li a:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            transform: translateY(-1px);
        }

        .pagination li.active span {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
            font-weight: 600;
        }

        .pagination li.disabled span {
            color: #9ca3af;
            border-color: #e5e7eb;
            background-color: #f9fafb;
            cursor: not-allowed;
        }

        .pagination .gap {
            display: flex;
            align-items: center;
            padding: 0 0.5rem;
            color: #6b7280;
        }
    </style>
@endsection
@extends('customer.layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
    <div x-data="productDetail" class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            {{-- <div class="mb-6">
                <nav class="flex text-sm text-gray-600">
                    <a href="{{ url('/') }}" class="hover:text-primary">Beranda</a>
                    <i class='bx bx-chevron-right mx-2'></i>
                    <a href="{{ route('products.index') }}" class="hover:text-primary">Produk</a>
                    <i class='bx bx-chevron-right mx-2'></i>
                    @if ($product->category)
                        <a href="{{ route('products.category.show', $product->category->slug) }}"
                            class="hover:text-primary">{{ $product->category->name }}</a>
                        <i class='bx bx-chevron-right mx-2'></i>
                    @endif
                    <span class="text-gray-800 font-medium">{{ $product->name }}</span>
                </nav>
            </div> --}}

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Product Info -->
                <div class="lg:col-span-2">
                    <!-- Product Header -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <div class="flex items-start">
                            <!-- Product Icon -->
                            <div
                                class="w-24 h-24 {{ $product->type === 'diamond' ? 'bg-gradient-to-br from-orange-400 to-red-500' : 'bg-gradient-to-br from-blue-400 to-purple-500' }} rounded-2xl flex items-center justify-center mr-6">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover rounded-2xl">
                                @else
                                    <span class="text-5xl">
                                        @if ($product->type === 'diamond')
                                            💎
                                        @elseif($product->type === 'voucher')
                                            🎫
                                        @elseif($product->type === 'item')
                                            🎮
                                        @elseif($product->type === 'pass')
                                            👑
                                        @else
                                            📱
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                                        <div class="flex items-center space-x-4">
                                            @if ($product->rating)
                                                <div class="flex items-center">
                                                    <i class='bx bxs-star text-yellow-500 mr-1'></i>
                                                    <span
                                                        class="font-medium">{{ number_format($product->rating, 1) }}</span>
                                                    <span class="text-gray-500 text-sm ml-1">
                                                        (<a href="{{ route('products.api.reviews', $product->slug) }}"
                                                            class="hover:text-primary">
                                                            {{ $product->reviews_count ?? 0 }} ulasan
                                                        </a>)
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="flex items-center">
                                                <i class='bx bx-package text-primary mr-1'></i>
                                                <span class="text-gray-600">{{ number_format($product->sold_count ?? 0) }}
                                                    terjual</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Favorite Button -->
                                    <button class="text-gray-400 hover:text-red-500">
                                        <i class='bx bx-heart text-2xl'></i>
                                    </button>
                                </div>

                                <p class="text-gray-600 mb-6">
                                    {{ $product->description ?? 'Deskripsi produk tidak tersedia.' }}
                                </p>

                                <!-- Features -->
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                                    <div class="flex items-center">
                                        <i class='bx bx-check-circle text-green-500 mr-2'></i>
                                        <span class="text-sm text-gray-700">Garansi 100%</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class='bx bx-bolt text-yellow-500 mr-2'></i>
                                        <span class="text-sm text-gray-700">Proses Instan</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class='bx bx-support text-blue-500 mr-2'></i>
                                        <span class="text-sm text-gray-700">Support 24/7</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nominal Selection -->
                    @if ($nominals->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Pilih Nominal</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($nominals as $nominal)
                                    <button @click="selectNominal(@js($nominal))"
                                        data-nominal-id="{{ $nominal->id }}"
                                        data-available-stock="{{ $nominal->available_stock }}"
                                        :class="selectedNominal?.id === {{ $nominal->id }} ?
                                            'border-2 border-primary bg-blue-50' :
                                            'border border-gray-200 hover:border-primary'"
                                        class="bg-white rounded-xl p-4 transition-all duration-200 text-left relative">
                                        @if ($nominal->available_stock == 0)
                                            <div
                                                class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-xl z-10">
                                                <span class="bg-red-500 text-white px-3 py-1 rounded-lg font-medium">
                                                    Habis
                                                </span>
                                            </div>
                                        @endif
                                        <div class="font-bold text-gray-800 mb-1">{{ $nominal->name }}</div>
                                        <div class="flex items-center">
                                            @if ($nominal->discount_price && $nominal->discount_price < $nominal->price)
                                                <div class="text-lg font-bold text-primary mb-2">
                                                    Rp {{ number_format($nominal->discount_price, 0, ',', '.') }}
                                                </div>
                                                <div class="text-sm text-gray-500 line-through ml-2">
                                                    Rp {{ number_format($nominal->price, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="text-lg font-bold text-primary mb-2">
                                                    Rp {{ number_format($nominal->price, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                        @if ($nominal->discount_price && $nominal->discount_price < $nominal->price)
                                            @php
                                                $discount = round(
                                                    (($nominal->price - $nominal->discount_price) / $nominal->price) *
                                                        100,
                                                );
                                            @endphp
                                            <div class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded inline-block">
                                                Hemat {{ $discount }}%
                                            </div>
                                        @endif
                                        @if ($nominal->available_stock <= 5 && $nominal->available_stock > 0)
                                            <div class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded inline-block mt-1">
                                                Tersisa {{ $nominal->available_stock }}
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 text-center">
                            <div class="text-gray-500 py-8">
                                <i class='bx bx-package text-4xl mb-4 text-gray-300'></i>
                                <p>Belum ada nominal tersedia untuk produk ini.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Section -->
                    <div id="payment-section" x-show="showPayment" x-transition
                        class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Pembayaran</h2>

                        <template x-if="selectedNominal">
                            <div>
                                <!-- Order Summary -->
                                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                    <h3 class="font-bold text-gray-800 mb-4">Rincian Pesanan</h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Produk</span>
                                            <span class="font-medium" x-text="selectedNominal.name"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Harga</span>
                                            <span class="font-medium"
                                                x-text="'Rp ' + formatNumber(selectedNominal.price)"></span>
                                        </div>
                                        <template
                                            x-if="selectedNominal.discount_price && selectedNominal.discount_price < selectedNominal.price">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Diskon</span>
                                                <span class="font-medium text-green-600"
                                                    x-text="'-Rp ' + formatNumber(selectedNominal.price - selectedNominal.discount_price)"></span>
                                            </div>
                                        </template>
                                        <div class="border-t border-gray-300 pt-3">
                                            <div class="flex justify-between">
                                                <span class="font-bold text-gray-800">Total Pembayaran</span>
                                                <span class="text-xl font-bold text-primary"
                                                    x-text="'Rp ' + formatNumber(selectedNominal.discount_price || selectedNominal.price)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-6">
                                    <h3 class="font-bold text-gray-800 mb-4">Metode Pembayaran</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <button @click="proceedToPayment()"
                                            :class="showQRIS ? 'border-2 border-primary bg-blue-50' : 'border border-gray-200'"
                                            class="bg-white rounded-lg p-4 text-center hover:border-primary transition-all">
                                            <div
                                                class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                                <i class='bx bx-qr text-green-600 text-2xl'></i>
                                            </div>
                                            <span class="text-sm font-medium">QRIS</span>
                                        </button>

                                        <button
                                            class="border border-gray-200 bg-white rounded-lg p-4 text-center hover:border-primary transition-all">
                                            <div
                                                class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                                <i class='bx bx-credit-card text-blue-600 text-2xl'></i>
                                            </div>
                                            <span class="text-sm font-medium">Transfer Bank</span>
                                        </button>

                                        <button
                                            class="border border-gray-200 bg-white rounded-lg p-4 text-center hover:border-primary transition-all">
                                            <div
                                                class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                                <i class='bx bx-wallet text-purple-600 text-2xl'></i>
                                            </div>
                                            <span class="text-sm font-medium">E-Wallet</span>
                                        </button>

                                        <button
                                            class="border border-gray-200 bg-white rounded-lg p-4 text-center hover:border-primary transition-all">
                                            <div
                                                class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                                <i class='bx bx-store text-orange-600 text-2xl'></i>
                                            </div>
                                            <span class="text-sm font-medium">Alfamart</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Payment Button -->
                                <button @click="proceedToPayment()"
                                    class="w-full bg-gradient-to-r from-primary to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class='bx bx-credit-card mr-2'></i> Bayar Sekarang
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- QRIS Section -->
                    <div id="qris-section" x-show="showQRIS" x-transition
                        class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Pembayaran QRIS</h2>

                        <div class="text-center">
                            <!-- QR Code Dummy -->
                            <div class="bg-white border-4 border-gray-200 rounded-2xl p-8 inline-block mb-6">
                                <div class="grid grid-cols-10 gap-1 mb-4">
                                    @for ($i = 0; $i < 100; $i++)
                                        <div class="w-3 h-3 {{ $i % 3 === 0 ? 'bg-black' : 'bg-gray-100' }} rounded-sm">
                                        </div>
                                    @endfor
                                </div>
                                <div class="text-sm text-gray-600">Scan QR Code di atas</div>
                            </div>

                            <!-- Payment Instructions -->
                            <div class="bg-blue-50 rounded-xl p-6 mb-6 text-left">
                                <h4 class="font-bold text-gray-800 mb-4">Cara Pembayaran:</h4>
                                <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                                    <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                                    <li>Pilih fitur Scan QRIS</li>
                                    <li>Arahkan kamera ke QR Code di atas</li>
                                    <li>Konfirmasi pembayaran</li>
                                    <li>Tunggu 1-5 menit untuk proses otomatis</li>
                                </ol>
                            </div>

                            <!-- Order ID -->
                            <div class="bg-gray-100 rounded-lg p-4 mb-6">
                                <div class="text-sm text-gray-600 mb-1">Kode Pesanan</div>
                                <div class="font-mono font-bold text-lg text-gray-800">ORD-{{ time() }}</div>
                                <p class="text-sm text-gray-500 mt-2">Simpan kode ini untuk konfirmasi pembayaran</p>
                            </div>

                            <!-- Countdown Timer -->
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                                <div class="flex items-center justify-center mb-2">
                                    <i class='bx bx-time text-red-500 text-xl mr-2'></i>
                                    <span class="font-bold text-red-600">Selesaikan dalam:</span>
                                </div>
                                <div class="text-2xl font-bold text-red-600" id="countdown">14:59</div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-4">
                                <button
                                    class="flex-1 border-2 border-primary text-primary hover:bg-primary hover:text-white py-3 rounded-lg font-medium transition-all">
                                    <i class='bx bx-download mr-2'></i> Simpan QR
                                </button>
                                <button
                                    class="flex-1 bg-primary hover:bg-primary-dark text-white py-3 rounded-lg font-medium transition-all">
                                    <i class='bx bx-check mr-2'></i> Sudah Bayar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Related Products & Info -->
                <div class="space-y-6">
                    <!-- Related Products -->
                    @if ($relatedProducts->count() > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Produk Terkait</h3>
                            <div class="space-y-4">
                                @foreach ($relatedProducts as $related)
                                    <a href="{{ route('products.show', $related->slug) }}"
                                        class="flex items-center p-3 border border-gray-200 rounded-lg hover:border-primary hover:shadow-sm transition-all">
                                        <div
                                            class="w-12 h-12
                                                @if ($related->type === 'diamond') bg-gradient-to-br from-orange-300 to-red-400
                                                @elseif($related->type === 'voucher') bg-gradient-to-br from-green-300 to-blue-400
                                                @elseif($related->type === 'item') bg-gradient-to-br from-purple-300 to-pink-400
                                                @elseif($related->type === 'pass') bg-gradient-to-br from-blue-300 to-cyan-400
                                                @else bg-gradient-to-br from-gray-300 to-gray-400 @endif
                                                rounded-lg flex items-center justify-center mr-4">
                                            @if ($related->image)
                                                <img src="{{ asset('storage/' . $related->image) }}"
                                                    alt="{{ $related->name }}"
                                                    class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <i class='bx bx-diamond text-white'></i>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800">{{ $related->name }}</h4>
                                            <div class="text-primary font-bold">
                                                Mulai dari Rp {{ number_format($related->min_price ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <i class='bx bx-chevron-right text-gray-400'></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Product Info -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Informasi Produk</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class='bx bx-check-circle text-green-500 mt-1 mr-3'></i>
                                <div>
                                    <h4 class="font-medium text-gray-800">Proses Instan</h4>
                                    <p class="text-sm text-gray-600">Pembelian diproses dalam 1-5 menit setelah pembayaran
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class='bx bx-shield text-blue-500 mt-1 mr-3'></i>
                                <div>
                                    <h4 class="font-medium text-gray-800">Garansi 100%</h4>
                                    <p class="text-sm text-gray-600">Uang kembali jika produk tidak terkirim</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class='bx bx-support text-purple-500 mt-1 mr-3'></i>
                                <div>
                                    <h4 class="font-medium text-gray-800">Support 24/7</h4>
                                    <p class="text-sm text-gray-600">Tim support siap membantu kapan saja</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Need Help -->
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
                        <h3 class="font-bold text-xl mb-3">Butuh Bantuan?</h3>
                        <p class="mb-4 opacity-90">Tim support kami siap membantu Anda 24/7</p>
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="block bg-white text-green-600 hover:bg-gray-100 py-3 rounded-lg font-bold text-center transition-all">
                            <i class='bx bxl-whatsapp mr-2'></i> Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:initialized', () => {
            // Countdown timer script
            let countdownTime = 15 * 60; // 15 minutes in seconds

            function updateCountdown() {
                if (countdownTime > 0) {
                    const minutes = Math.floor(countdownTime / 60);
                    const seconds = countdownTime % 60;
                    document.getElementById('countdown').textContent =
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    countdownTime--;
                } else {
                    document.getElementById('countdown').textContent = '00:00';
                    showToast('Waktu pembayaran telah habis', 'warning');
                }
            }

            // Update countdown every second
            setInterval(updateCountdown, 1000);
            updateCountdown();
        });

        function productDetail() {
            return {
                selectedNominal: null,
                showPayment: false,
                showQRIS: false,

                selectNominal(nominal) {
                    // Cek stok terlebih dahulu
                    if (nominal.available_stock === 0) {
                        showToast('Stok nominal ini habis', 'warning');
                        return;
                    }

                    this.selectedNominal = nominal;
                    this.showPayment = true;
                    this.showQRIS = false;

                    // Scroll ke payment section
                    document.getElementById('payment-section').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                },

                proceedToPayment() {
                    if (!this.selectedNominal) {
                        showToast('Silakan pilih nominal terlebih dahulu', 'warning');
                        return;
                    }

                    // Cek stok lagi sebelum melanjutkan
                    const nominalId = this.selectedNominal.id;
                    fetch(`{{ route('products.api.check-stock', '') }}/${nominalId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.available) {
                                showToast('Stok nominal ini telah habis', 'warning');
                                return;
                            }

                            this.showQRIS = true;

                            // Scroll ke QRIS section
                            document.getElementById('qris-section').scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        })
                        .catch(error => {
                            console.error('Error checking stock:', error);
                            showToast('Terjadi kesalahan, coba lagi nanti', 'error');
                        });
                },

                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            }
        }

        // Helper function untuk toast notification
        function showToast(message, type = 'info') {
            // Implementasi toast notification sesuai dengan library yang Anda gunakan
            // Contoh sederhana menggunakan alert
            alert(message);
        }
    </script>
@endsection

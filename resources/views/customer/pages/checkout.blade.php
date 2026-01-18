@extends('customer.layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-6">
            <!-- Header -->
            <div class="mb-6">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 text-sm">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-green-600">
                                <i class='bx bx-home mr-1.5'></i> Home
                            </a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right text-gray-400 text-xs'></i>
                        </li>
                        <li>
                            <a href="{{ route('products.digiflazz.show', $product->slug) }}"
                                class="inline-flex items-center text-gray-600 hover:text-green-600">
                                {{ $product->name }}
                            </a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right text-gray-400 text-xs'></i>
                        </li>
                        <li class="text-gray-900 font-medium">Checkout</li>
                    </ol>
                </nav>
            </div>

            <div class="max-w-2xl mx-auto">
                <!-- Checkout Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

                    <!-- Product Summary -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if ($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                            class="w-12 h-12 object-contain">
                                    @else
                                        <i class='bx bx-flash text-gray-400 text-2xl'></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $nominal->name }}</p>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="space-y-3">
                                @if (request('phone'))
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Nomor Handphone</span>
                                        <span class="font-medium text-gray-900">{{ request('phone') }}</span>
                                    </div>
                                @endif

                                @if (request('customer_id'))
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Nomor Pelanggan</span>
                                        <span class="font-medium text-gray-900">{{ request('customer_id') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pembayaran</h2>
                        
                        @php
                            // Hitung harga satuan (cek diskon)
                            $unitPrice = ($nominal->discount_price && $nominal->discount_price < $nominal->price) 
                                        ? $nominal->discount_price 
                                        : $nominal->price;
                            
                            // Pastikan quantity ada
                            $qty = $quantity ?? 1;
                            
                            // Hitung total
                            $totalPayment = $unitPrice * $qty;
                        @endphp

                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga Satuan</span>
                                <span class="text-gray-900">Rp {{ number_format($unitPrice, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Jumlah Pembelian</span>
                                <span class="text-gray-900 font-medium">{{ $qty }}x</span>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-900">Total Pembayaran</span>
                                    <span class="text-xl font-bold text-green-600">
                                        Rp {{ number_format($totalPayment, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-end">
                                    <div class="bg-gray-50 px-2 py-1 rounded text-right inline-block">
                                        <p class="text-[10px] sm:text-xs text-gray-500 flex items-center justify-end gap-1">
                                            <i class='bx bx-info-circle text-gray-400'></i>
                                            Biaya admin QRIS ditanggung pembeli
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>

                        <!-- Hanya QRIS yang tersedia -->
                        <div class="border border-green-200 bg-green-50 rounded-lg p-3 sm:p-4 hover:bg-green-100 transition-colors cursor-pointer">
                            <label class="flex items-center justify-between w-full cursor-pointer">

                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 bg-white rounded-lg flex-shrink-0 flex items-center justify-center border border-green-200">
                                        <i class='bx bx-qr-scan text-green-600 text-xl'></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 leading-tight">QRIS</span>
                                        <p class="text-xs text-gray-600 truncate pr-2">Scan QR Code untuk pembayaran</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <div class="text-right">
                                        <div class="text-xs sm:text-sm font-bold text-green-700">Tersedia</div>
                                        <p class="text-[10px] text-green-600 hidden sm:block">Instan & Aman</p>
                                    </div>
                                    <div class="relative flex items-center justify-center">
                                        <input type="radio" name="payment_method" value="qris" checked
                                            class="h-5 w-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 md:gap-4">
                        <a href="{{ route('home') }}"
                            class="flex-1 border border-gray-300 text-gray-700 font-medium py-2.5 px-3 text-sm md:py-3 md:px-4 md:text-base rounded-lg text-center hover:bg-gray-50 transition-colors flex items-center justify-center">
                            Kembali
                        </a>
                        <button id="payButton"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 px-3 text-sm md:py-3 md:px-4 md:text-base rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                            Bayar Sekarang
                        </button>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
                        <div>
                            <h3 class="font-medium text-blue-900 mb-1">Informasi Penting</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li class="flex items-start gap-2">
                                    <i class='bx bx-check mt-0.5'></i>
                                    Pastikan data yang Anda masukkan sudah benar
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class='bx bx-check mt-0.5'></i>
                                    Proses pengisian otomatis, tidak perlu konfirmasi manual
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class='bx bx-check mt-0.5'></i>
                                    Transaksi yang sudah dibayar tidak dapat dibatalkan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        console.log('=== INLINE SCRIPT LOADED ===');

        document.getElementById('payButton').addEventListener('click', function(e) {
            console.log('=== PAY BUTTON CLICKED ===');
            e.preventDefault();

            // Disable button
            this.disabled = true;
            this.innerHTML = '<i class="bx bx-loader bx-spin mr-2"></i> Memproses...';

            // Buat FormData
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('product_id', '{{ $product->id }}');
            formData.append('nominal_id', '{{ $nominal->id }}');
            formData.append('payment_method', 'qris');
            formData.append('phone', '{{ request('phone') }}');
            formData.append('customer_id', '{{ request('customer_id') }}');
            formData.append('quantity', '{{ $quantity ?? 1 }}');

            console.log('Sending request to: {{ route('checkout.store') }}');

            // Kirim request
            fetch('{{ route('checkout.store') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);

                    if (data.success) {
                        console.log('Redirecting to payment...');
                        window.location.href = '{{ route('checkout.payment', '') }}/' + data.order_id;
                    } else {
                        alert(data.message || 'Gagal membuat pembayaran');
                        this.disabled = false;
                        this.innerHTML = 'Bayar Sekarang';
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                    this.disabled = false;
                    this.innerHTML = 'Bayar Sekarang';
                });
        });

        console.log('Event listener attached successfully');
    </script>
@endsection

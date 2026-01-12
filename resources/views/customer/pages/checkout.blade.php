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
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga Produk</span>
                                <span class="text-gray-900">Rp {{ number_format($nominal->price, 0, ',', '.') }}</span>
                            </div>

                            @if ($nominal->discount_price && $nominal->discount_price < $nominal->price)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Diskon</span>
                                    <span class="text-green-600 font-medium">
                                        -Rp {{ number_format($nominal->price - $nominal->discount_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya Admin</span>
                                <span class="text-gray-900">Rp 0</span>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-900">Total Pembayaran</span>
                                    <span class="text-xl font-bold text-green-600">
                                        Rp {{ number_format($nominal->discount_price ?? $nominal->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <!-- Payment Method -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>

                        <!-- Hanya QRIS yang tersedia -->
                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                            <label class="flex items-center justify-between cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-green-200">
                                        <i class='bx bx-qr-scan text-green-600 text-xl'></i>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-900">QRIS</span>
                                        <p class="text-sm text-gray-600">Scan QR Code untuk pembayaran</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium text-green-700">Tersedia</div>
                                    <p class="text-xs text-green-600">Instan & Aman</p>
                                </div>
                                <input type="radio" name="payment_method" value="qris" checked
                                    class="h-4 w-4 text-green-600 border-gray-300">
                            </label>

                            <!-- Info QRIS -->
                            <div class="mt-4 pt-4 border-t border-green-200">
                                <div class="flex items-start gap-2">
                                    <i class='bx bx-check-circle text-green-600 mt-0.5'></i>
                                    <p class="text-sm text-green-700">
                                        Pembayaran via QRIS memungkinkan transfer dari bank mana pun yang mendukung
                                        QRIS
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Info tambahan -->
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">
                                <i class='bx bx-info-circle mr-1'></i>
                                Untuk saat ini hanya pembayaran QRIS yang tersedia
                            </p>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="mb-8">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="terms" required
                                class="h-4 w-4 text-green-600 border-gray-300 rounded mt-1">
                            <label for="terms" class="text-sm text-gray-600">
                                Saya setuju dengan
                                <a href="#" class="text-green-600 hover:text-green-700">Syarat & Ketentuan</a>
                                dan
                                <a href="#" class="text-green-600 hover:text-green-700">Kebijakan Privasi</a>
                                yang berlaku. Saya memahami bahwa transaksi yang sudah dibayar tidak dapat dibatalkan.
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <a href="{{ route('home') }}"
                            class="flex-1 border border-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg text-center hover:bg-gray-50 transition-colors">
                            Kembali
                        </a>
                        <button id="payButton"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
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

            // Cek terms
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                alert('Anda harus menyetujui Syarat & Ketentuan terlebih dahulu');
                return;
            }

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

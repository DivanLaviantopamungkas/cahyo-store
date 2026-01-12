@extends('customer.layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-6">
            <!-- Header -->
            <div class="mb-6">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2 text-sm">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-green-600">
                                <i class='bx bx-home mr-1.5'></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <i class='bx bx-chevron-right text-gray-400 text-xs'></i>
                        </li>
                        <li class="text-gray-900 font-medium truncate max-w-[150px]">
                            {{ $product->name }}
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Product Card -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Product Image -->
                        <div class="w-full md:w-1/3">
                            <div class="rounded-lg overflow-hidden bg-white border border-gray-200 shadow-sm">
                                @if ($product->image)
                                    <div
                                        class="bg-white flex items-center justify-center h-[180px] sm:h-[200px] md:h-[220px] lg:h-[240px]">
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                            class="object-contain max-h-full max-w-[220px] sm:max-w-[240px] md:max-w-[260px]"
                                            onerror="this.onerror=null; this.src='{{ asset('storage/' . $product->image) }}';">
                                    </div>
                                @else
                                    <div
                                        class="bg-white flex items-center justify-center h-[180px] sm:h-[200px] md:h-[220px] lg:h-[240px]">
                                        <i class='bx bx-game text-gray-500 text-5xl'></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="md:w-2/3">
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                                <div class="flex items-center gap-2 mb-4">
                                    <span
                                        class="inline-flex items-center bg-yellow-50 text-yellow-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class='bx bx-user-check mr-1'></i> Manual
                                    </span>
                                    <span
                                        class="inline-flex items-center bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class='bx bx-time mr-1'></i> 1-10 Menit
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $product->description ?? 'Pilih nominal yang diinginkan untuk melanjutkan.' }}
                                </p>
                            </div>

                            <!-- Info Section -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class='bx bx-info-circle text-yellow-500 mt-1 mr-3 text-lg'></i>
                                    <div>
                                        <h4 class="font-bold text-yellow-800 mb-1 text-sm">💡 Cara Pembelian Produk Manual
                                        </h4>
                                        <p class="text-yellow-600 text-xs leading-relaxed">
                                            1. Pilih nominal yang diinginkan<br>
                                            2. Klik tombol "Lanjutkan ke Pembayaran"<br>
                                            3. Selesaikan pembayaran via QRIS<br>
                                            4. Admin akan menghubungi Anda via WhatsApp<br>
                                            5. Berikan data akun kepada admin<br>
                                            6. Tunggu proses pengisian (1-10 menit)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nominal Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Pilih Nominal</h2>
                        <div class="text-xs text-gray-500">
                            <i class='bx bx-info-circle mr-1'></i>
                            Harga sudah termasuk biaya admin
                        </div>
                    </div>

                    @if ($nominals->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach ($nominals as $nominal)
                                <button onclick="selectNominal({{ $nominal->id }})"
                                    class="relative group border rounded-lg p-4 text-center transition-all duration-200
                                           {{ $nominal->available_stock == 0
                                               ? 'bg-gray-100 border-gray-300 cursor-not-allowed opacity-60'
                                               : 'bg-white border-gray-300 hover:border-green-500 hover:shadow-sm hover:bg-green-50' }}"
                                    data-nominal-id="{{ $nominal->id }}"
                                    {{ $nominal->available_stock == 0 ? 'disabled' : '' }}>

                                    @if ($nominal->available_stock == 0)
                                        <div
                                            class="absolute inset-0 bg-white/90 rounded-lg flex items-center justify-center z-10">
                                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                                STOK HABIS
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Nominal Name -->
                                    <div class="font-medium text-gray-900 text-sm mb-2">{{ $nominal->name }}</div>

                                    <!-- Price -->
                                    <div class="mb-2">
                                        @if ($nominal->discount_price && $nominal->discount_price < $nominal->price)
                                            <div class="space-y-1">
                                                <div class="text-lg font-bold text-green-600">
                                                    Rp {{ number_format($nominal->discount_price, 0, ',', '.') }}
                                                </div>
                                                <div class="text-xs text-gray-500 line-through">
                                                    Rp {{ number_format($nominal->price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                            @php
                                                $discount = round(
                                                    (($nominal->price - $nominal->discount_price) / $nominal->price) *
                                                        100,
                                                );
                                            @endphp
                                            <div class="mt-2">
                                                <span
                                                    class="inline-block bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded">
                                                    Hemat {{ $discount }}%
                                                </span>
                                            </div>
                                        @else
                                            <div class="text-lg font-bold text-green-600">
                                                Rp {{ number_format($nominal->price, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Stock Info -->
                                    @if ($nominal->available_stock <= 5 && $nominal->available_stock > 0)
                                        <div class="text-xs text-red-600 font-medium mt-2">
                                            <i class='bx bx-error-circle mr-1'></i> Tersisa {{ $nominal->available_stock }}
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        <!-- Info Section -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-money text-green-600'></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Harga Terjangkau</h4>
                                        <p class="text-xs text-gray-600">Harga kompetitif dengan kualitas terbaik</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-user-check text-yellow-600'></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Proses Manual</h4>
                                        <p class="text-xs text-gray-600">Admin akan bantu input data Anda</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-support text-blue-600'></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Bantuan Admin</h4>
                                        <p class="text-xs text-gray-600">Admin siap membantu via WhatsApp</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-300 mb-4">
                                <i class='bx bx-package text-4xl'></i>
                            </div>
                            <h3 class="text-base font-medium text-gray-600 mb-2">Belum Tersedia</h3>
                            <p class="text-gray-500 text-sm">Nominal untuk produk ini sedang tidak tersedia.</p>
                        </div>
                    @endif
                </div>

                <!-- Selected Nominal & CTA -->
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-4 -mx-4 -mb-6 mt-8">
                    <div class="max-w-4xl mx-auto">
                        <button id="checkoutBtn"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3.5 px-4 rounded-lg shadow transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                            disabled>
                            <i class='bx bx-shopping-bag mr-2'></i>
                            <span>Lanjutkan ke Pembayaran</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedNominalId = null;

        function selectNominal(nominalId) {
            // Check stock first
            const nominalButton = document.querySelector(`[data-nominal-id="${nominalId}"]`);
            if (nominalButton.disabled) {
                alert('Nominal ini sedang tidak tersedia');
                return;
            }

            // Reset all selections
            document.querySelectorAll('[data-nominal-id]').forEach(btn => {
                btn.classList.remove('border-green-500', 'bg-green-50', 'ring-1', 'ring-green-200');
            });

            selectedNominalId = nominalId;

            // Add selection style
            nominalButton.classList.add('border-green-500', 'bg-green-50', 'ring-1', 'ring-green-200');

            // Enable checkout button
            document.getElementById('checkoutBtn').disabled = false;
        }

        // Checkout button handler
        document.getElementById('checkoutBtn').addEventListener('click', function() {
            if (!selectedNominalId) return;

            // Check if user is logged in
            const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

            if (!isLoggedIn) {
                // Redirect to checkout (middleware akan handle redirect ke login)
                window.location.href =
                    `{{ route('checkout.create', ['product_slug' => $product->slug]) }}?nominal_id=${selectedNominalId}&product_type=manual`;
            } else {
                // User is logged in, proceed to checkout
                window.location.href =
                    `{{ route('checkout.create', ['product_slug' => $product->slug]) }}?nominal_id=${selectedNominalId}&product_type=manual`;
            }
        });

        // Auto-select if only one nominal
        @if ($nominals->count() === 1)
            window.addEventListener('DOMContentLoaded', function() {
                const nominalId = {{ $nominals->first()->id }};
                const nominalButton = document.querySelector(`[data-nominal-id="${nominalId}"]`);

                if (!nominalButton.disabled) {
                    // Auto select the only available nominal
                    selectNominal(nominalId);
                }
            });
        @endif
    </script>

    <style>
        button[data-nominal-id]:not([disabled]):hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        button[data-nominal-id].border-green-500 {
            border-width: 2px;
        }

        @media (max-width: 640px) {
            .container {
                padding-bottom: 80px;
            }
        }
    </style>
@endsection

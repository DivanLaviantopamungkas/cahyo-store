@extends('customer.layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-6">
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

            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex flex-col md:flex-row gap-6">
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

                                <div class="space-y-3">
                                    @if ($product->description)
                                        <p class="text-gray-600 text-sm leading-relaxed text-justify font-bold">
                                            {{ $product->description }}
                                        </p>
                                    @endif

                                    <p class="text-gray-500 text-sm">
                                        Pilih nominal yang diinginkan untuk melanjutkan.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Pilih Variasi</h2>
                        {{-- <div class="text-xs text-gray-500">
                            <i class='bx bx-info-circle mr-1'></i>
                            Harga sudah termasuk biaya admin
                        </div> --}}
                    </div>

                    @if ($nominals->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach ($nominals as $nominal)
                                @php
                                    // Untuk produk manual: gunakan is_available dari controller
                                    // Untuk backward compatibility, gunakan available_stock jika is_available tidak ada
                                    $isAvailable = isset($nominal->is_available)
                                        ? $nominal->is_available
                                        : $nominal->available_stock > 0;

                                    $stockText = isset($nominal->display_stock)
                                        ? $nominal->display_stock
                                        : ($isAvailable
                                            ? 'Tersedia'
                                            : 'Stok Habis');

                                    $remainingStock = 0;
                                    if ($nominal->stock_mode === 'manual') {
                                        $remainingStock = $nominal->available_voucher_codes_count ?? 0;
                                    } else {
                                        $remainingStock = $nominal->available_stock ?? 0;
                                    }
                                @endphp

                                <button onclick="selectNominal({{ $nominal->id }})"
                                    class="relative group border rounded-lg p-4 text-center transition-all duration-200
                       {{ !$isAvailable
                           ? 'bg-gray-100 border-gray-300 cursor-not-allowed opacity-60'
                           : 'bg-white border-gray-300 hover:border-green-500 hover:shadow-sm hover:bg-green-50' }}"
                                    data-nominal-id="{{ $nominal->id }}" data-stock-mode="{{ $nominal->stock_mode }}"
                                    data-voucher-count="{{ $nominal->available_voucher_codes_count ?? 0 }}"
                                    {{ !$isAvailable ? 'disabled' : '' }}>

                                    @if (!$isAvailable)
                                        <div
                                            class="absolute inset-0 bg-white/90 rounded-lg flex items-center justify-center z-10">
                                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                                {{ $stockText }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="font-medium text-gray-900 text-sm mb-2">{{ $nominal->name }}</div>

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

                                    <!-- Tampilkan stok tersisa hanya untuk produk manual -->
                                    @if ($nominal->stock_mode === 'manual' && $remainingStock > 0 && $remainingStock <= 10)
                                        <div class="text-xs text-red-600 font-medium mt-2">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            Tersisa {{ $remainingStock }} voucher
                                        </div>
                                    @endif

                                    <!-- Debug info (hapus setelah testing) -->
                                    <div class="text-xs text-gray-400 mt-1">
                                        Mode: {{ $nominal->stock_mode }} |
                                        @if ($nominal->stock_mode === 'manual')
                                            Voucher: {{ $nominal->available_voucher_codes_count ?? 0 }}
                                        @else
                                            Stock: {{ $nominal->available_stock ?? 0 }}
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Atur Jumlah Pembelian</h4>
                                    <p class="text-xs text-gray-500 mt-1">Masukkan jumlah voucher yang ingin dibeli</p>
                                </div>

                                <div class="flex items-center">
                                    <button type="button" onclick="updateQuantity(-1)"
                                        class="w-10 h-10 rounded-l-lg border border-gray-300 bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors">
                                        <i class='bx bx-minus'></i>
                                    </button>
                                    <input type="number" id="quantity" value="1" min="1" readonly
                                        class="w-16 h-10 border-t border-b border-gray-300 text-center text-gray-900 font-medium focus:outline-none focus:ring-0">
                                    <button type="button" onclick="updateQuantity(1)"
                                        class="w-10 h-10 rounded-r-lg border border-gray-300 bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-colors">
                                        <i class='bx bx-plus'></i>
                                    </button>
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
        let currentQuantity = 1;

        function selectNominal(nominalId) {
            const nominalButton = document.querySelector(`[data-nominal-id="${nominalId}"]`);
            if (nominalButton.disabled) {
                alert('Nominal ini sedang tidak tersedia');
                return;
            }

            document.querySelectorAll('[data-nominal-id]').forEach(btn => {
                btn.classList.remove('border-green-500', 'bg-green-50', 'ring-1', 'ring-green-200');
            });

            selectedNominalId = nominalId;

            nominalButton.classList.add('border-green-500', 'bg-green-50', 'ring-1', 'ring-green-200');
            document.getElementById('checkoutBtn').disabled = false;
        }

        function updateQuantity(change) {
            const qtyInput = document.getElementById('quantity');
            let newQty = currentQuantity + change;

            if (newQty < 1) newQty = 1;

            currentQuantity = newQty;
            qtyInput.value = currentQuantity;
        }

        document.getElementById('checkoutBtn').addEventListener('click', function() {
            if (!selectedNominalId) return;

            const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
            const checkoutUrl =
                `{{ route('checkout.create', ['product_slug' => $product->slug]) }}?nominal_id=${selectedNominalId}&product_type=manual&quantity=${currentQuantity}`;

            if (!isLoggedIn) {
                window.location.href = checkoutUrl;
            } else {
                window.location.href = checkoutUrl;
            }

            console.log('Redirecting to:', checkoutUrl); // Debug log

            // Redirect
            window.location.href = checkoutUrl;
        });

        @if ($nominals->count() === 1)
            window.addEventListener('DOMContentLoaded', function() {
                const nominalId = {{ $nominals->first()->id }};
                const nominalButton = document.querySelector(`[data-nominal-id="${nominalId}"]`);

                if (!nominalButton.disabled) {
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

        /* Hilangkan spinner input number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        @media (max-width: 640px) {
            .container {
                padding-bottom: 80px;
            }
        }
    </style>
@endsection

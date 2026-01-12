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
                        <div class="w-full md:w-1/3">
                            <div class="rounded-lg overflow-hidden bg-white border border-gray-200 shadow-sm">
                                @if ($product->image)
                                    <div
                                        class="bg-white flex items-center justify-center
                  h-[180px] sm:h-[200px] md:h-[220px] lg:h-[240px]">
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                            class="object-contain max-h-full max-w-[220px] sm:max-w-[240px] md:max-w-[260px]"
                                            onerror="this.onerror=null; this.src='{{ asset('storage/' . $product->image) }}';">
                                    </div>
                                @else
                                    <div
                                        class="bg-white flex items-center justify-center
                  h-[180px] sm:h-[200px] md:h-[220px] lg:h-[240px]">
                                        <i class='bx bx-flash text-gray-500 text-5xl'></i>
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
                                        class="inline-flex items-center bg-green-50 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class='bx bx-bolt mr-1'></i> Otomatis
                                    </span>
                                    <span
                                        class="inline-flex items-center bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <i class='bx bx-time mr-1'></i> Instan
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $product->description ?? 'Isi informasi akun Anda dan pilih nominal untuk melanjutkan.' }}
                                </p>
                            </div>

                            <!-- Form Input -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">1. Masukkan informasi Akun</h3>
                                <form id="customerForm" class="space-y-4">
                                    @php
                                        if (in_array($product->type, ['pulsa', 'data', 'e-wallet'])) {
                                            $inputs = ['phone'];
                                        } elseif ($product->type === 'pln') {
                                            $inputs = ['customer_id'];
                                        } elseif (in_array($product->type, ['pdam', 'bpjs'])) {
                                            $inputs = ['customer_id', 'phone'];
                                        } else {
                                            $inputs = ['phone'];
                                        }
                                    @endphp

                                    @if (in_array('phone', $inputs))
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nomor Handphone <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="tel" name="phone" id="phone"
                                                    placeholder="081234567890"
                                                    class="w-full pl-4 pr-4 py-3 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors text-sm"
                                                    required>
                                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                    <button type="button" class="text-gray-400 hover:text-gray-600"
                                                        title="Tutorial">
                                                        <i class='bx bx-help-circle text-lg'></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Contoh: 081234567890</p>
                                        </div>
                                    @endif

                                    @if (in_array('customer_id', $inputs))
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                @if ($product->type === 'pln')
                                                    Nomor ID Pelanggan PLN <span class="text-red-500">*</span>
                                                @else
                                                    Nomor Pelanggan <span class="text-red-500">*</span>
                                                @endif
                                            </label>
                                            <div class="relative">
                                                <input type="text" name="customer_id" id="customer_id"
                                                    placeholder="@if ($product->type === 'pln') 12345678901 @else 1234567890 @endif"
                                                    class="w-full pl-4 pr-4 py-3 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors text-sm"
                                                    required>
                                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                    <button type="button" class="text-gray-400 hover:text-gray-600"
                                                        title="Tutorial">
                                                        <i class='bx bx-help-circle text-lg'></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                @if ($product->type === 'pln')
                                                    Masukkan 11 digit nomor ID pelanggan PLN
                                                @else
                                                    Masukkan nomor pelanggan sesuai tagihan
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nominal Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">2. Pilih Nominal</h2>
                        <div class="text-xs text-gray-500">
                            <i class='bx bx-info-circle mr-1'></i>
                            Harga sudah termasuk biaya admin
                        </div>
                    </div>

                    @if ($nominals->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach ($nominals as $nominal)
                                <button onclick="handleNominalClick({{ $nominal->id }})"
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

                                    <!-- Tag Info -->
                                    @if ($nominal->price <= 20000)
                                        <div class="text-xs text-gray-600">
                                            Tanpa Potongan Admin
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
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Lebih Murah</h4>
                                        <p class="text-xs text-gray-600">Hemat hingga 39% tanpa biaya admin tambahan</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-bolt text-blue-600'></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Mudah & Otomatis</h4>
                                        <p class="text-xs text-gray-600">Tidak perlu kirim bukti pembayaran</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-time text-purple-600'></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">Tanpa Delay</h4>
                                        <p class="text-xs text-gray-600">Pengiriman hanya hitungan detik</p>
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

                <!-- CTA Button -->
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

        function handleNominalClick(nominalId) {
            // Check stock first
            const nominalButton = document.querySelector(`[data-nominal-id="${nominalId}"]`);
            if (nominalButton.disabled) {
                alert('Nominal ini sedang tidak tersedia');
                return;
            }

            // Validate form
            if (!validateForm()) {
                return;
            }

            // Select the nominal
            selectNominal(nominalId);
        }

        function validateForm() {
            const form = document.getElementById('customerForm');
            const inputs = form.querySelectorAll('input[required]');
            let formValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    formValid = false;
                    input.classList.add('border-red-500');
                    input.classList.remove('border-gray-300');
                    input.focus();
                } else {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-gray-300');
                }
            });

            if (!formValid) {
                alert('Harap isi semua data yang diperlukan terlebih dahulu');
            }

            return formValid;
        }

        function selectNominal(nominalId) {
            // Reset all selections
            document.querySelectorAll('[data-nominal-id]').forEach(btn => {
                btn.classList.remove('border-green-500', 'bg-green-50', 'ring-1', 'ring-green-200');
            });

            selectedNominalId = nominalId;

            // Add selection style
            const nominalButton = document.querySelector(`[data-nominal-id="${nominalId}"]`);
            nominalButton.classList.add('border-green-500', 'bg-green-50', 'ring-1', 'ring-green-200');

            // Enable checkout button
            document.getElementById('checkoutBtn').disabled = false;

            // Scroll to checkout button on mobile
            if (window.innerWidth < 768) {
                document.getElementById('checkoutBtn').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // Form validation on input
        document.querySelectorAll('#customerForm input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            });
        });

        // Checkout button handler
        document.getElementById('checkoutBtn').addEventListener('click', function() {
            if (!selectedNominalId) return;

            // Final validation
            if (!validateForm()) {
                return;
            }

            const form = document.getElementById('customerForm');
            const formData = new FormData(form);

            // Build URL parameters
            let params = `?nominal_id=${selectedNominalId}`;
            formData.forEach((value, key) => {
                if (value.trim()) {
                    params += `&${key}=${encodeURIComponent(value)}`;
                }
            });

            // Redirect ke checkout
            window.location.href = `{{ route('checkout.create', ['product_slug' => $product->slug]) }}${params}`;
        });

        @if ($nominals->count() === 1)
            document.querySelectorAll('#customerForm input').forEach(input => {
                input.addEventListener('input', function() {
                    // Check if all inputs are filled
                    const form = document.getElementById('customerForm');
                    const inputs = form.querySelectorAll('input[required]');
                    let allFilled = true;

                    inputs.forEach(input => {
                        if (!input.value.trim()) {
                            allFilled = false;
                        }
                    });

                    if (allFilled) {
                        const nominalId = {{ $nominals->first()->id }};
                        const nominalButton = document.querySelector(`[data-nominal-id="${nominalId}"]`);

                        if (!nominalButton.disabled && !selectedNominalId) {
                            selectNominal(nominalId);
                        }
                    }
                });
            });
        @endif
    </script>

    <style>
        /* Smooth transitions */
        button[data-nominal-id]:not([disabled]):hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        /* Selected state */
        button[data-nominal-id].border-green-500 {
            border-width: 2px;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .container {
                padding-bottom: 80px;
            }
        }

        /* Animation for error */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .border-red-500 {
            animation: shake 0.5s ease-in-out;
        }
    </style>
@endsection

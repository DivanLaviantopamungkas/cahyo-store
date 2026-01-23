@extends('customer.layouts.app')

@section('title', 'Detail Pesanan - ' . $transaction->invoice)

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4 py-6 sm:py-8">
            <!-- Header with Back Button -->
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('orders.index') }}"
                            class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-300">
                            <i class='bx bx-arrow-back text-xl text-gray-600'></i>
                        </a>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Pesanan</h1>
                            <p class="text-gray-600 mt-1">Invoice: {{ $transaction->invoice }}</p>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class='bx bx-calendar text-primary'></i>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 mb-1">Status Pesanan</h2>
                                <p class="text-gray-600">{{ $statusInfo['description'] }}</p>
                            </div>
                            <span
                                class="px-4 py-2 rounded-full text-sm font-medium {{ $statusInfo['color'] }} flex items-center gap-2 w-fit">
                                <i class='bx {{ $statusInfo['icon'] }}'></i>
                                {{ $statusInfo['label'] }}
                            </span>
                        </div>

                        <!-- Timeline -->
                        <div class="space-y-4">
                            @foreach ($timelineSteps as $index => $step)
                                <div class="flex items-start gap-4">
                                    <div class="relative">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center
                                        {{ $step['completed'] ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400' }}
                                        border-2 {{ $step['current'] ? $statusInfo['timeline_color'] : 'border-transparent' }}">
                                            <i class='bx {{ $step['icon'] }} text-lg'></i>
                                        </div>
                                        @if ($index < count($timelineSteps) - 1)
                                            <div
                                                class="absolute left-1/2 top-10 -translate-x-1/2 w-0.5 h-8
                                            {{ $step['completed'] ? 'bg-primary' : 'bg-gray-200' }}">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $step['label'] }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $step['description'] }}</p>
                                            </div>
                                            @if ($step['time'])
                                                <span class="text-sm text-gray-500 whitespace-nowrap">
                                                    <i class='bx bx-time mr-1'></i>{{ $step['time'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Detail Produk</h2>
                        <div class="space-y-6">
                            @foreach ($transaction->items as $item)
                                <div class="flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 rounded-xl">
                                    <!-- Product Image -->
                                    <div class="sm:w-1/4">
                                        <div
                                            class="w-full h-40 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center">
                                            @if ($item->product && $item->product->image)
                                                <img src="{{ asset($item->product->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="w-full h-full object-contain p-4">
                                            @else
                                                <i class='bx bx-package text-gray-400 text-4xl'></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="sm:w-3/4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <h3 class="font-bold text-gray-900 text-lg">
                                                    {{ $item->product->name ?? 'Produk' }}</h3>
                                                @if ($item->nominal)
                                                    <p class="text-gray-700 mt-2">{{ $item->nominal->name }}</p>
                                                @endif
                                                <p class="text-gray-600 text-sm mt-1">Kuantitas: {{ $item->quantity }}</p>

                                                @if ($item->phone)
                                                    <div class="mt-3">
                                                        <p class="text-sm font-medium text-gray-700 mb-1">Nomor Tujuan:</p>
                                                        <p class="text-gray-900 font-medium">{{ $item->phone }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Harga Satuan</span>
                                                    <span class="font-medium text-gray-900">
                                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Subtotal</span>
                                                    <span class="font-bold text-gray-900">
                                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Voucher Code -->
                                        @if ($item->voucher_code)
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <div
                                                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                    <div>
                                                        <p class="font-medium text-gray-700 mb-1">Kode Voucher</p>
                                                        <div class="flex items-center gap-2">
                                                            <code
                                                                class="bg-gray-100 px-3 py-1.5 rounded-lg font-mono text-lg font-bold text-gray-900">
                                                                {{ $item->voucher_code }}
                                                            </code>
                                                            <button onclick="copyToClipboard('{{ $item->voucher_code }}')"
                                                                class="text-primary hover:text-primary-dark">
                                                                <i class='bx bx-copy text-xl'></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if ($item->voucher_instructions)
                                                        <div class="text-sm text-gray-600">
                                                            <p class="font-medium mb-1">Cara Pakai:</p>
                                                            <p>{{ $item->voucher_instructions }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                            <div class="space-y-3 max-w-md">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal Produk</span>
                                    <span class="text-gray-900">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Biaya Admin</span>
                                    <span class="text-gray-900">
                                        Rp {{ number_format($transaction->fee ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-gray-900 pt-3 border-t">
                                    <span>Total Pembayaran</span>
                                    <span>Rp {{ number_format($total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Info & Actions -->
                <div class="space-y-6">
                    <!-- Order Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Informasi Pesanan</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Invoice ID</p>
                                <p class="font-medium text-gray-900">{{ $transaction->invoice }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Tanggal Pesanan</p>
                                <p class="font-medium text-gray-900">
                                    {{ $transaction->created_at->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Metode Pembayaran</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class='bx {{ $paymentInfo['icon'] }} text-gray-600'></i>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $paymentInfo['label'] }}</span>
                                </div>
                            </div>
                            @if ($transaction->paid_at)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Waktu Pembayaran</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $transaction->paid_at->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                            @endif
                            @if ($transaction->payment_reference)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Referensi Pembayaran</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->payment_reference }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Informasi Pelanggan</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nama</p>
                                <p class="font-medium text-gray-900">{{ $transaction->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Email</p>
                                <p class="font-medium text-gray-900">{{ $transaction->user->email }}</p>
                            </div>
                            @if ($transaction->user->phone)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Telepon</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->user->phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Aksi</h2>
                        <div class="space-y-3">
                            @if ($transaction->status == 'pending')
                                <a href="{{ route('checkout.payment', $transaction->id) }}"
                                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white px-4 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i class='bx bx-credit-card'></i>
                                    Lanjutkan Pembayaran
                                </a>
                            @endif

                            <a href="{{ route('orders.invoice', $transaction->id) }}" target="_blank"
                                class="w-full flex items-center justify-center gap-2 border border-primary text-primary hover:bg-primary hover:text-white px-4 py-3 rounded-xl font-medium transition-all duration-200">
                                <i class='bx bx-download'></i>
                                Unduh Invoice
                            </a>

                            @if ($transaction->status == 'completed' && $transaction->items->first()->voucher_code)
                                <button onclick="copyToClipboard('{{ $transaction->items->first()->voucher_code }}')"
                                    class="w-full flex items-center justify-center gap-2 border border-green-600 text-green-600 hover:bg-green-600 hover:text-white px-4 py-3 rounded-xl font-medium transition-all duration-200">
                                    <i class='bx bx-copy'></i>
                                    Salin Kode Voucher
                                </button>
                            @endif

                            <a href="{{ route('orders.index') }}"
                                class="w-full flex items-center justify-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                                <i class='bx bx-arrow-back'></i>
                                Kembali ke Daftar Pesanan
                            </a>

                            @if (in_array($transaction->status, ['pending', 'paid', 'processing']))
                                <button onclick="showCancelModal()"
                                    class="w-full flex items-center justify-center gap-2 border border-red-300 text-red-600 hover:bg-red-50 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                                    <i class='bx bx-x-circle'></i>
                                    Batalkan Pesanan
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Support Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class='bx bx-support text-blue-600 text-2xl'></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">Butuh Bantuan?</h3>
                                <p class="text-sm text-gray-700 mb-3">Jika ada masalah dengan pesanan Anda, hubungi
                                    customer service kami.</p>
                                <a href="https://wa.me/6281234567890" target="_blank"
                                    class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-medium">
                                    <i class='bx bxl-whatsapp'></i>
                                    Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Cancel Order Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class='bx bx-error text-red-600 text-3xl'></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Batalkan Pesanan?</h3>
            <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak
                dapat dibatalkan.</p>

            <div class="flex gap-3">
                <button onclick="hideCancelModal()"
                    class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                    Tidak, Kembali
                </button>
                <form action="{{ route('orders.cancel', $transaction->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div> --}}

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Kode berhasil disalin!');
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
                showToast('Gagal menyalin kode', 'error');
            });
        }

        function showCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
            }, 10);
        }

        function hideCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.querySelector('.bg-white').classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-4 py-3 rounded-xl font-medium shadow-lg z-50 transform transition-all duration-300 translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
            toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error'} text-xl'></i>
                <span>${message}</span>
            </div>
        `;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Close modal on outside click
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target.id === 'cancelModal') {
                hideCancelModal();
            }
        });
    </script>

    <style>
        .timeline-step {
            transition: all 0.3s ease;
        }

        .timeline-step.current {
            transform: scale(1.1);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        #cancelModal .bg-white {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #cancelModal.hidden .bg-white {
            transform: scale(0.95);
            opacity: 0;
        }
    </style>
@endsection

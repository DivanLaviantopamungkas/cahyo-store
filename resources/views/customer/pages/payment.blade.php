@extends('customer.layouts.app')

@section('title', 'Pembayaran - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran QRIS</h1>
                <p class="text-gray-600">Klik tombol di bawah untuk melanjutkan pembayaran</p>
            </div>

            <div class="max-w-md mx-auto">
                <!-- Payment Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                    <!-- Order Info -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="text-sm text-gray-600">Invoice</span>
                                <p class="font-semibold text-gray-900">{{ $transaction->invoice }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-600">Total</span>
                                <p class="text-xl font-bold text-green-600">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Countdown Timer -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <i class='bx bx-time-five text-yellow-600 text-xl'></i>
                                <span class="font-medium text-yellow-800">Batas Waktu Pembayaran</span>
                            </div>
                            <div class="text-center">
                                <div id="countdown" class="text-2xl font-bold text-yellow-700 mb-1">15:00</div>
                                <p class="text-sm text-yellow-600">Selesaikan pembayaran sebelum waktu habis</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <div class="mb-6">
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <div class="text-center mb-4">
                                <h3 class="font-semibold text-gray-900 mb-1">Pembayaran via Midtrans</h3>
                                <p class="text-sm text-gray-600">Anda akan diarahkan ke halaman pembayaran Midtrans</p>
                            </div>

                            @if ($transaction->payment_url)
                                <div class="text-center">
                                    <div class="mb-4">
                                        <i class='bx bx-qr-scan text-green-500 text-6xl mb-3'></i>
                                        <p class="text-gray-700">Klik tombol untuk melanjutkan</p>
                                    </div>

                                    <!-- BUTTON PASTI BISA -->
                                    <button id="payNowButton"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-8 rounded-lg text-lg transition-colors w-full">
                                        <i class='bx bx-credit-card mr-2'></i> Lanjutkan Pembayaran
                                    </button>

                                    <p class="text-sm text-gray-500 mt-3">
                                        Sistem akan otomatis memantau status pembayaran Anda
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class='bx bx-error text-gray-400 text-5xl mb-3'></i>
                                    <p class="text-gray-600">Link pembayaran tidak tersedia</p>
                                    <a href="{{ route('home') }}" class="text-blue-600 underline mt-2 inline-block">
                                        Kembali ke beranda
                                    </a>
                                </div>
                            @endif

                            <!-- Payment Instructions -->
                            <div class="mt-6 space-y-2 text-sm text-gray-600">
                                <div class="flex items-start gap-2">
                                    <i class='bx bx-check-circle text-green-500 mt-0.5'></i>
                                    <span>Klik "Lanjutkan Pembayaran" di atas</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class='bx bx-check-circle text-green-500 mt-0.5'></i>
                                    <span>Pilih metode pembayaran QRIS di halaman Midtrans</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class='bx bx-check-circle text-green-500 mt-0.5'></i>
                                    <span>Scan QR yang muncul dengan mobile banking/e-wallet</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div id="paymentStatus" class="mb-6 hidden">
                        <div class="rounded-lg p-4 text-center">
                            <div id="statusIcon" class="text-4xl mb-2"></div>
                            <h3 id="statusTitle" class="font-bold text-lg mb-1"></h3>
                            <p id="statusMessage" class="text-gray-600"></p>
                            <div id="statusAction" class="mt-3"></div>
                        </div>
                    </div>

                    <!-- Status Check Indicator -->
                    <div id="autoCheckInfo" class="mb-4 text-center">
                        <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-lg">
                            <i class='bx bx-refresh bx-spin text-blue-600'></i>
                            <span class="text-sm">Sistem sedang memantau status pembayaran...</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <a href="{{ route('home') }}"
                            class="flex-1 border border-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg text-center hover:bg-gray-50 transition-colors">
                            <i class='bx bx-home mr-2'></i> Beranda
                        </a>
                        <button onclick="location.reload()"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            <i class='bx bx-refresh mr-2'></i> Refresh
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
                                    Status pembayaran diperiksa otomatis setiap 5 detik
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class='bx bx-check mt-0.5'></i>
                                    Halaman akan otomatis redirect saat pembayaran berhasil
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class='bx bx-check mt-0.5'></i>
                                    Notifikasi WhatsApp akan dikirim otomatis setelah pembayaran
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT PASTI JALAN - LANGSUNG DI BAWAH BODY -->
    <script>
        // ==================== SCRIPT SIMPLE PASTI BISA ====================

        console.log('🚀 Payment page loaded');

        // 1. FUNCTION UNTUK REDIRECT KE MIDTRANS
        function goToMidtrans() {
            console.log('Going to Midtrans...');

            const paymentUrl = '{{ $transaction->payment_url }}';
            if (!paymentUrl) {
                alert('Link pembayaran tidak tersedia');
                return;
            }

            // Disable button
            const button = document.getElementById('payNowButton');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="bx bx-loader bx-spin mr-2"></i> Mengalihkan...';
            }

            // Redirect
            window.location.href = paymentUrl;
        }

        // 2. FUNCTION UNTUK CHECK STATUS
        function checkPayment() {
            const orderId = '{{ $transaction->id }}';

            fetch(`/checkout/validate?order_id=${orderId}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Status check:', data.status);

                    if (data.status === 'success') {
                        // Berhasil! Redirect ke success page
                        window.location.href = '{{ route('checkout.success', $transaction->id) }}';
                    } else if (data.status === 'failed') {
                        // Gagal
                        showError('Pembayaran gagal: ' + (data.message || ''));
                    }
                    // Jika pending, biarkan terus check
                })
                .catch(error => {
                    console.error('Check error:', error);
                });
        }

        // 3. FUNCTION UNTUK SHOW ERROR
        function showError(message) {
            const statusEl = document.getElementById('paymentStatus');
            if (statusEl) {
                statusEl.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <i class='bx bx-x-circle text-red-500 text-4xl mb-2'></i>
                <h3 class="font-bold text-red-700 text-lg mb-1">Pembayaran Gagal</h3>
                <p class="text-gray-600">${message}</p>
                <a href="{{ route('home') }}" class="mt-3 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Kembali ke Beranda
                </a>
            </div>
        `;
                statusEl.classList.remove('hidden');
            }
        }

        // 4. SETUP SEMUA KETIKA PAGE LOAD
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ DOM ready');

            // Setup button
            const button = document.getElementById('payNowButton');
            if (button) {
                button.addEventListener('click', goToMidtrans);
                console.log('✅ Button event attached');
            }

            // Start auto-check (setiap 10 detik)
            setInterval(checkPayment, 10000);
            console.log('✅ Auto-check started (10 seconds)');

            // Check pertama kali
            checkPayment();

            // Auto-redirect ke Midtrans setelah 3 detik (opsional)
            setTimeout(() => {
                if ('{{ $transaction->status }}' === 'pending') {
                    console.log('🔄 Auto-redirecting to Midtrans...');
                    goToMidtrans();
                }
            }, 3000);

            // Countdown timer
            let timeLeft = 15 * 60;
            const countdownEl = document.getElementById('countdown');

            function updateTimer() {
                if (timeLeft <= 0) {
                    showError('Waktu pembayaran telah habis');
                    return;
                }

                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;

                if (countdownEl) {
                    countdownEl.textContent =
                        minutes.toString().padStart(2, '0') + ':' +
                        seconds.toString().padStart(2, '0');
                }

                timeLeft--;
            }

            updateTimer();
            setInterval(updateTimer, 1000);
            console.log('✅ Countdown timer started');
        });

        // ==================== END OF SCRIPT ====================
    </script>
@endsection

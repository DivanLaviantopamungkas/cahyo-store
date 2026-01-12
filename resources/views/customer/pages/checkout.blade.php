@extends('customer.layouts.app')

@section('title', 'Checkout')

@section('content')
    <div x-data="checkoutData()" class="min-h-screen bg-gray-50">
        <!-- Toast Notification -->
        <div id="toast" class="fixed top-4 right-4 z-50 hidden">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                <i class='bx bx-check-circle text-xl mr-2'></i>
                <span id="toast-message"></span>
            </div>
        </div>

        <div class="container mx-auto px-4 py-6">
            <!-- Desktop Layout -->
            <div class="hidden lg:block">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Left Column - Product Details -->
                    <div class="md:w-2/3">
                        <!-- Product Card -->
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <div class="flex items-start space-x-6">
                                <!-- Product Image -->
                                <div
                                    class="w-32 h-32 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center">
                                    <i class='bx bx-joystick text-white text-5xl'></i>
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h2 class="text-2xl font-bold text-gray-800">Mobile Legends Diamonds</h2>
                                            <div class="flex items-center mt-2 space-x-6">
                                                <div>
                                                    <span class="text-gray-600">Harga:</span>
                                                    <span class="text-2xl font-bold text-primary ml-2">Rp 50.000</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">Stok:</span>
                                                    <span class="text-lg font-medium text-green-600 ml-2">Tersedia</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Desktop Order Summary -->
                                        <div class="bg-gray-50 rounded-xl p-6 w-80">
                                            <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                                            <div class="space-y-3 mb-4">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Subtotal</span>
                                                    <span class="font-medium">Rp 50.000</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Biaya Admin</span>
                                                    <span class="font-medium">Rp 1.500</span>
                                                </div>
                                                <div class="flex justify-between border-t border-gray-200 pt-3">
                                                    <span class="font-bold text-gray-800">Total</span>
                                                    <span class="font-bold text-2xl text-primary">Rp 51.500</span>
                                                </div>
                                            </div>
                                            <button @click="processPayment()"
                                                class="w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-lg font-bold text-lg transition-all">
                                                <i class='bx bx-credit-card mr-2'></i> Bayar Sekarang
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <h3 class="text-lg font-bold text-gray-800 mb-3">Deskripsi Produk</h3>
                                        <p class="text-gray-600">
                                            Diamonds Mobile Legends untuk semua server. Proses instan dalam 5 menit setelah
                                            pembayaran berhasil.
                                            Cocok untuk membeli skin, hero, battle pass, dan item lainnya dalam game.
                                        </p>
                                        <ul class="mt-4 space-y-2 text-gray-600">
                                            <li class="flex items-center">
                                                <i class='bx bx-check text-green-500 mr-2'></i>
                                                <span>Proses instan 1-5 menit</span>
                                            </li>
                                            <li class="flex items-center">
                                                <i class='bx bx-check text-green-500 mr-2'></i>
                                                <span>Garansi 100% aman</span>
                                            </li>
                                            <li class="flex items-center">
                                                <i class='bx bx-check text-green-500 mr-2'></i>
                                                <span>Support 24/7</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Data -->
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Data Pesanan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">ID Game</label>
                                    <input type="text" x-model="orderData.gameId" placeholder="Contoh: 12345678"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Server ID</label>
                                    <input type="text" x-model="orderData.serverId" placeholder="Contoh: 1234"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nickname</label>
                                    <input type="text" x-model="orderData.nickname" placeholder="Masukkan nickname game"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp</label>
                                    <input type="tel" x-model="orderData.phone" placeholder="0812 3456 7890"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                    <textarea x-model="orderData.notes" placeholder="Tambahkan catatan untuk pesanan ini..." rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Pembayaran (Only QRIS)</h3>

                            <!-- QRIS Info -->
                            <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class='bx bx-qr text-green-600 text-2xl'></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">QRIS (Quick Response Code Indonesian Standard)
                                        </h4>
                                        <p class="text-sm text-gray-600">Bayar dengan scan QR Code melalui aplikasi e-wallet
                                            atau mobile banking</p>
                                    </div>
                                </div>

                                <!-- QR Code Display -->
                                <div class="flex flex-col md:flex-row items-center justify-between mt-6">
                                    <div class="text-center mb-6 md:mb-0">
                                        <div class="bg-white border-4 border-gray-200 rounded-xl p-6 inline-block">
                                            <div class="grid grid-cols-10 gap-1 mb-4">
                                                <template x-for="i in 100" :key="i">
                                                    <div :class="i % 3 === 0 ? 'bg-black' : 'bg-gray-100'"
                                                        class="w-3 h-3 rounded-sm"></div>
                                                </template>
                                            </div>
                                            <div class="text-sm text-gray-600">Scan QR Code</div>
                                        </div>
                                    </div>

                                    <!-- Payment Instructions -->
                                    <div class="md:w-2/3 md:pl-8">
                                        <h5 class="font-bold text-gray-800 mb-3">Instruksi Pembayaran:</h5>
                                        <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                                            <li>Buka aplikasi e-wallet (GoPay, OVO, Dana, dll) atau mobile banking</li>
                                            <li>Pilih menu "Scan QRIS" atau "QR Code"</li>
                                            <li>Arahkan kamera ke QR Code di atas</li>
                                            <li>Pastikan nominal pembayaran sesuai</li>
                                            <li>Konfirmasi pembayaran</li>
                                            <li>Tunggu notifikasi konfirmasi otomatis</li>
                                        </ol>

                                        <!-- Order Info -->
                                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                            <div class="flex justify-between mb-2">
                                                <span class="text-gray-600">Kode Pesanan:</span>
                                                <span class="font-bold text-gray-800">ORD-{{ time() }}</span>
                                            </div>
                                            <div class="flex justify-between mb-2">
                                                <span class="text-gray-600">Total Pembayaran:</span>
                                                <span class="font-bold text-2xl text-primary">Rp 51.500</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Batas Waktu:</span>
                                                <div class="flex items-center text-red-600 font-bold">
                                                    <i class='bx bx-time mr-2'></i>
                                                    <span x-text="formatTime(countdown)">15:00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Confirmation -->
                            <div class="mt-6">
                                <label class="flex items-start">
                                    <input type="checkbox" x-model="termsAccepted"
                                        class="mt-1 mr-3 w-4 h-4 text-primary rounded">
                                    <span class="text-sm text-gray-600">
                                        Saya telah melakukan pembayaran dan setuju dengan
                                        <a href="#" class="text-primary hover:underline">Syarat & Ketentuan</a>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Layout -->
            <div class="lg:hidden">
                <!-- Product Card Mobile -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-4">
                    <div class="flex items-center space-x-4">
                        <!-- Product Image -->
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center">
                            <i class='bx bx-joystick text-white text-3xl'></i>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1">
                            <h2 class="text-lg font-bold text-gray-800">Mobile Legends Diamonds</h2>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <div class="text-gray-600">Harga:</div>
                                    <div class="text-xl font-bold text-primary">Rp 50.000</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-gray-600">Stok:</div>
                                    <div class="text-green-600 font-medium">Tersedia</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Mobile -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-2">Deskripsi Produk</h3>
                        <p class="text-sm text-gray-600">
                            Diamonds Mobile Legends untuk semua server. Proses instan dalam 5 menit setelah pembayaran
                            berhasil.
                        </p>
                    </div>
                </div>

                <!-- Order Data Mobile -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Data Pesanan</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ID Game</label>
                            <input type="text" x-model="orderData.gameId" placeholder="Contoh: 12345678"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Server ID</label>
                            <input type="text" x-model="orderData.serverId" placeholder="Contoh: 1234"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nickname</label>
                            <input type="text" x-model="orderData.nickname" placeholder="Masukkan nickname"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                            <input type="tel" x-model="orderData.phone" placeholder="0812 3456 7890"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                            <textarea x-model="orderData.notes" placeholder="Tambahkan catatan..." rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Mobile -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Pembayaran (Only QRIS)</h3>

                    <!-- QR Code Mobile -->
                    <div class="text-center mb-4">
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-4 inline-block">
                            <div class="grid grid-cols-10 gap-1 mb-3">
                                <template x-for="i in 100" :key="i">
                                    <div :class="i % 3 === 0 ? 'bg-black' : 'bg-gray-100'" class="w-2 h-2 rounded-sm">
                                    </div>
                                </template>
                            </div>
                            <div class="text-sm text-gray-600">Scan QR Code</div>
                        </div>
                    </div>

                    <!-- Payment Info Mobile -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Kode Pesanan:</span>
                            <span class="font-bold text-gray-800">ORD-{{ time() }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Batas Waktu:</span>
                            <span class="text-red-600 font-bold" x-text="formatTime(countdown)">15:00</span>
                        </div>
                    </div>

                    <!-- Instructions Mobile -->
                    <div class="text-sm text-gray-600 mb-4">
                        <p class="font-medium mb-2">Instruksi:</p>
                        <ol class="list-decimal pl-5 space-y-1">
                            <li>Buka aplikasi e-wallet atau mobile banking</li>
                            <li>Pilih menu Scan QRIS</li>
                            <li>Scan kode di atas</li>
                            <li>Konfirmasi pembayaran</li>
                        </ol>
                    </div>
                </div>

                <!-- Order Summary Mobile -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga Produk</span>
                            <span class="font-medium">Rp 50.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Admin</span>
                            <span class="font-medium">Rp 1.500</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-2xl text-primary">Rp 51.500</span>
                        </div>
                    </div>

                    <!-- Terms Mobile -->
                    <div class="mb-4">
                        <label class="flex items-start">
                            <input type="checkbox" x-model="termsAccepted"
                                class="mt-1 mr-2 w-4 h-4 text-primary rounded">
                            <span class="text-xs text-gray-600">
                                Saya telah membayar dan setuju dengan Syarat & Ketentuan
                            </span>
                        </label>
                    </div>

                    <!-- Pay Now Button Mobile -->
                    <button @click="processPayment()" :disabled="!termsAccepted"
                        :class="!termsAccepted ? 'bg-gray-400 cursor-not-allowed' : 'bg-primary hover:bg-primary-dark'"
                        class="w-full text-white py-3 rounded-lg font-bold text-lg transition-all">
                        <i class='bx bx-credit-card mr-2'></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkoutData() {
            return {
                orderData: {
                    gameId: '',
                    serverId: '',
                    nickname: '',
                    phone: '',
                    notes: ''
                },
                termsAccepted: false,
                countdown: 15 * 60, // 15 minutes in seconds
                timer: null,

                init() {
                    this.startCountdown();
                },

                startCountdown() {
                    this.timer = setInterval(() => {
                        if (this.countdown > 0) {
                            this.countdown--;
                        } else {
                            clearInterval(this.timer);
                            this.showToast('Waktu pembayaran telah habis', 'error');
                        }
                    }, 1000);
                },

                formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                },

                processPayment() {
                    if (!this.termsAccepted) {
                        this.showToast('Harap setujui syarat & ketentuan', 'error');
                        return;
                    }

                    if (!this.orderData.gameId || !this.orderData.phone) {
                        this.showToast('Harap lengkapi data pesanan', 'error');
                        return;
                    }

                    this.showToast('Pembayaran sedang diproses...', 'success');

                    // Simulate payment processing
                    setTimeout(() => {
                        this.showToast('Pembayaran berhasil!', 'success');
                        // Redirect to success page
                        setTimeout(() => {
                            window.location.href = '/checkout/success';
                        }, 2000);
                    }, 3000);
                },

                showToast(message, type = 'success') {
                    const toast = document.getElementById('toast');
                    const toastMessage = document.getElementById('toast-message');

                    toastMessage.textContent = message;
                    toast.className = `fixed top-4 right-4 z-50 flex items-center px-6 py-3 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;

                    toast.classList.remove('hidden');

                    setTimeout(() => {
                        toast.classList.add('hidden');
                    }, 3000);
                }
            };
        }
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }

        /* Focus styles */
        input:focus,
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
@endsection

@extends('customer.layouts.app')

@section('title', 'Bantuan & Support')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Butuh Bantuan?</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Kami siap membantu Anda 24/7 melalui WhatsApp. Hubungi kami untuk
                    pertanyaan seputar pembelian, pembayaran, atau masalah teknis.</p>
            </div>

            <!-- Live Chat Button -->
            <div class="max-w-md mx-auto mb-12">
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-center">
                        <div class="mr-4">
                            <i class='bx bxl-whatsapp text-4xl'></i>
                        </div>
                        <div class="text-left">
                            <h3 class="text-xl font-bold">Live Chat WhatsApp</h3>
                            <p class="opacity-90">Balas cepat dalam 5 menit</p>
                        </div>
                        <i class='bx bx-chevron-right text-2xl ml-auto'></i>
                    </div>
                </a>
            </div>

            <!-- FAQ Section -->
            <div class="max-w-4xl mx-auto mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Pertanyaan yang Sering Diajukan</h2>

                <div class="space-y-4" x-data="{ openFaq: null }">
                    @php
                        $faqs = [
                            [
                                'question' => 'Berapa lama proses top up?',
                                'answer' =>
                                    'Proses top up biasanya selesai dalam 1-5 menit setelah pembayaran berhasil. Untuk pembayaran via QRIS, proses mungkin membutuhkan waktu 2-3 menit untuk konfirmasi otomatis.',
                            ],
                            [
                                'question' => 'Bagaimana cara pembayaran?',
                                'answer' =>
                                    'Kami menerima pembayaran via QRIS, Transfer Bank (BCA, BRI, Mandiri, BNI), E-Wallet (GoPay, OVO, Dana, ShopeePay), dan Indomaret/Alfamart.',
                            ],
                            [
                                'question' => 'Apa yang harus dilakukan jika order belum diproses?',
                                'answer' =>
                                    'Jika order Anda belum diproses dalam 15 menit setelah pembayaran, silakan hubungi Live Chat WhatsApp kami dengan menyertakan nomor order/invoice.',
                            ],
                            [
                                'question' => 'Apakah ada jaminan keamanan?',
                                'answer' =>
                                    'Ya, kami menggunakan sistem pembayaran yang aman dan terenkripsi. Data pribadi Anda akan tetap rahasia dan tidak akan dibagikan kepada pihak ketiga.',
                            ],
                            [
                                'question' => 'Bisakah refund jika salah order?',
                                'answer' =>
                                    'Refund hanya dapat dilakukan jika produk belum diproses. Jika sudah diproses, tidak dapat direfund. Pastikan untuk memeriksa detail produk sebelum melakukan pembayaran.',
                            ],
                        ];
                    @endphp

                    @foreach ($faqs as $index => $faq)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-all">
                                <span class="font-medium text-gray-800">{{ $faq['question'] }}</span>
                                <i class='bx bx-chevron-down text-xl text-gray-500 transition-transform duration-300'
                                    :class="openFaq === {{ $index }} ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openFaq === {{ $index }}"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="px-6 pb-4 pt-2 border-t border-gray-100">
                                <p class="text-gray-600">{{ $faq['answer'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contact Methods -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <!-- Email -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-envelope text-blue-600 text-2xl'></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Email Support</h3>
                    <p class="text-gray-600 mb-4">Balas dalam 24 jam</p>
                    <a href="mailto:support@topupgaming.com" class="text-primary hover:text-primary-dark font-medium">
                        support@topupgaming.com
                    </a>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-phone text-green-600 text-2xl'></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Telepon</h3>
                    <p class="text-gray-600 mb-4">Senin - Jumat, 09:00 - 17:00</p>
                    <a href="tel:+6281234567890" class="text-primary hover:text-primary-dark font-medium">
                        (021) 1234 5678
                    </a>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bxs-chat text-purple-600 text-2xl'></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Social Media</h3>
                    <p class="text-gray-600 mb-4">Follow kami untuk update</p>
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="text-blue-600 hover:text-blue-700">
                            <i class='bx bxl-facebook-circle text-2xl'></i>
                        </a>
                        <a href="#" class="text-pink-600 hover:text-pink-700">
                            <i class='bx bxl-instagram text-2xl'></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-500">
                            <i class='bx bxl-twitter text-2xl'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

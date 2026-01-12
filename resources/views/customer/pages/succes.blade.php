@extends('customer.layouts.app')

@section('title', 'Pembayaran Berhasil - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <i class='bx bx-check-circle text-green-500 text-7xl'></i>
                    </div>

                    <!-- Success Message -->
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600 mb-4">
                        Terima kasih telah berbelanja. Pesanan Anda sedang diproses.
                    </p>

                    <!-- Order Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Invoice:</span>
                            <span class="font-semibold">{{ $transaction->invoice }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Produk:</span>
                            <span class="font-semibold">{{ $transaction->items->first()->product->name ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total:</span>
                            <span class="font-bold text-green-600">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- WhatsApp Notification Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-2">
                            <i class='bx bx-message-check text-blue-500 text-xl mt-0.5'></i>
                            <div class="text-left">
                                <p class="text-sm text-blue-700">
                                    Notifikasi WhatsApp akan dikirim dalam 1-2 menit.
                                    Periksa pesan WhatsApp Anda.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="{{ route('home') }}"
                            class="block bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            <i class='bx bx-home mr-2'></i> Kembali ke Beranda
                        </a>

                        <a href="{{ route('user.transactions') }}"
                            class="block border border-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class='bx bx-history mr-2'></i> Lihat Riwayat Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

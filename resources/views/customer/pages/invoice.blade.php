@extends('customer.layouts.app')

@section('title', 'Invoice ' . $transaction->invoice)

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Invoice</h1>
                    <p class="text-gray-600">Nomor: {{ $transaction->invoice }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="window.print()"
                        class="flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class='bx bx-printer'></i> Cetak
                    </button>
                    <a href="{{ route('orders.show', $transaction->id) }}"
                        class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-5 py-2.5 rounded-xl font-medium transition-all duration-200">
                        <i class='bx bx-arrow-back'></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Invoice Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden print:shadow-none">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-primary to-primary-dark p-8 text-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">INVOICE</h1>
                            <div class="space-y-1">
                                <p class="text-primary-light">Nomor Invoice: {{ $transaction->invoice }}</p>
                                <p class="text-primary-light">Tanggal:
                                    {{ $transaction->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ strtoupper($transaction->status) }}</div>
                                    <div class="text-sm opacity-90 mt-1">Status Transaksi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company & Customer Info -->
                <div class="p-8 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Company Info -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Dari:</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <i class='bx bx-store text-primary'></i>
                                    <span class="font-bold text-gray-900">Nama Toko Anda</span>
                                </div>
                                <p class="text-gray-700">Alamat lengkap perusahaan Anda</p>
                                <p class="text-gray-700">Email: info@tokoanda.com</p>
                                <p class="text-gray-700">Telepon: (021) 1234-5678</p>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Kepada:</h3>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <i class='bx bx-user text-primary'></i>
                                    <span class="font-bold text-gray-900">{{ $transaction->user->name }}</span>
                                </div>
                                <p class="text-gray-700">Email: {{ $transaction->user->email }}</p>
                                @if ($transaction->user->phone)
                                    <p class="text-gray-700">Telepon: {{ $transaction->user->phone }}</p>
                                @endif
                                <p class="text-gray-700">Customer ID: {{ $transaction->user_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="p-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Detail Pesanan</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium text-gray-700 rounded-l-lg">Produk</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Harga Satuan</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Qty</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700 rounded-r-lg">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($transaction->items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-4">
                                            <div>
                                                <div class="font-medium text-gray-900">
                                                    {{ $item->product->name ?? 'Produk' }}</div>
                                                @if ($item->nominal)
                                                    <div class="text-sm text-gray-600 mt-1">{{ $item->nominal->name }}
                                                    </div>
                                                @endif
                                                @if ($item->phone)
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        <i class='bx bx-phone mr-1'></i> {{ $item->phone }}
                                                    </div>
                                                @endif
                                                @if ($item->voucher_code)
                                                    <div class="text-sm text-primary font-medium mt-1">
                                                        Kode: {{ $item->voucher_code }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="font-medium text-gray-900">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="font-medium text-gray-900">{{ $item->quantity }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="font-medium text-gray-900">
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Summary -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="max-w-md ml-auto">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal Produk</span>
                                    <span class="font-medium text-gray-900">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600">Biaya Admin</span>
                                    <span class="font-medium text-gray-900">
                                        Rp {{ number_format($transaction->fee ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="pt-4 border-t border-gray-200">
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <span>Total Pembayaran</span>
                                        <span>Rp {{ number_format($total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="p-8 bg-gray-50 border-t border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Pembayaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Metode Pembayaran</p>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                                        @php
                                            $paymentIcons = [
                                                'qris' => 'bx-qr-scan',
                                                'bank_transfer' => 'bx-building-house',
                                                'credit_card' => 'bx-credit-card',
                                                'gopay' => 'bxl-google',
                                                'ovo' => 'bxl-mastercard',
                                                'dana' => 'bxl-paypal',
                                            ];
                                            $paymentIcon =
                                                $paymentIcons[$transaction->payment_method] ?? 'bx-credit-card';
                                        @endphp
                                        <i class='bx {{ $paymentIcon }} text-gray-600'></i>
                                    </div>
                                    <span
                                        class="font-medium text-gray-900">{{ strtoupper($transaction->payment_method) }}</span>
                                </div>
                            </div>

                            @if ($transaction->paid_at)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Tanggal Pembayaran</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $transaction->paid_at->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Status Pembayaran</p>
                                @php
                                    $statusConfig = [
                                        'pending' => [
                                            'color' => 'bg-yellow-100 text-yellow-800',
                                            'label' => 'Menunggu Pembayaran',
                                        ],
                                        'paid' => ['color' => 'bg-blue-100 text-blue-800', 'label' => 'Dibayar'],
                                        'processing' => [
                                            'color' => 'bg-purple-100 text-purple-800',
                                            'label' => 'Diproses',
                                        ],
                                        'completed' => ['color' => 'bg-green-100 text-green-800', 'label' => 'Selesai'],
                                        'cancelled' => ['color' => 'bg-red-100 text-red-800', 'label' => 'Dibatalkan'],
                                        'expired' => ['color' => 'bg-gray-100 text-gray-800', 'label' => 'Kadaluarsa'],
                                        'failed' => ['color' => 'bg-red-100 text-red-800', 'label' => 'Gagal'],
                                    ];
                                    $statusInfo = $statusConfig[$transaction->status] ?? [
                                        'color' => 'bg-gray-100 text-gray-800',
                                        'label' => $transaction->status,
                                    ];
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusInfo['color'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </div>

                            @if ($transaction->payment_reference)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Referensi Pembayaran</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->payment_reference }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-8 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h4 class="font-bold text-gray-800 mb-3">Ketentuan</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Invoice ini sah sebagai bukti pembayaran</li>
                                <li>• Harap simpan invoice untuk keperluan klaim</li>
                                <li>• Batas waktu pembayaran: 24 jam</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-800 mb-3">Pembayaran</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Pembayaran melalui {{ strtoupper($transaction->payment_method) }}</li>
                                <li>• Invoice akan kadaluarsa jika tidak dibayar</li>
                                <li>• Konfirmasi otomatis setelah pembayaran</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-800 mb-3">Hubungi Kami</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Email: support@tokoanda.com</li>
                                <li>• WhatsApp: 0812-3456-7890</li>
                                <li>• Telepon: (021) 1234-5678</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                        <div class="inline-flex items-center gap-2 text-gray-600">
                            <i class='bx bx-check-circle text-green-500'></i>
                            <span class="text-sm">Invoice ini dibuat secara otomatis dan sah secara hukum</span>
                        </div>
                    </div>
                </div>

                <!-- Stamp / Watermark -->
                <div
                    class="hidden print:block absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-10">
                    <div class="text-center">
                        <div class="text-6xl font-bold text-primary">PAID</div>
                        <div class="text-2xl mt-2 text-gray-600">INVOICE</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap gap-4 print:hidden">
                <button onclick="downloadInvoice()"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class='bx bx-download'></i> Download PDF
                </button>

                {{-- <button onclick="shareInvoice()"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class='bx bx-share-alt'></i> Bagikan
                </button>

                <a href="mailto:?subject=Invoice {{ $transaction->invoice }}&body=Berikut invoice pesanan Anda: {{ url()->current() }}"
                    class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl font-medium transition-all duration-200">
                    <i class='bx bx-envelope'></i> Kirim via Email
                </a> --}}
            </div>

            <!-- QR Code for Mobile Payment -->
            @if ($transaction->status == 'pending' && $transaction->payment_method == 'qris')
                <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 print:hidden">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="md:w-2/3">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Bayar dengan QRIS</h3>
                            <p class="text-gray-600 mb-4">Scan QR code di bawah untuk melakukan pembayaran</p>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class='bx bx-info-circle text-primary'></i>
                                    <span class="text-gray-700">Buka aplikasi mobile banking/e-wallet Anda</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class='bx bx-info-circle text-primary'></i>
                                    <span class="text-gray-700">Pilih fitur scan QRIS</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class='bx bx-info-circle text-primary'></i>
                                    <span class="text-gray-700">Arahkan kamera ke QR code di samping</span>
                                </div>
                            </div>
                        </div>
                        <div class="md:w-1/3 flex flex-col items-center">
                            <div
                                class="w-48 h-48 bg-gray-100 rounded-xl border border-gray-300 flex items-center justify-center mb-3">
                                <!-- Placeholder QR Code -->
                                <div class="text-center">
                                    <i class='bx bx-qr-scan text-6xl text-gray-400'></i>
                                    <p class="text-xs text-gray-500 mt-2">QR Code Pembayaran</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="font-bold text-gray-900 mb-1">Rp
                                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-600">Total yang harus dibayar</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Important Notes -->
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-2xl p-6 print:hidden">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class='bx bx-info-circle text-yellow-600 text-xl'></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Catatan Penting</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Invoice ini hanya sebagai bukti transaksi digital</li>
                            <li>• Simpan invoice untuk keperluan garansi atau komplain</li>
                            <li>• Klaim hanya dapat dilakukan dengan menunjukkan invoice</li>
                            <li>• Hubungi customer service untuk pertanyaan lebih lanjut</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Print invoice
        function printInvoice() {
            window.print();
        }

        // Download as PDF (simulated)
        function downloadInvoice() {
            Swal.fire({
                title: 'Download Invoice?',
                text: 'Invoice akan diunduh dalam format PDF',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Ya, Download',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Simulate download
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Invoice sedang diunduh...'
                    });

                    // In a real implementation, this would be an API call to generate PDF
                    setTimeout(() => {
                        window.open('{{ route('orders.invoice.download', $transaction->id) }}', '_blank');
                    }, 1000);
                }
            });
        }

        // Share invoice
        function shareInvoice() {
            const shareData = {
                title: 'Invoice {{ $transaction->invoice }}',
                text: 'Invoice pesanan {{ $transaction->invoice }} dari Toko Anda',
                url: window.location.href
            };

            if (navigator.share) {
                navigator.share(shareData)
                    .then(() => console.log('Invoice shared successfully'))
                    .catch((error) => console.log('Error sharing:', error));
            } else {
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Link disalin!',
                        text: 'Link invoice telah disalin ke clipboard',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            }
        }

        // Auto-hide watermark on print
        window.addEventListener('afterprint', function() {
            const watermark = document.querySelector('.absolute.opacity-10');
            if (watermark) {
                watermark.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Add print styles
            const style = document.createElement('style');
            style.innerHTML = `
            @media print {
                body * {
                    visibility: hidden;
                }
                .bg-white, .bg-white * {
                    visibility: visible;
                }
                .bg-white {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    box-shadow: none !important;
                    border: 1px solid #ddd !important;
                }
                .print\\:hidden {
                    display: none !important;
                }
                .bg-gradient-to-r {
                    background: linear-gradient(to right, #3b82f6, #1d4ed8) !important;
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
                .bg-gray-50 {
                    background-color: #f9fafb !important;
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
                .absolute.opacity-10 {
                    opacity: 0.1 !important;
                }
            }
        `;
            document.head.appendChild(style);
        });
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .invoice-header {
                background: linear-gradient(to right, #3b82f6, #1d4ed8) !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            body {
                background: white !important;
                font-size: 12pt !important;
            }

            h1,
            h2,
            h3,
            h4 {
                color: black !important;
            }

            .text-primary {
                color: #3b82f6 !important;
            }

            .border-gray-200 {
                border-color: #e5e7eb !important;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
        }

        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .bg-gradient-to-r {
            background-size: 200% auto;
            background-position: 0% 0%;
            transition: background-position 0.5s ease-in-out;
        }

        .hover\:shadow-xl:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
@endsection

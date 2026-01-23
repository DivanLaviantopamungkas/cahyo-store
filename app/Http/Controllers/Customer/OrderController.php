<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Trancsaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function index()
    {
        $transactions = Trancsaction::where('user_id', auth()->id())
            ->with(['items.product', 'items.nominal'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => Trancsaction::where('user_id', auth()->id())->count(),
            'pending' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'completed' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['completed', 'paid'])
                ->count(),
            'failed' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['cancelled', 'expired', 'failed'])
                ->count(),
            'total_spent' => Trancsaction::where('user_id', auth()->id())
                ->whereIn('status', ['completed', 'paid'])
                ->sum('amount'),
        ];

        return view('customer.pages.orders', compact('transactions', 'stats'));
    }

    public function show($order_id)
    {
        // Ambil transaksi dengan relasi lengkap
        $transaction = Trancsaction::with([
            'items.product',
            'items.nominal',
            'user'
        ])->where('user_id', auth()->id())
            ->findOrFail($order_id);

        // Hitung total_amount jika tidak ada di database
        // Gunakan amount + fee
        $subtotal = $transaction->amount ?? 0;
        $fee = $transaction->fee ?? 0;
        $total_amount = $subtotal + $fee;

        // Status badge styling
        $statusConfig = [
            'pending' => [
                'color' => 'bg-yellow-100 text-yellow-800',
                'icon' => 'bx-time-five',
                'label' => 'Menunggu Pembayaran',
                'description' => 'Pesanan menunggu pembayaran',
                'timeline_color' => 'border-yellow-500'
            ],
            'paid' => [
                'color' => 'bg-blue-100 text-blue-800',
                'icon' => 'bx-credit-card',
                'label' => 'Dibayar',
                'description' => 'Pembayaran berhasil diterima',
                'timeline_color' => 'border-blue-500'
            ],
            'processing' => [
                'color' => 'bg-purple-100 text-purple-800',
                'icon' => 'bx-refresh',
                'label' => 'Diproses',
                'description' => 'Pesanan sedang diproses',
                'timeline_color' => 'border-purple-500'
            ],
            'completed' => [
                'color' => 'bg-green-100 text-green-800',
                'icon' => 'bx-check-circle',
                'label' => 'Selesai',
                'description' => 'Pesanan telah selesai',
                'timeline_color' => 'border-green-500'
            ],
            'cancelled' => [
                'color' => 'bg-red-100 text-red-800',
                'icon' => 'bx-x-circle',
                'label' => 'Dibatalkan',
                'description' => 'Pesanan dibatalkan',
                'timeline_color' => 'border-red-500'
            ],
            'expired' => [
                'color' => 'bg-gray-100 text-gray-800',
                'icon' => 'bx-time',
                'label' => 'Kadaluarsa',
                'description' => 'Pesanan telah kadaluarsa',
                'timeline_color' => 'border-gray-500'
            ],
            'failed' => [
                'color' => 'bg-red-100 text-red-800',
                'icon' => 'bx-error',
                'label' => 'Gagal',
                'description' => 'Pesanan gagal diproses',
                'timeline_color' => 'border-red-500'
            ],
        ];

        $statusInfo = $statusConfig[$transaction->status] ?? [
            'color' => 'bg-gray-100 text-gray-800',
            'icon' => 'bx-question-mark',
            'label' => $transaction->status,
            'description' => 'Status tidak diketahui',
            'timeline_color' => 'border-gray-500'
        ];

        // Timeline steps
        $timelineSteps = $this->getTimelineSteps($transaction);

        // Payment method icons
        $paymentMethods = [
            'qris' => ['icon' => 'bx-qr-scan', 'label' => 'QRIS'],
            'bank_transfer' => ['icon' => 'bx-building-house', 'label' => 'Transfer Bank'],
            'credit_card' => ['icon' => 'bx-credit-card', 'label' => 'Kartu Kredit'],
            'gopay' => ['icon' => 'bxl-google', 'label' => 'Gopay'],
            'ovo' => ['icon' => 'bxl-mastercard', 'label' => 'OVO'],
            'dana' => ['icon' => 'bxl-paypal', 'label' => 'Dana'],
        ];

        $paymentInfo = $paymentMethods[$transaction->payment_method] ?? [
            'icon' => 'bx-credit-card',
            'label' => strtoupper($transaction->payment_method)
        ];

        return view('customer.pages.order-show', compact(
            'transaction',
            'statusInfo',
            'timelineSteps',
            'paymentInfo',
            'total_amount' // Tambahkan ini
        ));
    }

    private function getTimelineSteps($transaction)
    {
        $steps = [
            [
                'status' => 'pending',
                'label' => 'Pesanan Dibuat',
                'description' => 'Pesanan berhasil dibuat',
                'icon' => 'bx-shopping-bag',
                'completed' => true,
                'time' => $transaction->created_at->translatedFormat('d M Y, H:i'),
                'current' => $transaction->status == 'pending'
            ]
        ];

        if ($transaction->paid_at) {
            $steps[] = [
                'status' => 'paid',
                'label' => 'Pembayaran',
                'description' => 'Pembayaran berhasil dikonfirmasi',
                'icon' => 'bx-credit-card',
                'completed' => true,
                'time' => $transaction->paid_at->translatedFormat('d M Y, H:i'),
                'current' => $transaction->status == 'paid'
            ];
        } else {
            $steps[] = [
                'status' => 'paid',
                'label' => 'Pembayaran',
                'description' => 'Menunggu pembayaran',
                'icon' => 'bx-credit-card',
                'completed' => false,
                'time' => null,
                'current' => $transaction->status == 'pending'
            ];
        }

        if ($transaction->status == 'processing') {
            $steps[] = [
                'status' => 'processing',
                'label' => 'Diproses',
                'description' => 'Pesanan sedang diproses',
                'icon' => 'bx-refresh',
                'completed' => false,
                'time' => $transaction->updated_at->translatedFormat('d M Y, H:i'),
                'current' => true
            ];
        } elseif ($transaction->status == 'completed') {
            $steps[] = [
                'status' => 'processing',
                'label' => 'Diproses',
                'description' => 'Pesanan diproses',
                'icon' => 'bx-refresh',
                'completed' => true,
                'time' => $transaction->updated_at->translatedFormat('d M Y, H:i'),
                'current' => false
            ];

            $steps[] = [
                'status' => 'completed',
                'label' => 'Selesai',
                'description' => 'Pesanan telah selesai',
                'icon' => 'bx-check-circle',
                'completed' => true,
                'time' => $transaction->updated_at->translatedFormat('d M Y, H:i'),
                'current' => true
            ];
        } else {
            $steps[] = [
                'status' => 'processing',
                'label' => 'Diproses',
                'description' => 'Pesanan akan diproses',
                'icon' => 'bx-refresh',
                'completed' => false,
                'time' => null,
                'current' => false
            ];

            $steps[] = [
                'status' => 'completed',
                'label' => 'Selesai',
                'description' => 'Menunggu penyelesaian',
                'icon' => 'bx-check-circle',
                'completed' => false,
                'time' => null,
                'current' => false
            ];
        }

        return $steps;
    }

    public function invoice($order_id)
    {
        $transaction = Trancsaction::with(['items.product', 'items.nominal', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($order_id);

        // Hitung total_amount
        $subtotal = $transaction->amount ?? 0;
        $fee = $transaction->fee ?? 0;
        $total_amount = $subtotal + $fee;

        return view('customer.pages.invoice', compact('transaction', 'total_amount'));
    }

    public function downloadInvoice($order_id)
    {
        $transaction = Trancsaction::with(['items.product', 'items.nominal', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($order_id);

        // Hitung total_amount
        $subtotal = $transaction->amount ?? 0;
        $fee = $transaction->fee ?? 0;
        $total_amount = $subtotal + $fee;

        // Status configuration
        $statusConfig = [
            'pending' => ['label' => 'Menunggu Pembayaran', 'color' => '#fbbf24'],
            'paid' => ['label' => 'Dibayar', 'color' => '#3b82f6'],
            'processing' => ['label' => 'Diproses', 'color' => '#8b5cf6'],
            'completed' => ['label' => 'Selesai', 'color' => '#10b981'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => '#ef4444'],
            'expired' => ['label' => 'Kadaluarsa', 'color' => '#6b7280'],
            'failed' => ['label' => 'Gagal', 'color' => '#ef4444'],
        ];

        $statusInfo = $statusConfig[$transaction->status] ?? ['label' => $transaction->status, 'color' => '#6b7280'];

        // Payment method labels
        $paymentMethods = [
            'qris' => 'QRIS',
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'gopay' => 'Gopay',
            'ovo' => 'OVO',
            'dana' => 'Dana',
        ];

        $paymentLabel = $paymentMethods[$transaction->payment_method] ?? strtoupper($transaction->payment_method);

        $data = [
            'transaction' => $transaction,
            'total_amount' => $total_amount,
            'statusInfo' => $statusInfo,
            'paymentLabel' => $paymentLabel,
            'company' => [
                'name' => 'Nama Toko Anda',
                'address' => 'Alamat lengkap perusahaan Anda',
                'email' => 'info@tokoanda.com',
                'phone' => '(021) 1234-5678',
                'website' => 'www.tokoanda.com',
            ]
        ];

        $pdf = Pdf::loadView('customer.pages.invoice-pdf', $data);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Download PDF
        return $pdf->download('invoice-' . $transaction->invoice . '.pdf');
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createQRISPayment($orderId, $amount, $customerName, $customerEmail, $customerPhone): array
    {
        try {
            Log::info('Creating Midtrans payment for: ' . $orderId);

            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ];

            $customerDetails = [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
            ];

            // PARAMETER SIMPLE - Biarkan Midtrans handle
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'callbacks' => [
                    'finish' => route('checkout.handle.midtrans.return'),
                    'error' => route('checkout.handle.midtrans.return'),
                ],
            ];

            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);

            Log::info('Snap Token generated: ' . $snapToken);

            // Redirect URL ke Snap VTWeb
            $redirectUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken;

            return [
                'success' => true,
                'token' => $snapToken,
                'redirect_url' => $redirectUrl,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status
     *
     * @param string $orderId
     * @return object|null
     */
    public function checkStatus($orderId)
    {
        try {
            /** @var \stdClass $status */
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Midtrans status check error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get QR code URL from payment data
     *
     * @param array|object $paymentData
     * @return string|null
     */
    public function getQrUrl($paymentData): ?string
    {
        if (is_array($paymentData)) {
            return $paymentData['qr_url'] ?? $paymentData['redirect_url'] ?? null;
        }

        if (is_object($paymentData)) {
            if (isset($paymentData->qr_string)) {
                // Create QR image from string
                return 'data:image/png;base64,' . base64_encode(
                    file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' .
                        urlencode($paymentData->qr_string))
                );
            }
            return $paymentData->redirect_url ?? null;
        }

        return null;
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $phoneNumberId;
    protected $accessToken;

    public function __construct()
    {
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->accessToken = config('services.whatsapp.access_token');
    }

    public function sendMessage($phone, $message)
    {
        // Format nomor: 6281234567890
        $phone = $this->formatPhone($phone);
        if (!$phone) return false;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("https://graph.facebook.com/v17.0/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => ['body' => $message]
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendPaymentSuccess($transaction)
    {
        $phone = $transaction->user->phone;

        // WA 1: Kode Voucher (jika manual)
        if ($transaction->items[0]->fulfillment_source === 'manual') {
            $voucherCode = 'VOUCH-' . strtoupper(\Illuminate\Support\Str::random(10));

            $message = "🎫 *KODE VOUCHER ANDA*\n\n";
            $message .= "Invoice: {$transaction->invoice}\n";
            $message .= "Kode: *{$voucherCode}*\n\n";
            $message .= "Simpan kode ini untuk klaim produk.";

            $this->sendMessage($phone, $message);

            // Simpan ke database
            $transaction->items[0]->update(['voucher_code' => $voucherCode]);

            // Tunggu 2 detik
            sleep(2);
        }

        // WA 2: QR Code
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' .
            urlencode('TRX-' . $transaction->invoice);

        $message = "📱 *QR CODE PRODUK*\n\n";
        $message .= "Scan QR untuk klaim produk:\n";
        $message .= "Link: {$qrCodeUrl}";

        $this->sendMessage($phone, $message);

        // Simpan ke database
        $transaction->items[0]->update(['qr_code_url' => $qrCodeUrl]);

        // Tunggu 2 detik
        sleep(2);

        // WA 3: Terima Kasih + Expired
        $expiredDate = now()->addDays(30)->format('d F Y');

        $message = "🎉 *TERIMA KASIH*\n\n";
        $message .= "Pembayaran berhasil!\n\n";
        $message .= "Produk berlaku hingga: *{$expiredDate}*\n\n";
        $message .= "Hubungi admin jika butuh bantuan.";

        $this->sendMessage($phone, $message);

        // Update expired_at
        $transaction->items[0]->update(['expired_at' => now()->addDays(30)]);
    }

    private function formatPhone($phone)
    {
        // Format: 628123456789
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            return '62' . $phone;
        }

        return $phone;
    }
}

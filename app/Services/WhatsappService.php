<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use CURLFile;
use Endroid\QrCode\Encoding\Encoding;

class WhatsAppService
{
    protected $deviceId;

    public function __construct()
    {
        $this->deviceId = config('services.whacenter.device_id');
    }

    public function sendMessage($phone, $message)
    {
        $phone = $this->formatPhone($phone);
        $url = "https://app.whacenter.com/api/send?device_id={$this->deviceId}&number={$phone}&message=" . urlencode($message);

        $response = Http::timeout(30)->get($url);
        $data = $response->json();

        Log::info('Whacenter response', ['response' => $data]);
        return $response->successful();
    }

    /** ✅ GENERATE QR CODE */
    private function generateQrImage($voucherCode, $invoice)
    {
        $qrData = "VOUCHER:{$voucherCode}|{$invoice}";

        try {
            // ✅ endroid/qr-code v4.8.2 SYNTAX
            $qrCode = new QrCode($qrData);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);
            $qrCode->setEncoding(new Encoding('UTF-8'));

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $imagePath = storage_path("app/public/qr-{$invoice}.png");

            // Buat folder
            $dir = dirname($imagePath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $result->saveToFile($imagePath);

            if (file_exists($imagePath) && filesize($imagePath) > 100) {
                Log::info('✅ QR Generated OK', [
                    'path' => $imagePath,
                    'size' => filesize($imagePath)
                ]);
                return $imagePath;
            }

            Log::error('❌ QR Generate FAILED', ['path' => $imagePath]);
            return null;
        } catch (\Exception $e) {
            Log::error('QR Generation Error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /** ✅ UPLOAD ke Imgur */
    private function uploadToImgur($imagePath)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.imgur.com/3/image',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ['image' => new CURLFile($imagePath)],
                CURLOPT_HTTPHEADER => ['Authorization: Client-ID 8e0ccdd13e4dffd'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            $data = json_decode($response, true);
            $imgurUrl = $data['data']['link'] ?? null;

            Log::info('Imgur upload', [
                'http_code' => $httpCode,
                'url' => $imgurUrl,
                'response' => $data ?? 'no json'
            ]);

            // Hapus file lokal setelah upload
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            return $imgurUrl;
        } catch (\Exception $e) {
            Log::error('Imgur Upload Error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /** ✅ KIRIM GAMBAR via Whacenter */
    public function sendQrImage($phone, $voucherCode, $invoice)
    {
        $phone = $this->formatPhone($phone);

        // 1. Generate QR Code
        $qrImagePath = $this->generateQrImage($voucherCode, $invoice);

        if (!$qrImagePath) {
            Log::error('❌ QR Generation failed, using fallback');
            return $this->sendQrFallback($phone, $voucherCode, $invoice);
        }

        // 2. Upload ke Imgur untuk mendapatkan URL gambar
        $imgurUrl = $this->uploadToImgur($qrImagePath);

        if (!$imgurUrl) {
            Log::error('❌ Imgur upload failed, using fallback');
            return $this->sendQrFallback($phone, $voucherCode, $invoice);
        }

        // 3. Kirim gambar via Whacenter API
        $caption = "🖼️ *QR VOUCHER ANDA*\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━\n" .
            "🎫 **Voucher:** {$voucherCode}\n" .
            "📄 **Invoice:** {$invoice}\n" .
            "━━━━━━━━━━━━━━━━━━━━━\n\n" .
            "📱 **CARA SCAN:**\n" .
            "• Buka kamera HP\n" .
            "• Arahkan ke kode QR\n" .
            "• Tunggu hingga terdeteksi\n\n" .
            "✅ Klaim otomatis!";

        // Kirim gambar via Whacenter
        $url = "https://app.whacenter.com/api/send?device_id={$this->deviceId}&number={$phone}&message=" .
            urlencode($caption) . "&image=" . urlencode($imgurUrl);

        $response = Http::timeout(30)->get($url);
        $data = $response->json();

        Log::info('Whacenter Image Send', ['response' => $data]);

        return $response->successful();
    }

    /** ✅ FALLBACK jika upload gambar gagal */
    private function sendQrFallback($phone, $voucherCode, $invoice)
    {
        // Generate QR Code URL dari layanan eksternal
        $qrData = "VOUCHER:{$voucherCode}|{$invoice}";
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);

        $message = "📱 *QR VOUCHER ANDA*\n\n" .
            "Karena ada kendala teknis, silakan scan QR code melalui link berikut:\n" .
            $qrUrl . "\n\n" .
            "🎫 **Voucher:** {$voucherCode}\n" .
            "📄 **Invoice:** {$invoice}";

        return $this->sendMessage($phone, $message);
    }

    /** ✅ KIRIM SEMUA PESAN (Voucher + QR + Info) */
    public function sendPaymentSuccess($transaction)
    {
        $transaction->load(['items.product', 'items.nominal', 'user']);
        $transactionItem = $transaction->items->first();

        if (!$transactionItem) {
            Log::error('❌ NO TRANSACTION ITEM');
            return false;
        }

        $phone = $transaction->user->whatsapp ?? '6281226594919';

        if (!$transactionItem->voucher_code) {
            Log::error('❌ NO VOUCHER CODE');
            return false;
        }

        try {
            // 1. Kirim pesan voucher code
            $message1 = "🎫 *KODE VOUCHER ANDA*\n\n" .
                "━━━━━━━━━━━━━━━━━━━━━\n" .
                "📄 **Invoice:** {$transaction->invoice}\n" .
                "📦 **Produk:** {$transactionItem->product->name}\n" .
                "🎫 **Kode:** `{$transactionItem->voucher_code}`\n" .
                "━━━━━━━━━━━━━━━━━━━━━\n\n" .
                "💡 Simpan kode ini untuk klaim";

            $this->sendMessage($phone, $message1);
            sleep(2);

            // 2. Kirim QR Code sebagai gambar
            $qrSent = $this->sendQrImage($phone, $transactionItem->voucher_code, $transaction->invoice);
            Log::info('QR Send Status', ['sent' => $qrSent]);
            sleep(2);

            // 3. Kirim pesan expired
            $expiredDate = $transactionItem->expired_at?->format('d M Y') ?? now()->addDays(30)->format('d M Y');
            $message3 = "🎉 *TERIMA KASIH*\n\n" .
                "✅ Transaksi Anda berhasil\n" .
                "📄 Invoice: {$transaction->invoice}\n" .
                "⏰ Berlaku hingga: {$expiredDate}\n\n" .
                "📞 **Customer Service:**\n" .
                "6281226594919";

            $this->sendMessage($phone, $message3);

            Log::info('✅ 3 WhatsApp messages sent', [
                'invoice' => $transaction->invoice,
                'phone' => $phone
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp', [
                'error' => $e->getMessage(),
                'invoice' => $transaction->invoice
            ]);
            return false;
        }
    }

    /** ✅ FORMAT NOMOR TELEPON */
    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '62')) {
            return '62' . $phone;
        }

        return $phone;
    }
}

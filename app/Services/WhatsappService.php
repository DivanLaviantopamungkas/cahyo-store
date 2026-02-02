<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Intervention\Image\Facades\Image;

class WhatsAppService
{
    protected $deviceId;

    public function __construct()
    {
        $this->deviceId = config('services.whacenter.device_id');
    }

    /* ================= SEND TEXT ================= */
    public function sendMessage(string $phone, string $message): bool
    {
        $phone = $this->formatPhone($phone);

        $response = Http::timeout(30)->get('https://app.whacenter.com/api/send', [
            'device_id' => $this->deviceId,
            'number'    => $phone,
            'message'   => $message,
        ]);

        Log::info('Whacenter Text', ['response' => $response->json()]);

        return $response->successful();
    }

    /* ================= GENERATE BARCODE IMAGE ================= */
    private function generateRetailBarcodeImage(
        string $productName,
        string $nominalName,
        int $nominalPrice,
        string $voucherCode,
        string $invoice
    ): ?string {
        try {
            // BARCODE
            $generator = new BarcodeGeneratorPNG();
            $barcodeBinary = $generator->getBarcode(
                $voucherCode,
                $generator::TYPE_CODE_128,
                3,
                120
            );

            $barcode = Image::make($barcodeBinary);

            // CANVAS
            $canvas = Image::canvas(600, 420, '#ffffff');

            // HEADER
            $canvas->text(strtoupper($productName), 300, 40, function ($font) {
                $font->file(storage_path('fonts/Roboto-Bold.ttf'));
                $font->size(28);
                $font->align('center');
                $font->color('#000000');
            });

            // NOMINAL
            $canvas->text(
                strtoupper($nominalName) . ' Rp' . number_format($nominalPrice, 0, ',', '.'),
                300,
                85,
                function ($font) {
                    $font->file(storage_path('fonts/Roboto-Regular.ttf'));
                    $font->size(18);
                    $font->align('center');
                    $font->color('#000000');
                }
            );

            // BARCODE
            $canvas->insert($barcode, 'center', 0, -10);

            // CODE
            $canvas->text($voucherCode, 300, 290, function ($font) {
                $font->file(storage_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->align('center');
                $font->color('#000000');
            });

            // FOOTER
            $canvas->text("Invoice: {$invoice}", 300, 340, function ($font) {
                $font->file(storage_path('fonts/Roboto-Regular.ttf'));
                $font->size(14);
                $font->align('center');
                $font->color('#666666');
            });

            $path = storage_path("app/public/barcode-{$invoice}.png");
            $canvas->save($path, 100);

            Log::info('BARCODE IMAGE GENERATED', [
                'path'   => $path,
                'exists' => file_exists($path),
                'size'  => file_exists($path) ? filesize($path) : 0
            ]);

            return file_exists($path)
                ? asset("storage/barcode-{$invoice}.png")
                : null;
        } catch (\Exception $e) {
            Log::error('Barcode Generate Error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /* ================= SEND PAYMENT SUCCESS ================= */
    public function sendPaymentSuccess($transaction): bool
    {
        $transaction->load(['items.product', 'items.nominal', 'user']);
        $item = $transaction->items->first();

        if (!$item || !$item->voucher_code) {
            Log::error('❌ Voucher code missing');
            return false;
        }

        $phone = $transaction->user->phone ?? $item->phone;

        if (empty($phone)) {
            Log::warning("⚠️ WhatsApp Voucher Skipped: No phone number for Invoice {$transaction->invoice}");
            return false;
        }

        // 1️⃣ SEND TEXT
        $this->sendMessage(
            $phone,
            "🎫 *VOUCHER ANDA*\n\n" .
                "Produk : {$item->product->name}\n" .
                "Nominal: {$item->nominal->name}\n" .
                "Kode   : `{$item->voucher_code}`\n" .
                "Invoice: {$transaction->invoice}\n\n" .
                "Simpan kode ini"
        );

        sleep(2);

        // 2️⃣ GENERATE BARCODE
        $imageUrl = $this->generateRetailBarcodeImage(
            $item->product->name,
            $item->nominal->name,
            $item->nominal->price,
            $item->voucher_code,
            $transaction->invoice
        );

        if ($imageUrl) {
            $response = Http::timeout(30)->get('https://app.whacenter.com/api/send', [
                'device_id' => $this->deviceId,
                'number'    => $this->formatPhone($phone),
                'image'     => $imageUrl,
                'message'   => '*TUNJUKKAN BARCODE INI KE KASIR*'
            ]);
        }

        return true;
    }

    /* ================= FORMAT PHONE ================= */
    private function formatPhone(string $phone): string
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

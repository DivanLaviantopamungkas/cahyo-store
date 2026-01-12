<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderProduct;
use App\Models\Trancsaction;
use App\Services\DigiflazzService;
use App\Services\TelegramService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentWebhookController extends Controller
{
    protected $telegramService;
    protected $whatsappService;

    public function __construct()
    {
        $this->telegramService = new TelegramService();
        $this->whatsappService = new WhatsAppService();
    }

    /**
     * Handle Midtrans payment notification
     */
    public function midtrans(Request $request)
    {
        Log::info('Midtrans Webhook Received:', $request->all());

        // Validasi signature (opsional tapi disarankan)
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash(
            'sha512',
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
        );

        if ($hashed !== $request->signature_key) {
            Log::warning('Midtrans signature mismatch', [
                'received' => $request->signature_key,
                'calculated' => $hashed
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        // Cari transaksi berdasarkan invoice
        $transaction = Trancsaction::where('invoice', $orderId)->first();

        if (!$transaction) {
            Log::warning('Transaction not found', ['invoice' => $orderId]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Skip jika sudah diproses
        if ($transaction->status !== 'pending') {
            Log::info('Transaction already processed', [
                'invoice' => $orderId,
                'current_status' => $transaction->status
            ]);
            return response()->json(['message' => 'Already processed']);
        }

        DB::beginTransaction();
        try {
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($fraudStatus === 'accept') {
                    $this->processSuccessfulPayment($transaction, $request);
                }
            } elseif ($transactionStatus === 'pending') {
                $transaction->update(['status' => 'pending']);
            } elseif (
                $transactionStatus === 'deny' ||
                $transactionStatus === 'cancel' ||
                $transactionStatus === 'expire'
            ) {
                $this->processFailedPayment($transaction, $request);
            }

            DB::commit();
            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook processing error: ' . $e->getMessage(), [
                'invoice' => $orderId,
                'error' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Processing error'], 500);
        }
    }

    /**
     * Process successful payment
     */
    private function processSuccessfulPayment($transaction, $request)
    {
        // Update transaction
        $transaction->update([
            'status' => 'paid',
            'total_paid' => $request->gross_amount,
            'paid_at' => Carbon::now(),
            'payment_reference' => $request->transaction_id,
        ]);

        // Update transaction item
        $transactionItem = $transaction->items()->first();
        $transactionItem->update(['status' => 'processing']);

        // Process based on product type
        $product = $transactionItem->product;

        if ($product->is_digiflazz) {
            $this->processDigiflazz($transactionItem);
        } else {
            $this->processManual($transactionItem);
        }

        // Send notifications
        $this->sendNotifications($transaction);

        Log::info('Payment processed successfully', [
            'invoice' => $transaction->invoice,
            'amount' => $request->gross_amount
        ]);
    }

    /**
     * Process failed payment
     */
    private function processFailedPayment($transaction, $request)
    {
        $transaction->update([
            'status' => 'cancelled',
            'payment_reference' => $request->transaction_id,
        ]);

        // Update transaction item
        $transaction->items()->update(['status' => 'cancelled']);

        // Restock jika produk manual
        $transactionItem = $transaction->items()->first();
        if (!$transactionItem->product->is_digiflazz) {
            $transactionItem->nominal->increment('available_stock');
        }

        // Send notification to admin
        $this->sendFailedNotification($transaction, $request->transaction_status);

        Log::info('Payment cancelled', [
            'invoice' => $transaction->invoice,
            'reason' => $request->transaction_status
        ]);
    }

    /**
     * Process Digiflazz product
     */
    private function processDigiflazz($transactionItem)
    {
        try {
            $product = $transactionItem->product;
            $nominal = $transactionItem->nominal;
            $phone = $transactionItem->phone;

            // Find Digiflazz provider
            $provider = Provider::where('type', 'digiflazz')
                ->where('status', 'active')
                ->first();

            if (!$provider) {
                throw new \Exception('Provider Digiflazz tidak ditemukan');
            }

            // Instantiate DigiflazzService
            $digiflazzService = new DigiflazzService($provider);

            // Find SKU from provider product
            $providerProduct = ProviderProduct::where('provider_id', $provider->id)
                ->where(function ($query) use ($nominal) {
                    $query->where('provider_sku', $nominal->provider_sku)
                        ->orWhere('name', 'like', '%' . $nominal->name . '%');
                })
                ->first();

            if (!$providerProduct) {
                throw new \Exception('SKU produk tidak ditemukan di Digiflazz');
            }

            // Generate ref_id
            $refId = 'DGF-' . strtoupper(Str::random(8)) . '-' . time();

            // Call Digiflazz API
            $result = $digiflazzService->topup(
                $providerProduct->provider_sku,
                $phone,
                $refId
            );

            // Parse response
            $responseData = $result['data'] ?? [];
            $status = $responseData['status'] ?? 'error';
            $sn = $responseData['sn'] ?? null;
            $message = $responseData['message'] ?? 'No message';

            if ($status === 'success' || $status === '1') {
                // Update transaction item
                $transactionItem->update([
                    'status' => 'completed',
                    'provider_trx_id' => $responseData['trx_id'] ?? $refId,
                    'provider_status' => 'success',
                    'provider_rc' => $responseData['rc'] ?? null,
                    'provider_message' => $message,
                    'sn' => $sn,
                    'raw_response' => json_encode($responseData),
                    'completed_at' => Carbon::now(),
                ]);

                // Update parent transaction
                $transactionItem->transaction->update([
                    'status' => 'completed',
                    'completed_at' => Carbon::now(),
                ]);

                Log::info('Digiflazz purchase successful', [
                    'transaction_id' => $transactionItem->transaction_id,
                    'ref_id' => $refId,
                    'sn' => $sn
                ]);
            } else {
                // Update as failed
                $transactionItem->update([
                    'status' => 'cancelled',
                    'provider_status' => 'failed',
                    'provider_message' => $message,
                    'raw_response' => json_encode($responseData),
                ]);

                // Update parent transaction
                $transactionItem->transaction->update([
                    'status' => 'failed',
                ]);

                throw new \Exception('Digiflazz error: ' . $message);
            }
        } catch (\Exception $e) {
            Log::error('Digiflazz processing error: ' . $e->getMessage(), [
                'transaction_item_id' => $transactionItem->id
            ]);
            throw $e;
        }
    }

    /**
     * Process Manual product
     */
    private function processManual($transactionItem)
    {
        // Generate voucher code
        $voucherCode = 'VOUCH-' . strtoupper(Str::random(10));

        // Generate QR Code URL
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' .
            urlencode('VOUCHER-' . $voucherCode);

        // Update transaction item
        $transactionItem->update([
            'status' => 'completed',
            'voucher_code' => $voucherCode,
            'qr_code_url' => $qrCodeUrl,
            'expired_at' => Carbon::now()->addDays(30),
            'completed_at' => Carbon::now(),
        ]);

        // Update parent transaction
        $transactionItem->transaction->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);
    }

    /**
     * Send notifications
     */
    private function sendNotifications($transaction)
    {
        try {
            // 1. Telegram to admin
            $this->telegramService->sendNewTransaction($transaction);

            // 2. WhatsApp to customer
            $transactionItem = $transaction->items()->first();
            $product = $transactionItem->product;

            if ($product->is_digiflazz) {
                $this->sendDigiflazzWhatsApp($transaction);
            } else {
                $this->sendManualWhatsApp($transaction);
            }
        } catch (\Exception $e) {
            Log::error('Notification error: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp for Digiflazz
     */
    private function sendDigiflazzWhatsApp($transaction)
    {
        $transactionItem = $transaction->items()->first();

        // Message 1: Payment confirmation
        $message1 = "✅ *PEMBAYARAN BERHASIL*\n\n";
        $message1 .= "Invoice: {$transaction->invoice}\n";
        $message1 .= "Produk: {$transactionItem->product->name}\n";
        $message1 .= "Nominal: {$transactionItem->nominal->name}\n";
        $message1 .= "No. Tujuan: {$transactionItem->phone}\n";
        $message1 .= "Status: Diproses otomatis\n\n";
        $message1 .= "Mohon tunggu 1-5 menit untuk pengisian.";

        $this->whatsappService->sendMessage($transaction->user->phone, $message1);

        sleep(2);

        // Message 2: Result
        if ($transactionItem->provider_status === 'success') {
            $message2 = "🎉 *PENGISIAN SUKSES*\n\n";
            $message2 .= "SN: {$transactionItem->sn}\n";
            $message2 .= "Status: Berhasil\n";
            $message2 .= "Waktu: " . now()->format('d/m/Y H:i:s') . "\n\n";
            $message2 .= "Terima kasih telah berbelanja!";
        } else {
            $message2 = "⚠️ *PENGISIAN GAGAL*\n\n";
            $message2 .= "Status: {$transactionItem->provider_message}\n";
            $message2 .= "Silakan hubungi admin untuk refund.\n\n";
            $message2 .= "WhatsApp Admin: 628xxxxxxxxxx";
        }

        $this->whatsappService->sendMessage($transaction->user->phone, $message2);
    }

    /**
     * Send WhatsApp for Manual product
     */
    private function sendManualWhatsApp($transaction)
    {
        $transactionItem = $transaction->items()->first();
        $expiredDate = $transactionItem->expired_at->format('d F Y');

        // WA 1: Voucher Code
        $message1 = "🎫 *KODE VOUCHER ANDA*\n\n";
        $message1 .= "Invoice: {$transaction->invoice}\n";
        $message1 .= "Produk: {$transactionItem->product->name}\n";
        $message1 .= "Kode: *{$transactionItem->voucher_code}*\n\n";
        $message1 .= "Simpan kode ini untuk klaim produk.";

        $this->whatsappService->sendMessage($transaction->user->phone, $message1);

        sleep(2);

        // WA 2: QR Code
        $message2 = "📱 *QR CODE PRODUK*\n\n";
        $message2 .= "Scan QR untuk klaim produk:\n";
        $message2 .= "Link: {$transactionItem->qr_code_url}";

        $this->whatsappService->sendMessage($transaction->user->phone, $message2);

        sleep(2);

        // WA 3: Thank you + Expired
        $message3 = "🎉 *TERIMA KASIH*\n\n";
        $message3 .= "Pembayaran berhasil!\n\n";
        $message3 .= "Produk berlaku hingga: *{$expiredDate}*\n\n";
        $message3 .= "Hubungi admin jika butuh bantuan.";

        $this->whatsappService->sendMessage($transaction->user->phone, $message3);
    }

    /**
     * Send failed notification to admin
     */
    private function sendFailedNotification($transaction, $reason)
    {
        try {
            $message = "❌ *TRANSAKSI DIBATALKAN*\n\n";
            $message .= "Invoice: <code>{$transaction->invoice}</code>\n";
            $message .= "Customer: {$transaction->user->name}\n";
            $message .= "Alasan: {$reason}\n\n";
            $message .= "💰 <a href='" . route('admin.transactions.show', $transaction->id) . "'>LIHAT DETAIL</a>";

            $this->telegramService->sendMessage($message);
        } catch (\Exception $e) {
            Log::error('Failed to send failed notification: ' . $e->getMessage());
        }
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request)
    {
        Log::info('Test webhook received', $request->all());
        return response()->json(['message' => 'Webhook test successful']);
    }
}

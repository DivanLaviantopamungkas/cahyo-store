<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\MessageLog;
use App\Models\PaymentWebhook;
use App\Models\Trancsaction;
use App\Models\TransactionItem;
use App\Models\VoucherCode;
use App\Services\DigiflazzService;
use App\Services\TelegramService;
use App\Services\TokopayService;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokopayWebhookController extends Controller
{
     public function handle(
        Request $request,
        TokopayService $tokopay,
        WhatsappService $wa,
        TelegramService $telegram,
        DigiflazzService $digiflazz
    ) {
        // Tokopay contoh callback: {reff_id, status, signature, data:{...}} [web:41]
        $reffId = (string) $request->input('reff_id');
        $status = (string) $request->input('status');
        $sig    = (string) $request->input('signature');

        if ($reffId === '') {
            return response()->json(['message' => 'Missing reff_id'], 422);
        }

        // verify signature tokopay [web:61]
        $expectedSig = $tokopay->signature($reffId);
        $sigOk = hash_equals($expectedSig, $sig);

        // log webhook untuk idempotency
        PaymentWebhook::updateOrCreate(
            ['provider' => 'tokopay', 'reference' => $reffId],
            [
                'event' => 'payment',
                'status' => $status,
                'payload' => json_encode($request->all()),
                'signature_valid' => $sigOk ? 1 : 0,
                'received_at' => now(),
            ]
        );

        if (!$sigOk) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        // hanya proses kalau status Success
        if (strtolower($status) !== 'success') {
            return response()->json(['message' => 'Ignored'], 200);
        }

        // proses transaksi dengan transaction + lock (anti double)
        $result = DB::transaction(function () use ($reffId, $wa, $telegram, $digiflazz) {

            /** @var Transaction $trx */
            $trx = Trancsaction::where('reff_id', $reffId)->lockForUpdate()->first();

            if (!$trx) {
                return ['message' => 'Transaction not found'];
            }

            // idempotent: kalau sudah paid/completed, stop
            if (in_array($trx->status, ['paid','processing','completed'], true)) {
                return ['message' => 'Already processed', 'invoice' => $trx->invoice];
            }

            $trx->update([
                'status' => 'paid',
                'paid_at' => now(),
                'total_paid' => $trx->amount,
            ]);

            // ambil item pertama (MVP: 1 item per transaksi)
            /** @var TransactionItem $item */
            $item = TransactionItem::where('transaction_id', $trx->id)->first();

            if (!$item) {
                return ['message' => 'Transaction item missing', 'invoice' => $trx->invoice];
            }

            $item->update(['status' => 'processing']);

            // ===== Fulfillment: manual voucher vs digiflazz =====
            $product = $item->product()->first();
            $nominal = $item->nominal()->first();

            if ($product && ($product->source ?? 'manual') === 'digiflazz') {
                // target disimpan sementara di raw_response (dari checkout)
                $target = data_get(json_decode($item->raw_response ?? '{}', true), 'target');

                if (!$nominal || !$nominal->provider_sku || !$target) {
                    $item->update([
                        'status' => 'cancelled',
                        'provider_message' => 'Missing DigiFlazz SKU or target',
                    ]);
                } else {
                    $df = $digiflazz->topup($nominal->provider_sku, $target, $trx->invoice);

                    $item->update([
                        'fulfillment_source' => 'digiflazz',
                        'provider_status' => (string) data_get($df, 'data.status'),
                        'provider_rc' => (string) data_get($df, 'data.rc'),
                        'provider_message' => (string) data_get($df, 'data.message'),
                        'sn' => (string) data_get($df, 'data.sn'),
                        'raw_response' => json_encode($df),
                    ]);
                }
            } else {
                // Manual voucher: ambil 1 kode available
                $voucher = VoucherCode::where('product_id', $item->product_id)
                    ->when($item->product_nominal_id, fn($q) => $q->where('product_nominal_id', $item->product_nominal_id))
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (!$voucher) {
                    $item->update([
                        'status' => 'cancelled',
                        'provider_message' => 'Out of stock voucher',
                    ]);
                } else {
                    $voucher->update([
                        'status' => 'sold',
                        'sold_to' => $trx->user_id,
                        'sold_at' => now(),
                    ]);

                    $item->update([
                        'voucher_code_id' => $voucher->id,
                        'fulfillment_source' => 'manual',
                        'status' => 'completed',
                        'delivered_at' => now(),
                    ]);
                }
            }

            // ===== Kirim WhatsApp 3 step + Telegram notif =====
            $user = $trx->user()->first();
            $toWa = $user?->whatsapp;

            // Pesan 1: kode/SN
            $msg1 = "Pembayaran sukses ✅\nInvoice: {$trx->invoice}\nProduk: " .
                ($product?->name ?? '-') . "\n";

            $voucherText = '';
            if ($item->voucherCode) {
                $voucherText = "Kode: " . $item->voucherCode->code;
            } elseif ($item->sn) {
                $voucherText = "SN: " . $item->sn;
            } else {
                $voucherText = "Detail: " . ($item->provider_message ?? '-');
            }

            $msg1 .= $voucherText;

            if ($toWa) {
                $wa->sendText($toWa, $msg1);
                MessageLog::create([
                    'channel' => 'whatsapp',
                    'step' => 'wa1',
                    'transaction_id' => $trx->id,
                    'transaction_item_id' => $item->id,
                    'recipient' => $toWa,
                    'payload' => $msg1,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }

            // Pesan 2: QR voucher (MVP: belum generate QR image; nanti diupdate)
            // Sementara kirim text bahwa QR akan menyusul / atau link redeem bila ada
            if ($toWa) {
                $msg2 = "QR Voucher: (akan dikirim setelah fitur generate QR image diaktifkan)";
                $wa->sendText($toWa, $msg2);

                MessageLog::create([
                    'channel' => 'whatsapp',
                    'step' => 'wa2',
                    'transaction_id' => $trx->id,
                    'transaction_item_id' => $item->id,
                    'recipient' => $toWa,
                    'payload' => $msg2,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }

            // Pesan 3: garansi + masa berlaku + thanks
            if ($toWa) {
                $msg3 = "Garansi: jika voucher bermasalah silakan hubungi CS.\n"
                      . "Masa berlaku: cek pada voucher/ketentuan produk.\n"
                      . "Terima kasih sudah berbelanja.";

                $wa->sendText($toWa, $msg3);

                MessageLog::create([
                    'channel' => 'whatsapp',
                    'step' => 'wa3',
                    'transaction_id' => $trx->id,
                    'transaction_item_id' => $item->id,
                    'recipient' => $toWa,
                    'payload' => $msg3,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }

            // Telegram notif admin
            $chatId = config('services.telegram.chat_id');
            if ($chatId) {
                $tmsg = "PAYMENT SUCCESS\nInvoice: {$trx->invoice}\nUser: {$user?->name} ({$user?->whatsapp})\nAmount: {$trx->amount}";
                $telegram->sendMessage($chatId, $tmsg);

                MessageLog::create([
                    'channel' => 'telegram',
                    'step' => 'telegram_paid',
                    'transaction_id' => $trx->id,
                    'transaction_item_id' => $item->id,
                    'recipient' => (string) $chatId,
                    'payload' => $tmsg,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }

            $trx->update(['status' => 'completed', 'completed_at' => now()]);

            return ['message' => 'Processed', 'invoice' => $trx->invoice];
        });

        return response()->json($result);
    }
}

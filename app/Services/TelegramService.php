<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.admin_chat_id');
    }

    public function sendMessage($text)
    {
        if (!$this->botToken || !$this->chatId) {
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $text,
                'parse_mode' => 'HTML'
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendNewTransaction($transaction)
    {
        $product = $transaction->items[0]->product;
        $nominal = $transaction->items[0]->nominal;

        $message = "🆕 <b>TRANSAKSI BARU</b>\n\n";
        $message .= "Invoice: <code>{$transaction->invoice}</code>\n";
        $message .= "Customer: {$transaction->user->name}\n";
        $message .= "Produk: {$product->name}\n";
        $message .= "Nominal: {$nominal->name}\n";
        $message .= "Total: Rp " . number_format($transaction->amount, 0, ',', '.') . "\n";
        $message .= "Status: {$transaction->status}\n\n";

        $this->sendMessage($message);
    }
}

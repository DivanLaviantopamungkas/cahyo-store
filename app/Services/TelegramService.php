<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    private string $token;
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->token   = config('services.telegram.bot_token');
        $this->timeout = (int) config('services.telegram.timeout', 15);
        $this->baseUrl = 'https://api.telegram.org/bot' . $this->token;
    }

    public function sendMessage(string|int $chatId, string $text): array
    {
        $response = Http::asJson()
            ->timeout($this->timeout)
            ->post($this->baseUrl . '/sendMessage', [
                'chat_id' => $chatId,
                'text'    => $text,
            ]);

        return $this->handleJson($response);
    }

    private function handleJson(Response $response): array
    {
        if ($response->successful()) {
            return $response->json() ?? [];
        }

        throw new \RuntimeException('Telegram API error: ' . $response->status() . ' ' . $response->body());
    }
}

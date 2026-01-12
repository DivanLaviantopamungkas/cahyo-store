<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WhatsappService
{
    private string $baseUrl;
    private string $token;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.whatsapp.base_url'), '/');
        $this->token   = config('services.whatsapp.token');
        $this->timeout = (int) config('services.whatsapp.timeout', 25);
    }

    private function client()
    {
        return Http::acceptJson()
            ->asJson()
            ->timeout($this->timeout)
            ->withToken($this->token);
    }

    /**
     * Kirim teks (gateway generik).
     * Anda tinggal sesuaikan endpoint & payload sesuai provider WA Anda.
     */
    public function sendText(string $to, string $text): array
    {
        $response = $this->client()->post($this->baseUrl . '/messages/text', [
            'to'   => $to,
            'text' => $text,
        ]);

        return $this->handleJson($response);
    }

    /**
     * Kirim image via URL (gateway generik).
     * Untuk WhatsApp Cloud API, konsep image message memang ada (image message supported). 
     */
    public function sendImageUrl(string $to, string $imageUrl, ?string $caption = null): array
    {
        $payload = [
            'to'    => $to,
            'image' => [
                'link' => $imageUrl,
            ],
        ];

        if ($caption) {
            $payload['caption'] = $caption;
        }

        $response = $this->client()->post($this->baseUrl . '/messages/image', $payload);

        return $this->handleJson($response);
    }

    private function handleJson(Response $response): array
    {
        if ($response->successful()) {
            return $response->json() ?? [];
        }

        throw new \RuntimeException('WhatsApp API error: ' . $response->status() . ' ' . $response->body());
    }
}

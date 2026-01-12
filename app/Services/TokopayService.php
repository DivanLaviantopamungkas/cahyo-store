<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TokopayService
{
     private string $baseUrl;
    private string $merchantId;
    private string $secret;
    private string $kodeChannel;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl     = rtrim(config('services.tokopay.base_url'), '/');
        $this->merchantId  = (string) config('services.tokopay.merchant_id');
        $this->secret      = (string) config('services.tokopay.secret');
        $this->kodeChannel = (string) config('services.tokopay.kode_channel', 'QRIS');
        $this->timeout     = (int) config('services.tokopay.timeout', 20);
    }

    public function signature(string $reffId): string
    {
        // Tokopay: md5(MERCHANT_ID:SECRET:REFF_ID) [web:61]
        return md5($this->merchantId . ':' . $this->secret . ':' . $reffId);
    }

    public function createQrisOrder(array $payload): array
    {
        // Tokopay advanced order endpoint: POST /v1/order [web:61]
        $response = Http::acceptJson()
            ->asJson()
            ->timeout($this->timeout)
            ->post($this->baseUrl . '/v1/order', $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('Tokopay error: ' . $response->status() . ' ' . $response->body());
        }

        return $response->json() ?? [];
    }

    public function defaultKodeChannel(): string
    {
        return $this->kodeChannel;
    }

    public function merchantId(): string
    {
        return $this->merchantId;
    }
}

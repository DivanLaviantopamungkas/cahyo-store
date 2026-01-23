<?php

namespace App\Services;

use App\Models\Provider;
use App\Models\ProviderProduct;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigiflazzService
{
    private Provider $provider;
    private string $baseUrl;
    private bool $testing;
    private int $timeout;

    public function __construct(Provider $provider)
    {
        if (!$provider->isDigiflazz()) {
            throw new \InvalidArgumentException('Provider must be Digiflazz');
        }

        $this->provider = $provider;
        $this->baseUrl = 'https://api.digiflazz.com';
        $this->testing = (bool) $provider->getSetting('testing', false);
        $this->timeout = (int) $provider->getSetting('timeout', 25);
    }

    public function getCredentials(): array
    {
        return $this->provider->credentials;
    }

    public function testConnection(): array
    {
        $username = $this->provider->getCredential('username');
        $apiKey = $this->provider->getCredential('api_key');

        if (empty($username) || empty($apiKey)) {
            throw new \Exception('Username dan API Key diperlukan untuk Digiflazz');
        }

        try {
            // Hanya test dengan mengambil price list
            $signature = md5($username . $apiKey . 'pricelist');

            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->post($this->baseUrl . '/v1/price-list', [
                    'cmd' => 'prepaid',
                    'username' => $username,
                    'sign' => $signature,
                ]);

            // Cek response
            if (!$response->successful()) {
                $errorData = $response->json();
                throw new \Exception(
                    'Gagal terhubung: ' .
                        ($errorData['message'] ?? 'HTTP ' . $response->status())
                );
            }

            $data = $response->json();
            $products = $data['data'] ?? [];

            return [
                'success' => true,
                'message' => 'Koneksi ke Digiflazz berhasil!',
                'data' => [
                    'status' => 'connected',
                    'products_count' => count($products),
                    'tested_at' => now()->toDateTimeString(),
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('Test koneksi gagal: ' . $e->getMessage());
        }
    }

    public function syncProducts(): array
    {
        $username = $this->provider->getCredential('username');
        $apiKey = $this->provider->getCredential('api_key');

        if (empty($username) || empty($apiKey)) {
            throw new \Exception('Username dan API Key diperlukan untuk sinkronisasi');
        }

        try {
            $signature = md5($username . $apiKey . 'pricelist');

            Log::info('Syncing Digiflazz products', [
                'username' => $username,
                'provider_id' => $this->provider->id
            ]);

            $response = Http::timeout($this->timeout)
                ->asJson()
                ->post($this->baseUrl . '/v1/price-list', [
                    'cmd' => 'prepaid',
                    'username' => $username,
                    'sign' => $signature,
                ]);

            if (!$response->successful()) {
                $errorData = $response->json();
                throw new \Exception('Gagal mengambil produk: ' . ($errorData['message'] ?? 'Unknown error'));
            }

            $data = $response->json();
            $products = $data['data'] ?? [];

            $count = 0;
            $skipped = 0;

            foreach ($products as $product) {
                // Skip jika tidak ada SKU
                $sku = $product['buyer_sku_code'] ?? $product['sku_code'] ?? null;
                if (empty($sku)) {
                    Log::warning('Skipping product without SKU', [
                        'product' => $product['product_name'] ?? 'Unknown'
                    ]);
                    $skipped++;
                    continue;
                }

                // Validasi data required
                $name = $product['product_name'] ?? $product['product'] ?? 'Unknown Product';
                $category = $product['category'] ?? 'uncategorized';
                $brand = $product['brand'] ?? 'Unknown';
                $price = $product['price'] ?? 0;

                // Check availability
                $isAvailable = ($product['unlimited_stock'] ?? false) ||
                    (isset($product['stock']) && $product['stock'] > 0) ||
                    ($product['status'] ?? 1) == 1;

                try {
                    // Extract image URL if available
                    $details = $product;

                    // Check if there's icon URL in the data
                    if (isset($product['icon_url'])) {
                        $details['image_url'] = $product['icon_url'];
                    } elseif (isset($product['icon'])) {
                        $details['image_url'] = $product['icon'];
                    } elseif (isset($product['images']) && is_array($product['images']) && !empty($product['images'])) {
                        $details['image_url'] = $product['images'][0];
                    }

                    // Extract description if available
                    $description = $product['description'] ??
                        $product['desc'] ??
                        "Produk {$name} kategori {$category}";

                    ProviderProduct::updateOrCreate(
                        [
                            'provider_id' => $this->provider->id,
                            'provider_sku' => $sku,
                        ],
                        [
                            'name' => $name,
                            'category' => $category,
                            'brand' => $brand,
                            'provider_price' => $price,
                            'description' => $description,
                            'is_available' => $isAvailable,
                            'details' => json_encode($details),
                            'last_sync_at' => now(),
                        ]
                    );
                    $count++;

                    Log::info("Synced product: {$name} - {$sku}", [
                        'has_image' => isset($details['image_url']) ? 'Yes' : 'No'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error saving product', [
                        'sku' => $sku,
                        'error' => $e->getMessage(),
                        'product_data' => $product
                    ]);
                    $skipped++;
                }
            }

            Log::info('Digiflazz products synced', [
                'success' => $count,
                'skipped' => $skipped,
                'total' => count($products),
                'provider_id' => $this->provider->id
            ]);

            return [
                'success' => true,
                'message' => '✅ ' . $count . ' produk berhasil disinkronisasi' .
                    ($skipped > 0 ? ' (' . $skipped . ' dilewati)' : ''),
                'count' => $count,
                'skipped' => $skipped,
            ];
        } catch (\Exception $e) {
            Log::error('Digiflazz sync error', [
                'error' => $e->getMessage(),
                'provider_id' => $this->provider->id
            ]);

            throw new \Exception('Sinkronisasi gagal: ' . $e->getMessage());
        }
    }

    public function topup(string $sku, string $customerNo, string $refId): array
    {
        $username = $this->provider->getCredential('username');
        $apiKey = $this->provider->getCredential('api_key');

        $payload = [
            'username' => $username,
            'buyer_sku_code' => $sku,
            'customer_no' => $customerNo,
            'ref_id' => $refId,
            'sign' => md5($username . $apiKey . $refId),
        ];

        if ($this->testing) {
            $payload['testing'] = true;
        }

        $response = Http::timeout($this->timeout)
            ->acceptJson()
            ->post($this->baseUrl . '/v1/transaction', $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('Digiflazz error: ' . $response->body());
        }

        return $response->json();
    }
}

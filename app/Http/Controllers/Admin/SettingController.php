<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\SettingHelper;
use Illuminate\Support\Facades\Validator;

class SettingController extends BaseAdminController
{
    // Helper method untuk mengambil settings berdasarkan group
    private function getSettingsByGroup($group)
    {
        return Setting::where('group', $group)
            ->get()
            ->keyBy('key')
            ->map(function ($setting) {
                return $setting->value;
            });
    }

    private function updateSettings($group, $data, $excludes = [])
    {
        DB::beginTransaction();

        try {
            $ignoredKeys = ['_token', '_method', 'submit', 'hero_slides'];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $ignoredKeys)) {
                    continue;
                }

                if (in_array($key, $excludes)) {
                    continue;
                }

                if (is_array($value)) {
                    continue;
                }

                if (request()->hasFile($key)) {
                    $file = request()->file($key);
                    $path = $this->handleFileUpload($file, $key);
                    $value = $path;
                }

                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                }

                Setting::updateOrCreate(
                    [
                        'group' => $group,
                        'key' => $key
                    ],
                    [
                        'value' => $value ?? '',
                        'type' => 'text'
                    ]
                );
            }

            DB::commit();
            
            SettingHelper::clearCache();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // ========== Halaman Individu ==========

    /**
     * Halaman Umum
     */
    public function general()
    {
        $settings = $this->getSettingsByGroup('general');
        $title = 'Pengaturan Umum';
        $breadcrumb = 'Kelola Pengaturan Umum';

        return $this->view('settings.general', compact('settings', 'title', 'breadcrumb'));
    }

    public function updateGeneral(Request $request)
    {
        try {
            $this->updateSettings('general', $request->all());

            return redirect()
                ->route('admin.settings.general')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Pengaturan umum berhasil disimpan.'
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.general')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Halaman Landing Page
     */
    public function landing()
    {
        $settings = $this->getSettingsByGroup('landing');

        // Ambil hero slides
        $heroSlides = [];
        $slideSettings = Setting::where('key', 'like', 'hero_slide.%')->get();

        foreach ($slideSettings as $setting) {
            if (strpos($setting->key, 'hero_slide.') === 0) {
                $parts = explode('.', $setting->key);
                if (count($parts) >= 3) {
                    $slideId = $parts[1];
                    $field = $parts[2];

                    if (!isset($heroSlides[$slideId])) {
                        $heroSlides[$slideId] = [
                            'id' => $slideId,
                            'existing_image' => null,
                            'title' => '',
                            'description' => '',
                            'button_text' => 'Mulai Sekarang',
                            'button_link' => '#products',
                            'is_active' => true,
                            'order' => 0
                        ];
                    }

                    $heroSlides[$slideId][$field] = $setting->value;
                }
            }
        }

        $heroSlides = array_values($heroSlides);
        $title = 'Pengaturan Landing Page';
        $breadcrumb = 'Kelola Landing Page';

        return $this->view('settings.landing', compact('settings', 'heroSlides', 'title', 'breadcrumb'));
    }

    public function updateLanding(Request $request)
    {
        DB::beginTransaction();

        try {
            // Update regular settings
            $this->updateSettings('landing', $request->except(['hero_slides']));

            // Handle hero slides
            if ($request->has('hero_slides')) {
                $this->processHeroSlides($request->hero_slides);
            }

            DB::commit();

            return redirect()
                ->route('admin.settings.landing')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Pengaturan landing page berhasil disimpan.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.settings.landing')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Halaman Provider
     */
    public function providers()
    {
        $providers = Provider::orderBy('name')->get();
        $title = 'Pengaturan Provider';
        $breadcrumb = 'Kelola Provider';

        return $this->view('settings.providers', compact('providers', 'title', 'breadcrumb'));
    }

    /**
     * Halaman Kontak
     */
    public function contact()
    {
        $settings = $this->getSettingsByGroup('contact');
        $title = 'Pengaturan Kontak';
        $breadcrumb = 'Kelola Kontak & Dukungan';

        return $this->view('settings.contact', compact('settings', 'title', 'breadcrumb'));
    }

    public function updateContact(Request $request)
    {
        try {
            $this->updateSettings('contact', $request->all());

            return redirect()
                ->route('admin.settings.contact')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Pengaturan kontak berhasil disimpan.'
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.contact')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Halaman Sosial Media
     */
    public function social()
    {
        $settings = $this->getSettingsByGroup('social');
        $title = 'Pengaturan Sosial Media';
        $breadcrumb = 'Kelola Media Sosial';

        return $this->view('settings.social', compact('settings', 'title', 'breadcrumb'));
    }

    public function updateSocial(Request $request)
    {
        try {
            $this->updateSettings('social', $request->all());

            return redirect()
                ->route('admin.settings.social')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Pengaturan sosial media berhasil disimpan.'
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.social')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Halaman Pembayaran
     */
    public function payment()
    {
        $settings = $this->getSettingsByGroup('payment');
        $title = 'Pengaturan Pembayaran';
        $breadcrumb = 'Kelola Pembayaran';

        return $this->view('settings.payment', compact('settings', 'title', 'breadcrumb'));
    }

    public function updatePayment(Request $request)
    {
        try {
            $this->updateSettings('payment', $request->all());

            return redirect()
                ->route('admin.settings.payment')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Pengaturan pembayaran berhasil disimpan.'
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.settings.payment')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Update Provider (tetap sama)
     */
    public function updateProvider(Request $request, $code)
    {
        $request->validate([
            'credentials' => 'required|array',
            'is_active' => 'nullable|boolean',
            'settings' => 'nullable|array',
        ]);

        $provider = Provider::where('code', $code)->firstOrFail();

        DB::beginTransaction();

        try {
            $data = [
                'credentials' => $request->credentials,
                'is_active' => $request->boolean('is_active', false),
            ];

            // Merge settings jika ada
            if ($request->has('settings')) {
                $currentSettings = $provider->settings ?? [];
                $newSettings = array_merge($currentSettings, $request->settings);
                $data['settings'] = $newSettings;
            }

            $provider->update($data);

            DB::commit();

            return redirect()
                ->route('admin.settings.providers')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Konfigurasi ' . $provider->name . ' berhasil diperbarui.'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.settings.providers')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
        }
    }

    public function testProvider(Request $request, $code)
    {
        $provider = Provider::where('code', $code)->first();

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Provider tidak ditemukan',
            ], 404);
        }

        try {
            // Pastikan menggunakan service yang tepat
            $service = $this->getProviderService($provider);
            $result = $service->testConnection();

            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Test berhasil',
                'data' => $result['data'] ?? null,
            ]);
        } catch (\Exception $e) {
            // Debug: log error untuk troubleshooting
            Log::error('Test Provider Error: ' . $e->getMessage(), [
                'provider' => $code,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function syncProvider($code)
    {
        $provider = Provider::where('code', $code)->first();

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Provider not found',
            ], 404);
        }

        try {
            $service = $this->getProviderService($provider);
            $result = $service->syncProducts();

            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Sync successful',
                'count' => $result['count'] ?? 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ========== PRIVATE METHODS ==========

    private function processHeroSlides($slides)
    {
        // Hapus semua hero slide yang ada
        Setting::where('key', 'like', 'hero_slide.%')->delete();

        foreach ($slides as $index => $slide) {
            $slideId = $slide['id'] ?? $index + 1;

            // Simpan setiap field sebagai setting terpisah
            $fields = [
                'title' => $slide['title'] ?? '',
                'description' => $slide['description'] ?? '',
                'button_text' => $slide['button_text'] ?? 'Mulai Sekarang',
                'button_link' => $slide['button_link'] ?? '#products',
                'is_active' => $slide['is_active'] ?? true,
                'order' => $index
            ];

            foreach ($fields as $field => $value) {
                Setting::updateOrCreate(
                    [
                        'key' => "hero_slide.{$slideId}.{$field}",
                        'group' => 'landing'
                    ],
                    [
                        'value' => $value,
                        'type' => 'text'
                    ]
                );
            }

            // Handle gambar jika ada
            if (isset($slide['image']) && $slide['image'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $slide['image'];
                $path = $this->handleFileUpload($file, "hero_slide_{$slideId}_image");

                Setting::updateOrCreate(
                    [
                        'key' => "hero_slide.{$slideId}.image",
                        'group' => 'landing'
                    ],
                    [
                        'value' => $path,
                        'type' => 'image'
                    ]
                );
            } elseif (isset($slide['existing_image'])) {
                // Keep existing image
                Setting::updateOrCreate(
                    [
                        'key' => "hero_slide.{$slideId}.image",
                        'group' => 'landing'
                    ],
                    [
                        'value' => $slide['existing_image'],
                        'type' => 'image'
                    ]
                );
            }
        }

        SettingHelper::clearCache();
    }

    private function handleFileUpload($file, $key)
    {
        $validator = Validator::make(
            ['file_upload' => $file], 
            ['file_upload' => 'mimes:jpg,jpeg,png,gif,webp|max:2048']
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $fileName = $key . '_' . time() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('settings', $fileName, 'public');

        $oldSetting = Setting::where('key', $key)->first();
        
        if ($oldSetting && $oldSetting->value && Storage::disk('public')->exists($oldSetting->value)) {
            Storage::disk('public')->delete($oldSetting->value);
        }

        return $path;
    }

    private function testDigiflazzConnection(array $credentials)
    {
        // Validasi credentials
        if (empty($credentials['username']) || empty($credentials['api_key'])) {
            throw new \Exception("Username dan API Key diperlukan untuk Digiflazz.");
        }

        try {
            $baseUrl = 'https://api.digiflazz.com';
            $username = $credentials['username'];
            $apiKey = $credentials['api_key'];

            // Test dengan request ke endpoint price list
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($baseUrl . '/v1/price-list', [
                'cmd' => 'prepaid',
                'username' => $username,
                'sign' => md5($username . $apiKey . 'pricelist'),
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                throw new \Exception("Digiflazz Error: " . ($error['message'] ?? $response->status()));
            }

            $data = $response->json();

            // Cek saldo
            $balance = null;
            $checkBalanceResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($baseUrl . '/v1/cek-saldo', [
                'username' => $username,
                'sign' => md5($username . $apiKey . 'depo'),
            ]);

            if ($checkBalanceResponse->successful()) {
                $balanceData = $checkBalanceResponse->json();
                $balance = $balanceData['data']['deposit'] ?? null;
            }

            return [
                'success' => true,
                'message' => 'Koneksi Digiflazz berhasil!',
                'data' => [
                    'balance' => $balance,
                    'status' => 'connected',
                    'products_count' => count($data['data'] ?? []),
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception("Gagal test koneksi Digiflazz: " . $e->getMessage());
        }
    }

    private function testTokopayConnection(array $credentials)
    {
        // Validasi credentials
        if (empty($credentials['merchant_code']) || empty($credentials['secret_key'])) {
            throw new \Exception("Merchant Code dan Secret Key diperlukan untuk Tokopay.");
        }

        try {
            $baseUrl = 'https://api.tokopay.id';
            $merchantId = $credentials['merchant_code'];
            $secret = $credentials['secret_key'];

            // Test dengan request ke endpoint status
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($baseUrl . '/v1/status', [
                'merchant_id' => $merchantId,
                'signature' => md5($merchantId . ':' . $secret . ':status'),
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                throw new \Exception("Tokopay Error: " . ($error['message'] ?? $response->status()));
            }

            $data = $response->json();

            return [
                'success' => true,
                'message' => 'Koneksi Tokopay berhasil!',
                'data' => [
                    'status' => 'connected',
                    'merchant_id' => $merchantId,
                    'merchant_name' => $data['merchant_name'] ?? null,
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception("Gagal test koneksi Tokopay: " . $e->getMessage());
        }
    }

    private function syncDigiflazzProducts(Provider $provider)
    {
        try {
            $credentials = $provider->credentials;
            $username = $credentials['username'] ?? null;
            $apiKey = $credentials['api_key'] ?? null;

            if (empty($username) || empty($apiKey)) {
                throw new \Exception("Konfigurasi Digiflazz belum lengkap.");
            }

            $baseUrl = 'https://api.digiflazz.com';

            // Ambil price list
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($baseUrl . '/v1/price-list', [
                'cmd' => 'prepaid',
                'username' => $username,
                'sign' => md5($username . $apiKey . 'pricelist'),
            ]);

            if (!$response->successful()) {
                throw new \Exception("Gagal mengambil data produk.");
            }

            $data = $response->json();
            $products = $data['data'] ?? [];

            // Simpan ke provider_products
            $count = 0;
            foreach ($products as $productData) {
                \App\Models\ProviderProduct::updateOrCreate(
                    [
                        'provider_id' => $provider->id,
                        'provider_sku' => $productData['buyer_sku_code'] ?? null,
                    ],
                    [
                        'name' => $productData['product_name'] ?? '',
                        'category' => $productData['category'] ?? '',
                        'brand' => $productData['brand'] ?? '',
                        'provider_price' => $productData['price'] ?? 0,
                        'is_available' => ($productData['unlimited_stock'] ?? false) || ($productData['stock'] ?? 0) > 0,
                        'details' => $productData,
                        'last_sync_at' => now(),
                    ]
                );
                $count++;
            }

            return [
                'success' => true,
                'message' => 'Produk berhasil disinkronisasi',
                'count' => $count,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Gagal sinkronisasi produk: " . $e->getMessage());
        }
    }

    private function getProviderService(Provider $provider)
    {
        $serviceName = ucfirst(strtolower($provider->code)) . 'Service';
        $serviceClass = 'App\\Services\\' . $serviceName;

        Log::info('Loading provider service', [
            'provider' => $provider->code,
            'service_class' => $serviceClass
        ]);

        if (!class_exists($serviceClass)) {
            throw new \Exception("Service class {$serviceClass} tidak ditemukan");
        }

        return new $serviceClass($provider);
    }
}

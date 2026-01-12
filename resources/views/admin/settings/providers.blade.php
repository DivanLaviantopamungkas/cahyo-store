@extends('admin.layouts.app')

@section('title', 'Pengaturan Provider')
@section('breadcrumb', 'Kelola Provider')

@php
    // Helper function untuk akses credentials yang aman
    function getProviderCredential($provider, $key, $default = '') {
        if (!$provider) return $default;
        
        try {
            $credentials = $provider->credentials;
            if (is_string($credentials)) {
                try {
                    $credentials = json_decode($credentials, true) ?? [];
                } catch (\Exception $e) {
                    $credentials = [];
                }
            }
            return $credentials[$key] ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
    
    // Helper untuk get setting
    function getProviderSetting($provider, $key, $default = '') {
        if (!$provider) return $default;
        
        $settings = $provider->settings ?? [];
        if (is_string($settings)) {
            try {
                $settings = json_decode($settings, true) ?? [];
            } catch (\Exception $e) {
                $settings = [];
            }
        }
        return $settings[$key] ?? $default;
    }
    
    $digiflazz = $providers->where('code', 'digiflazz')->first();
    $tokopay = $providers->where('code', 'tokopay')->first();
@endphp

@section('content')
<div class="pb-6">
    <div class="space-y-6">
        <!-- Digiflazz Configuration -->
        <x-admin.card class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Digiflazz</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Provider voucher digital</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full {{ ($digiflazz->is_active ?? false) ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>
                        <span class="text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap">
                            {{ ($digiflazz->is_active ?? false) ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.settings.providers.update', 'digiflazz') }}" method="POST" class="space-y-4" id="digiflazz-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="digiflazz_username" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Username *
                        </label>
                        <input type="text" id="digiflazz_username" name="credentials[username]"
                            value="{{ old('credentials.username', getProviderCredential($digiflazz, 'username')) }}"
                            required
                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Username Digiflazz Anda</p>
                    </div>
                    
                    <div>
                        <label for="digiflazz_api_key" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            API Key *
                        </label>
                        <div class="relative">
                            <input type="password" id="digiflazz_api_key" name="credentials[api_key]"
                                   value="{{ old('credentials.api_key', getProviderCredential($digiflazz, 'api_key')) }}"
                                   required
                                   class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all pr-10">
                            <button type="button" onclick="togglePassword('digiflazz_api_key')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300">
                                <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">API Key dari akun Digiflazz</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="digiflazz_testing" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Mode Testing
                        </label>
                        <div class="flex items-center">
                            <div class="relative inline-block w-12 h-6 mr-3">
                                <input type="checkbox" 
                                       id="digiflazz_testing" 
                                       name="settings[testing]" 
                                       value="1"
                                       {{ getProviderSetting($digiflazz, 'testing', false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <label for="digiflazz_testing" 
                                       class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                            </div>
                            <label for="digiflazz_testing" class="text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                                Aktifkan mode testing
                            </label>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Untuk transaksi uji coba</p>
                    </div>
                    
                    <div>
                        <label for="digiflazz_timeout" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Timeout (detik)
                        </label>
                        <input type="number" id="digiflazz_timeout" name="settings[timeout]"
                               value="{{ old('settings.timeout', getProviderSetting($digiflazz, 'timeout', 25)) }}"
                               min="10" max="60"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Waktu tunggu API (10-60 detik)</p>
                    </div>
                </div>
                
                <div class="flex items-center mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex items-center">
                        <div class="relative inline-block w-12 h-6 mr-3">
                            <input type="checkbox" 
                                   id="digiflazz_is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ ($digiflazz->is_active ?? false) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <label for="digiflazz_is_active" 
                                   class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                            <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                        </div>
                        
                        <label for="digiflazz_is_active" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                            Aktifkan Digiflazz
                        </label>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-4 sm:mt-6">
                    <button type="button" onclick="testProvider('digiflazz')" 
                            class="px-4 py-2 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 
                                   text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm font-medium">
                        Test Connection
                    </button>
                    
                    <button type="button" onclick="syncProvider('digiflazz')"
                            class="px-4 py-2 rounded-xl sm:rounded-2xl bg-violet-500 hover:bg-violet-600 text-white text-sm font-medium">
                        Sync Products
                    </button>
                    
                    <button type="submit" class="px-4 py-2 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium">
                        Save Digiflazz
                    </button>
                </div>
            </form>
        </x-admin.card>
        
        <!-- Tokopay Configuration -->
        <x-admin.card class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Tokopay</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Payment gateway</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full {{ ($tokopay->is_active ?? false) ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>
                        <span class="text-sm text-slate-600 dark:text-slate-400 whitespace-nowrap">
                            {{ ($tokopay->is_active ?? false) ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.settings.providers.update', 'tokopay') }}" method="POST" class="space-y-4" id="tokopay-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="tokopay_merchant_code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Merchant Code *
                        </label>
                        <input type="text" id="tokopay_merchant_code" name="credentials[merchant_code]" 
                               value="{{ old('credentials.merchant_code', getProviderCredential($tokopay, 'merchant_code')) }}"
                               required
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Merchant ID Tokopay</p>
                    </div>
                    
                    <div>
                        <label for="tokopay_secret_key" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Secret Key *
                        </label>
                        <div class="relative">
                            <input type="password" id="tokopay_secret_key" name="credentials[secret_key]"
                                   value="{{ old('credentials.secret_key', getProviderCredential($tokopay, 'secret_key')) }}"
                                   required
                                   class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all pr-10">
                            <button type="button" onclick="togglePassword('tokopay_secret_key')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300">
                                <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Secret Key dari akun Tokopay</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="tokopay_kode_channel" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Kode Channel Default
                        </label>
                        <input type="text" id="tokopay_kode_channel" name="settings[kode_channel]"
                               value="{{ old('settings.kode_channel', getProviderSetting($tokopay, 'kode_channel', 'QRIS')) }}"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Contoh: QRIS, OVO, DANA</p>
                    </div>
                    
                    <div>
                        <label for="tokopay_timeout" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Timeout (detik)
                        </label>
                        <input type="number" id="tokopay_timeout" name="settings[timeout]"
                               value="{{ old('settings.timeout', getProviderSetting($tokopay, 'timeout', 20)) }}"
                               min="10" max="60"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Waktu tunggu API (10-60 detik)</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="tokopay_url_callback" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            URL Callback
                        </label>
                        <input type="url" id="tokopay_url_callback" name="settings[url_callback]"
                               value="{{ old('settings.url_callback', getProviderSetting($tokopay, 'url_callback', url('/api/payment/tokopay/callback'))) }}"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">URL untuk menerima callback pembayaran</p>
                    </div>
                    
                    <div>
                        <label for="tokopay_url_return" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            URL Return
                        </label>
                        <input type="url" id="tokopay_url_return" name="settings[url_return]"
                               value="{{ old('settings.url_return', getProviderSetting($tokopay, 'url_return', url('/payment/status'))) }}"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">URL setelah pembayaran berhasil</p>
                    </div>
                </div>
                
                <div class="flex items-center mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex items-center">
                        <div class="relative inline-block w-12 h-6 mr-3">
                            <input type="checkbox" 
                                   id="tokopay_is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ ($tokopay->is_active ?? false) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <label for="tokopay_is_active" 
                                   class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                            <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                        </div>
                        
                        <label for="tokopay_is_active" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                            Aktifkan Tokopay
                        </label>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-4 sm:mt-6">
                    <button type="button" onclick="testProvider('tokopay')" 
                            class="px-4 py-2 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 
                                   text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm font-medium">
                        Test Connection
                    </button>
                    
                    <button type="submit" class="px-4 py-2 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium">
                        Save Tokopay
                    </button>
                </div>
            </form>
        </x-admin.card>
    </div>
</div>

<!-- Sync Confirmation Modal -->
<div id="syncModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-violet-100 dark:bg-violet-900/30 mb-4">
                <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Sinkronisasi Produk</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                Sinkronisasi produk dari <span id="syncProviderName" class="font-medium"></span>?
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">
                Proses ini mungkin memakan waktu beberapa menit.
            </p>
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="closeSyncModal()" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">
                    Batal
                </button>
                <button type="button" onclick="confirmSync()" class="px-4 py-2 rounded-xl bg-violet-500 hover:bg-violet-600 text-white">
                    Ya, Sinkronisasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Test Connection Modal -->
<div id="testModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mx-auto mb-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500 mx-auto"></div>
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Test Koneksi</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                Sedang menguji koneksi ke <span id="testProviderName" class="font-medium"></span>...
            </p>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div id="resultModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4" id="resultIcon">
                <!-- Icon akan diisi oleh JavaScript -->
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2" id="resultTitle"></h3>
            <div class="text-sm text-slate-600 dark:text-slate-400 mb-6 text-left" id="resultMessage"></div>
            <button type="button" onclick="closeResultModal()" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white w-full">
                OK
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// CSRF token dari Laravel blade
const csrfToken = '{{ csrf_token() }}';
let currentProviderCode = '';
let currentFormId = '';

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('svg use');
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('href', '#icon-eye-slash');
    } else {
        input.type = 'password';
        icon.setAttribute('href', '#icon-eye');
    }
}

// Format currency
function formatCurrency(amount) {
    if (!amount) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

// Show test connection modal
function testProvider(code) {
    currentProviderCode = code;
    const providerName = code === 'digiflazz' ? 'Digiflazz' : 'Tokopay';
    
    // Validasi form
    const form = code === 'digiflazz' ? document.getElementById('digiflazz-form') : document.getElementById('tokopay-form');
    const formData = new FormData(form);
    
    if (code === 'digiflazz') {
        const username = formData.get('credentials[username]');
        const apiKey = formData.get('credentials[api_key]');
        if (!username || !apiKey) {
            showResult('warning', 'Data tidak lengkap', 'Harap isi username dan API key terlebih dahulu');
            return;
        }
    } else if (code === 'tokopay') {
        const merchantCode = formData.get('credentials[merchant_code]');
        const secretKey = formData.get('credentials[secret_key]');
        if (!merchantCode || !secretKey) {
            showResult('warning', 'Data tidak lengkap', 'Harap isi merchant code dan secret key terlebih dahulu');
            return;
        }
    }
    
    // Set provider name in modal
    document.getElementById('testProviderName').textContent = providerName;
    
    // Show modal
    const modal = document.getElementById('testModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Start test
    performTest(code, form);
}

// Perform test connection
async function performTest(code, form) {
    const formData = new FormData(form);
    let credentials = {};
    
    if (code === 'digiflazz') {
        credentials = {
            username: formData.get('credentials[username]') || '',
            api_key: formData.get('credentials[api_key]') || ''
        };
    } else if (code === 'tokopay') {
        credentials = {
            merchant_code: formData.get('credentials[merchant_code]') || '',
            secret_key: formData.get('credentials[secret_key]') || ''
        };
    }
    
    try {
        const url = `/admin/settings/providers/${code}/test`;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ credentials })
        });
        
        const data = await response.json();
        
        // Close test modal
        closeTestModal();
        
        if (data.success) {
            let message = data.message;
            if (data.data) {
                message += '<div class="mt-3 space-y-1">';
                if (data.data.balance !== undefined) {
                    message += `<p><span class="font-medium">Saldo:</span> ${formatCurrency(data.data.balance)}</p>`;
                }
                if (data.data.products_count !== undefined) {
                    message += `<p><span class="font-medium">Jumlah Produk:</span> ${data.data.products_count}</p>`;
                }
                if (data.data.merchant_name) {
                    message += `<p><span class="font-medium">Merchant:</span> ${data.data.merchant_name}</p>`;
                }
                if (data.data.status) {
                    message += `<p><span class="font-medium">Status:</span> <span class="text-emerald-600 font-medium">${data.data.status}</span></p>`;
                }
                message += '</div>';
            }
            showResult('success', 'Test Koneksi Berhasil', message);
        } else {
            showResult('error', 'Test Koneksi Gagal', data.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        closeTestModal();
        showResult('error', 'Error', error.message || 'Terjadi kesalahan jaringan');
    }
}

// Show sync confirmation modal
function syncProvider(code) {
    currentProviderCode = code;
    const providerName = code === 'digiflazz' ? 'Digiflazz' : 'Tokopay';
    
    document.getElementById('syncProviderName').textContent = providerName;
    
    const modal = document.getElementById('syncModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Confirm sync
async function confirmSync() {
    closeSyncModal();
    
    // Show loading
    const providerName = currentProviderCode === 'digiflazz' ? 'Digiflazz' : 'Tokopay';
    document.getElementById('testProviderName').textContent = providerName;
    
    const modal = document.getElementById('testModal');
    modal.classList.remove('hidden');
    
    try {
        const url = `/admin/settings/providers/${currentProviderCode}/sync`;
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        // Close test modal
        closeTestModal();
        
        if (data.success) {
            const message = `${data.message}<br><br><span class="font-medium">Total produk:</span> ${data.count || 0}`;
            showResult('success', 'Sinkronisasi Berhasil', message);
        } else {
            showResult('error', 'Sinkronisasi Gagal', data.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        closeTestModal();
        showResult('error', 'Error', error.message || 'Terjadi kesalahan jaringan');
    }
}

// Show result modal
function showResult(type, title, message) {
    const icon = document.getElementById('resultIcon');
    const titleEl = document.getElementById('resultTitle');
    const messageEl = document.getElementById('resultMessage');
    
    // Set icon based on type
    icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4';
    
    if (type === 'success') {
        icon.classList.add('bg-emerald-100', 'dark:bg-emerald-900/30');
        icon.innerHTML = `
            <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        `;
    } else if (type === 'error') {
        icon.classList.add('bg-rose-100', 'dark:bg-rose-900/30');
        icon.innerHTML = `
            <svg class="h-6 w-6 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        `;
    } else if (type === 'warning') {
        icon.classList.add('bg-yellow-100', 'dark:bg-yellow-900/30');
        icon.innerHTML = `
            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        `;
    }
    
    titleEl.textContent = title;
    messageEl.innerHTML = message;
    
    const modal = document.getElementById('resultModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Modal close functions
function closeSyncModal() {
    const modal = document.getElementById('syncModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeTestModal() {
    const modal = document.getElementById('testModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeResultModal() {
    const modal = document.getElementById('resultModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside or pressing Escape
document.querySelectorAll('#syncModal, #testModal, #resultModal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            if (modal.id === 'syncModal') closeSyncModal();
            if (modal.id === 'testModal') closeTestModal();
            if (modal.id === 'resultModal') closeResultModal();
        }
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSyncModal();
        closeTestModal();
        closeResultModal();
    }
});
</script>
@endpush
@endsection
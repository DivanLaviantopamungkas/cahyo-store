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
                $credentials = json_decode($credentials, true) ?? [];
            }
            return $credentials[$key] ?? $default;
        } catch (\Exception $e) { return $default; }
    }
    
    // Helper untuk get setting
    function getProviderSetting($provider, $key, $default = '') {
        if (!$provider) return $default;
        $settings = $provider->settings ?? [];
        if (is_string($settings)) {
            $settings = json_decode($settings, true) ?? [];
        }
        return $settings[$key] ?? $default;
    }
    
    $digiflazz = $providers->where('code', 'digiflazz')->first();
    $tokopay = $providers->where('code', 'tokopay')->first();
@endphp

@section('content')
<div class="max-w-5xl mx-auto pb-24 px-2 lg:px-0">
    <div class="space-y-12">
        
        <!-- DIGIFLAZZ SECTION -->
        <section class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none">
            <div class="p-6 lg:p-8 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                        <svg class="w-7 h-7"><use href="#icon-bolt"></use></svg>
                    </div>
                    <div>
                        <h3 class="text-base lg:text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Digiflazz</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Provider Produk Digital & PPOB</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white dark:bg-slate-800 px-4 py-2 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="w-2 h-2 rounded-full {{ ($digiflazz->is_active ?? false) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest {{ ($digiflazz->is_active ?? false) ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ ($digiflazz->is_active ?? false) ? 'Sistem Aktif' : 'Sistem Off' }}
                    </span>
                </div>
            </div>
            
            <form action="{{ route('admin.settings.providers.update', 'digiflazz') }}" method="POST" class="p-6 lg:p-8 space-y-8" id="digiflazz-form">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">API Username</label>
                        <input type="text" name="credentials[username]" value="{{ old('credentials.username', getProviderCredential($digiflazz, 'username')) }}" required
                            class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">API Production Key</label>
                        <div class="relative">
                            <input type="password" id="digiflazz_api_key" name="credentials[api_key]" value="{{ old('credentials.api_key', getProviderCredential($digiflazz, 'api_key')) }}" required
                                class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner pr-12">
                            <button type="button" onclick="togglePassword('digiflazz_api_key')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500">
                                <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 pt-4">
                    <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700 flex items-center justify-between transition-colors hover:border-emerald-500/30">
                        <div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Environment</span>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Mode Testing</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="settings[testing]" value="1" {{ getProviderSetting($digiflazz, 'testing', false) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                        </label>
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Request Timeout</label>
                        <div class="relative">
                            <input type="number" name="settings[timeout]" value="{{ old('settings.timeout', getProviderSetting($digiflazz, 'timeout', 25)) }}" min="10" max="60"
                                class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner pr-16">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400 uppercase">Detik</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6 pt-8 border-t border-slate-50 dark:border-slate-700/50">
                    <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900/50 px-6 py-4 rounded-2xl border border-slate-100 dark:border-slate-700 w-full lg:w-auto transition-colors hover:border-emerald-500/30">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="digiflazz_is_active" name="is_active" value="1" {{ ($digiflazz->is_active ?? false) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                        <span class="text-[11px] font-black text-slate-700 dark:text-white uppercase tracking-[0.1em]">Aktifkan Digiflazz</span>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                        <button type="button" onclick="testProvider('digiflazz')" class="flex-1 lg:flex-none px-6 py-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all active:scale-95 shadow-sm">
                            Test Connection
                        </button>
                        <button type="button" onclick="syncProvider('digiflazz')" class="flex-1 lg:flex-none px-6 py-4 rounded-2xl bg-violet-500 text-white font-black uppercase text-[10px] tracking-widest hover:bg-violet-600 transition-all active:scale-95 shadow-lg shadow-violet-500/20">
                            Sync Products
                        </button>
                        <button type="submit" class="w-full lg:w-auto px-10 py-4 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase text-[10px] tracking-[0.2em] hover:opacity-90 transition-all active:scale-95 shadow-xl">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- TOKOPAY SECTION -->
        <section class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none">
            <div class="p-6 lg:p-8 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-500">
                        <svg class="w-7 h-7"><use href="#icon-credit-card"></use></svg>
                    </div>
                    <div>
                        <h3 class="text-base lg:text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Tokopay</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Automated Payment Gateway</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white dark:bg-slate-800 px-4 py-2 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="w-2 h-2 rounded-full {{ ($tokopay->is_active ?? false) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest {{ ($tokopay->is_active ?? false) ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ ($tokopay->is_active ?? false) ? 'Gateway Aktif' : 'Gateway Off' }}
                    </span>
                </div>
            </div>
            
            <form action="{{ route('admin.settings.providers.update', 'tokopay') }}" method="POST" class="p-6 lg:p-8 space-y-8" id="tokopay-form">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-violet-500 transition-colors">Merchant Code</label>
                        <input type="text" name="credentials[merchant_code]" value="{{ old('credentials.merchant_code', getProviderCredential($tokopay, 'merchant_code')) }}" required
                            class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner">
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-violet-500 transition-colors">Secret Key</label>
                        <div class="relative">
                            <input type="password" id="tokopay_secret_key" name="credentials[secret_key]" value="{{ old('credentials.secret_key', getProviderCredential($tokopay, 'secret_key')) }}" required
                                class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner pr-12">
                            <button type="button" onclick="togglePassword('tokopay_secret_key')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-violet-500">
                                <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Default Channel</label>
                        <input type="text" name="settings[kode_channel]" value="{{ old('settings.kode_channel', getProviderSetting($tokopay, 'kode_channel', 'QRIS')) }}"
                            class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner" placeholder="QRIS">
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Timeout API</label>
                        <div class="relative">
                            <input type="number" name="settings[timeout]" value="{{ old('settings.timeout', getProviderSetting($tokopay, 'timeout', 20)) }}" min="10" max="60"
                                class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner pr-16">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400 uppercase">Detik</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">URL Callback</label>
                        <input type="url" name="settings[url_callback]" value="{{ old('settings.url_callback', getProviderSetting($tokopay, 'url_callback', url('/api/payment/tokopay/callback'))) }}"
                            class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-xs focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">URL Return</label>
                        <input type="url" name="settings[url_return]" value="{{ old('settings.url_return', getProviderSetting($tokopay, 'url_return', url('/payment/status'))) }}"
                            class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-xs focus:ring-2 focus:ring-violet-500/50 transition-all shadow-inner">
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6 pt-8 border-t border-slate-50 dark:border-slate-700/50">
                    <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900/50 px-6 py-4 rounded-2xl border border-slate-100 dark:border-slate-700 w-full lg:w-auto transition-colors hover:border-violet-500/30">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="tokopay_is_active" name="is_active" value="1" {{ ($tokopay->is_active ?? false) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-violet-500"></div>
                        </label>
                        <span class="text-[11px] font-black text-slate-700 dark:text-white uppercase tracking-[0.1em]">Aktifkan Tokopay</span>
                    </div>
                    
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <button type="button" onclick="testProvider('tokopay')" class="flex-1 lg:flex-none px-8 py-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-black uppercase text-[10px] tracking-widest hover:bg-slate-50 transition-all active:scale-95 shadow-sm">
                            Test Connection
                        </button>
                        <button type="submit" class="flex-1 lg:flex-none px-12 py-4 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase text-[10px] tracking-[0.2em] hover:opacity-90 transition-all active:scale-95 shadow-xl">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<div id="syncModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeSyncModal()"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-[3rem] w-full max-w-sm p-8 shadow-2xl border border-slate-100 dark:border-slate-700">
            <div class="w-20 h-20 bg-violet-50 dark:bg-violet-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            </div>
            <h3 class="text-xl font-black text-center text-slate-800 dark:text-white mb-2 uppercase tracking-tight">Sinkronisasi?</h3>
            <p class="text-center text-slate-500 text-sm mb-8 font-medium">Lanjutkan sinkronisasi produk dari <span id="syncProviderName" class="text-violet-500 font-bold"></span>?</p>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeSyncModal()" class="py-4 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-white font-bold text-xs uppercase tracking-widest active:scale-95">Batal</button>
                <button onclick="confirmSync()" class="w-full py-4 rounded-2xl bg-violet-500 text-white font-black text-xs uppercase tracking-widest shadow-lg active:scale-95">Ya, Sync</button>
            </div>
        </div>
    </div>
</div>

<div id="testModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
    <div class="bg-white dark:bg-slate-800 rounded-[3rem] p-10 text-center max-w-xs w-full shadow-2xl border border-slate-100 dark:border-slate-700">
        <div class="relative w-20 h-20 mx-auto mb-6">
            <div class="absolute inset-0 rounded-full border-4 border-emerald-500/20"></div>
            <div class="absolute inset-0 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin"></div>
        </div>
        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-widest">Testing...</h3>
        <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Checking <span id="testProviderName" class="text-emerald-500"></span></p>
    </div>
</div>

<div id="resultModal" class="hidden fixed inset-0 z-[110] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeResultModal()"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-[3rem] w-full max-w-sm p-8 shadow-2xl border border-slate-100 dark:border-slate-700">
            <div id="resultIcon" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 transition-colors duration-500"></div>
            <h3 id="resultTitle" class="text-xl font-black text-center text-slate-800 dark:text-white mb-2 uppercase tracking-tight"></h3>
            <div id="resultMessage" class="text-center text-slate-500 text-xs mb-8 font-medium leading-relaxed bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-700 text-left"></div>
            <button onclick="closeResultModal()" class="w-full py-4 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black text-xs uppercase tracking-[0.2em] shadow-lg active:scale-95 transition-all">Selesai</button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';
        let currentProviderCode = '';

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        }

        function testProvider(code) {
            currentProviderCode = code;
            document.getElementById('testProviderName').textContent = code.toUpperCase();
            document.getElementById('testModal').classList.remove('hidden');
            const form = document.getElementById(code + '-form');
            performTest(code, form);
        }

        async function performTest(code, form) {
            const formData = new FormData(form);
            let credentials = {};
            if(code === 'digiflazz') {
                credentials = { username: formData.get('credentials[username]'), api_key: formData.get('credentials[api_key]') };
            } else {
                credentials = { merchant_code: formData.get('credentials[merchant_code]'), secret_key: formData.get('credentials[secret_key]') };
            }

            try {
                const response = await fetch(`/admin/settings/providers/${code}/test`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ credentials })
                });
                const data = await response.json();
                closeTestModal();
                if (data.success) {
                    let info = `<p class="mb-1">${data.message}</p>`;
                    if (data.data?.balance) info += `<p class="text-emerald-500 font-bold">Saldo: ${formatCurrency(data.data.balance)}</p>`;
                    showResult('success', 'Berhasil', info);
                } else {
                    showResult('error', 'Gagal', data.message);
                }
            } catch (error) {
                closeTestModal();
                showResult('error', 'Error', 'Gagal menghubungi server API');
            }
        }

        function syncProvider(code) {
            currentProviderCode = code;
            document.getElementById('syncProviderName').textContent = code.toUpperCase();
            document.getElementById('syncModal').classList.remove('hidden');
        }

        async function confirmSync() {
            closeSyncModal();
            document.getElementById('testProviderName').textContent = currentProviderCode.toUpperCase();
            document.getElementById('testModal').classList.remove('hidden');
            try {
                const response = await fetch(`/admin/settings/providers/${currentProviderCode}/sync`);
                const data = await response.json();
                closeTestModal();
                if (data.success) showResult('success', 'Selesai', `${data.message}<br>Total: ${data.count} Produk`);
                else showResult('error', 'Gagal', data.message);
            } catch (error) {
                closeTestModal();
                showResult('error', 'Error', 'Gagal melakukan sinkronisasi');
            }
        }

        function showResult(type, title, message) {
            const icon = document.getElementById('resultIcon');
            const titleEl = document.getElementById('resultTitle');
            const messageEl = document.getElementById('resultMessage');
            icon.className = 'w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6';
            if (type === 'success') {
                icon.classList.add('bg-emerald-50', 'dark:bg-emerald-900/30');
                icon.innerHTML = '<svg class="w-10 h-10 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
            } else {
                icon.classList.add('bg-rose-50', 'dark:bg-rose-900/30');
                icon.innerHTML = '<svg class="w-10 h-10 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
            }
            titleEl.textContent = title;
            messageEl.innerHTML = message;
            document.getElementById('resultModal').classList.remove('hidden');
        }

        function closeSyncModal() { 
            document.getElementById('syncModal').classList.add('hidden'); 
        }

        function closeTestModal() { 
            document.getElementById('testModal').classList.add('hidden'); 
        }

        function closeResultModal() { 
            document.getElementById('resultModal').classList.add('hidden'); 
        }
    </script>
@endpush
@endsection
@extends('admin.layouts.app')

@section('title', 'Tambah Nominal')
@section('breadcrumb')
    <a href="{{ route('admin.nominals.index') }}">Nominals</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Tambah Baru</span>
@endsection

@section('actions')
    <a href="{{ route('admin.nominals.index') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
        <svg class="w-4 h-4 mr-2">
            <use href="#icon-chevron-right"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="max-w-3xl">
        <x-admin.card>
            <form action="{{ route('admin.nominals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div
                        class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800">
                        <h4 class="text-rose-700 dark:text-rose-300 font-semibold mb-2">Terdapat kesalahan:</h4>
                        <ul class="list-disc list-inside text-sm text-rose-600 dark:text-rose-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Product Selection -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pilih Produk</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="product_id"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Produk
                                    *</label>
                                <select id="product_id" name="product_id" required
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('product_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Code Input Section -->
                    <div id="code-section" class="hidden">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Input Kode</h3>
                        <div class="space-y-6">
                            <!-- Upload File Method -->
                            <div class="border border-slate-300 dark:border-slate-600 rounded-2xl p-4">
                                <div class="flex items-center mb-4">
                                    <input type="radio" id="method-upload" name="code_method" value="upload"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                                    <label for="method-upload"
                                        class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Upload File TXT
                                    </label>
                                </div>

                                <div id="upload-section" class="hidden pl-6">
                                    <div class="mb-4">
                                        <label for="code_file"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            File Kode (.txt)
                                        </label>
                                        <input type="file" id="code_file" name="code_file" accept=".txt"
                                            class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-900/30 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 dark:hover:file:bg-emerald-900/50">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                            Format: satu kode per baris. Maksimal 10MB
                                        </p>
                                        <div id="file-preview" class="mt-4 hidden">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Preview
                                                    kode:</span>
                                                <span id="code-count" class="text-xs text-slate-500"></span>
                                            </div>
                                            <div id="code-preview"
                                                class="max-h-60 overflow-y-auto border border-slate-200 dark:border-slate-700 rounded-xl p-3 bg-slate-50 dark:bg-slate-800/50 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Input Method -->
                            <div class="border border-slate-300 dark:border-slate-600 rounded-2xl p-4">
                                <div class="flex items-center mb-4">
                                    <input type="radio" id="method-manual" name="code_method" value="manual"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                                    <label for="method-manual"
                                        class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Input Manual
                                    </label>
                                </div>

                                <div id="manual-section" class="hidden pl-6">
                                    <div class="mb-4">
                                        <label for="codes_input"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            Kode (satu per baris)
                                        </label>
                                        <textarea id="codes_input" name="codes_input" rows="6"
                                            class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="Masukkan kode, satu per baris. Contoh:&#10;CODE123&#10;CODE456&#10;CODE789"></textarea>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                            Tekan Enter untuk baris baru. Kosongkan jika tidak ada kode.
                                        </p>
                                        <div id="manual-code-count" class="mt-2 hidden">
                                            <span class="text-xs text-slate-500">Total kode: <span
                                                    id="manual-count">0</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No Code Option -->
                            <div class="border border-slate-300 dark:border-slate-600 rounded-2xl p-4">
                                <div class="flex items-center">
                                    <input type="radio" id="method-none" name="code_method" value="none" checked
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                                    <label for="method-none"
                                        class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Tidak Ada Kode
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nominal Information -->
                    <div id="nominal-section" class="hidden">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Informasi Nominal</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Nominal
                                    *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('name') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Contoh: 100 Diamond">
                                @error('name')
                                    <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="provider_sku"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">SKU
                                    Provider</label>
                                <input type="text" id="provider_sku" name="provider_sku"
                                    value="{{ old('provider_sku') }}"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('provider_sku') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Kode SKU dari provider">
                                @error('provider_sku')
                                    <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="price"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Harga
                                        Normal *</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" id="price" name="price" value="{{ old('price') }}"
                                            required min="0"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border {{ $errors->has('price') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="100000">
                                    </div>
                                    @error('price')
                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discount_price"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Harga
                                        Diskon</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" id="discount_price" name="discount_price"
                                            value="{{ old('discount_price') }}" min="0"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border {{ $errors->has('discount_price') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="90000">
                                    </div>
                                    @error('discount_price')
                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="cost_price"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Harga
                                        Modal</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" id="cost_price" name="cost_price"
                                            value="{{ old('cost_price') }}" min="0"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border {{ $errors->has('cost_price') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="80000">
                                    </div>
                                    @error('cost_price')
                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="margin"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Margin
                                        (%)</label>
                                    <div class="relative">
                                        <input type="number" id="margin" name="margin" value="{{ old('margin') }}"
                                            min="0" max="100" step="0.01"
                                            class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('margin') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="20">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">%</span>
                                        </div>
                                    </div>
                                    @error('margin')
                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="stock"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total
                                        Stok *</label>
                                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}"
                                        required min="0"
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('stock') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    @error('stock')
                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="available_stock"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Stok
                                        Tersedia *</label>
                                    <input type="number" id="available_stock" name="available_stock"
                                        value="{{ old('available_stock', 0) }}" required min="0"
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('available_stock') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    @error('available_stock')
                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="stock_mode"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mode
                                    Stok</label>
                                <select id="stock_mode" name="stock_mode"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('stock_mode') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    <option value="">Pilih Mode Stok</option>
                                    <option value="manual" {{ old('stock_mode') == 'manual' ? 'selected' : '' }}>Manual
                                    </option>
                                    <option value="provider" {{ old('stock_mode') == 'provider' ? 'selected' : '' }}>
                                        Provider</option>
                                </select>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Manual: Stok dikelola manual | Provider: Stok sinkron dengan provider
                                </p>
                                @error('stock_mode')
                                    <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div id="settings-section" class="hidden">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pengaturan</h3>
                        <div class="space-y-4">
                            <div
                                class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                                <div>
                                    <label for="is_active"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status
                                        Aktif</label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Nominal dapat dipesan</p>
                                </div>
                                <div class="relative inline-block w-12 h-6">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', 1) ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors">
                                    </div>
                                    <div
                                        class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="order"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Urutan</label>
                                <input type="number" id="order" name="order" value="{{ old('order', 0) }}"
                                    min="0"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('order') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Angka lebih kecil muncul lebih
                                    awal</p>
                                @error('order')
                                    <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div id="submit-section" class="hidden pt-6 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.nominals.index') }}"
                                class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                Simpan Nominal
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const codeSection = document.getElementById('code-section');
            const nominalSection = document.getElementById('nominal-section');
            const settingsSection = document.getElementById('settings-section');
            const submitSection = document.getElementById('submit-section');
            const codeMethods = document.querySelectorAll('input[name="code_method"]');
            const uploadSection = document.getElementById('upload-section');
            const manualSection = document.getElementById('manual-section');
            const codeFileInput = document.getElementById('code_file');
            const filePreview = document.getElementById('file-preview');
            const codePreview = document.getElementById('code-preview');
            const codeCount = document.getElementById('code-count');
            const codesInput = document.getElementById('codes_input');
            const manualCodeCount = document.getElementById('manual-code-count');
            const manualCount = document.getElementById('manual-count');

            // Show sections when product is selected
            productSelect.addEventListener('change', function() {
                if (this.value) {
                    codeSection.classList.remove('hidden');
                    // Show nominal section only if product is selected
                    nominalSection.classList.remove('hidden');
                    settingsSection.classList.remove('hidden');
                    submitSection.classList.remove('hidden');
                } else {
                    codeSection.classList.add('hidden');
                    nominalSection.classList.add('hidden');
                    settingsSection.classList.add('hidden');
                    submitSection.classList.add('hidden');
                }
            });

            // Handle code method selection
            codeMethods.forEach(method => {
                method.addEventListener('change', function() {
                    uploadSection.classList.add('hidden');
                    manualSection.classList.add('hidden');

                    if (this.value === 'upload') {
                        uploadSection.classList.remove('hidden');
                    } else if (this.value === 'manual') {
                        manualSection.classList.remove('hidden');
                        updateManualCodeCount();
                    }
                });
            });

            // Handle file upload preview
            codeFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.type !== 'text/plain' && !file.name.endsWith('.txt')) {
                        alert('Hanya file TXT yang diperbolehkan');
                        this.value = '';
                        filePreview.classList.add('hidden');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const content = e.target.result;
                        const lines = content.split('\n')
                            .map(line => line.trim())
                            .filter(line => line.length > 0);

                        codePreview.innerHTML = lines.slice(0, 50)
                            .map(line =>
                                `<div class="py-1 px-2 mb-1 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700">${line}</div>`
                                )
                            .join('');

                        if (lines.length > 50) {
                            codePreview.innerHTML +=
                                `<div class="text-center text-sm text-slate-500 py-2">... dan ${lines.length - 50} kode lainnya</div>`;
                        }

                        codeCount.textContent = `${lines.length} kode ditemukan`;
                        filePreview.classList.remove('hidden');
                    };
                    reader.readAsText(file);
                } else {
                    filePreview.classList.add('hidden');
                }
            });

            // Count manual input codes
            codesInput.addEventListener('input', updateManualCodeCount);

            function updateManualCodeCount() {
                const codes = codesInput.value.split('\n')
                    .map(line => line.trim())
                    .filter(line => line.length > 0);

                if (codes.length > 0) {
                    manualCount.textContent = codes.length;
                    manualCodeCount.classList.remove('hidden');
                } else {
                    manualCodeCount.classList.add('hidden');
                }
            }

            // Existing calculation functions
            const priceInput = document.getElementById('price');
            const costPriceInput = document.getElementById('cost_price');
            const marginInput = document.getElementById('margin');
            const discountInput = document.getElementById('discount_price');
            const stockInput = document.getElementById('stock');
            const availableStockInput = document.getElementById('available_stock');

            // Auto calculate margin
            function calculateMargin() {
                if (priceInput.value && costPriceInput.value) {
                    const price = parseFloat(priceInput.value);
                    const cost = parseFloat(costPriceInput.value);

                    if (price > cost && cost > 0) {
                        const margin = ((price - cost) / price) * 100;
                        marginInput.value = margin.toFixed(2);
                    }
                }
            }

            priceInput.addEventListener('change', calculateMargin);
            costPriceInput.addEventListener('change', calculateMargin);

            // Validasi discount_price < price
            discountInput.addEventListener('change', function() {
                const price = parseFloat(priceInput.value);
                const discount = parseFloat(this.value);

                if (discount && price && discount >= price) {
                    alert('Harga diskon harus lebih kecil dari harga normal');
                    this.value = '';
                    this.focus();
                }
            });

            // Validasi available_stock <= stock
            availableStockInput.addEventListener('change', function() {
                const stock = parseFloat(stockInput.value);
                const available = parseFloat(this.value);

                if (available > stock) {
                    alert('Stok tersedia tidak boleh lebih besar dari total stok');
                    this.value = stock;
                }
            });

            // Initialize based on existing product selection
            if (productSelect.value) {
                codeSection.classList.remove('hidden');
                nominalSection.classList.remove('hidden');
                settingsSection.classList.remove('hidden');
                submitSection.classList.remove('hidden');
            }

            // Initialize code method based on selection
            const selectedMethod = document.querySelector('input[name="code_method"]:checked');
            if (selectedMethod) {
                if (selectedMethod.value === 'upload') {
                    uploadSection.classList.remove('hidden');
                } else if (selectedMethod.value === 'manual') {
                    manualSection.classList.remove('hidden');
                    updateManualCodeCount();
                }
            }
        });
    </script>
@endsection

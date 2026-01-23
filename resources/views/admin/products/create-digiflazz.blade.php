@extends('admin.layouts.app')

@section('title', 'Import Produk dari Digiflazz')
@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}">Produk</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <a href="{{ route('admin.products.create.digiflazz') }}">Tambah Produk</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Import dari Digiflazz</span>
@endsection

@section('actions')
    <a href="{{ route('admin.products.index') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
        <svg class="w-4 h-4 mr-2">
            <use href="#icon-arrow-left"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Import Produk dari Digiflazz</h2>
            <div class="flex items-center space-x-3">
                <!-- Tombol Import Semua -->
                <button type="button" onclick="showBulkImportModal()"
                    class="inline-flex items-center px-4 py-2 rounded-2xl bg-purple-500 hover:bg-purple-600 text-white font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2">
                        <use href="#icon-download"></use>
                    </svg>
                    Import Semua Produk
                </button>

                <a href="{{ route('admin.products.create.manual') }}"
                    class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2">
                        <use href="#icon-edit"></use>
                    </svg>
                    Buat Manual
                </a>
            </div>
        </div>

        <x-admin.card>
            <form action="{{ route('admin.products.store.digiflazz') }}" method="POST" enctype="multipart/form-data"
                id="digiflazzForm">
                @csrf
                <input type="hidden" name="source" value="digiflazz">

                <div class="space-y-8">
                    <!-- Kategori -->
                    <div class="p-6 border border-slate-200 dark:border-slate-700 rounded-2xl">
                        <h4 class="text-sm font-semibold text-slate-800 dark:text-white mb-4">Kategori Produk *</h4>
                        <div>
                            <label for="category_id"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Pilih Kategori
                                @error('category_id')
                                    <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                @enderror
                            </label>
                            <select id="category_id" name="category_id" required
                                class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('category_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kategori wajib dipilih</p>
                        </div>
                    </div>

                    <!-- Pilih Produk Digiflazz -->
                    <div>
                        <h3
                            class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                            Pilih Produk dari Digiflazz</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="provider_id"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Provider Digiflazz *
                                    @error('provider_id')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <select id="provider_id" name="provider_id" required onchange="loadProviderProducts()"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('provider_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="">Pilih Provider</option>
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider->id }}"
                                            {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                            {{ $provider->name }} ({{ $provider->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="provider_sku"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Pilih Produk *
                                    @error('provider_sku')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <select id="provider_sku" name="provider_sku" required
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('provider_sku') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    disabled>
                                    <option value="">Pilih provider terlebih dahulu</option>
                                </select>
                                <div id="productLoading" class="hidden mt-2">
                                    <div class="flex items-center space-x-2">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                                        <span class="text-sm text-slate-600 dark:text-slate-400">Memuat produk...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Preview -->
                        <div id="productPreview"
                            class="mt-6 p-6 border border-slate-200 dark:border-slate-700 rounded-2xl hidden">
                            <h4 class="font-semibold text-slate-800 dark:text-white mb-4">Preview Produk Digiflazz</h4>
                            <div class="flex items-start space-x-4">
                                <div id="previewImage"
                                    class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-400">
                                        <use href="#icon-package"></use>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 id="previewName" class="font-semibold text-slate-800 dark:text-white mb-2"></h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">SKU:</span>
                                            <p id="previewSku" class="text-sm text-slate-700 dark:text-slate-300 font-mono">
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">Kategori:</span>
                                            <p id="previewCategory" class="text-sm text-slate-700 dark:text-slate-300"></p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">Brand:</span>
                                            <p id="previewBrand" class="text-sm text-slate-700 dark:text-slate-300"></p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">Harga Provider:</span>
                                            <p id="previewPrice" class="text-sm font-medium text-slate-800 dark:text-white">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <span class="text-sm text-slate-500 dark:text-slate-400">Deskripsi:</span>
                                        <p id="previewDescription" class="text-sm text-slate-700 dark:text-slate-300 mt-1">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Auto Fill Nominals Section -->
                    <div id="autoFillSection" class="hidden">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Nominal Produk</h3>
                        <div id="autoNominalsContainer" class="space-y-4">
                            <!-- Nominals akan diisi otomatis oleh JavaScript -->
                        </div>
                    </div>

                    <!-- Customize (Optional) -->
                    <div>
                        <h3
                            class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                            Kustomisasi (Opsional)
                            <span class="text-sm font-normal text-slate-500 dark:text-slate-400">- Kosongkan untuk
                                menggunakan data dari Digiflazz</span>
                        </h3>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Nama Produk (Opsional)
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Biarkan kosong untuk menggunakan nama dari Digiflazz">
                            </div>

                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Deskripsi (Opsional)
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Biarkan kosong untuk menggunakan deskripsi dari Digiflazz">{{ old('description') }}</textarea>
                            </div>

                            <!-- Tipe Produk -->
                            <div>
                                <label for="type"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Tipe Produk *
                                    @error('type')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <select id="type" name="type" required
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('type') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="single" {{ old('type') == 'single' ? 'selected' : 'selected' }}>Single
                                        (1 nominal)</option>
                                    <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>Multiple
                                        (banyak nominal)</option>
                                </select>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Single: Produk dengan 1 nominal saja<br>
                                    Multiple: Produk dengan banyak pilihan nominal
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Nominal -->
                    <div id="nominalsSection">
                        <div
                            class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Daftar Nominal</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Harga akan disesuaikan dengan margin
                                    yang diatur</p>
                            </div>
                            <button type="button" onclick="addNominal()"
                                class="inline-flex items-center px-4 py-2 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors">
                                <svg class="w-4 h-4 mr-2">
                                    <use href="#icon-plus"></use>
                                </svg>
                                Tambah Nominal
                            </button>
                        </div>

                        <!-- Container untuk dynamic nominals -->
                        <div id="nominalsContainer" class="space-y-6">
                            <!-- Default single nominal untuk produk tipe single -->
                            <div class="nominal-item p-6 border border-slate-200 dark:border-slate-700 rounded-2xl"
                                data-index="0">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-sm font-semibold text-slate-800 dark:text-white">Nominal #1</h4>
                                    <button type="button" onclick="removeNominal(this)"
                                        class="text-rose-600 hover:text-rose-800">
                                        <svg class="w-5 h-5">
                                            <use href="#icon-trash"></use>
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                Nama Nominal *
                                            </label>
                                            <input type="text" name="nominals[0][name]" required
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                placeholder="Contoh: 100 Diamond" value="{{ old('nominals.0.name') }}">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                SKU Provider (Opsional)
                                            </label>
                                            <input type="text" name="nominals[0][provider_sku]"
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                placeholder="Kode SKU dari Digiflazz"
                                                value="{{ old('nominals.0.provider_sku') }}">
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Harga Modal (Dari Digiflazz) *
                                                </label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][cost_price]" required
                                                        min="0" step="100"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                        placeholder="100000" value="{{ old('nominals.0.cost_price') }}">
                                                </div>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Margin (%) *
                                                </label>
                                                <div class="relative">
                                                    <input type="number" name="nominals[0][margin]" required
                                                        min="0" max="100" step="0.5"
                                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                        placeholder="10" value="{{ old('nominals.0.margin', 10) }}">
                                                    <div
                                                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Harga Jual *
                                                </label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][price]" required
                                                        min="0" step="100"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                        placeholder="110000" value="{{ old('nominals.0.price') }}">
                                                </div>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Akan otomatis
                                                    terisi dari harga modal + margin</p>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Harga Diskon (Opsional)
                                                </label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][discount_price]"
                                                        min="0" step="100"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                        placeholder="105000"
                                                        value="{{ old('nominals.0.discount_price') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Total Stok *
                                                </label>
                                                <input type="number" name="nominals[0][stock]" required min="0"
                                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                    value="{{ old('nominals.0.stock', 9999) }}">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Stok Tersedia *
                                                </label>
                                                <input type="number" name="nominals[0][available_stock]" required
                                                    min="0"
                                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                    value="{{ old('nominals.0.available_stock', 9999) }}">
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                Mode Stok
                                            </label>
                                            <select name="nominals[0][stock_mode]"
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                <option value="provider"
                                                    {{ old('nominals.0.stock_mode') == 'provider' ? 'selected' : 'selected' }}>
                                                    Provider (Sinkron dengan Digiflazz)</option>
                                                <option value="manual"
                                                    {{ old('nominals.0.stock_mode') == 'manual' ? 'selected' : '' }}>Manual
                                                </option>
                                            </select>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                Provider: Stok akan sinkron dengan Digiflazz secara otomatis<br>
                                                Manual: Stok dikelola manual
                                            </p>
                                        </div>

                                        <div
                                            class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                                    Status Aktif
                                                </label>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">Nominal dapat dipesan
                                                </p>
                                            </div>
                                            <div class="relative inline-block w-12 h-6">
                                                <input type="hidden" name="nominals[0][is_active]" value="0">
                                                <input type="checkbox" name="nominals[0][is_active]" value="1"
                                                    {{ old('nominals.0.is_active', 1) ? 'checked' : '' }}
                                                    class="sr-only peer">
                                                <label
                                                    class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-blue-500 transition-colors cursor-pointer"></label>
                                                <div
                                                    class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div>
                        <h3
                            class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                            Pengaturan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Status Aktif -->
                            <div
                                class="flex items-center justify-between p-4 rounded-2xl border {{ $errors->has('is_active') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}">
                                <div>
                                    <label for="is_active"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Status Aktif
                                        @error('is_active')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs block"> -
                                                {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Produk dapat dilihat</p>
                                </div>
                                <div class="relative inline-block w-12 h-6">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                                    <label for="is_active"
                                        class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-blue-500 transition-colors cursor-pointer"></label>
                                    <div
                                        class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Featured -->
                            <div
                                class="flex items-center justify-between p-4 rounded-2xl border {{ $errors->has('is_featured') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}">
                                <div>
                                    <label for="is_featured"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Featured
                                        @error('is_featured')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs block"> -
                                                {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Tampilkan di utama</p>
                                </div>
                                <div class="relative inline-block w-12 h-6">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                        {{ old('is_featured') ? 'checked' : '' }} class="sr-only peer">
                                    <label for="is_featured"
                                        class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-blue-500 transition-colors cursor-pointer"></label>
                                    <div
                                        class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Urutan -->
                            <div>
                                <label for="order"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Urutan
                                    @error('order')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <input type="number" id="order" name="order" value="{{ old('order', 0) }}"
                                    min="0"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('order') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.products.index') }}"
                                class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                Import dari Digiflazz
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>

    <!-- Bulk Import Modal -->
    <!-- Bulk Import Modal -->
    <div id="bulkImportModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Import Semua Produk</h3>
                <button type="button" onclick="closeBulkImportModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5">
                        <use href="#icon-x"></use>
                    </svg>
                </button>
            </div>

            <form id="bulkImportForm" action="{{ route('admin.products.products.import-all-digiflazz') }}"
                method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Provider *
                        </label>
                        <select name="provider_id" id="bulkProviderId" required onchange="loadProviderCategories()"
                            class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Pilih Provider</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }} ({{ $provider->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="providerCategoriesSection" class="hidden">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Filter Kategori Digiflazz (Opsional)
                                </label>
                                <button type="button" onclick="toggleAllCategories()"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    Pilih Semua
                                </button>
                            </div>

                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-2">
                                Kosongkan untuk import semua produk
                            </div>

                            <div id="providerCategoriesContainer"
                                class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                                <!-- Kategori akan diisi otomatis oleh JavaScript -->
                            </div>

                            <div id="categoryLoading" class="hidden text-center py-6">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Memuat kategori...</p>
                            </div>

                            <div id="noCategoriesMessage" class="hidden">
                                <div
                                    class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                                    <p class="text-sm text-amber-700 dark:text-amber-300">
                                        Tidak ada kategori tersedia. Semua produk akan diimport.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan kategori: manual atau auto -->
                    <div>
                        <div class="mb-4">
                            <div class="flex items-center space-x-3 mb-3">
                                <input type="radio" id="categoryOptionAuto" name="category_option" value="auto"
                                    checked class="text-blue-600 focus:ring-blue-500">
                                <label for="categoryOptionAuto"
                                    class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Buat Kategori Otomatis dari Digiflazz
                                </label>
                            </div>
                            <div class="flex items-center space-x-3">
                                <input type="radio" id="categoryOptionManual" name="category_option" value="manual"
                                    class="text-blue-600 focus:ring-blue-500">
                                <label for="categoryOptionManual"
                                    class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Pilih Kategori Manual
                                </label>
                            </div>
                        </div>

                        <!-- Kategori Manual (hidden by default) -->
                        <div id="manualCategorySection" class="hidden">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Pilih Kategori Lokal
                            </label>
                            <select name="category_id"
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Pilih Kategori Lokal</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Semua produk akan disimpan di kategori ini
                            </p>
                        </div>

                        <!-- Info Auto Category -->
                        <div id="autoCategoryInfo"
                            class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <span class="font-medium">📁 Buat Kategori Otomatis:</span><br>
                                • Kategori akan dibuat otomatis dari nama kategori Digiflazz<br>
                                • Jika kategori sudah ada, akan digunakan kategori yang ada<br>
                                • Produk akan dikelompokkan berdasarkan kategori aslinya
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Margin (%) *
                            </label>
                            <div class="relative">
                                <input type="number" name="margin" required min="0" max="100"
                                    step="0.5" value="10"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500">%</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Tipe Produk *
                            </label>
                            <select name="type" required
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="multiple" selected>Multiple (Kelompok berdasarkan brand)</option>
                                <option value="single">Single (Setiap produk terpisah)</option>
                            </select>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Multiple: Produk dikelompokkan berdasarkan brand<br>
                                Single: Setiap produk dibuat terpisah
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar untuk Import -->
                    <div id="importProgress" class="hidden space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-700 dark:text-slate-300">Progress</span>
                            <span id="progressPercentage" class="font-medium">0%</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            <div id="progressBar" class="bg-purple-500 h-2 rounded-full transition-all duration-300"
                                style="width: 0%"></div>
                        </div>
                        <p id="progressText" class="text-xs text-slate-500 dark:text-slate-400">
                            Menyiapkan import...
                        </p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeBulkImportModal()"
                            class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="submitBulkImport"
                            class="px-6 py-3 rounded-2xl bg-purple-500 hover:bg-purple-600 text-white font-semibold transition-colors">
                            Mulai Import
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Import Success Modal -->
    <div id="bulkImportSuccessModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md p-8">
            <div class="text-center">
                <div
                    class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400">
                        <use href="#icon-check"></use>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-2" id="successTitle">Import Berhasil!
                </h3>
                <p class="text-slate-600 dark:text-slate-400 mb-4" id="successMessage"></p>
                <p id="failedProducts" class="text-xs text-slate-500 dark:text-slate-400 hidden"></p>
                <div class="mt-6">
                    <button type="button" onclick="closeSuccessModal()"
                        class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Bulk Import Modal Functions
            function showBulkImportModal() {
                document.getElementById('bulkImportModal').classList.remove('hidden');
                document.getElementById('providerCategoriesSection').classList.add('hidden');
                document.getElementById('providerCategoriesContainer').innerHTML = '';
                document.getElementById('bulkProviderId').value = '';
            }

            function closeBulkImportModal() {
                document.getElementById('bulkImportModal').classList.add('hidden');
                document.getElementById('providerCategoriesSection').classList.add('hidden');
                document.getElementById('importProgress').classList.add('hidden');
            }

            function closeSuccessModal() {
                document.getElementById('bulkImportSuccessModal').classList.add('hidden');
            }

            // Load provider products (existing function)
            function loadProviderProducts() {
                const providerId = document.getElementById('provider_id').value;
                const productSelect = document.getElementById('provider_sku');
                const loading = document.getElementById('productLoading');
                const preview = document.getElementById('productPreview');

                if (!providerId) {
                    productSelect.innerHTML = '<option value="">Pilih provider terlebih dahulu</option>';
                    productSelect.disabled = true;
                    preview.classList.add('hidden');
                    return;
                }

                loading.classList.remove('hidden');
                productSelect.disabled = true;
                preview.classList.add('hidden');

                productSelect.innerHTML = '<option value="">Memuat produk...</option>';

                const url = `/admin/products/providers/${providerId}/products`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        productSelect.innerHTML = '<option value="">Pilih Produk</option>';

                        if (data.error) {
                            productSelect.innerHTML += `<option value="">Error: ${data.message}</option>`;
                        } else if (data.length === 0) {
                            productSelect.innerHTML += '<option value="">Tidak ada produk tersedia</option>';
                        } else {
                            data.forEach(product => {
                                const productData = {
                                    sku: product.sku || '',
                                    name: product.name || 'Tanpa Nama',
                                    category: product.category || '',
                                    brand: product.brand || '',
                                    price: product.price || '0',
                                    details: product.details || {}
                                };

                                productSelect.innerHTML += `<option value="${productData.sku}"
                                    data-product='${JSON.stringify(productData)}'>
                                    ${productData.name} - Rp ${productData.price}
                                </option>`;
                            });
                        }

                        productSelect.disabled = false;
                        loading.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error loading products:', error);
                        productSelect.innerHTML = '<option value="">Gagal memuat produk</option>';
                        loading.classList.add('hidden');
                    });
            }

            // Load provider categories
            function loadProviderCategories() {
                const providerId = document.getElementById('bulkProviderId').value;
                const categoriesContainer = document.getElementById('providerCategoriesContainer');
                const categoriesSection = document.getElementById('providerCategoriesSection');
                const loading = document.getElementById('categoryLoading');

                if (!providerId) {
                    categoriesSection.classList.add('hidden');
                    return;
                }

                // Show loading
                loading.classList.remove('hidden');
                categoriesSection.classList.remove('hidden');
                categoriesContainer.innerHTML = '';

                const url = `{{ route('admin.products.products.provider-categories', ':id') }}`.replace(':id', providerId);

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(categories => {
                        loading.classList.add('hidden');

                        if (categories.length === 0) {
                            categoriesContainer.innerHTML = `
                                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                                    <p class="text-sm text-amber-700 dark:text-amber-300">
                                        Tidak ada kategori tersedia. Semua produk akan diimport.
                                    </p>
                                </div>
                            `;
                            return;
                        }

                        // Add select all option
                        categoriesContainer.innerHTML = `
                            <div class="mb-2">
                                <label class="flex items-center space-x-2 text-sm">
                                    <input type="checkbox" id="selectAllCategories" onchange="toggleAllCategories()"
                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Pilih Semua</span>
                                </label>
                            </div>
                            <div class="max-h-48 overflow-y-auto space-y-1 p-1">
                        `;

                        categories.forEach((category, index) => {
                            const categoryId = `category_${index}`;
                            categoriesContainer.innerHTML += `
                                <div class="flex items-center space-x-2 p-2 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg">
                                    <input type="checkbox" id="${categoryId}" name="provider_categories[]"
                                           value="${category.value}"
                                           class="category-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updateSelectAll()">
                                    <label for="${categoryId}" class="flex-1 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                                        ${category.label}
                                    </label>
                                </div>
                            `;
                        });

                        categoriesContainer.innerHTML += `</div>`;
                    })
                    .catch(error => {
                        console.error('Error loading categories:', error);
                        loading.classList.add('hidden');
                        categoriesContainer.innerHTML = `
                            <div class="p-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl">
                                <p class="text-sm text-rose-700 dark:text-rose-300">
                                    Gagal memuat kategori. Anda tetap dapat melanjutkan import semua produk.
                                </p>
                            </div>
                        `;
                    });
            }

            // Toggle semua kategori
            function toggleAllCategories() {
                const selectAll = document.getElementById('selectAllCategories');
                const checkboxes = document.querySelectorAll('.category-checkbox');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            }

            // Update select all checkbox
            function updateSelectAll() {
                const checkboxes = document.querySelectorAll('.category-checkbox');
                const selectAll = document.getElementById('selectAllCategories');

                if (checkboxes.length > 0) {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                }
            }

            // Show product preview
            function showProductPreview(product) {
                const preview = document.getElementById('productPreview');
                const previewImage = document.getElementById('previewImage');

                document.getElementById('previewName').textContent = product.name;
                document.getElementById('previewSku').textContent = product.sku;
                document.getElementById('previewCategory').textContent = product.category || '-';
                document.getElementById('previewBrand').textContent = product.brand || '-';
                document.getElementById('previewPrice').textContent = `Rp ${parseInt(product.price).toLocaleString()}`;

                const description = product.details?.description || '-';
                document.getElementById('previewDescription').textContent = description;

                if (product.details?.icon_url) {
                    previewImage.innerHTML =
                        `<img src="${product.details.icon_url}" alt="${product.name}" class="w-full h-full object-cover rounded-xl">`;
                } else {
                    previewImage.innerHTML = `<svg class="w-10 h-10 text-slate-400"><use href="#icon-package"></use></svg>`;
                }

                const nameField = document.getElementById('name');
                const descField = document.getElementById('description');

                if (!nameField.value) {
                    nameField.value = product.name;
                }

                if (!descField.value && description && description !== '-') {
                    descField.value = description;
                }

                // Auto-fill nominal data
                const costPriceInput = document.querySelector('input[name="nominals[0][cost_price]"]');
                if (costPriceInput && !costPriceInput.value) {
                    costPriceInput.value = product.price;
                    calculatePrice(costPriceInput);
                }

                preview.classList.remove('hidden');
            }

            // Product select change event
            document.getElementById('provider_sku').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.product) {
                    const product = JSON.parse(selectedOption.dataset.product);
                    showProductPreview(product);
                } else {
                    document.getElementById('productPreview').classList.add('hidden');
                }
            });

            // Calculate price from cost price and margin
            function calculatePrice(input) {
                const container = input.closest('.nominal-item');
                if (!container) return;

                const costPriceInput = container.querySelector('[name$="[cost_price]"]');
                const marginInput = container.querySelector('[name$="[margin]"]');
                const priceInput = container.querySelector('[name$="[price]"]');

                if (costPriceInput && marginInput && priceInput) {
                    const cost = parseFloat(costPriceInput.value) || 0;
                    const margin = parseFloat(marginInput.value) || 0;
                    const marginPercent = margin / 100;

                    if (cost > 0 && marginPercent > 0) {
                        const price = cost / (1 - marginPercent);
                        priceInput.value = Math.ceil(price / 100) * 100;
                    }
                }
            }

            // Attach event listeners to price calculation
            function attachPriceCalculation(container) {
                const costPriceInput = container.querySelector('[name$="[cost_price]"]');
                const marginInput = container.querySelector('[name$="[margin]"]');

                if (costPriceInput) {
                    costPriceInput.addEventListener('change', function() {
                        calculatePrice(this);
                    });
                }

                if (marginInput) {
                    marginInput.addEventListener('change', function() {
                        calculatePrice(this);
                    });
                }
            }

            // Nominal management
            let nominalIndex = {{ count(old('nominals', [])) }};

            function addNominal() {
                const container = document.getElementById('nominalsContainer');
                const template = document.createElement('div');
                template.classList.add('nominal-item', 'p-6', 'border', 'border-slate-200', 'dark:border-slate-700',
                    'rounded-2xl');
                template.setAttribute('data-index', nominalIndex);

                template.innerHTML = `
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-semibold text-slate-800 dark:text-white">Nominal #${nominalIndex + 1}</h4>
                        <button type="button" onclick="removeNominal(this)" class="text-rose-600 hover:text-rose-800">
                            <svg class="w-5 h-5">
                                <use href="#icon-trash"></use>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Nama Nominal *
                                </label>
                                <input type="text" name="nominals[${nominalIndex}][name]" required
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Contoh: 100 Diamond">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    SKU Provider (Opsional)
                                </label>
                                <input type="text" name="nominals[${nominalIndex}][provider_sku]"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Kode SKU dari Digiflazz">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Modal (Dari Digiflazz) *
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" name="nominals[${nominalIndex}][cost_price]" required min="0" step="100"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="100000">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Margin (%) *
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="nominals[${nominalIndex}][margin]" required min="0" max="100" step="0.5"
                                            class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="10" value="10">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Jual *
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" name="nominals[${nominalIndex}][price]" required min="0" step="100"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="110000">
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Akan otomatis terisi dari harga modal + margin</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Diskon (Opsional)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" name="nominals[${nominalIndex}][discount_price]" min="0" step="100"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="105000">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Total Stok *
                                    </label>
                                    <input type="number" name="nominals[${nominalIndex}][stock]" required min="0"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        value="9999">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Stok Tersedia *
                                    </label>
                                    <input type="number" name="nominals[${nominalIndex}][available_stock]" required min="0"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        value="9999">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Mode Stok
                                </label>
                                <select name="nominals[${nominalIndex}][stock_mode]"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="provider" selected>Provider (Sinkron dengan Digiflazz)</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Status Aktif
                                    </label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Nominal dapat dipesan</p>
                                </div>
                                <div class="relative inline-block w-12 h-6">
                                    <input type="hidden" name="nominals[${nominalIndex}][is_active]" value="0">
                                    <input type="checkbox" name="nominals[${nominalIndex}][is_active]" value="1" checked class="sr-only peer">
                                    <label class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-blue-500 transition-colors cursor-pointer"></label>
                                    <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(template);
                nominalIndex++;

                // Attach event listeners
                attachPriceCalculation(template);
            }

            function removeNominal(button) {
                const item = button.closest('.nominal-item');
                const container = document.getElementById('nominalsContainer');
                const items = container.querySelectorAll('.nominal-item');

                // Don't remove if only one item remains
                if (items.length <= 1) {
                    alert('Minimal harus ada 1 nominal');
                    return;
                }

                item.remove();
                reindexNominals();
            }

            function reindexNominals() {
                const container = document.getElementById('nominalsContainer');
                const items = container.querySelectorAll('.nominal-item');

                items.forEach((item, index) => {
                    item.setAttribute('data-index', index);

                    const heading = item.querySelector('h4');
                    heading.textContent = `Nominal #${index + 1}`;

                    item.querySelectorAll('[name]').forEach(input => {
                        const oldName = input.getAttribute('name');
                        const newName = oldName.replace(/nominals\[\d+\]/, `nominals[${index}]`);
                        input.setAttribute('name', newName);
                    });
                });

                nominalIndex = items.length;
            }

            // Initialize event listeners for existing nominals
            document.addEventListener('DOMContentLoaded', function() {
                const initialItems = document.querySelectorAll('.nominal-item');
                initialItems.forEach(item => {
                    attachPriceCalculation(item);
                });

                // Handle old provider selection
                const oldProviderId = "{{ old('provider_id') }}";
                if (oldProviderId) {
                    document.getElementById('provider_id').value = oldProviderId;
                    setTimeout(() => loadProviderProducts(), 100);
                }
            });

            // Handle product type change
            document.getElementById('type').addEventListener('change', function(e) {
                const nominalsSection = document.getElementById('nominalsSection');
                const addButton = nominalsSection.querySelector('button');

                if (e.target.value === 'single') {
                    addButton.style.display = 'none';
                    const items = document.querySelectorAll('.nominal-item');
                    items.forEach((item, index) => {
                        if (index > 0) {
                            item.style.display = 'none';
                        }
                    });
                } else {
                    addButton.style.display = 'inline-flex';
                    const items = document.querySelectorAll('.nominal-item');
                    items.forEach(item => {
                        item.style.display = 'block';
                    });
                }
            });

            // Handle category option change
            document.querySelectorAll('input[name="category_option"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const manualSection = document.getElementById('manualCategorySection');
                    const autoInfo = document.getElementById('autoCategoryInfo');

                    if (this.value === 'manual') {
                        manualSection.classList.remove('hidden');
                        autoInfo.classList.add('hidden');
                        // Set required attribute for manual category select
                        manualSection.querySelector('select').required = true;
                    } else {
                        manualSection.classList.add('hidden');
                        autoInfo.classList.remove('hidden');
                        // Remove required attribute
                        manualSection.querySelector('select').required = false;
                    }
                });
            });

            // Update form submission to handle category option
            document.getElementById('bulkImportForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();

                const providerId = this.querySelector('[name="provider_id"]').value;
                const submitBtn = document.getElementById('submitBulkImport');

                if (!providerId) {
                    alert('Harap pilih provider terlebih dahulu');
                    return;
                }

                // Check category option
                const categoryOption = document.querySelector('input[name="category_option"]:checked').value;
                const manualCategoryId = document.querySelector('select[name="category_id"]').value;

                if (categoryOption === 'manual' && !manualCategoryId) {
                    alert('Harap pilih kategori manual terlebih dahulu');
                    return;
                }

                // Prepare form data
                const formData = new FormData(this);
                formData.append('auto_create_category', categoryOption === 'auto' ? '1' : '0');

                // Remove category_id if auto mode
                if (categoryOption === 'auto') {
                    formData.delete('category_id');
                }

                // Disable submit button and show progress
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
        <div class="flex items-center justify-center">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
            Memproses...
        </div>
    `;

                const importProgress = document.getElementById('importProgress');
                importProgress.classList.remove('hidden');

                // Update progress indicator
                const progressBar = document.getElementById('progressBar');
                const progressPercentage = document.getElementById('progressPercentage');
                const progressText = document.getElementById('progressText');

                progressBar.style.width = '10%';
                progressPercentage.textContent = '10%';
                progressText.textContent = 'Menyiapkan data import...';

                // Collect selected categories
                const selectedCategories = Array.from(document.querySelectorAll(
                        'input[name="provider_categories[]"]:checked'))
                    .map(cb => cb.value);

                formData.append('selected_categories', JSON.stringify(selectedCategories));

                try {
                    // Submit form
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Show success modal with category info
                        document.getElementById('bulkImportModal').classList.add('hidden');
                        document.getElementById('successTitle').textContent = 'Import Berhasil!';

                        let successMessage = result.message;
                        if (result.auto_created_categories && result.auto_created_categories.length > 0) {
                            successMessage += `<br><br><strong>Kategori yang dibuat:</strong><br>`;
                            successMessage += result.auto_created_categories.map(cat => `• ${cat}`).join('<br>');
                        }

                        document.getElementById('successMessage').innerHTML = successMessage;

                        if (result.failed_count > 0) {
                            const failedProducts = document.getElementById('failedProducts');
                            failedProducts.classList.remove('hidden');
                            failedProducts.textContent = `${result.failed_count} produk gagal diimport.`;
                        }

                        document.getElementById('bulkImportSuccessModal').classList.remove('hidden');

                        // Reset form
                        this.reset();
                        document.getElementById('providerCategoriesContainer').innerHTML = '';
                        document.getElementById('providerCategoriesSection').classList.add('hidden');
                        document.getElementById('manualCategorySection').classList.add('hidden');
                        document.getElementById('autoCategoryInfo').classList.remove('hidden');
                    } else {
                        alert(result.message || 'Gagal mengimport produk');
                    }
                } catch (error) {
                    console.error('Import error:', error);
                    alert('Terjadi kesalahan saat mengimport produk');
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Mulai Import';
                    importProgress.classList.add('hidden');
                }
            });

            // Simulate progress updates
            function simulateProgress() {
                const progressBar = document.getElementById('progressBar');
                const progressPercentage = document.getElementById('progressPercentage');
                const progressText = document.getElementById('progressText');

                let progress = 10;
                const interval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 95) progress = 95;

                    progressBar.style.width = progress + '%';
                    progressPercentage.textContent = Math.round(progress) + '%';

                    if (progress < 30) {
                        progressText.textContent = 'Mengambil data dari Digiflazz...';
                    } else if (progress < 60) {
                        progressText.textContent = 'Memproses produk...';
                    } else if (progress < 85) {
                        progressText.textContent = 'Menyimpan ke database...';
                    } else {
                        progressText.textContent = 'Menyelesaikan import...';
                    }
                }, 500);

                return interval;
            }
        </script>
    @endpush
@endsection

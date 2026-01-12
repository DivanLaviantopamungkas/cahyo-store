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
            <a href="{{ route('admin.products.create.manual') }}"
                class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-colors">
                <svg class="w-4 h-4 mr-2">
                    <use href="#icon-edit"></use>
                </svg>
                Buat Manual
            </a>
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
                                                    value="{{ old('nominals.0.stock', 0) }}">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Stok Tersedia *
                                                </label>
                                                <input type="number" name="nominals[0][available_stock]" required
                                                    min="0"
                                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                                    value="{{ old('nominals.0.available_stock', 0) }}">
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

    @push('scripts')
        <script>
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

            // Show product preview
            function showProductPreview(product) {
                const preview = document.getElementById('productPreview');
                const previewImage = document.getElementById('previewImage');

                document.getElementById('previewName').textContent = product.name;
                document.getElementById('previewSku').textContent = product.sku;
                document.getElementById('previewCategory').textContent = product.category || '-';
                document.getElementById('previewBrand').textContent = product.brand || '-';
                document.getElementById('previewPrice').textContent = `Rp ${product.price}`;

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

                // Auto-fill nominal data jika hanya ada 1 nominal
                if (document.getElementById('type').value === 'single') {
                    const costPriceInput = document.querySelector('input[name="nominals[0][cost_price]"]');
                    if (costPriceInput && !costPriceInput.value) {
                        costPriceInput.value = product.price;
                    }
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

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                const oldProviderId = "{{ old('provider_id') }}";
                if (oldProviderId) {
                    document.getElementById('provider_id').value = oldProviderId;
                    loadProviderProducts();
                }
            });

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
                                        value="0">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Stok Tersedia *
                                    </label>
                                    <input type="number" name="nominals[${nominalIndex}][available_stock]" required min="0"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        value="0">
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
                attachEventListeners(template);
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

            function attachEventListeners(container) {
                // Auto calculate price from cost price and margin
                const costPriceInput = container.querySelector('[name$="[cost_price]"]');
                const marginInput = container.querySelector('[name$="[margin]"]');
                const priceInput = container.querySelector('[name$="[price]"]');
                const discountInput = container.querySelector('[name$="[discount_price]"]');
                const stockInput = container.querySelector('[name$="[stock]"]');
                const availableStockInput = container.querySelector('[name$="[available_stock]"]');

                function calculatePrice() {
                    if (costPriceInput.value && marginInput.value) {
                        const cost = parseFloat(costPriceInput.value);
                        const margin = parseFloat(marginInput.value) / 100;
                        const price = cost / (1 - margin);
                        priceInput.value = Math.ceil(price / 100) * 100; // Round up to nearest 100
                    }
                }

                if (costPriceInput && marginInput && priceInput) {
                    costPriceInput.addEventListener('change', calculatePrice);
                    marginInput.addEventListener('change', calculatePrice);
                }

                if (discountInput && priceInput) {
                    discountInput.addEventListener('change', function() {
                        const price = parseFloat(priceInput.value);
                        const discount = parseFloat(this.value);

                        if (discount && price && discount >= price) {
                            alert('Harga diskon harus lebih kecil dari harga normal');
                            this.value = '';
                            this.focus();
                        }
                    });
                }

                if (availableStockInput && stockInput) {
                    availableStockInput.addEventListener('change', function() {
                        const stock = parseFloat(stockInput.value);
                        const available = parseFloat(this.value);

                        if (available > stock) {
                            alert('Stok tersedia tidak boleh lebih besar dari total stok');
                            this.value = stock;
                        }
                    });
                }
            }

            // Initialize event listeners for existing nominals
            document.addEventListener('DOMContentLoaded', function() {
                const initialItems = document.querySelectorAll('.nominal-item');
                initialItems.forEach(item => {
                    attachEventListeners(item);
                });
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

            // Initialize based on current type
            document.addEventListener('DOMContentLoaded', function() {
                const typeSelect = document.getElementById('type');
                if (typeSelect.value === 'single') {
                    const addButton = document.querySelector('#nominalsSection button');
                    if (addButton) {
                        addButton.style.display = 'none';
                    }
                }
            });
        </script>
    @endpush
@endsection

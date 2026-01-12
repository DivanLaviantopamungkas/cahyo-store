@extends('admin.layouts.app')

@section('title', 'Tambah Produk Manual')
@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}">Produk</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <a href="{{ route('admin.products.create.manual') }}">Tambah Produk</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Manual</span>
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
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Produk Manual</h2>
            <a href="{{ route('admin.products.create.digiflazz') }}"
                class="inline-flex items-center px-4 py-2 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors">
                <svg class="w-4 h-4 mr-2">
                    <use href="#icon-refresh"></use>
                </svg>
                Import dari Digiflazz
            </a>
        </div>

        <x-admin.card>
            <form action="{{ route('admin.products.store.manual') }}" method="POST" enctype="multipart/form-data"
                id="productForm">
                @csrf
                <input type="hidden" name="source" value="manual">

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
                                class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('category_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
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

                    <!-- Informasi Produk -->
                    <div>
                        <h3
                            class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                            Informasi Produk</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Nama Produk *
                                        @error('name')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> -
                                                {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        required
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('name') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="Contoh: Voucher Google Play">
                                </div>

                                <div>
                                    <label for="slug"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Slug
                                        @error('slug')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> -
                                                {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('slug') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="voucher-google-play">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Slug akan digenerate otomatis
                                        jika kosong</p>
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Deskripsi
                                        @error('description')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> -
                                                {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <textarea id="description" name="description" rows="3"
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('description') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="type"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Tipe Produk *
                                        @error('type')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> -
                                                {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select id="type" name="type" required
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('type') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                        <option value="">Pilih Tipe Produk</option>
                                        <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single (1
                                            nominal)</option>
                                        <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>Multiple
                                            (banyak nominal)</option>
                                    </select>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        Single: Produk dengan 1 nominal saja<br>
                                        Multiple: Produk dengan banyak pilihan nominal
                                    </p>
                                </div>

                                <div>
                                    <label for="image"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Gambar Produk
                                        @error('image')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> -
                                                {{ $message }}</span>
                                        @enderror
                                    </label>

                                    <div id="imagePreviewContainer" class="hidden mb-4">
                                        <img id="imagePreview" src="" alt="Preview"
                                            class="w-full aspect-video object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                                        <button type="button" onclick="removeImage()"
                                            class="mt-2 text-sm text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300">
                                            Hapus gambar
                                        </button>
                                    </div>

                                    <div id="uploadArea" class="mt-1">
                                        <div class="relative">
                                            <input type="file" id="image" name="image"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                accept="image/*" onchange="previewImage(event)">

                                            <div
                                                class="flex justify-center px-6 pt-5 pb-6 border-2 {{ $errors->has('image') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} border-dashed rounded-2xl hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors">
                                                <div class="space-y-1 text-center pointer-events-none">
                                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor"
                                                        fill="none" viewBox="0 0 48 48">
                                                        <path
                                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    <div
                                                        class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                                        <span class="font-medium text-emerald-600 dark:text-emerald-400">
                                                            Upload file
                                                        </span>
                                                        <p class="pl-1">atau drag & drop</p>
                                                    </div>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG, GIF up
                                                        to 2MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Nominal -->
                    <div id="nominalsSection">
                        <div
                            class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Daftar Nominal</h3>
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
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                placeholder="Contoh: 100 Diamond" value="{{ old('nominals.0.name') }}">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                SKU Provider
                                            </label>
                                            <input type="text" name="nominals[0][provider_sku]"
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                placeholder="Kode SKU dari provider"
                                                value="{{ old('nominals.0.provider_sku') }}">
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Harga Normal *
                                                </label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][price]" required
                                                        min="0"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        placeholder="100000" value="{{ old('nominals.0.price') }}">
                                                </div>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Harga Diskon
                                                </label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][discount_price]"
                                                        min="0"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        placeholder="90000"
                                                        value="{{ old('nominals.0.discount_price') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Harga Modal
                                                </label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][cost_price]" min="0"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        placeholder="80000" value="{{ old('nominals.0.cost_price') }}">
                                                </div>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Margin (%)
                                                </label>
                                                <div class="relative">
                                                    <input type="number" name="nominals[0][margin]" min="0"
                                                        max="100" step="0.01"
                                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        placeholder="20" value="{{ old('nominals.0.margin') }}">
                                                    <div
                                                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">%</span>
                                                    </div>
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
                                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    value="{{ old('nominals.0.stock', 0) }}">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Stok Tersedia *
                                                </label>
                                                <input type="number" name="nominals[0][available_stock]" required
                                                    min="0"
                                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    value="{{ old('nominals.0.available_stock', 0) }}">
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                Mode Stok
                                            </label>
                                            <select name="nominals[0][stock_mode]"
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                                <option value="">Pilih Mode Stok</option>
                                                <option value="manual"
                                                    {{ old('nominals.0.stock_mode') == 'manual' ? 'selected' : '' }}>Manual
                                                </option>
                                                <option value="provider"
                                                    {{ old('nominals.0.stock_mode') == 'provider' ? 'selected' : '' }}>
                                                    Provider</option>
                                            </select>
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
                                                    class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
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
                                        class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
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
                                        class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
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
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('order') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
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
                                class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                Simpan Produk
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>

    @push('scripts')
        <script>
            // Auto-generate slug
            document.getElementById('name').addEventListener('input', function(e) {
                const slugInput = document.getElementById('slug');
                if (slugInput && !slugInput.value) {
                    slugInput.value = e.target.value
                        .toLowerCase()
                        .replace(/[^\w\s]/gi, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                }
            });

            // Image preview
            function previewImage(event) {
                const input = event.target;
                const previewContainer = document.getElementById('imagePreviewContainer');
                const previewImage = document.getElementById('imagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeImage() {
                const input = document.getElementById('image');
                const previewContainer = document.getElementById('imagePreviewContainer');

                input.value = '';
                previewContainer.classList.add('hidden');
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
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Contoh: 100 Diamond">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    SKU Provider
                                </label>
                                <input type="text" name="nominals[${nominalIndex}][provider_sku]"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Kode SKU dari provider">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Normal *
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" name="nominals[${nominalIndex}][price]" required min="0"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="100000">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Diskon
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" name="nominals[${nominalIndex}][discount_price]" min="0"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="90000">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Modal
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">Rp</span>
                                        </div>
                                        <input type="number" name="nominals[${nominalIndex}][cost_price]" min="0"
                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="80000">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Margin (%)
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="nominals[${nominalIndex}][margin]" min="0" max="100" step="0.01"
                                            class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="20">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Total Stok *
                                    </label>
                                    <input type="number" name="nominals[${nominalIndex}][stock]" required min="0"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        value="0">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Stok Tersedia *
                                    </label>
                                    <input type="number" name="nominals[${nominalIndex}][available_stock]" required min="0"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        value="0">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Mode Stok
                                </label>
                                <select name="nominals[${nominalIndex}][stock_mode]"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    <option value="">Pilih Mode Stok</option>
                                    <option value="manual">Manual</option>
                                    <option value="provider">Provider</option>
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
                                    <label class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                    <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(template);
                nominalIndex++;

                // Attach event listeners for new inputs
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

                // Reindex remaining items
                reindexNominals();
            }

            function reindexNominals() {
                const container = document.getElementById('nominalsContainer');
                const items = container.querySelectorAll('.nominal-item');

                items.forEach((item, index) => {
                    item.setAttribute('data-index', index);

                    // Update heading
                    const heading = item.querySelector('h4');
                    heading.textContent = `Nominal #${index + 1}`;

                    // Update input names
                    item.querySelectorAll('[name]').forEach(input => {
                        const oldName = input.getAttribute('name');
                        const newName = oldName.replace(/nominals\[\d+\]/, `nominals[${index}]`);
                        input.setAttribute('name', newName);
                    });
                });

                nominalIndex = items.length;
            }

            function attachEventListeners(container) {
                // Auto calculate margin
                const priceInput = container.querySelector('[name$="[price]"]');
                const costPriceInput = container.querySelector('[name$="[cost_price]"]');
                const marginInput = container.querySelector('[name$="[margin]"]');
                const discountInput = container.querySelector('[name$="[discount_price]"]');
                const stockInput = container.querySelector('[name$="[stock]"]');
                const availableStockInput = container.querySelector('[name$="[available_stock]"]');

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

                if (priceInput && costPriceInput && marginInput) {
                    priceInput.addEventListener('change', calculateMargin);
                    costPriceInput.addEventListener('change', calculateMargin);
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
                    // Hide all but first nominal
                    const items = document.querySelectorAll('.nominal-item');
                    items.forEach((item, index) => {
                        if (index > 0) {
                            item.style.display = 'none';
                        }
                    });
                } else {
                    addButton.style.display = 'inline-flex';
                    // Show all nominals
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

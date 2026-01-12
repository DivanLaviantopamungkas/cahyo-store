@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}">Produk</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Tambah Baru</span>
@endsection

@section('actions')
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
        <svg class="w-4 h-4 mr-2"><use href="#icon-arrow-left"></use></svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <x-admin.card>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="space-y-8">
                <!-- Source Selection -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">Sumber Produk</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Manual -->
                        <div class="relative">
                            <input
                                type="radio"
                                id="source_manual"
                                name="source"
                                value="manual"
                                {{ old('source', 'manual') == 'manual' ? 'checked' : '' }}
                                class="sr-only peer"
                                onchange="toggleSource()"
                            >
                            <label for="source_manual" class="flex flex-col p-6 border-2 border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-emerald-500 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 transition-all">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400"><use href="#icon-edit"></use></svg>
                                        </div>
                                        <span class="font-semibold text-slate-800 dark:text-white">Manual</span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden peer-checked:block"><use href="#icon-check"></use></svg>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Buat produk manual dengan data sendiri</p>
                            </label>
                        </div>

                        <!-- Digiflazz -->
                        <div class="relative">
                            <input
                                type="radio"
                                id="source_digiflazz"
                                name="source"
                                value="digiflazz"
                                {{ old('source') == 'digiflazz' ? 'checked' : '' }}
                                class="sr-only peer"
                                onchange="toggleSource()"
                            >
                            <label for="source_digiflazz" class="flex flex-col p-6 border-2 border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400"><use href="#icon-refresh"></use></svg>
                                        </div>
                                        <span class="font-semibold text-slate-800 dark:text-white">Digiflazz</span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white hidden peer-checked:block"><use href="#icon-check"></use></svg>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Import produk dari provider Digiflazz</p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Kategori (untuk semua sumber) -->
                <div class="p-6 border border-slate-200 dark:border-slate-700 rounded-2xl">
                    <h4 class="text-sm font-semibold text-slate-800 dark:text-white mb-4">Kategori Produk *</h4>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Pilih Kategori
                            @error('category_id')
                                <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                            @enderror
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('category_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                        >
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kategori wajib dipilih untuk semua jenis produk</p>
                    </div>
                </div>

                <!-- Manual Product Form -->
                <div id="manualForm" class="space-y-8 {{ old('source', 'manual') == 'manual' ? '' : 'hidden' }}">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">Informasi Produk Manual</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Nama Produk *
                                        @error('name')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('name') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="Contoh: Voucher Google Play"
                                    >
                                </div>

                                <div>
                                    <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Slug
                                        @error('slug')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input
                                        type="text"
                                        id="slug"
                                        name="slug"
                                        value="{{ old('slug') }}"
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('slug') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="voucher-google-play"
                                    >
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Slug akan digenerate otomatis jika kosong</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Tipe Produk *
                                        @error('type')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select
                                        id="type"
                                        name="type"
                                        required
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('type') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    >
                                        <option value="">Pilih Tipe Produk</option>
                                        <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single (1 nominal)</option>
                                        <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>Multiple (banyak nominal)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="image" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Gambar Produk
                                        @error('image')
                                            <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                        @enderror
                                    </label>

                                    <div id="imagePreviewContainer" class="hidden mb-4">
                                        <img id="imagePreview" src="" alt="Preview" class="w-full aspect-video object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                                        <button type="button" onclick="removeImage()" class="mt-2 text-sm text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300">
                                            Hapus gambar
                                        </button>
                                    </div>

                                    <div id="uploadArea" class="mt-1">
                                        <div class="relative">
                                            <input
                                                type="file"
                                                id="image"
                                                name="image"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                accept="image/*"
                                                onchange="previewImage(event)"
                                            >

                                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 {{ $errors->has('image') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} border-dashed rounded-2xl hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors">
                                                <div class="space-y-1 text-center pointer-events-none">
                                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                                        <span class="font-medium text-emerald-600 dark:text-emerald-400">
                                                            Upload file
                                                        </span>
                                                        <p class="pl-1">atau drag & drop</p>
                                                    </div>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG, GIF up to 2MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Deskripsi
                                @error('description')
                                    <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                @enderror
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('description') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="Deskripsi produk..."
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Digiflazz Product Form -->
                <div id="digiflazzForm" class="space-y-8 {{ old('source') == 'digiflazz' ? '' : 'hidden' }}">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">Pilih Produk dari Digiflazz</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="provider_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Provider Digiflazz *
                                    @error('provider_id')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <select
                                    id="provider_id"
                                    name="provider_id"
                                    onchange="loadProviderProducts()"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('provider_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                                    <option value="">Pilih Provider</option>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                            {{ $provider->name }} ({{ $provider->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="provider_sku" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Pilih Produk *
                                    @error('provider_sku')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <select
                                    id="provider_sku"
                                    name="provider_sku"
                                    required
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('provider_sku') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    disabled
                                >
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
                        <div id="productPreview" class="mt-6 p-6 border border-slate-200 dark:border-slate-700 rounded-2xl hidden">
                            <h4 class="font-semibold text-slate-800 dark:text-white mb-4">Preview Produk</h4>
                            <div class="flex items-start space-x-4">
                                <div id="previewImage" class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-400"><use href="#icon-package"></use></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 id="previewName" class="font-semibold text-slate-800 dark:text-white mb-2"></h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">SKU:</span>
                                            <p id="previewSku" class="text-sm text-slate-700 dark:text-slate-300 font-mono"></p>
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
                                            <p id="previewPrice" class="text-sm font-medium text-slate-800 dark:text-white"></p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <span class="text-sm text-slate-500 dark:text-slate-400">Deskripsi:</span>
                                        <p id="previewDescription" class="text-sm text-slate-700 dark:text-slate-300 mt-1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Name Override (optional) -->
                        <div class="mt-6">
                            <label for="digiflazz_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nama Produk (Opsional)
                                <span class="text-xs text-slate-500 dark:text-slate-400">- Kosongkan untuk menggunakan nama dari Digiflazz</span>
                            </label>
                            <input
                                type="text"
                                id="digiflazz_name"
                                name="name"
                                value="{{ old('name') }}"
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Biarkan kosong untuk menggunakan nama dari Digiflazz"
                            >
                        </div>

                        <div class="mt-6">
                            <label for="digiflazz_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Deskripsi (Opsional)
                                <span class="text-xs text-slate-500 dark:text-slate-400">- Kosongkan untuk menggunakan deskripsi dari Digiflazz</span>
                            </label>
                            <textarea
                                id="digiflazz_description"
                                name="description"
                                rows="3"
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Biarkan kosong untuk menggunakan deskripsi dari Digiflazz"
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">Pengaturan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Status Aktif -->
                        <div class="flex items-center justify-between p-4 rounded-2xl border {{ $errors->has('is_active') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}">
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Status Aktif
                                    @error('is_active')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs block"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Produk dapat dilihat</p>
                            </div>
                            <div class="relative inline-block w-12 h-6">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <label for="is_active" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                            </div>
                        </div>

                        <!-- Featured -->
                        <div class="flex items-center justify-between p-4 rounded-2xl border {{ $errors->has('is_featured') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}">
                            <div>
                                <label for="is_featured" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Featured
                                    @error('is_featured')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs block"> - {{ $message }}</span>
                                    @enderror
                                </label>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Tampilkan di utama</p>
                            </div>
                            <div class="relative inline-block w-12 h-6">
                                <input
                                    type="checkbox"
                                    id="is_featured"
                                    name="is_featured"
                                    value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <label for="is_featured" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                            </div>
                        </div>

                        <!-- Urutan -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Urutan
                                @error('order')
                                    <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                @enderror
                            </label>
                            <input
                                type="number"
                                id="order"
                                name="order"
                                value="{{ old('order', 0) }}"
                                min="0"
                                class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('order') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                            >
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn" class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
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
// Toggle source forms
function toggleSource() {
    const manualForm = document.getElementById('manualForm');
    const digiflazzForm = document.getElementById('digiflazzForm');
    const submitBtn = document.getElementById('submitBtn');

    const isManual = document.getElementById('source_manual').checked;

    if (isManual) {
        manualForm.classList.remove('hidden');
        digiflazzForm.classList.add('hidden');
        submitBtn.innerHTML = 'Simpan Produk';
        submitBtn.className = submitBtn.className.replace('bg-blue', 'bg-emerald').replace('hover:bg-blue', 'hover:bg-emerald');

        // Enable manual fields
        document.getElementById('name').required = true;
        document.getElementById('type').required = true;

        // Disable digiflazz fields
        document.getElementById('provider_id').required = false;
        document.getElementById('provider_sku').required = false;
    } else {
        manualForm.classList.add('hidden');
        digiflazzForm.classList.remove('hidden');
        submitBtn.innerHTML = 'Import dari Digiflazz';
        submitBtn.className = submitBtn.className.replace('bg-emerald', 'bg-blue').replace('hover:bg-emerald', 'hover:bg-blue');

        // Enable digiflazz fields
        document.getElementById('provider_id').required = true;
        document.getElementById('provider_sku').required = true;

        // Disable manual fields
        document.getElementById('name').required = false;
        document.getElementById('type').required = false;
    }
}

// Load provider products
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

    // Clear current options
    productSelect.innerHTML = '<option value="">Memuat produk...</option>';

    fetch(`/admin/providers/${providerId}/products`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            productSelect.innerHTML = '<option value="">Pilih Produk</option>';
            data.forEach(product => {
                productSelect.innerHTML += `<option value="${product.sku}" data-product='${JSON.stringify(product)}'>${product.name} - Rp ${product.price}</option>`;
            });
            productSelect.disabled = false;
            loading.classList.add('hidden');

            // Trigger change event if old value exists
            const oldSku = "{{ old('provider_sku') }}";
            if (oldSku) {
                productSelect.value = oldSku;
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                if (selectedOption.value) {
                    const product = JSON.parse(selectedOption.dataset.product);
                    showProductPreview(product);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            productSelect.innerHTML = '<option value="">Gagal memuat produk</option>';
            loading.classList.add('hidden');
        });
}

// Show product preview
function showProductPreview(product) {
    const preview = document.getElementById('productPreview');
    const previewImage = document.getElementById('previewImage');

    // Set product details
    document.getElementById('previewName').textContent = product.name;
    document.getElementById('previewSku').textContent = product.sku;
    document.getElementById('previewCategory').textContent = product.category || '-';
    document.getElementById('previewBrand').textContent = product.brand || '-';
    document.getElementById('previewPrice').textContent = `Rp ${product.price}`;

    // Set description
    const description = product.details?.description || '-';
    document.getElementById('previewDescription').textContent = description;

    // Set image if available
    if (product.details?.icon_url) {
        previewImage.innerHTML = `<img src="${product.details.icon_url}" alt="${product.name}" class="w-full h-full object-cover rounded-xl">`;
    } else {
        previewImage.innerHTML = `<svg class="w-10 h-10 text-slate-400"><use href="#icon-package"></use></svg>`;
    }

    // Fill form fields if empty
    const nameField = document.getElementById('digiflazz_name');
    const descField = document.getElementById('digiflazz_description');

    if (!nameField.value) {
        nameField.value = product.name;
    }

    if (!descField.value && description && description !== '-') {
        descField.value = description;
    }

    preview.classList.remove('hidden');
}

// Auto-generate slug for manual products
document.getElementById('name')?.addEventListener('input', function(e) {
    const slugInput = document.getElementById('slug');
    if (slugInput && !slugInput.value) {
        slugInput.value = e.target.value
            .toLowerCase()
            .replace(/[^\w\s]/gi, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
});

// Image preview for manual products
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

// Product select change event
document.getElementById('provider_sku')?.addEventListener('change', function() {
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
    toggleSource();

    // If digiflazz was selected previously, load products
    if (document.getElementById('source_digiflazz').checked) {
        const providerId = document.getElementById('provider_id').value;
        if (providerId) {
            loadProviderProducts();
        }
    }
});
</script>
@endpush
@endsection

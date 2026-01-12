@extends('admin.layouts.app')

@section('title', 'Edit Produk')
@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}">Produk</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Edit</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-2xl border border-rose-300 dark:border-rose-600 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-medium transition-colors">
                <svg class="w-4 h-4 mr-2"><use href="#icon-trash"></use></svg>
                Hapus
            </button>
        </form>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-arrow-left"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <x-admin.card>
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">Informasi Dasar</h3>

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
                                    value="{{ old('name', $product->name) }}"
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
                                    value="{{ old('slug', $product->slug) }}"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('slug') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="voucher-google-play"
                                >
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Slug akan digenerate otomatis jika kosong</p>
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Kategori *
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                    onchange="handleTypeChange()"
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('type') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                >
                                    <option value="single" {{ old('type', $product->type) == 'single' ? 'selected' : '' }}>Single (1 nominal)</option>
                                    <option value="multiple" {{ old('type', $product->type) == 'multiple' ? 'selected' : '' }}>Multiple (banyak nominal)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Gambar Produk
                                    @error('image')
                                        <span class="text-rose-600 dark:text-rose-400 text-xs"> - {{ $message }}</span>
                                    @enderror
                                </label>

                                <!-- Current Image Preview -->
                                @if($product->image)
                                <div id="current_image_preview" class="mb-4 relative group">
                                    <img src="{{ Storage::url($product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="h-40 w-full object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-xl opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button"
                                                onclick="removeCurrentImage()"
                                                class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-sm rounded-lg">
                                            Hapus Gambar
                                        </button>
                                    </div>
                                </div>
                                @endif

                                <!-- Hidden input untuk remove image -->
                                <input type="hidden" name="remove_image" id="remove_image" value="0">

                                <!-- Image Preview Container -->
                                <div id="imagePreviewContainer" class="hidden mb-4">
                                    <img id="imagePreview" src="" alt="Preview" class="w-full aspect-video object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                                    <button type="button" onclick="removeNewImage()" class="mt-2 text-sm text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300">
                                        Hapus gambar baru
                                    </button>
                                </div>

                                <!-- Upload Area -->
                                <div id="uploadArea" class="mt-1 {{ $product->image ? 'hidden' : '' }}">
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
                                                        Upload file baru
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
                        >{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- Daftar Nominal -->
                <div id="nominalsSection">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Daftar Nominal</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola nominal untuk produk ini</p>
                        </div>
                        <button type="button" onclick="addNominal()"
                            class="inline-flex items-center px-4 py-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2">
                                <use href="#icon-plus"></use>
                            </svg>
                            Tambah Nominal
                        </button>
                    </div>

                    <!-- Container untuk dynamic nominals -->
                    <div id="nominalsContainer" class="space-y-6">
                        @php
                            $nominals = old('nominals', $product->nominals->toArray());
                            $nominalIndex = 0;
                        @endphp

                        @if(count($nominals) > 0)
                            @foreach($nominals as $index => $nominal)
                                <div class="nominal-item p-6 border border-slate-200 dark:border-slate-700 rounded-2xl" data-index="{{ $index }}">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-sm font-semibold text-slate-800 dark:text-white">Nominal #{{ $index + 1 }}</h4>
                                        @if($nominalIndex > 0 || $product->type === 'multiple')
                                            <button type="button" onclick="removeNominal(this)" class="text-rose-600 hover:text-rose-800">
                                                <svg class="w-5 h-5">
                                                    <use href="#icon-trash"></use>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Hidden input untuk ID jika edit -->
                                    @if(isset($nominal['id']))
                                        <input type="hidden" name="nominals[{{ $index }}][id]" value="{{ $nominal['id'] }}">
                                    @endif

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Nama Nominal *
                                                </label>
                                                <input type="text" name="nominals[{{ $index }}][name]" required
                                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.name') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    placeholder="Contoh: 100 Diamond"
                                                    value="{{ old('nominals.'.$index.'.name', $nominal['name'] ?? '') }}">
                                                @error('nominals.'.$index.'.name')
                                                    <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    SKU Provider (Opsional)
                                                </label>
                                                <input type="text" name="nominals[{{ $index }}][provider_sku]"
                                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.provider_sku') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    placeholder="Kode SKU dari provider"
                                                    value="{{ old('nominals.'.$index.'.provider_sku', $nominal['provider_sku'] ?? '') }}">
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                        Harga Modal
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-slate-500">Rp</span>
                                                        </div>
                                                        <input type="number" name="nominals[{{ $index }}][cost_price]" min="0"
                                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.cost_price') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                            placeholder="80000"
                                                            value="{{ old('nominals.'.$index.'.cost_price', $nominal['cost_price'] ?? '') }}">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                        Margin (%)
                                                    </label>
                                                    <div class="relative">
                                                        <input type="number" name="nominals[{{ $index }}][margin]" min="0" max="100" step="0.01"
                                                            class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.margin') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                            placeholder="20"
                                                            value="{{ old('nominals.'.$index.'.margin', $nominal['margin'] ?? '') }}">
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
                                                        Harga Normal *
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-slate-500">Rp</span>
                                                        </div>
                                                        <input type="number" name="nominals[{{ $index }}][price]" required min="0"
                                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.price') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                            placeholder="100000"
                                                            value="{{ old('nominals.'.$index.'.price', $nominal['price'] ?? '') }}">
                                                    </div>
                                                    @error('nominals.'.$index.'.price')
                                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                        Harga Diskon
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-slate-500">Rp</span>
                                                        </div>
                                                        <input type="number" name="nominals[{{ $index }}][discount_price]" min="0"
                                                            class="block w-full pl-10 pr-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.discount_price') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                            placeholder="90000"
                                                            value="{{ old('nominals.'.$index.'.discount_price', $nominal['discount_price'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                        Total Stok *
                                                    </label>
                                                    <input type="number" name="nominals[{{ $index }}][stock]" required min="0"
                                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.stock') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        value="{{ old('nominals.'.$index.'.stock', $nominal['stock'] ?? 0) }}">
                                                    @error('nominals.'.$index.'.stock')
                                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                        Stok Tersedia *
                                                    </label>
                                                    <input type="number" name="nominals[{{ $index }}][available_stock]" required min="0"
                                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.available_stock') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        value="{{ old('nominals.'.$index.'.available_stock', $nominal['available_stock'] ?? 0) }}">
                                                    @error('nominals.'.$index.'.available_stock')
                                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Mode Stok
                                                </label>
                                                <select name="nominals[{{ $index }}][stock_mode]"
                                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.stock_mode') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                                    <option value="manual" {{ (old('nominals.'.$index.'.stock_mode', $nominal['stock_mode'] ?? 'manual') == 'manual') ? 'selected' : '' }}>Manual</option>
                                                    <option value="provider" {{ (old('nominals.'.$index.'.stock_mode', $nominal['stock_mode'] ?? 'manual') == 'provider') ? 'selected' : '' }}>Provider</option>
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
                                                    <input type="hidden" name="nominals[{{ $index }}][is_active]" value="0">
                                                    <input type="checkbox" name="nominals[{{ $index }}][is_active]" value="1"
                                                        {{ old('nominals.'.$index.'.is_active', $nominal['is_active'] ?? true) ? 'checked' : '' }}
                                                        class="sr-only peer">
                                                    <label class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                                    <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Urutan
                                                </label>
                                                <input type="number" name="nominals[{{ $index }}][order]" min="0"
                                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('nominals.'.$index.'.order') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    value="{{ old('nominals.'.$index.'.order', $nominal['order'] ?? $index) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $nominalIndex++; @endphp
                            @endforeach
                        @else
                            <!-- Default nominal jika tidak ada -->
                            <div class="nominal-item p-6 border border-slate-200 dark:border-slate-700 rounded-2xl" data-index="0">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-sm font-semibold text-slate-800 dark:text-white">Nominal #1</h4>
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
                                            <input type="text" name="nominals[0][name]" required
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                placeholder="Contoh: 100 Diamond" value="{{ old('nominals.0.name') }}">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                SKU Provider (Opsional)
                                            </label>
                                            <input type="text" name="nominals[0][provider_sku]"
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                placeholder="Kode SKU dari provider">
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Harga Modal
                                                </label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][cost_price]" min="0"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        placeholder="80000">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Margin (%)
                                                </label>
                                                <div class="relative">
                                                    <input type="number" name="nominals[0][margin]" min="0" max="100" step="0.01"
                                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        placeholder="20">
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
                                                    Harga Normal *
                                                </label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500">Rp</span>
                                                    </div>
                                                    <input type="number" name="nominals[0][price]" required min="0"
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
                                                    <input type="number" name="nominals[0][discount_price]" min="0"
                                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                        placeholder="90000">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Total Stok *
                                                </label>
                                                <input type="number" name="nominals[0][stock]" required min="0"
                                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    value="0">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Stok Tersedia *
                                                </label>
                                                <input type="number" name="nominals[0][available_stock]" required min="0"
                                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                                    value="0">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                Mode Stok
                                            </label>
                                            <select name="nominals[0][stock_mode]"
                                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                                <option value="manual" selected>Manual</option>
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
                                                <input type="hidden" name="nominals[0][is_active]" value="0">
                                                <input type="checkbox" name="nominals[0][is_active]" value="1" checked class="sr-only peer">
                                                <label class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">Pengaturan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Status Aktif Switch -->
                        <div x-data="{ is_active: {{ old('is_active', $product->is_active) ? 'true' : 'false' }} }"
                             class="flex items-center justify-between p-4 rounded-2xl border {{ $errors->has('is_active') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}">
                            <div>
                                <span class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Status Aktif
                                </span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Produk dapat dilihat</p>
                            </div>

                            <div class="flex items-center">
                                <!-- Hidden input untuk form submission -->
                                <input type="hidden" name="is_active" :value="is_active ? 1 : 0">

                                <!-- Toggle Switch -->
                                <button type="button"
                                        @click="is_active = !is_active"
                                        :class="is_active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'"
                                        class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <span :class="is_active ? 'translate-x-7' : 'translate-x-1'"
                                          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"></span>
                                </button>

                                <!-- Status text -->
                                <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300"
                                      x-text="is_active ? 'Aktif' : 'Nonaktif'">
                                </span>
                            </div>
                        </div>

                        <!-- Featured Switch -->
                        <div x-data="{ is_featured: {{ old('is_featured', $product->is_featured) ? 'true' : 'false' }} }"
                             class="flex items-center justify-between p-4 rounded-2xl border {{ $errors->has('is_featured') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}">
                            <div>
                                <span class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Featured
                                </span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Tampilkan di utama</p>
                            </div>

                            <div class="flex items-center">
                                <!-- Hidden input untuk form submission -->
                                <input type="hidden" name="is_featured" :value="is_featured ? 1 : 0">

                                <!-- Toggle Switch -->
                                <button type="button"
                                        @click="is_featured = !is_featured"
                                        :class="is_featured ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'"
                                        class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <span :class="is_featured ? 'translate-x-7' : 'translate-x-1'"
                                          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"></span>
                                </button>

                                <!-- Status text -->
                                <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300"
                                      x-text="is_featured ? 'Ya' : 'Tidak'">
                                </span>
                            </div>
                        </div>

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
                                value="{{ old('order', $product->order) }}"
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
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                            Update Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </x-admin.card>
</div>

@push('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function(e) {
    const slugInput = document.getElementById('slug');
    if (!slugInput.value) {
        slugInput.value = e.target.value
            .toLowerCase()
            .replace(/[^\w\s]/gi, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
});

// Image preview for new upload
function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadArea.classList.add('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function removeNewImage() {
    const previewContainer = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');
    const input = document.getElementById('image');

    preview.src = '';
    previewContainer.classList.add('hidden');
    uploadArea.classList.remove('hidden');
    input.value = '';
}

function removeCurrentImage() {
    document.getElementById('remove_image').value = '1';
    document.getElementById('current_image_preview').classList.add('hidden');
    document.getElementById('uploadArea').classList.remove('hidden');
}

// Handle drag and drop for image upload
const uploadArea = document.getElementById('uploadArea');
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight() {
    uploadArea.querySelector('.border-dashed').classList.add('border-emerald-500', 'dark:border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900/10');
}

function unhighlight() {
    uploadArea.querySelector('.border-dashed').classList.remove('border-emerald-500', 'dark:border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900/10');
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    const input = document.getElementById('image');

    if (files.length > 0) {
        input.files = files;
        previewImage({ target: input });
    }
}

// Nominal management
let nominalIndex = {{ $nominalIndex }};

function addNominal() {
    const container = document.getElementById('nominalsContainer');
    const template = document.createElement('div');
    template.classList.add('nominal-item', 'p-6', 'border', 'border-slate-200', 'dark:border-slate-700', 'rounded-2xl');
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
                        SKU Provider (Opsional)
                    </label>
                    <input type="text" name="nominals[${nominalIndex}][provider_sku]"
                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                        placeholder="Kode SKU dari provider">
                </div>

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
            </div>

            <div class="space-y-4">
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
                        <option value="manual" selected>Manual</option>
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

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Urutan
                    </label>
                    <input type="number" name="nominals[${nominalIndex}][order]" min="0"
                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                        value="${nominalIndex}">
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
function handleTypeChange() {
    const type = document.getElementById('type').value;
    const nominalsSection = document.getElementById('nominalsSection');
    const addButton = nominalsSection.querySelector('button');
    const container = document.getElementById('nominalsContainer');
    const items = container.querySelectorAll('.nominal-item');

    if (type === 'single') {
        addButton.style.display = 'none';
        // Hide delete buttons for first item
        items.forEach((item, index) => {
            const deleteBtn = item.querySelector('button');
            if (deleteBtn && index === 0) {
                deleteBtn.style.display = 'none';
            }
            if (index > 0) {
                item.style.display = 'none';
            }
        });
    } else {
        addButton.style.display = 'inline-flex';
        // Show all items and delete buttons
        items.forEach((item, index) => {
            item.style.display = 'block';
            const deleteBtn = item.querySelector('button');
            if (deleteBtn) {
                deleteBtn.style.display = 'block';
            }
        });
    }
}

// Initialize based on current type
document.addEventListener('DOMContentLoaded', function() {
    handleTypeChange();
});
</script>
@endpush
@endsection

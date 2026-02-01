@extends('admin.layouts.app')

@section('title', 'Edit Produk')
@section('subtitle', 'Lakukan perubahan informasi produk Anda')

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Edit</span>
@endsection

@section('actions')
    <div class="flex items-center gap-2 lg:gap-3">
        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 lg:px-6 py-2.5 rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-black text-[10px] uppercase tracking-widest border border-rose-100 dark:border-rose-800 transition-all active:scale-95">
                <svg class="w-4 h-4 mr-2"><use href="#icon-trash"></use></svg>
                Hapus
            </button>
        </form>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 lg:px-6 py-2.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 shadow-sm">
            <svg class="w-4 h-4 mr-2 transform rotate-180"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-8 pb-20">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-50 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                    <svg class="w-6 h-6"><use href="#icon-shopping-bag"></use></svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Informasi Dasar</h3>
                            </div>
                        </div>

                        <div class="p-8 lg:p-10 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Produk *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold"
                                        placeholder="Contoh: Voucher Google Play">
                                    @error('name') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="slug" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Slug URL</label>
                                    <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}"
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-500 dark:text-slate-400 focus:ring-2 focus:ring-emerald-500 transition-all font-mono text-sm font-bold"
                                        placeholder="voucher-google-play">
                                </div>

                                <div class="space-y-2">
                                    <label for="category_id" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori *</label>
                                    <select id="category_id" name="category_id" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold appearance-none">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label for="type" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Produk *</label>
                                    <select id="type" name="type" required onchange="handleTypeChange()"
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-800 dark:text-white focus:ring-2 focus:ring-violet-500 transition-all font-bold appearance-none">
                                        <option value="single" {{ old('type', $product->type) == 'single' ? 'selected' : '' }}>Single (1 Nominal)</option>
                                        <option value="multiple" {{ old('type', $product->type) == 'multiple' ? 'selected' : '' }}>Multiple (Banyak Nominal)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Produk</label>
                                <textarea id="description" name="description" rows="4"
                                    class="block w-full px-5 py-4 rounded-3xl bg-slate-100 dark:bg-slate-900 border-none text-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium"
                                    placeholder="Tuliskan deskripsi lengkap produk di sini...">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div id="nominalsSection" class="space-y-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4">
                            <div>
                                <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter flex items-center">
                                    <span class="w-8 h-[2px] bg-emerald-500 mr-3"></span>
                                    Daftar Nominal Produk
                                </h3>
                            </div>
                            <button type="button" onclick="addNominal()"
                                class="inline-flex items-center px-6 py-3 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest shadow-xl active:scale-95 transition-all">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                                Tambah Nominal
                            </button>
                        </div>

                        <div id="nominalsContainer" class="grid grid-cols-1 gap-6">
                            @php
                                $nominals = old('nominals', $product->product_nominals->toArray());
                                $nominalIndex = 0;
                            @endphp

                            @foreach($nominals as $index => $nominal)
                                <div class="nominal-item bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 transition-all hover:border-emerald-200 dark:hover:border-emerald-900" data-index="{{ $index }}">
                                    <div class="flex justify-between items-center mb-8">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xs font-black">
                                                {{ $index + 1 }}
                                            </div>
                                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Konfigurasi Nominal</h4>
                                        </div>
                                        
                                        @if($index > 0 || $product->type === 'multiple')
                                            <button type="button" onclick="removeNominal(this)" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                                <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                                            </button>
                                        @endif
                                    </div>

                                    @if(isset($nominal['id']))
                                        <input type="hidden" name="nominals[{{ $index }}][id]" value="{{ $nominal['id'] }}">
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-5">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Nominal *</label>
                                                <input type="text" name="nominals[{{ $index }}][name]" required
                                                    value="{{ old('nominals.'.$index.'.name', $nominal['name'] ?? '') }}"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-emerald-500 font-bold"
                                                    placeholder="Contoh: 100 Diamond">
                                            </div>

                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Provider SKU</label>
                                                <input type="text" name="nominals[{{ $index }}][provider_sku]"
                                                    value="{{ old('nominals.'.$index.'.provider_sku', $nominal['provider_sku'] ?? '') }}"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-500 font-mono text-sm"
                                                    placeholder="SKU-KODE-123">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Modal (Rp)</label>
                                                    <input type="number" name="nominals[{{ $index }}][cost_price]"
                                                        value="{{ old('nominals.'.$index.'.cost_price', $nominal['cost_price'] ?? '') }}"
                                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Margin (%)</label>
                                                    <input type="number" name="nominals[{{ $index }}][margin]" step="0.01"
                                                        value="{{ old('nominals.'.$index.'.margin', $nominal['margin'] ?? '') }}"
                                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 font-bold">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-5">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga Jual *</label>
                                                    <input type="number" name="nominals[{{ $index }}][price]" required
                                                        value="{{ old('nominals.'.$index.'.price', $nominal['price'] ?? '') }}"
                                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-black">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Diskon (Rp)</label>
                                                    <input type="number" name="nominals[{{ $index }}][discount_price]"
                                                        value="{{ old('nominals.'.$index.'.discount_price', $nominal['discount_price'] ?? '') }}"
                                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-rose-500 font-bold">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mode Stok</label>
                                                    <select name="nominals[{{ $index }}][stock_mode]"
                                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold appearance-none">
                                                        <option value="manual" {{ (old('nominals.'.$index.'.stock_mode', $nominal['stock_mode'] ?? 'manual') == 'manual') ? 'selected' : '' }}>Manual</option>
                                                        <option value="provider" {{ (old('nominals.'.$index.'.stock_mode', $nominal['stock_mode'] ?? 'manual') == 'provider') ? 'selected' : '' }}>Provider</option>
                                                    </select>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Urutan</label>
                                                    <input type="number" name="nominals[{{ $index }}][order]"
                                                        value="{{ old('nominals.'.$index.'.order', $nominal['order'] ?? $index) }}"
                                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold">
                                                </div>
                                            </div>

                                            <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/50 flex items-center justify-between">
                                                <div>
                                                    <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Nominal</p>
                                                    <p class="text-[9px] text-slate-500 font-medium">Izinkan pelanggan membeli</p>
                                                </div>
                                                <div class="relative inline-block w-12 h-6">
                                                    <input type="hidden" name="nominals[{{ $index }}][is_active]" value="0">
                                                    <input type="checkbox" id="nominal_active_{{ $index }}" name="nominals[{{ $index }}][is_active]" value="1" 
                                                        {{ old('nominals.'.$index.'.is_active', $nominal['is_active'] ?? true) ? 'checked' : '' }}
                                                        class="sr-only peer">
                                                    <label for="nominal_active_{{ $index }}" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                                    <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $nominalIndex++; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8">
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 ml-1">Gambar Produk</h3>
                        
                        <div class="space-y-6">
                            @if($product->image)
                                <div id="current_image_preview" class="relative group aspect-square rounded-[2rem] overflow-hidden border-4 border-slate-50 dark:border-slate-900 shadow-xl">
                                    <img src="{{ asset($product->image) }}" class="w-full h-full object-contain p-4 bg-white">
                                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <button type="button" onclick="removeCurrentImage()" class="px-6 py-2.5 bg-rose-500 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg active:scale-95">
                                            Ganti Gambar
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <input type="hidden" name="remove_image" id="remove_image" value="0">

                            <div id="imagePreviewContainer" class="hidden relative aspect-square rounded-[2rem] overflow-hidden border-4 border-emerald-500/20 shadow-xl">
                                <img id="imagePreview" src="" class="w-full h-full object-contain p-4 bg-white">
                                <button type="button" onclick="removeNewImage()" class="absolute top-4 right-4 p-2 bg-rose-500 text-white rounded-full shadow-lg">
                                    <svg class="w-4 h-4"><use href="#icon-x-mark"></use></svg>
                                </button>
                            </div>

                            <div id="uploadArea" class="{{ $product->image ? 'hidden' : '' }}">
                                <label class="flex flex-col items-center justify-center w-full aspect-square rounded-[2rem] border-4 border-dashed border-slate-100 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition-all cursor-pointer group relative">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-emerald-500 transition-all duration-500">
                                            <svg class="w-8 h-8 text-slate-400 group-hover:text-white"><use href="#icon-arrow-up-tray"></use></svg>
                                        </div>
                                        <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">Upload Baru</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">PNG, JPG Up to 2MB</p>
                                    </div>
                                    <input type="file" id="image" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 space-y-6">
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Visibilitas</h3>
                        
                        <div class="flex items-center justify-between p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                    <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">Online / Offline</p>
                                </div>
                            </div>
                            <div x-data="{ is_active: {{ old('is_active', $product->is_active) ? 'true' : 'false' }} }">
                                <input type="hidden" name="is_active" :value="is_active ? 1 : 0">
                                <button type="button" @click="is_active = !is_active" :class="is_active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'"
                                    class="relative inline-flex h-7 w-14 items-center rounded-full transition-all duration-300 shadow-inner">
                                    <span :class="is_active ? 'translate-x-8' : 'translate-x-1'" class="inline-block h-5 w-5 transform rounded-full bg-white transition-all shadow-sm"></span>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                                    <svg class="w-5 h-5"><use href="#icon-star"></use></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Featured</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">Tampil Populer</p>
                                </div>
                            </div>
                            <div x-data="{ is_featured: {{ old('is_featured', $product->is_featured) ? 'true' : 'false' }} }">
                                <input type="hidden" name="is_featured" :value="is_featured ? 1 : 0">
                                <button type="button" @click="is_featured = !is_featured" :class="is_featured ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'"
                                    class="relative inline-flex h-7 w-14 items-center rounded-full transition-all duration-300 shadow-inner">
                                    <span :class="is_featured ? 'translate-x-8' : 'translate-x-1'" class="inline-block h-5 w-5 transform rounded-full bg-white transition-all shadow-sm"></span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Order Tampil</label>
                            <input type="number" name="order" value="{{ old('order', $product->order) }}"
                                class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-black text-lg">
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl p-8 sticky top-6 z-10">
                        <button type="submit" class="w-full py-5 rounded-[1.5rem] bg-emerald-500 text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20 hover:scale-[1.02] active:scale-95 transition-all mb-4">
                            Update Produk
                        </button>
                        <p class="text-[10px] text-center text-slate-400 font-bold uppercase tracking-widest">Simpan perubahan sekarang</p>
                    </div>
                </div>
            </div>
        </form>

        <div class="mt-12 space-y-6">
            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter flex items-center">
                <span class="w-8 h-[2px] bg-violet-500 mr-3"></span>
                Ringkasan Stok Voucher
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($product->product_nominals as $nominal)
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm group hover:shadow-lg transition-all relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-violet-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $nominal->name }}</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white mb-4 tracking-tighter">Rp {{ number_format($nominal->price, 0, ',', '.') }}</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase">Stok Real</span>
                                <span class="text-sm font-black {{ $nominal->real_stock > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $nominal->real_stock }} Tersedia
                                </span>
                            </div>
                            <span class="px-3 py-1 rounded-lg {{ $nominal->is_active ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-100 text-slate-400' }} text-[8px] font-black uppercase tracking-widest">
                                {{ $nominal->is_active ? 'Aktif' : 'Off' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 bg-slate-50 dark:bg-slate-900 rounded-[2rem] text-center border-2 border-dashed border-slate-200">
                        <p class="text-sm font-bold text-slate-400 uppercase">Belum ada nominal tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
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

            // Image preview logic
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

            // Nominal management logic
            let nominalIndex = {{ $nominalIndex }};

            function addNominal() {
                const container = document.getElementById('nominalsContainer');
                const template = document.createElement('div');
                template.classList.add('nominal-item', 'bg-white', 'dark:bg-slate-800', 'rounded-[2.5rem]', 'border', 'border-slate-100', 'dark:border-slate-700', 'shadow-sm', 'p-8');
                template.setAttribute('data-index', nominalIndex);

                template.innerHTML = `
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xs font-black">${nominalIndex + 1}</div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Konfigurasi Nominal</h4>
                        </div>
                        <button type="button" onclick="removeNominal(this)" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                            <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Nominal *</label>
                                <input type="text" name="nominals[${nominalIndex}][name]" required class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Provider SKU</label>
                                <input type="text" name="nominals[${nominalIndex}][provider_sku]" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-500 font-mono text-sm">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Modal (Rp)</label>
                                    <input type="number" name="nominals[${nominalIndex}][cost_price]" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Margin (%)</label>
                                    <input type="number" name="nominals[${nominalIndex}][margin]" step="0.01" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 font-bold">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga Jual *</label>
                                    <input type="number" name="nominals[${nominalIndex}][price]" required class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-black">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Diskon (Rp)</label>
                                    <input type="number" name="nominals[${nominalIndex}][discount_price]" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-rose-500 font-bold">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mode Stok</label>
                                    <select name="nominals[${nominalIndex}][stock_mode]" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 font-bold appearance-none">
                                        <option value="manual">Manual</option>
                                        <option value="provider">Provider</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Urutan</label>
                                    <input type="number" name="nominals[${nominalIndex}][order]" value="${nominalIndex}" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 font-bold">
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/50 flex items-center justify-between">
                                <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</p>
                                <div class="relative inline-block w-12 h-6">
                                    <input type="hidden" name="nominals[${nominalIndex}][is_active]" value="0">
                                    <input type="checkbox" id="nominal_active_${nominalIndex}" name="nominals[${nominalIndex}][is_active]" value="1" checked class="sr-only peer">
                                    <label for="nominal_active_${nominalIndex}" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                    <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(template);
                nominalIndex++;
                attachEventListeners(template);
            }

            function removeNominal(button) {
                const item = button.closest('.nominal-item');
                const container = document.getElementById('nominalsContainer');
                if (container.querySelectorAll('.nominal-item').length <= 1) {
                    alert('Minimal harus ada 1 nominal');
                    return;
                }
                item.remove();
                reindexNominals();
            }

            function reindexNominals() {
                const items = document.querySelectorAll('.nominal-item');
                items.forEach((item, index) => {
                    item.setAttribute('data-index', index);
                    item.querySelector('.w-8.h-8').textContent = index + 1;
                    item.querySelectorAll('[name]').forEach(input => {
                        input.name = input.name.replace(/nominals\[\d+\]/, `nominals[${index}]`);
                        if(input.id.startsWith('nominal_active_')) {
                            const newId = `nominal_active_${index}`;
                            input.id = newId;
                            input.nextElementSibling.setAttribute('for', newId);
                        }
                    });
                });
                nominalIndex = items.length;
            }

            function attachEventListeners(container) {
                const priceInput = container.querySelector('[name$="[price]"]');
                const costPriceInput = container.querySelector('[name$="[cost_price]"]');
                const marginInput = container.querySelector('[name$="[margin]"]');

                function calculateMargin() {
                    if (priceInput.value && costPriceInput.value) {
                        const price = parseFloat(priceInput.value);
                        const cost = parseFloat(costPriceInput.value);
                        if (price > cost && cost > 0) {
                            marginInput.value = (((price - cost) / price) * 100).toFixed(2);
                        }
                    }
                }

                if (priceInput && costPriceInput && marginInput) {
                    priceInput.addEventListener('change', calculateMargin);
                    costPriceInput.addEventListener('change', calculateMargin);
                }
            }

            function handleTypeChange() {
                const type = document.getElementById('type').value;
                const addButton = document.querySelector('[onclick="addNominal()"]');
                const items = document.querySelectorAll('.nominal-item');

                if (type === 'single') {
                    addButton.style.display = 'none';
                    items.forEach((item, index) => {
                        if (index === 0) {
                            item.querySelector('button[onclick="removeNominal(this)"]')?.style.setProperty('display', 'none');
                        } else {
                            item.style.display = 'none';
                        }
                    });
                } else {
                    addButton.style.display = 'inline-flex';
                    items.forEach(item => {
                        item.style.display = 'block';
                        item.querySelector('button[onclick="removeNominal(this)"]')?.style.setProperty('display', 'block');
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.nominal-item').forEach(item => attachEventListeners(item));
                handleTypeChange();
            });
        </script>
    @endpush
@endsection
@extends('admin.layouts.app')

@section('title', 'Import Produk dari Digiflazz')

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <a href="{{ route('admin.products.create.digiflazz') }}" class="hover:text-emerald-500 transition-colors">Tambah</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Import Digiflazz</span>
@endsection

@section('actions')
    <a href="{{ route('admin.products.index') }}"
        class="inline-flex items-center px-6 py-2.5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 shadow-sm">
        <svg class="w-4 h-4 mr-2 transform rotate-180">
            <use href="#icon-chevron-right"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row justify-end items-start md:items-center gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" onclick="showBulkImportModal()"
                    class="inline-flex items-center px-5 py-3 rounded-2xl bg-violet-500 hover:bg-violet-600 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-violet-500/20 transition-all active:scale-95">
                    <svg class="w-4 h-4 mr-2">
                        <use href="#icon-arrow-down-tray"></use>
                    </svg>
                    Import Semua Produk
                </button>

                <a href="{{ route('admin.products.create.manual') }}"
                    class="inline-flex items-center px-5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
                    <svg class="w-4 h-4 mr-2">
                        <use href="#icon-pencil"></use>
                    </svg>
                    Buat Manual
                </a>
            </div>
        </div>

        <form action="{{ route('admin.products.store.digiflazz') }}" method="POST" enctype="multipart/form-data" id="digiflazzForm">
            @csrf
            <input type="hidden" name="source" value="digiflazz">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-50 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                    <svg class="w-6 h-6"><use href="#icon-bolt"></use></svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Sumber Produk</h3>
                            </div>
                        </div>

                        <div class="p-8 lg:p-10 space-y-6">
                            <div class="space-y-2">
                                <label for="category_id" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Kategori Produk *</label>
                                <div class="relative">
                                    <select id="category_id" name="category_id" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-bold appearance-none">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('category_id') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="provider_id" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Provider Digiflazz *</label>
                                    <div class="relative">
                                        <select id="provider_id" name="provider_id" required onchange="loadProviderProducts()"
                                            class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-bold appearance-none">
                                            <option value="">Pilih Provider</option>
                                            @foreach ($providers as $provider)
                                                <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                                    {{ $provider->name }} ({{ $provider->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    @error('provider_id') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="provider_sku" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Pilih Produk *</label>
                                    <div class="relative">
                                        <select id="provider_sku" name="provider_sku" required
                                            class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-bold appearance-none disabled:opacity-50"
                                            disabled>
                                            <option value="">Pilih provider terlebih dahulu</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    
                                    <div id="productLoading" class="hidden mt-2 flex items-center space-x-2 ml-1">
                                        <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-blue-500"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Memuat produk...</span>
                                    </div>
                                    @error('provider_sku') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div id="productPreview" class="hidden mt-4 p-6 rounded-[2rem] bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 transition-all">
                                <div class="flex items-start gap-6">
                                    <div id="previewImage" class="w-20 h-20 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center shrink-0 border border-slate-100 dark:border-slate-700">
                                        <svg class="w-8 h-8 text-slate-300"><use href="#icon-package"></use></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 id="previewName" class="font-black text-slate-800 dark:text-white text-lg mb-1 leading-tight"></h4>
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <span class="px-2 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[10px] font-black text-slate-500 uppercase tracking-widest" id="previewSku"></span>
                                            <span class="px-2 py-1 rounded-md bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest" id="previewBrand"></span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Kategori Asli</p>
                                                <p id="previewCategory" class="text-xs font-bold text-slate-700 dark:text-slate-300"></p>
                                            </div>
                                            <div>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Harga Provider</p>
                                                <p id="previewPrice" class="text-sm font-black text-emerald-600 dark:text-emerald-400"></p>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Deskripsi</p>
                                            <p id="previewDescription" class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="autoFillSection" class="hidden">
                        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8">
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4">Nominal Produk</h3>
                            <div id="autoNominalsContainer" class="space-y-4">
                                </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-violet-500/10 text-violet-500 flex items-center justify-center">
                                <svg class="w-4 h-4"><use href="#icon-pencil"></use></svg>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Kustomisasi (Opsional)</h3>
                        </div>
                        
                        <p class="text-xs text-slate-500 mb-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                            <strong>Note:</strong> Kosongkan field di bawah ini jika ingin menggunakan data asli dari Digiflazz.
                        </p>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nama Produk</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-bold"
                                    placeholder="Biarkan kosong untuk menggunakan nama dari Digiflazz">
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Deskripsi</label>
                                <textarea id="description" name="description" rows="3"
                                    class="block w-full px-5 py-4 rounded-3xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-medium"
                                    placeholder="Biarkan kosong untuk menggunakan deskripsi dari Digiflazz">{{ old('description') }}</textarea>
                            </div>

                            <div class="space-y-2">
                                <label for="type" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Tipe Produk *</label>
                                <div class="relative">
                                    <select id="type" name="type" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-bold appearance-none">
                                        <option value="single" {{ old('type') == 'single' ? 'selected' : 'selected' }}>Single (1 Nominal)</option>
                                        <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>Multiple (Banyak Nominal)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium px-2 leading-relaxed mt-2">
                                    <strong>Single:</strong> Produk dengan 1 nominal saja.<br>
                                    <strong>Multiple:</strong> Produk dengan banyak pilihan nominal.
                                </p>
                                @error('type') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div id="nominalsSection">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 px-2">
                            <div>
                                <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter flex items-center">
                                    <span class="w-8 h-[2px] bg-blue-500 mr-3"></span>
                                    Daftar Nominal
                                </h3>
                                <p class="text-xs text-slate-500 font-medium ml-11 mt-1">Harga akan disesuaikan dengan margin yang diatur</p>
                            </div>
                            <button type="button" onclick="addNominal()"
                                class="inline-flex items-center px-6 py-3 rounded-2xl bg-slate-900 dark:bg-blue-500 text-white font-black text-[10px] uppercase tracking-widest shadow-xl active:scale-95 transition-all">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                                Tambah Nominal
                            </button>
                        </div>

                        <div id="nominalsContainer" class="grid grid-cols-1 gap-6">
                            <div class="nominal-item bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 transition-all hover:border-blue-200 dark:hover:border-blue-900" data-index="0">
                                <div class="flex justify-between items-center mb-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center text-xs font-black">1</div>
                                        <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Nominal Utama</h4>
                                    </div>
                                    <button type="button" onclick="removeNominal(this)" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="space-y-5">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nama Nominal *</label>
                                            <input type="text" name="nominals[0][name]" required
                                                class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold"
                                                placeholder="Contoh: 100 Diamond" value="{{ old('nominals.0.name') }}">
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">SKU Provider (Opsional)</label>
                                            <input type="text" name="nominals[0][provider_sku]"
                                                class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-500 font-mono text-sm"
                                                placeholder="Kode SKU dari Digiflazz" value="{{ old('nominals.0.provider_sku') }}">
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Modal *</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">Rp</span>
                                                    <input type="number" name="nominals[0][cost_price]" required min="0" step="100"
                                                        class="block w-full pl-10 pr-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold"
                                                        placeholder="100000" value="{{ old('nominals.0.cost_price') }}">
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Margin (%) *</label>
                                                <div class="relative">
                                                    <input type="number" name="nominals[0][margin]" required min="0" max="100" step="0.5"
                                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-blue-600 font-bold"
                                                        placeholder="10" value="{{ old('nominals.0.margin', 10) }}">
                                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Jual *</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">Rp</span>
                                                    <input type="number" name="nominals[0][price]" required min="0" step="100"
                                                        class="block w-full pl-10 pr-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-black"
                                                        placeholder="110000" value="{{ old('nominals.0.price') }}">
                                                </div>
                                                <p class="text-[9px] text-slate-400 mt-1">Otomatis hitung dari modal + margin</p>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Diskon (Opsional)</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">Rp</span>
                                                    <input type="number" name="nominals[0][discount_price]" min="0" step="100"
                                                        class="block w-full pl-10 pr-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-rose-500 font-bold"
                                                        placeholder="105000" value="{{ old('nominals.0.discount_price') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Total Stok *</label>
                                                <input type="number" name="nominals[0][stock]" required min="0"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold"
                                                    value="{{ old('nominals.0.stock', 9999) }}">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Stok Tersedia *</label>
                                                <input type="number" name="nominals[0][available_stock]" required min="0"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold"
                                                    value="{{ old('nominals.0.available_stock', 9999) }}">
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Mode Stok</label>
                                            <div class="relative">
                                                <select name="nominals[0][stock_mode]"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white font-bold appearance-none">
                                                    <option value="provider" {{ old('nominals.0.stock_mode') == 'provider' ? 'selected' : 'selected' }}>Provider (Sinkron dengan Digiflazz)</option>
                                                    <option value="manual" {{ old('nominals.0.stock_mode') == 'manual' ? 'selected' : '' }}>Manual</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/50 flex items-center justify-between">
                                            <div>
                                                <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Nominal</p>
                                                <p class="text-[9px] text-slate-500 mt-0.5">Nominal dapat dipesan</p>
                                            </div>
                                            <div class="relative inline-block w-12 h-6">
                                                <input type="hidden" name="nominals[0][is_active]" value="0">
                                                <input type="checkbox" name="nominals[0][is_active]" value="1" {{ old('nominals.0.is_active', 1) ? 'checked' : '' }} class="sr-only peer">
                                                <label class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-blue-500 transition-colors cursor-pointer"></label>
                                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl p-8">
                        <button type="submit" class="w-full py-5 rounded-[1.5rem] bg-blue-500 hover:bg-blue-600 text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-500/20 hover:scale-[1.02] active:scale-95 transition-all mb-4">
                            Import dari Digiflazz
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="block w-full py-4 rounded-[1.5rem] bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-widest text-center hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </a>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8">
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 ml-1">Gambar Cover (Opsional)</h3>
                        
                        <div id="imagePreviewContainer" class="hidden relative aspect-square rounded-[2rem] overflow-hidden border-4 border-blue-500/20 shadow-xl mb-4">
                            <img id="imageUploadPreview" src="" class="w-full h-full object-cover">
                            <button type="button" onclick="removeImage()" class="absolute top-4 right-4 p-2 bg-rose-500 text-white rounded-full shadow-lg hover:scale-110 transition-all">
                                <svg class="w-4 h-4"><use href="#icon-x-mark"></use></svg>
                            </button>
                        </div>

                        <div id="uploadArea">
                            <label class="flex flex-col items-center justify-center w-full aspect-square rounded-[2rem] border-4 border-dashed border-slate-100 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all cursor-pointer group relative overflow-hidden bg-slate-50/50 dark:bg-slate-900/50">
                                <div class="flex flex-col items-center justify-center py-6 text-center px-4 z-10">
                                    <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 shadow-sm">
                                        <svg class="w-8 h-8 text-slate-400 group-hover:text-white"><use href="#icon-arrow-up-tray"></use></svg>
                                    </div>
                                    <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">Upload Gambar</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">PNG, JPG Up to 2MB</p>
                                </div>
                                <input type="file" id="image" name="image" class="hidden" accept="image/*" onchange="previewImageUpload(event)">
                            </label>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 space-y-4">
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Pengaturan</h3>
                        
                        <div class="flex items-center justify-between p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                    <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">Produk dapat dilihat</p>
                                </div>
                            </div>
                            <div class="relative inline-block w-12 h-6">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                                <label for="is_active" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                            </div>
                            @error('is_active') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-between p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                                    <svg class="w-5 h-5"><use href="#icon-star"></use></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Featured</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">Tampil di Utama</p>
                                </div>
                            </div>
                            <div class="relative inline-block w-12 h-6">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="sr-only peer">
                                <label for="is_featured" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                            </div>
                            @error('is_featured') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2 pt-2">
                            <label for="order" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Urutan Tampil</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-black text-lg text-center">
                            @error('order') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="bulkImportModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-200 dark:border-slate-700 transform transition-all scale-100 max-h-[90vh] overflow-y-auto">
            <div class="p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Import Semua Produk</h3>
                <button type="button" onclick="closeBulkImportModal()" class="p-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 text-slate-500"><use href="#icon-x-mark"></use></svg>
                </button>
            </div>

            <div class="p-8">
                <form id="bulkImportForm" action="{{ route('admin.products.products.import-all-digiflazz') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Provider *</label>
                            <div class="relative">
                                <select name="provider_id" id="bulkProviderId" required onchange="loadProviderCategories()"
                                    class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-violet-500 transition-all font-bold appearance-none">
                                    <option value="">Pilih Provider</option>
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }} ({{ $provider->code }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div id="providerCategoriesSection" class="hidden space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Filter Kategori (Opsional)</label>
                                <button type="button" onclick="toggleAllCategories()" class="text-[10px] font-bold text-violet-500 hover:text-violet-600 uppercase tracking-widest">Pilih Semua</button>
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 mb-2">Kosongkan untuk import semua produk</div>
                            
                            <div id="providerCategoriesContainer" class="border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden max-h-48 overflow-y-auto p-2 bg-slate-50 dark:bg-slate-900/50">
                                </div>
                            
                            <div id="categoryLoading" class="hidden text-center py-4">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-violet-500 mx-auto"></div>
                                <p class="text-[10px] text-slate-500 mt-2">Memuat kategori...</p>
                            </div>
                            
                            <div id="noCategoriesMessage" class="hidden">
                                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                                    <p class="text-xs text-amber-700 dark:text-amber-300">Tidak ada kategori tersedia. Semua produk akan diimport.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/50 space-y-3">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" id="categoryOptionAuto" name="category_option" value="auto" checked class="text-violet-600 focus:ring-violet-500 w-4 h-4">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Buat Kategori Otomatis</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" id="categoryOptionManual" name="category_option" value="manual" class="text-violet-600 focus:ring-violet-500 w-4 h-4">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Pilih Kategori Manual</span>
                            </label>
                        </div>
                        
                        <div id="autoCategoryInfo" class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl">
                            <p class="text-[10px] text-blue-700 dark:text-blue-300 leading-relaxed">
                                <span class="font-bold">📁 Buat Kategori Otomatis:</span><br>
                                • Kategori dibuat dari nama kategori Digiflazz<br>
                                • Jika sudah ada, akan menggunakan yang ada
                            </p>
                        </div>

                        <div id="manualCategorySection" class="hidden space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Pilih Kategori Lokal</label>
                            <div class="relative">
                                <select name="category_id" class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white font-bold appearance-none">
                                    <option value="">Pilih Kategori Lokal</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium px-2">Semua produk akan disimpan di kategori ini</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Margin (%) *</label>
                                <div class="relative">
                                    <input type="number" name="margin" required min="0" max="100" step="0.5" value="10"
                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white font-bold">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">%</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Tipe Produk *</label>
                                <div class="relative">
                                    <select name="type" required class="block w-full px-5 py-3.5 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white font-bold appearance-none">
                                        <option value="multiple" selected>Multiple</option>
                                        <option value="single">Single</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium px-2">Multiple: Kelompok brand | Single: Produk terpisah</p>

                        <div id="importProgress" class="hidden space-y-2 pt-4">
                            <div class="flex justify-between text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                <span>Progress</span>
                                <span id="progressPercentage">0%</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
                                <div id="progressBar" class="bg-violet-500 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p id="progressText" class="text-[10px] text-slate-400 text-center">Menyiapkan import...</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" onclick="closeBulkImportModal()" class="px-6 py-3 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
                        <button type="submit" id="submitBulkImport" class="px-6 py-3 rounded-2xl bg-violet-500 text-white font-black text-xs uppercase tracking-widest shadow-lg hover:bg-violet-600 transition-colors">Mulai Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="bulkImportSuccessModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] w-full max-w-md p-8 text-center shadow-2xl border border-slate-200 dark:border-slate-700">
            <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-emerald-500"><use href="#icon-check"></use></svg>
            </div>
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-tight" id="successTitle">Import Berhasil!</h3>
            <div class="text-sm text-slate-600 dark:text-slate-400 mb-6 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl" id="successMessage"></div>
            <p id="failedProducts" class="text-xs text-rose-500 font-medium hidden mb-4"></p>
            <button type="button" onclick="closeSuccessModal()" class="px-8 py-3 rounded-2xl bg-emerald-500 text-white font-black text-xs uppercase tracking-widest shadow-lg hover:bg-emerald-600 transition-colors w-full">Tutup</button>
        </div>
    </div>

    @push('scripts')
        <script>
            // Helper function untuk menampilkan preview image baru
            function previewImageUpload(event) {
                const input = event.target;
                const previewContainer = document.getElementById('imagePreviewContainer');
                const preview = document.getElementById('imageUploadPreview');
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

            function removeImage() {
                const input = document.getElementById('image');
                const previewContainer = document.getElementById('imagePreviewContainer');
                const uploadArea = document.getElementById('uploadArea');

                input.value = '';
                previewContainer.classList.add('hidden');
                uploadArea.classList.remove('hidden');
            }

            // --- Kode Javascript Asli ---
            
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
                        if (!response.ok) throw new Error('Network response was not ok');
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

                                productSelect.innerHTML += `<option value="${productData.sku}" data-product='${JSON.stringify(productData)}'>${productData.name} - Rp ${parseInt(productData.price).toLocaleString('id-ID')}</option>`;
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

            function loadProviderCategories() {
                const providerId = document.getElementById('bulkProviderId').value;
                const categoriesContainer = document.getElementById('providerCategoriesContainer');
                const categoriesSection = document.getElementById('providerCategoriesSection');
                const loading = document.getElementById('categoryLoading');
                const noCategoriesMsg = document.getElementById('noCategoriesMessage');

                if (!providerId) {
                    categoriesSection.classList.add('hidden');
                    return;
                }

                loading.classList.remove('hidden');
                categoriesSection.classList.remove('hidden');
                noCategoriesMsg.classList.add('hidden');
                categoriesContainer.innerHTML = '';

                const url = `{{ route('admin.products.products.provider-categories', ':id') }}`.replace(':id', providerId);

                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                })
                .then(response => response.json())
                .then(categories => {
                    loading.classList.add('hidden');
                    
                    if (categories.length === 0) {
                        categoriesContainer.innerHTML = '';
                        noCategoriesMsg.classList.remove('hidden');
                        return;
                    }
                    
                    // Add select all option
                    categoriesContainer.innerHTML = `
                        <div class="mb-2 p-2 bg-slate-100 dark:bg-slate-800 rounded-lg">
                            <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <input type="checkbox" id="selectAllCategories" onchange="toggleAllCategories()"
                                       class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                                <span>Pilih Semua Kategori</span>
                            </label>
                        </div>
                    `;

                    categories.forEach((category, index) => {
                        const categoryId = `category_${index}`;
                        categoriesContainer.innerHTML += `
                            <div class="flex items-center space-x-3 p-2 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-colors">
                                <input type="checkbox" id="${categoryId}" name="provider_categories[]" value="${category.value}"
                                    class="category-checkbox rounded border-slate-300 text-violet-600 focus:ring-violet-500 w-4 h-4" onchange="updateSelectAll()">
                                <label for="${categoryId}" class="flex-1 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer uppercase tracking-wide">
                                    ${category.label}
                                </label>
                            </div>
                        `;
                    });
                })
                .catch(error => {
                    console.error(error);
                    loading.classList.add('hidden');
                    categoriesContainer.innerHTML = `<div class="p-3 text-center text-xs text-rose-500">Gagal memuat kategori.</div>`;
                });
            }

            function toggleAllCategories() {
                const selectAll = document.getElementById('selectAllCategories');
                const checkboxes = document.querySelectorAll('.category-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
            }

            function updateSelectAll() {
                const checkboxes = document.querySelectorAll('.category-checkbox');
                const selectAll = document.getElementById('selectAllCategories');
                if (checkboxes.length > 0) {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                }
            }

            function showProductPreview(product) {
                const preview = document.getElementById('productPreview');
                const previewImage = document.getElementById('previewImage');

                document.getElementById('previewName').textContent = product.name;
                document.getElementById('previewSku').textContent = product.sku;
                document.getElementById('previewCategory').textContent = product.category || '-';
                document.getElementById('previewBrand').textContent = product.brand || '-';
                document.getElementById('previewPrice').textContent = `Rp ${parseInt(product.price).toLocaleString('id-ID')}`;

                const description = product.details?.description || '-';
                document.getElementById('previewDescription').textContent = description;

                if (product.details?.icon_url) {
                    previewImage.innerHTML = `<img src="${product.details.icon_url}" alt="${product.name}" class="w-full h-full object-cover rounded-xl">`;
                } else {
                    previewImage.innerHTML = `<svg class="w-8 h-8 text-slate-300"><use href="#icon-package"></use></svg>`;
                }

                const nameField = document.getElementById('name');
                const descField = document.getElementById('description');

                if (!nameField.value) nameField.value = product.name;
                if (!descField.value && description !== '-') descField.value = description;

                // Auto-fill nominal data logic
                const costPriceInput = document.querySelector('input[name="nominals[0][cost_price]"]');
                if (costPriceInput && !costPriceInput.value) {
                    costPriceInput.value = product.price;
                    calculatePrice(costPriceInput);
                }

                preview.classList.remove('hidden');
            }

            document.getElementById('provider_sku').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.product) {
                    const product = JSON.parse(selectedOption.dataset.product);
                    showProductPreview(product);
                } else {
                    document.getElementById('productPreview').classList.add('hidden');
                }
            });

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

                    if (cost > 0) {
                        const price = cost / (1 - marginPercent);
                        priceInput.value = Math.ceil(price / 100) * 100;
                    }
                }
            }

            function attachPriceCalculation(container) {
                const costPriceInput = container.querySelector('[name$="[cost_price]"]');
                const marginInput = container.querySelector('[name$="[margin]"]');

                if (costPriceInput) {
                    costPriceInput.addEventListener('change', function() { calculatePrice(this); });
                    // Also on input for real-time
                    costPriceInput.addEventListener('input', function() { calculatePrice(this); });
                }
                if (marginInput) {
                    marginInput.addEventListener('change', function() { calculatePrice(this); });
                    marginInput.addEventListener('input', function() { calculatePrice(this); });
                }
            }

            let nominalIndex = {{ count(old('nominals', [])) }};

            function addNominal() {
                const container = document.getElementById('nominalsContainer');
                const template = document.createElement('div');
                // Updated Style
                template.className = 'nominal-item bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 transition-all hover:border-blue-200 dark:hover:border-blue-900';
                template.setAttribute('data-index', nominalIndex);

                // Template content matching the styled version
                template.innerHTML = `
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center text-xs font-black">${nominalIndex + 1}</div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Nominal #${nominalIndex + 1}</h4>
                        </div>
                        <button type="button" onclick="removeNominal(this)" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                            <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nama Nominal *</label>
                                <input type="text" name="nominals[${nominalIndex}][name]" required class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 font-bold" placeholder="Contoh: 100 Diamond">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">SKU Provider</label>
                                <input type="text" name="nominals[${nominalIndex}][provider_sku]" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-500 font-mono text-sm" placeholder="SKU-KODE-123">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Modal (Rp)</label>
                                    <input type="number" name="nominals[${nominalIndex}][cost_price]" required min="0" step="100" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold" placeholder="100000">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Margin (%)</label>
                                    <input type="number" name="nominals[${nominalIndex}][margin]" required min="0" max="100" step="0.5" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-blue-600 font-bold" value="10">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Jual</label>
                                    <input type="number" name="nominals[${nominalIndex}][price]" required min="0" step="100" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-black" placeholder="110000">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Diskon</label>
                                    <input type="number" name="nominals[${nominalIndex}][discount_price]" min="0" step="100" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-rose-500 font-bold">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Total Stok</label>
                                    <input type="number" name="nominals[${nominalIndex}][stock]" required min="0" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold" value="9999">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Available</label>
                                    <input type="number" name="nominals[${nominalIndex}][available_stock]" required min="0" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold" value="9999">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Mode Stok</label>
                                <div class="relative">
                                    <select name="nominals[${nominalIndex}][stock_mode]" class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white font-bold appearance-none">
                                        <option value="provider" selected>Provider</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/50 flex items-center justify-between">
                                <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Nominal</p>
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
                attachPriceCalculation(template);
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
                const container = document.getElementById('nominalsContainer');
                const items = container.querySelectorAll('.nominal-item');
                items.forEach((item, index) => {
                    item.setAttribute('data-index', index);
                    item.querySelector('h4').textContent = `Nominal #${index + 1}`;
                    item.querySelector('.w-8.h-8').textContent = index + 1;
                    
                    item.querySelectorAll('[name]').forEach(input => {
                        const oldName = input.getAttribute('name');
                        const newName = oldName.replace(/nominals\[\d+\]/, `nominals[${index}]`);
                        input.setAttribute('name', newName);
                    });
                });
                nominalIndex = items.length;
            }

            document.addEventListener('DOMContentLoaded', function() {
                const initialItems = document.querySelectorAll('.nominal-item');
                initialItems.forEach(item => attachPriceCalculation(item));

                const typeSelect = document.getElementById('type');
                const nominalsSection = document.getElementById('nominalsSection');
                const addButton = nominalsSection.querySelector('button');

                typeSelect.addEventListener('change', function(e) {
                    if (e.target.value === 'single') {
                        addButton.style.display = 'none';
                        const items = document.querySelectorAll('.nominal-item');
                        items.forEach((item, index) => {
                            if (index > 0) item.style.display = 'none';
                        });
                    } else {
                        addButton.style.display = 'inline-flex';
                        const items = document.querySelectorAll('.nominal-item');
                        items.forEach(item => item.style.display = 'block');
                    }
                });

                document.querySelectorAll('input[name="category_option"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const manualSection = document.getElementById('manualCategorySection');
                        const autoInfo = document.getElementById('autoCategoryInfo');
                        if (this.value === 'manual') {
                            manualSection.classList.remove('hidden');
                            autoInfo.classList.add('hidden');
                            manualSection.querySelector('select').required = true;
                        } else {
                            manualSection.classList.add('hidden');
                            autoInfo.classList.remove('hidden');
                            manualSection.querySelector('select').required = false;
                        }
                    });
                });

                // Form handler untuk bulk import
                document.getElementById('bulkImportForm')?.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const providerId = this.querySelector('[name="provider_id"]').value;
                    const submitBtn = document.getElementById('submitBulkImport');

                    if (!providerId) { alert('Harap pilih provider'); return; }

                    // Check manual category
                    const categoryOption = document.querySelector('input[name="category_option"]:checked').value;
                    const manualCategoryId = document.querySelector('select[name="category_id"]').value;
                    if (categoryOption === 'manual' && !manualCategoryId) {
                        alert('Harap pilih kategori manual');
                        return;
                    }

                    const formData = new FormData(this);
                    formData.append('auto_create_category', categoryOption === 'auto' ? '1' : '0');
                    if (categoryOption === 'auto') formData.delete('category_id');

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <div class="flex items-center justify-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                            Memproses...
                        </div>`;
                    document.getElementById('importProgress').classList.remove('hidden');

                    const selectedCategories = Array.from(document.querySelectorAll('input[name="provider_categories[]"]:checked')).map(cb => cb.value);
                    formData.append('selected_categories', JSON.stringify(selectedCategories));

                    // Simulation Progress
                    const progressBar = document.getElementById('progressBar');
                    const progressPercentage = document.getElementById('progressPercentage');
                    const progressText = document.getElementById('progressText');
                    
                    let progress = 10;
                    const interval = setInterval(() => {
                        progress += Math.random() * 10;
                        if (progress > 90) progress = 90;
                        progressBar.style.width = progress + '%';
                        progressPercentage.textContent = Math.round(progress) + '%';
                        
                        if(progress < 40) progressText.textContent = 'Mengambil data dari Digiflazz...';
                        else if(progress < 70) progressText.textContent = 'Memproses produk...';
                        else progressText.textContent = 'Menyimpan ke database...';
                    }, 500);

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST', body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                        });
                        const result = await response.json();

                        clearInterval(interval);
                        progressBar.style.width = '100%';
                        progressPercentage.textContent = '100%';

                        if (result.success) {
                            document.getElementById('bulkImportModal').classList.add('hidden');
                            document.getElementById('successTitle').textContent = 'Import Berhasil!';
                            let msg = result.message;
                            if (result.auto_created_categories?.length > 0) {
                                msg += `<br><br><strong>Kategori Baru:</strong><br>` + result.auto_created_categories.map(c => `• ${c}`).join('<br>');
                            }
                            document.getElementById('successMessage').innerHTML = msg;
                            if(result.failed_count > 0) {
                                const fp = document.getElementById('failedProducts');
                                fp.classList.remove('hidden');
                                fp.textContent = `${result.failed_count} produk gagal diimport.`;
                            }
                            document.getElementById('bulkImportSuccessModal').classList.remove('hidden');
                            this.reset();
                            document.getElementById('providerCategoriesContainer').innerHTML = '';
                            document.getElementById('providerCategoriesSection').classList.add('hidden');
                            document.getElementById('manualCategorySection').classList.add('hidden');
                            document.getElementById('autoCategoryInfo').classList.remove('hidden');
                        } else {
                            alert(result.message || 'Gagal import');
                        }
                    } catch (error) {
                        clearInterval(interval);
                        console.error(error);
                        alert('Terjadi kesalahan saat import');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Mulai Import';
                        document.getElementById('importProgress').classList.add('hidden');
                    }
                });
                
                // Handle old inputs
                const oldProviderId = "{{ old('provider_id') }}";
                if (oldProviderId) {
                    document.getElementById('provider_id').value = oldProviderId;
                    setTimeout(() => loadProviderProducts(), 100);
                }
            });
        </script>
    @endpush
@endsection
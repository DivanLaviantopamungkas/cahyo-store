@extends('admin.layouts.app')

@section('title', 'Tambah Produk Manual')

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>

    <a href="{{ route('admin.products.create.manual') }}">Tambah Produk</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>

    <span class="text-slate-600 dark:text-slate-300">Tambah Manual</span>
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
        <form action="{{ route('admin.products.store.manual') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <input type="hidden" name="source" value="manual">

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
                            <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700">
                                <label for="category_id" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Kategori Produk *</label>
                                <select id="category_id" name="category_id" required
                                    class="block w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold shadow-sm appearance-none">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="text-[10px] text-rose-500 font-bold mt-2 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nama Produk *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold"
                                        placeholder="Contoh: Voucher Google Play">
                                    @error('name') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="slug" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Slug URL</label>
                                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-500 dark:text-slate-400 focus:ring-2 focus:ring-emerald-500 transition-all font-mono text-sm font-bold"
                                        placeholder="voucher-google-play">
                                </div>

                                <div class="space-y-2">
                                    <label for="type" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Tipe Produk *</label>
                                    <select id="type" name="type" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-violet-500 transition-all font-bold appearance-none">
                                        <option value="">Pilih Tipe Produk</option>
                                        <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single (1 Nominal)</option>
                                        <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>Multiple (Banyak Nominal)</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Informasi Tipe</label>
                                    <div class="p-4 rounded-2xl bg-violet-50 dark:bg-violet-900/20 border border-violet-100 dark:border-violet-800">
                                        <p class="text-[10px] text-violet-600 dark:text-violet-400 font-bold leading-relaxed uppercase tracking-tighter">
                                            Single: 1 Harga tetap | Multiple: Banyak pilihan nominal
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Deskripsi Produk</label>
                                <textarea id="description" name="description" rows="3"
                                    class="block w-full px-5 py-4 rounded-3xl bg-slate-100 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-medium"
                                    placeholder="Tuliskan deskripsi lengkap produk di sini...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div id="nominalsSection" class="space-y-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4">
                            <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter flex items-center">
                                <span class="w-8 h-[2px] bg-emerald-500 mr-3"></span>
                                Konfigurasi Nominal
                            </h3>
                            <button type="button" onclick="addNominal()"
                                class="inline-flex items-center px-6 py-3 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest shadow-xl active:scale-95 transition-all">
                                <svg class="w-4 h-4 mr-2"><use href="#icon-plus"></use></svg>
                                Tambah Nominal
                            </button>
                        </div>

                        <div id="nominalsContainer" class="grid grid-cols-1 gap-6">
                            <div class="nominal-item bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 transition-all hover:border-emerald-200 dark:hover:border-emerald-900" data-index="0">
                                <div class="flex justify-between items-center mb-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xs font-black">1</div>
                                        <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Konfigurasi Nominal Utama</h4>
                                    </div>
                                    <button type="button" onclick="removeNominal(this)" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-5">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nama Nominal *</label>
                                            <input type="text" name="nominals[0][name]" required
                                                class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 font-bold"
                                                placeholder="Contoh: 100 Diamond" value="{{ old('nominals.0.name') }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Provider SKU</label>
                                            <input type="text" name="nominals[0][provider_sku]"
                                                class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-500 font-mono text-sm"
                                                placeholder="SKU-KODE-123" value="{{ old('nominals.0.provider_sku') }}">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Normal *</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">Rp</span>
                                                    <input type="number" name="nominals[0][price]" required min="0"
                                                        class="block w-full pl-10 pr-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-black"
                                                        placeholder="100000" value="{{ old('nominals.0.price') }}">
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Diskon</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">Rp</span>
                                                    <input type="number" name="nominals[0][discount_price]" min="0"
                                                        class="block w-full pl-10 pr-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-rose-500 font-bold"
                                                        placeholder="90000" value="{{ old('nominals.0.discount_price') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Modal</label>
                                                <input type="number" name="nominals[0][cost_price]" min="0"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold"
                                                    placeholder="80000" value="{{ old('nominals.0.cost_price') }}">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Margin (%)</label>
                                                <input type="number" name="nominals[0][margin]" min="0" max="100" step="0.01"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 font-bold"
                                                    placeholder="20" value="{{ old('nominals.0.margin') }}">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Total Stok *</label>
                                                <input type="number" name="nominals[0][stock]" required min="0"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold"
                                                    value="{{ old('nominals.0.stock', 0) }}">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Tersedia *</label>
                                                <input type="number" name="nominals[0][available_stock]" required min="0"
                                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold"
                                                    value="{{ old('nominals.0.available_stock', 0) }}">
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Mode Stok</label>
                                            <select name="nominals[0][stock_mode]" 
                                                class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white font-bold appearance-none">
                                                <option value="">Pilih Mode Stok</option>
                                                <option value="manual" {{ old('nominals.0.stock_mode') == 'manual' ? 'selected' : '' }}>Manual</option>
                                                <option value="provider" {{ old('nominals.0.stock_mode') == 'provider' ? 'selected' : '' }}>Provider</option>
                                            </select>
                                        </div>

                                        <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/50 flex items-center justify-between">
                                            <div>
                                                <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</p>
                                                <p class="text-[9px] text-slate-500 font-medium">Izinkan pelanggan membeli nominal ini</p>
                                            </div>
                                            <div class="relative inline-block w-12 h-6">
                                                <input type="hidden" name="nominals[0][is_active]" value="0">
                                                <input type="checkbox" name="nominals[0][is_active]" value="1" {{ old('nominals.0.is_active', 1) ? 'checked' : '' }} class="sr-only peer">
                                                <label class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
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
                        <button type="submit" class="w-full py-5 rounded-[1.5rem] bg-emerald-500 text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20 hover:scale-[1.02] active:scale-95 transition-all mb-4">
                            Simpan Produk
                        </button>
                        <p class="text-[10px] text-center text-slate-400 font-bold uppercase tracking-widest">Wajib isi field bertanda *</p>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8">
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 ml-1 block">Media Produk</h3>
                        <div id="imagePreviewContainer" class="hidden relative aspect-square rounded-[2rem] overflow-hidden border-4 border-emerald-500/20 shadow-xl mb-4">
                            <img id="imagePreview" src="" class="w-full h-full object-cover">
                            <button type="button" onclick="removeImage()" class="absolute top-4 right-4 p-2 bg-rose-500 text-white rounded-full shadow-lg hover:scale-110 transition-all">
                                <svg class="w-4 h-4"><use href="#icon-x-mark"></use></svg>
                            </button>
                        </div>
                        <div id="uploadArea">
                            <label class="flex flex-col items-center justify-center w-full aspect-square rounded-[2rem] border-4 border-dashed border-slate-100 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition-all cursor-pointer group relative overflow-hidden">
                                <div class="flex flex-col items-center justify-center py-6 text-center px-4 z-10">
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                        <svg class="w-8 h-8 text-slate-400 group-hover:text-white"><use href="#icon-arrow-up-tray"></use></svg>
                                    </div>
                                    <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">Upload Foto</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">PNG, JPG Up to 2MB</p>
                                </div>
                                <input type="file" id="image" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                            </label>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 space-y-6">
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 block">Visibilitas</h3>
                        <div class="flex items-center justify-between p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                    <svg class="w-5 h-5"><use href="#icon-eye"></use></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">Online / Draft</p>
                                </div>
                            </div>
                            <div class="relative inline-block w-12 h-6">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                                <label for="is_active" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
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
                            <div class="relative inline-block w-12 h-6">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="sr-only peer">
                                <label for="is_featured" class="block w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors cursor-pointer"></label>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6 pointer-events-none"></div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="order" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Urutan Tampil</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}"
                                class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-black text-lg">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Slug Generator
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

            // Image Preview
            function previewImage(event) {
                const input = event.target;
                const previewContainer = document.getElementById('imagePreviewContainer');
                const previewImage = document.getElementById('imagePreview');
                const uploadArea = document.getElementById('uploadArea');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
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

            // Nominal Management
            let nominalIndex = {{ count(old('nominals', [0])) }};

            function addNominal() {
                const container = document.getElementById('nominalsContainer');
                const template = document.createElement('div');
                template.className = 'nominal-item bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-8 transition-all hover:border-emerald-200 dark:hover:border-emerald-900';
                template.setAttribute('data-index', nominalIndex);

                template.innerHTML = `
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xs font-black">${nominalIndex + 1}</div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Konfigurasi Nominal Baru</h4>
                        </div>
                        <button type="button" onclick="removeNominal(this)" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                            <svg class="w-5 h-5"><use href="#icon-trash"></use></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nama Nominal *</label>
                                <input type="text" name="nominals[${nominalIndex}][name]" required
                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 font-bold"
                                    placeholder="Contoh: 100 Diamond">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Provider SKU</label>
                                <input type="text" name="nominals[${nominalIndex}][provider_sku]"
                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-500 font-mono text-sm"
                                    placeholder="SKU-KODE-123">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Normal *</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">Rp</span>
                                        <input type="number" name="nominals[${nominalIndex}][price]" required min="0"
                                            class="block w-full pl-10 pr-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 font-black"
                                            placeholder="100000">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Diskon</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">Rp</span>
                                        <input type="number" name="nominals[${nominalIndex}][discount_price]" min="0"
                                            class="block w-full pl-10 pr-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-rose-500 font-bold"
                                            placeholder="90000">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Harga Modal</label>
                                    <input type="number" name="nominals[${nominalIndex}][cost_price]" min="0"
                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Margin (%)</label>
                                    <input type="number" name="nominals[${nominalIndex}][margin]" min="0" max="100" step="0.01"
                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-emerald-600 font-bold">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Total Stok *</label>
                                    <input type="number" name="nominals[${nominalIndex}][stock]" required min="0"
                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold" value="0">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Tersedia *</label>
                                    <input type="number" name="nominals[${nominalIndex}][available_stock]" required min="0"
                                        class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white font-bold" value="0">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Mode Stok</label>
                                <select name="nominals[${nominalIndex}][stock_mode]" 
                                    class="block w-full px-5 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-900 dark:text-white font-bold appearance-none">
                                    <option value="manual">Manual</option>
                                    <option value="provider">Provider</option>
                                </select>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/50 flex items-center justify-between">
                                <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</p>
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
                attachEventListeners(template);
            }

            function removeNominal(button) {
                const container = document.getElementById('nominalsContainer');
                if (container.querySelectorAll('.nominal-item').length <= 1) {
                    alert('Minimal harus ada 1 nominal');
                    return;
                }
                button.closest('.nominal-item').remove();
                reindexNominals();
            }

            function reindexNominals() {
                const items = document.querySelectorAll('.nominal-item');
                items.forEach((item, index) => {
                    item.setAttribute('data-index', index);
                    item.querySelector('.w-8.h-8').textContent = index + 1;
                    item.querySelectorAll('[name]').forEach(input => {
                        const oldName = input.getAttribute('name');
                        input.setAttribute('name', oldName.replace(/nominals\[\d+\]/, `nominals[${index}]`));
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

            document.getElementById('type').addEventListener('change', function(e) {
                const addButton = document.querySelector('#nominalsSection button');
                const items = document.querySelectorAll('.nominal-item');
                if (e.target.value === 'single') {
                    if (addButton) addButton.style.display = 'none';
                    items.forEach((item, index) => { if (index > 0) item.style.display = 'none'; });
                } else {
                    if (addButton) addButton.style.display = 'inline-flex';
                    items.forEach(item => { item.style.display = 'block'; });
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.nominal-item').forEach(attachEventListeners);
                const typeSelect = document.getElementById('type');
                if (typeSelect.value === 'single') {
                    const addButton = document.querySelector('#nominalsSection button');
                    if (addButton) addButton.style.display = 'none';
                }
            });
        </script>
    @endpush
@endsection
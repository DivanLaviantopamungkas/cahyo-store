@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}">Kategori</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Tambah Baru</span>
@endsection

@section('actions')
    <a href="{{ route('admin.categories.index') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
        <svg class="w-4 h-4 mr-2 transform rotate-180">
            <use href="#icon-chevron-right"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="max-w-2xl">
        <x-admin.card>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Informasi Dasar</h3>
                        <div class="space-y-4">
                            <!-- Nama Kategori -->
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Nama Kategori *
                                    @error('name')
                                        <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                    @enderror
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="block w-full px-4 py-3 rounded-2xl border @error('name') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Contoh: Game Online" autofocus>
                                @error('name')
                                    <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Deskripsi
                                    @error('description')
                                        <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                    @enderror
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="block w-full px-4 py-3 rounded-2xl border @error('description') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all resize-none"
                                    placeholder="Deskripsi singkat tentang kategori (opsional)">{{ old('description') }}</textarea>
                                <div class="flex justify-between">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Maksimal 500 karakter</p>
                                    <span class="text-xs text-slate-500 dark:text-slate-400" id="charCount">0/500</span>
                                </div>
                            </div>

                            <!-- Slug (Hidden - auto generated) -->
                            <input type="hidden" id="slug" name="slug" value="{{ old('slug') }}">

                            <!-- Preview Slug -->
                            @if (old('slug') || old('name'))
                                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4">
                                    <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">Preview URL:</div>
                                    <div class="font-mono text-sm text-emerald-600 dark:text-emerald-400">
                                        {{ url('/categories') }}/<span
                                            id="slugPreview">{{ old('slug') ?: Str::slug(old('name')) ?: 'nama-kategori' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Gambar Kategori</h3>
                        <div class="space-y-4">
                            <!-- Image Upload -->
                            <div>
                                <label for="image"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Gambar Kategori
                                    @error('image')
                                        <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                    @enderror
                                </label>

                                <!-- File Input -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="image"
                                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-2xl border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-12 h-12 mb-4 text-slate-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <p class="mb-2 text-sm text-slate-500 dark:text-slate-400">
                                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    JPG, PNG, GIF, atau SVG (Maks. 2MB)
                                                </p>
                                            </div>
                                            <input id="image" name="image" type="file" class="hidden"
                                                accept="image/*" />
                                        </label>
                                    </div>
                                    @error('image')
                                        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreviewContainer" class="{{ old('image') ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Preview Gambar
                                    </label>
                                    <div class="relative inline-block">
                                        <img id="imagePreview" src="{{ old('image') ? '' : '#' }}" alt="Preview"
                                            class="w-32 h-32 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                                        <button type="button" id="removeImageBtn"
                                            class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-rose-600 transition-colors">
                                            <svg class="w-4 h-4">
                                                <use href="#icon-x"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                        Gambar akan ditampilkan sebagai ikon kategori
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pengaturan</h3>
                        <div class="space-y-4">
                            <!-- Status Aktif -->
                            <div
                                class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                                <div>
                                    <label for="is_active"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Status Aktif
                                        @error('is_active')
                                            <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                        @enderror
                                    </label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Kategori akan ditampilkan jika
                                        aktif</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                                    </div>
                                </label>
                            </div>

                            <!-- Urutan -->
                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                                <label for="order"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Urutan
                                    @error('order')
                                        <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                    @enderror
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="number" id="order" name="order"
                                        value="{{ old('order', $suggestedOrder ?? 0) }}" min="0"
                                        class="block w-32 px-4 py-3 rounded-2xl border @error('order') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    <div class="text-sm text-slate-500 dark:text-slate-400">
                                        <div class="font-medium">Saran: <span
                                                class="text-emerald-600 dark:text-emerald-400">{{ $suggestedOrder ?? 'auto' }}</span>
                                        </div>
                                        <div class="text-xs">Angka lebih kecil = lebih awal</div>
                                    </div>
                                </div>
                                @error('order')
                                    <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Preview</h3>
                        <div
                            class="p-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900">
                            <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-4">Tampilan Kategori</h4>
                            <div class="flex items-center space-x-4">
                                <!-- Image Preview -->
                                <div
                                    class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden border border-slate-200 dark:border-slate-700">
                                    <img id="previewImage" src="{{ old('image') ? '' : '#' }}" alt="Preview"
                                        class="w-full h-full object-cover"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div id="defaultIconPreview"
                                        class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6">
                                            <use href="#icon-photo"></use>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Info Preview -->
                                <div>
                                    <div id="namePreview" class="text-lg font-semibold text-slate-800 dark:text-white">
                                        {{ old('name') ?: 'Game Online' }}
                                    </div>
                                    @if (old('description'))
                                        <div id="descriptionPreview"
                                            class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                            {{ old('description') }}
                                        </div>
                                    @endif
                                    <div class="flex items-center mt-2 space-x-3">
                                        <div id="statusPreview"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ old('is_active', true) ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300' }}">
                                            {{ old('is_active', true) ? 'Aktif' : 'Nonaktif' }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            Urutan: <span id="orderPreview"
                                                class="font-medium">{{ old('order', $suggestedOrder ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                <span class="font-medium">*</span> Wajib diisi
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center">
                                    <svg class="w-5 h-5 mr-2">
                                        <use href="#icon-check"></use>
                                    </svg>
                                    Simpan Kategori
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const nameInput = document.getElementById('name');
            const descriptionInput = document.getElementById('description');
            const imageInput = document.getElementById('image');
            const orderInput = document.getElementById('order');
            const isActiveInput = document.getElementById('is_active');

            // Preview elements
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const defaultIconPreview = document.getElementById('defaultIconPreview');
            const namePreview = document.getElementById('namePreview');
            const descriptionPreview = document.getElementById('descriptionPreview');
            const statusPreview = document.getElementById('statusPreview');
            const orderPreview = document.getElementById('orderPreview');
            const slugPreview = document.getElementById('slugPreview');
            const slugInput = document.getElementById('slug');
            const removeImageBtn = document.getElementById('removeImageBtn');

            // Character counter for description
            if (descriptionInput) {
                const charCount = document.getElementById('charCount');

                function updateCharCount() {
                    const length = descriptionInput.value.length;
                    charCount.textContent = `${length}/500`;

                    if (length > 500) {
                        charCount.classList.add('text-rose-600', 'dark:text-rose-400');
                    } else {
                        charCount.classList.remove('text-rose-600', 'dark:text-rose-400');
                    }

                    if (descriptionPreview) {
                        descriptionPreview.textContent = descriptionInput.value || 'Tidak ada deskripsi';
                    }
                }

                descriptionInput.addEventListener('input', updateCharCount);
                updateCharCount(); // Initial count
            }

            // Auto-generate slug from name
            if (nameInput) {
                function generateSlug(text) {
                    return text
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }

                nameInput.addEventListener('input', function() {
                    const name = this.value;

                    if (namePreview) {
                        namePreview.textContent = name || 'Game Online';
                    }

                    // Only auto-generate slug if slug field is empty or matches previous auto-generated slug
                    if (!slugInput.value || slugInput.value === generateSlug(nameInput.dataset.lastName ||
                            '')) {
                        const slug = generateSlug(name);
                        slugInput.value = slug;

                        if (slugPreview) {
                            slugPreview.textContent = slug || 'nama-kategori';
                        }
                    }

                    nameInput.dataset.lastName = name;
                });

                // Initialize
                if (nameInput.value) {
                    nameInput.dispatchEvent(new Event('input'));
                }
            }

            // Image upload preview
            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (imagePreview) {
                                imagePreview.src = e.target.result;
                            }
                            if (previewImage) {
                                previewImage.src = e.target.result;
                                previewImage.style.display = 'block';
                                defaultIconPreview.style.display = 'none';
                            }
                            imagePreviewContainer.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Remove image
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function() {
                    imageInput.value = '';
                    if (imagePreview) {
                        imagePreview.src = '#';
                    }
                    if (previewImage) {
                        previewImage.src = '#';
                        previewImage.style.display = 'none';
                        defaultIconPreview.style.display = 'flex';
                    }
                    imagePreviewContainer.classList.add('hidden');
                });
            }

            // Order change
            if (orderInput) {
                orderInput.addEventListener('input', function() {
                    if (orderPreview) {
                        orderPreview.textContent = this.value;
                    }
                });
            }

            // Status change
            if (isActiveInput) {
                isActiveInput.addEventListener('change', function() {
                    if (statusPreview) {
                        if (this.checked) {
                            statusPreview.className =
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300';
                            statusPreview.textContent = 'Aktif';
                        } else {
                            statusPreview.className =
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300';
                            statusPreview.textContent = 'Nonaktif';
                        }
                    }
                });
            }

            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-rose-500');

                            // Scroll to first error
                            if (isValid === false) {
                                field.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                                field.focus();
                            }
                        }
                    });

                    // Validate image file size (2MB)
                    if (imageInput.files[0]) {
                        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                        if (imageInput.files[0].size > maxSize) {
                            isValid = false;
                            showNotification('Ukuran gambar maksimal 2MB', 'error');
                        }
                    }

                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            }

            function showNotification(message, type = 'info') {
                // You can integrate with your notification system here
                alert(message);
            }
        });
    </script>

    <style>
        /* Custom scrollbar */
        select::-webkit-scrollbar {
            width: 8px;
        }

        select::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        select::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark select::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .dark select::-webkit-scrollbar-thumb {
            background: #475569;
        }

        /* Drag and drop styling */
        #image:hover {
            border-color: #10b981;
        }
    </style>
@endpush

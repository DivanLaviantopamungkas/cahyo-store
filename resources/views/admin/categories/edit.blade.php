@extends('admin.layouts.app')

@section('title', 'Edit Kategori: ' . $category->name)
@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}">Kategori</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <a href="{{ route('admin.categories.show', $category) }}">{{ $category->name }}</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Edit</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.categories.show', $category) }}" 
           class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-eye"></use></svg>
            Detail
        </a>
        <a href="{{ route('admin.categories.index') }}" 
           class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2 transform rotate-180"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">
    <x-admin.card>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <!-- Nama Kategori -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nama Kategori *
                                @error('name')
                                <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                @enderror
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $category->name) }}" 
                                required
                                class="block w-full px-4 py-3 rounded-2xl border @error('name') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="Contoh: Game Online"
                                autofocus
                            >
                            @error('name')
                            <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Slug
                                @error('slug')
                                <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                @enderror
                            </label>
                            <input 
                                type="text" 
                                id="slug" 
                                name="slug" 
                                value="{{ old('slug', $category->slug) }}" 
                                class="block w-full px-4 py-3 rounded-2xl border @error('slug') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="game-online"
                            >
                            @error('slug')
                            <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">URL unik untuk kategori</p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Deskripsi
                                @error('description')
                                <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                @enderror
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="3"
                                class="block w-full px-4 py-3 rounded-2xl border @error('description') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all resize-none"
                                placeholder="Deskripsi singkat tentang kategori (opsional)"
                            >{{ old('description', $category->description) }}</textarea>
                            <div class="flex justify-between">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Maksimal 500 karakter</p>
                                <span class="text-xs text-slate-500 dark:text-slate-400" id="charCount">{{ strlen(old('description', $category->description)) }}/500</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Penampilan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Icon -->
                        <div>
                            <label for="icon" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Icon
                                @error('icon')
                                <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                @enderror
                            </label>
                            <div class="relative">
                                <select 
                                    id="icon" 
                                    name="icon" 
                                    class="block w-full px-4 py-3 rounded-2xl border @error('icon') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all appearance-none"
                                >
                                    <option value="">Pilih Icon</option>
                                    <option value="🎮" {{ old('icon', $category->icon) == '🎮' ? 'selected' : '' }}>🎮 Game</option>
                                    <option value="🎫" {{ old('icon', $category->icon) == '🎫' ? 'selected' : '' }}>🎫 Voucher</option>
                                    <option value="🎁" {{ old('icon', $category->icon) == '🎁' ? 'selected' : '' }}>🎁 Gift</option>
                                    <option value="📱" {{ old('icon', $category->icon) == '📱' ? 'selected' : '' }}>📱 Mobile</option>
                                    <option value="💳" {{ old('icon', $category->icon) == '💳' ? 'selected' : '' }}>💳 Credit</option>
                                    <option value="🎯" {{ old('icon', $category->icon) == '🎯' ? 'selected' : '' }}>🎯 Entertainment</option>
                                    <option value="🛒" {{ old('icon', $category->icon) == '🛒' ? 'selected' : '' }}>🛒 Shopping</option>
                                    <option value="📺" {{ old('icon', $category->icon) == '📺' ? 'selected' : '' }}>📺 Streaming</option>
                                    <option value="✈️" {{ old('icon', $category->icon) == '✈️' ? 'selected' : '' }}>✈️ Travel</option>
                                    <option value="🍔" {{ old('icon', $category->icon) == '🍔' ? 'selected' : '' }}>🍔 Food</option>
                                </select>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400"><use href="#icon-chevron-down"></use></svg>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Icon akan ditampilkan di samping nama kategori</p>
                        </div>

                        <!-- Warna -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Warna
                                @error('color')
                                <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                @enderror
                            </label>
                            <div class="flex items-center space-x-3">
                                <div class="relative flex-1">
                                    <input 
                                        type="text" 
                                        id="color" 
                                        name="color" 
                                        value="{{ old('color', $category->color ?: '#10b981') }}"
                                        class="block w-full px-4 py-3 pl-12 rounded-2xl border @error('color') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono"
                                        placeholder="#10b981"
                                    >
                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 h-6 rounded border border-slate-300 dark:border-slate-600" 
                                         style="background-color: {{ old('color', $category->color ?: '#10b981') }};"
                                         id="colorPreview">
                                    </div>
                                </div>
                                <input 
                                    type="color" 
                                    id="colorPicker" 
                                    value="{{ old('color', $category->color ?: '#10b981') }}"
                                    class="w-12 h-12 rounded-xl border border-slate-300 dark:border-slate-600 cursor-pointer"
                                >
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Hex color code (contoh: #10b981)</p>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pengaturan</h3>
                    <div class="space-y-4">
                        <!-- Status Aktif -->
                        <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Status Aktif
                                    @error('is_active')
                                    <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                    @enderror
                                </label>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Kategori akan ditampilkan jika aktif</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    id="is_active" 
                                    name="is_active" 
                                    value="1" 
                                    {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <!-- Urutan -->
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                            <label for="order" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Urutan
                                @error('order')
                                <span class="text-rose-600 dark:text-rose-400 ml-1">({{ $message }})</span>
                                @enderror
                            </label>
                            <div class="flex items-center space-x-3">
                                <input 
                                    type="number" 
                                    id="order" 
                                    name="order" 
                                    value="{{ old('order', $category->order) }}" 
                                    min="0"
                                    class="block w-32 px-4 py-3 rounded-2xl border @error('order') border-rose-500 @else border-slate-300 dark:border-slate-600 @enderror bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                >
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    <div class="font-medium">Saat ini: <span class="text-emerald-600 dark:text-emerald-400">{{ $category->order }}</span></div>
                                    <div class="text-xs">Angka lebih kecil = lebih awal</div>
                                </div>
                            </div>
                            @error('order')
                            <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Statistik</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $category->products()->count() }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Total Produk
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-sky-600 dark:text-sky-400">
                                {{ $category->products()->where('is_active', true)->count() }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Produk Aktif
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                                {{ $category->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Dibuat Pada
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $category->updated_at->diffForHumans() }}
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                Terakhir Diperbarui
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
                                <svg class="w-5 h-5 mr-2"><use href="#icon-check"></use></svg>
                                Update Kategori
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
    // Character counter for description
    const descriptionInput = document.getElementById('description');
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
        }
        
        descriptionInput.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count
    }

    // Color picker sync
    const colorInput = document.getElementById('color');
    const colorPicker = document.getElementById('colorPicker');
    const colorPreview = document.getElementById('colorPreview');
    
    if (colorInput && colorPicker && colorPreview) {
        function updateColor(value) {
            colorInput.value = value;
            colorPicker.value = value;
            colorPreview.style.backgroundColor = value;
        }
        
        colorInput.addEventListener('input', function() {
            updateColor(this.value);
        });
        
        colorPicker.addEventListener('input', function() {
            updateColor(this.value);
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
                        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        field.focus();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Harap isi semua field yang wajib diisi', 'error');
            }
        });
    }

    function showNotification(message, type = 'info') {
        // You can integrate with your notification system here
        alert(message);
    }
});
</script>
@endpush
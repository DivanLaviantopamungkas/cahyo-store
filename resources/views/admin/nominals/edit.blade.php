@extends('admin.layouts.app')

@section('title', 'Edit Nominal')
@section('breadcrumb')
    <a href="{{ route('admin.nominals.index') }}">Nominals</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Edit</span>
@endsection

@section('actions')
    <div class="flex items-center space-x-3">
        <form action="{{ route('admin.nominals.destroy', $nominal->id) }}" method="POST" onsubmit="return confirm('Hapus nominal ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-2xl border border-rose-300 dark:border-rose-600 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-medium transition-colors">
                <svg class="w-4 h-4 mr-2"><use href="#icon-trash"></use></svg>
                Hapus
            </button>
        </form>
        <a href="{{ route('admin.nominals.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
            <svg class="w-4 h-4 mr-2"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">
    <x-admin.card>
        <form action="{{ route('admin.nominals.update', $nominal->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Product Selection -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pilih Produk</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Produk *</label>
                            <select 
                                id="product_id" 
                                name="product_id" 
                                required
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                @input="$el.classList.remove('border-rose-500')"
                            >
                                <option value="">Pilih Produk</option>
                                <option value="1" {{ old('product_id', $nominal->product_id) == 1 ? 'selected' : '' }}>Game Online</option>
                                <option value="2" {{ old('product_id', $nominal->product_id) == 2 ? 'selected' : '' }}>Voucher Game</option>
                                <option value="3" {{ old('product_id', $nominal->product_id) == 3 ? 'selected' : '' }}>E-Money</option>
                            </select>
                            <div x-show="document.getElementById('product_id').value === '' && document.getElementById('product_id').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                                Produk wajib dipilih
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nominal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Informasi Nominal</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Nominal *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $nominal->name) }}" 
                                required
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="Contoh: 100 Diamond"
                                @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                @input="$el.classList.remove('border-rose-500')"
                            >
                            <div x-show="document.getElementById('name').value === '' && document.getElementById('name').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                                Nama nominal wajib diisi
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Harga Normal *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500">Rp</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        id="price" 
                                        name="price" 
                                        value="{{ old('price', $nominal->price) }}" 
                                        required
                                        min="0"
                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="100000"
                                        @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                        @input="$el.classList.remove('border-rose-500')"
                                    >
                                </div>
                                <div x-show="document.getElementById('price').value === '' && document.getElementById('price').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                                    Harga wajib diisi
                                </div>
                            </div>

                            <div>
                                <label for="discount_price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Harga Diskon</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500">Rp</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        id="discount_price" 
                                        name="discount_price" 
                                        value="{{ old('discount_price', $nominal->discount_price) }}" 
                                        min="0"
                                        class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="90000"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="stock" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total Stok *</label>
                                <input 
                                    type="number" 
                                    id="stock" 
                                    name="stock" 
                                    value="{{ old('stock', $nominal->stock) }}" 
                                    required
                                    min="0"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                    @input="$el.classList.remove('border-rose-500')"
                                >
                                <div x-show="document.getElementById('stock').value === '' && document.getElementById('stock').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                                    Stok wajib diisi
                                </div>
                            </div>

                            <div>
                                <label for="available_stock" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Stok Tersedia *</label>
                                <input 
                                    type="number" 
                                    id="available_stock" 
                                    name="available_stock" 
                                    value="{{ old('available_stock', $nominal->available_stock) }}" 
                                    required
                                    min="0"
                                    class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                    @input="$el.classList.remove('border-rose-500')"
                                >
                                <div x-show="document.getElementById('available_stock').value === '' && document.getElementById('available_stock').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                                    Stok tersedia wajib diisi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pengaturan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status Aktif</label>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Nominal dapat dipesan</p>
                            </div>
                            <div class="relative inline-block w-12 h-6">
                                <input 
                                    type="checkbox" 
                                    id="is_active" 
                                    name="is_active" 
                                    value="1" 
                                    {{ old('is_active', $nominal->is_active) ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <div class="w-12 h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors"></div>
                                <div class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform peer-checked:translate-x-6"></div>
                            </div>
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Urutan</label>
                            <input 
                                type="number" 
                                id="order" 
                                name="order" 
                                value="{{ old('order', $nominal->order) }}" 
                                min="0"
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                            >
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Angka lebih kecil muncul lebih awal</p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.nominals.index') }}" class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                            Update Nominal
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('subtitle', 'Buat kategori baru untuk mengorganisir produk Anda')

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}" class="hover:text-emerald-500 transition-colors">Kategori</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Tambah Baru</span>
@endsection

@section('actions')
    <a href="{{ route('admin.categories.index') }}"
        class="inline-flex items-center px-5 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs uppercase tracking-widest transition-all active:scale-95 shadow-sm">
        <svg class="w-4 h-4 mr-2 transform rotate-180">
            <use href="#icon-chevron-right"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto pb-20">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-md">
                    <div class="p-6 lg:p-8 space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <svg class="w-6 h-6"><use href="#icon-tag"></use></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Informasi Dasar</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Detail utama kategori anda</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="group">
                                <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                                    Nama Kategori <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="block w-full px-6 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner placeholder-slate-400"
                                    placeholder="Contoh: Game Online" autofocus>
                                @error('name')
                                    <p class="text-[10px] font-bold text-rose-500 mt-2 ml-2 uppercase tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                                    Deskripsi Singkat
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="block w-full px-6 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-medium text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner placeholder-slate-400 resize-none"
                                    placeholder="Jelaskan secara singkat kategori ini...">{{ old('description') }}</textarea>
                                <div class="flex justify-between mt-2 px-2">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Informasi tambahan untuk pelanggan</p>
                                    <span class="text-[10px] font-black text-slate-400 tracking-tighter" id="charCount">0/500</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-md">
                    <div class="p-6 lg:p-8 space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-500">
                                <svg class="w-6 h-6"><use href="#icon-photo"></use></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Media Visual</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Icon atau gambar kategori</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            <div class="space-y-4">
                                <label class="relative flex flex-col items-center justify-center w-full h-48 border-4 border-dashed rounded-[2rem] border-slate-100 dark:border-slate-700 hover:border-emerald-500/50 dark:hover:border-emerald-500/50 hover:bg-slate-50 dark:hover:bg-slate-900/50 cursor-pointer transition-all group overflow-hidden shadow-inner text-center">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 transition-all group-hover:scale-110">
                                        <svg class="w-10 h-10 mb-3 text-slate-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilih Gambar</p>
                                    </div>
                                    <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                                </label>
                            </div>

                            <div id="imagePreviewContainer" class="hidden relative group/preview">
                                <img id="imagePreview" src="#" alt="Preview" class="w-full h-48 object-cover rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-lg">
                                <button type="button" id="removeImageBtn" class="absolute -top-3 -right-3 bg-rose-500 text-white rounded-2xl p-2.5 shadow-xl hover:bg-rose-600 transition-all active:scale-90 border-4 border-white dark:border-slate-800">
                                    <svg class="w-4 h-4"><use href="#icon-x-mark"></use></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden p-6 lg:p-8">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6">Status & Posisi</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-inner transition-all hover:border-emerald-500/30">
                            <div>
                                <span class="block text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase mt-1">Tampil di client</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-12 h-6.5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:rounded-full after:h-5.5 after:w-5.5 after:transition-all peer-checked:bg-emerald-500 shadow-sm"></div>
                            </label>
                        </div>

                        <div class="group p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-inner transition-all hover:border-emerald-500/30">
                            <label for="order" class="block text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest mb-3">
                                Urutan Tampil
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="number" id="order" name="order" value="{{ old('order', $suggestedOrder ?? 0) }}" min="0"
                                    class="w-24 px-4 py-2.5 rounded-xl border-none bg-white dark:bg-slate-800 text-slate-800 dark:text-white font-black text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-sm">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Posisi tampilan<br>menu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sticky top-6">
                    <div class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden group">
                        <div id="statusPreviewBadge" class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-[0.2em] shadow-lg transition-colors {{ old('is_active', true) ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                            {{ old('is_active', true) ? 'Active' : 'Offline' }}
                        </div>

                        <div class="aspect-square bg-slate-50 dark:bg-slate-900 flex items-center justify-center relative">
                            <img id="cardPreviewImg" src="#" class="w-full h-full object-cover hidden transition-transform group-hover:scale-110 duration-700">
                            <div id="defaultIconPreview" class="flex flex-col items-center gap-2 text-slate-300 dark:text-slate-700">
                                <svg class="w-16 h-16 opacity-20"><use href="#icon-photo"></use></svg>
                                <span class="text-[8px] font-black uppercase tracking-widest">Visual Preview</span>
                            </div>
                        </div>
                        
                        <div class="p-6 text-center">
                            <h4 id="namePreview" class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tighter truncate">Nama Kategori</h4>
                            <p id="descriptionPreview" class="text-[10px] text-slate-400 mt-2 line-clamp-2 leading-relaxed">Deskripsi kategori akan muncul di sini untuk dilihat pengunjung.</p>
                            
                            <div class="mt-6 pt-6 border-t border-slate-50 dark:border-slate-700 flex items-center justify-between px-2">
                                <div class="text-left">
                                    <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest">Order Posisi</span>
                                    <span id="orderPreview" class="text-sm font-black text-emerald-500">{{ old('order', $suggestedOrder ?? 0) }}</span>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                    <svg class="w-5 h-5"><use href="#icon-arrow-right-circle"></use></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-3">
                        <button type="submit" class="w-full py-5 rounded-[1.8rem] bg-emerald-500 hover:bg-emerald-600 text-white font-black uppercase tracking-[0.2em] text-[11px] shadow-xl shadow-emerald-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 group">
                            <span>Simpan Kategori</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"><use href="#icon-check"></use></svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const descriptionInput = document.getElementById('description');
            const imageInput = document.getElementById('image');
            const orderInput = document.getElementById('order');
            const isActiveInput = document.getElementById('is_active');

            // Preview Selectors
            const namePreview = document.getElementById('namePreview');
            const descriptionPreview = document.getElementById('descriptionPreview');
            const orderPreview = document.getElementById('orderPreview');
            const statusPreviewBadge = document.getElementById('statusPreviewBadge');
            const imagePreview = document.getElementById('imagePreview');
            const cardPreviewImg = document.getElementById('cardPreviewImg');
            const defaultIconPreview = document.getElementById('defaultIconPreview');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const charCount = document.getElementById('charCount');

            // 1. Name Preview
            nameInput.addEventListener('input', function() {
                namePreview.textContent = this.value || 'Nama Kategori';
            });

            // 2. Description Counter & Preview
            descriptionInput.addEventListener('input', function() {
                const len = this.value.length;
                charCount.textContent = `${len}/500`;
                descriptionPreview.textContent = this.value || 'Deskripsi kategori akan muncul di sini...';
                
                if(len > 450) charCount.classList.add('text-rose-500');
                else charCount.classList.remove('text-rose-500');
            });

            // 3. Image Logic
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        
                        cardPreviewImg.src = e.target.result;
                        cardPreviewImg.classList.remove('hidden');
                        defaultIconPreview.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('removeImageBtn').addEventListener('click', function() {
                imageInput.value = '';
                previewContainer.classList.add('hidden');
                cardPreviewImg.classList.add('hidden');
                defaultIconPreview.classList.remove('hidden');
            });

            // 4. Status Badge Logic
            isActiveInput.addEventListener('change', function() {
                if(this.checked) {
                    statusPreviewBadge.textContent = 'Active';
                    statusPreviewBadge.className = 'absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-[0.2em] shadow-lg bg-emerald-500 text-white';
                } else {
                    statusPreviewBadge.textContent = 'Offline';
                    statusPreviewBadge.className = 'absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-[0.2em] shadow-lg bg-rose-500 text-white';
                }
            });

            // 5. Order Posisi Logic
            orderInput.addEventListener('input', function() {
                orderPreview.textContent = this.value || '0';
            });
        });
    </script>
@endpush
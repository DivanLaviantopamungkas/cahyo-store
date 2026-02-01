@extends('admin.layouts.app')

@section('title', 'Edit Kategori: ' . $category->name)
@section('subtitle', 'Perbarui informasi dan tampilan kategori produk Anda')

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}" class="hover:text-emerald-500 transition-colors">Kategori</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Edit Kategori</span>
@endsection

@section('actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.show', $category) }}" 
           class="inline-flex items-center px-5 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs uppercase tracking-widest transition-all active:scale-95 shadow-sm">
            <svg class="w-4 h-4 mr-2"><use href="#icon-eye"></use></svg>
            Detail
        </a>
        
        <a href="{{ route('admin.categories.index') }}" 
           class="inline-flex items-center px-5 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs uppercase tracking-widest transition-all active:scale-95 shadow-sm">
            <svg class="w-4 h-4 mr-2 transform rotate-180"><use href="#icon-chevron-right"></use></svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto pb-20">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-md">
                    <div class="p-6 lg:p-8 space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <svg class="w-6 h-6"><use href="#icon-tag"></use></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Informasi Dasar</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Identitas utama kategori</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="group">
                                <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                                    Nama Kategori <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                                    class="block w-full px-6 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner placeholder-slate-400">
                            </div>

                            <div class="group">
                                <label for="slug" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                                    Slug URL
                                </label>
                                <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}"
                                    class="block w-full px-6 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner placeholder-slate-400">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 ml-2">Digunakan untuk akses URL (e.g: /cat/slug-nama)</p>
                            </div>

                            <div class="group">
                                <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                                    Deskripsi Singkat
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="block w-full px-6 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-medium text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner placeholder-slate-400 resize-none">{{ old('description', $category->description) }}</textarea>
                                <div class="flex justify-between mt-2 px-2">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Maksimal 500 karakter</p>
                                    <span class="text-[10px] font-black text-slate-400 tracking-tighter" id="charCount">0/500</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden p-6 lg:p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                            <svg class="w-6 h-6"><use href="#icon-bolt"></use></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Penampilan</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Kustomisasi warna dan icon</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label for="icon" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Pilih Icon</label>
                            <div class="relative">
                                <select id="icon" name="icon" class="block w-full px-6 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 appearance-none shadow-inner">
                                    <option value="">Pilih Icon</option>
                                    @php $icons = ['🎮' => 'Game', '🎫' => 'Voucher', '🎁' => 'Gift', '📱' => 'Mobile', '💳' => 'Credit', '🎯' => 'Entertainment', '🛒' => 'Shopping', '📺' => 'Streaming', '✈️' => 'Travel', '🍔' => 'Food']; @endphp
                                    @foreach($icons as $emoji => $label)
                                        <option value="{{ $emoji }}" {{ old('icon', $category->icon) == $emoji ? 'selected' : '' }}>{{ $emoji }} {{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4"><use href="#icon-chevron-down"></use></svg>
                                </div>
                            </div>
                        </div>

                        <div class="group">
                            <label for="color" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Warna Aksen</label>
                            <div class="flex items-center gap-3">
                                <div class="relative flex-1">
                                    <input type="text" id="color" name="color" value="{{ old('color', $category->color ?: '#10b981') }}"
                                        class="block w-full px-6 py-4 pl-14 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 shadow-inner">
                                    <div id="colorPreview" class="absolute left-4 top-1/2 -translate-y-1/2 w-7 h-7 rounded-lg border border-white dark:border-slate-700 shadow-sm" style="background-color: {{ old('color', $category->color ?: '#10b981') }}"></div>
                                </div>
                                <input type="color" id="colorPicker" value="{{ old('color', $category->color ?: '#10b981') }}" class="w-14 h-14 rounded-2xl border-none p-1 bg-slate-50 dark:bg-slate-900/50 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden p-6 lg:p-8 space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-500">
                            <svg class="w-6 h-6"><use href="#icon-photo"></use></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Thumbnail Kategori</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Format JPG, PNG atau SVG (Maks 2MB)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <label class="relative flex flex-col items-center justify-center w-full h-48 border-4 border-dashed rounded-[2rem] border-slate-100 dark:border-slate-700 hover:border-emerald-500/50 hover:bg-slate-50 dark:hover:bg-slate-900/50 cursor-pointer transition-all group overflow-hidden shadow-inner text-center">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 transition-all group-hover:scale-110">
                                <svg class="w-10 h-10 mb-3 text-slate-300 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Klik Untuk Upload</p>
                            </div>
                            <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                        </label>

                        <div id="imagePreviewContainer" class="relative group/preview {{ $category->image ? '' : 'hidden' }}">
                            <img id="imagePreview" src="{{ $category->image ? Storage::url($category->image) : '#' }}" alt="Preview" class="w-full h-48 object-cover rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-lg">
                            <button type="button" id="removeImageBtn" class="absolute -top-3 -right-3 bg-rose-500 text-white rounded-2xl p-2.5 shadow-xl hover:bg-rose-600 transition-all active:scale-90 border-4 border-white dark:border-slate-800">
                                <svg class="w-4 h-4"><use href="#icon-x-mark"></use></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden p-6 lg:p-8">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6">Pengaturan</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-inner">
                            <div>
                                <span class="block text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Aktif</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase mt-1">Tampil di client</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-12 h-6.5 bg-slate-200 dark:bg-slate-700 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:rounded-full after:h-5.5 after:w-5.5 after:transition-all peer-checked:bg-emerald-500 shadow-sm"></div>
                            </label>
                        </div>

                        <div class="group p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-inner">
                            <label for="order" class="block text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest mb-3">Urutan Tampil</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $category->order) }}" min="0"
                                class="w-full px-4 py-2.5 rounded-xl border-none bg-white dark:bg-slate-800 text-slate-800 dark:text-white font-black text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-6 lg:p-8">
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 text-center">Statistik</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-3xl bg-emerald-500/5 border border-emerald-500/10 text-center transition-transform hover:scale-105">
                            <span class="block text-xl font-black text-emerald-500 leading-none">{{ $category->products()->count() }}</span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1 block">Total Produk</span>
                        </div>
                        <div class="p-4 rounded-3xl bg-sky-500/5 border border-sky-500/10 text-center transition-transform hover:scale-105">
                            <span class="block text-xl font-black text-sky-500 leading-none">{{ $category->products()->where('is_active', true)->count() }}</span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1 block">Produk Aktif</span>
                        </div>
                        <div class="p-4 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 text-center">
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Dibuat Pada</span>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase">{{ $category->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="p-4 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 text-center">
                            <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Terakhir Update</span>
                            <span class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase">{{ $category->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <div class="sticky top-6">
                    <div id="livePreviewCard" class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] border-4 border-white dark:border-slate-700 shadow-2xl overflow-hidden group transition-all" style="border-color: {{ old('color', $category->color ?: '#10b981') }}33">
                        <div id="statusPreviewBadge" class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-[0.2em] shadow-lg transition-colors {{ $category->is_active ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                            {{ $category->is_active ? 'Active' : 'Offline' }}
                        </div>

                        <div class="aspect-square bg-slate-50 dark:bg-slate-900 flex items-center justify-center relative overflow-hidden">
                            <img id="cardPreviewImg" src="{{ $category->image ? Storage::url($category->image) : '#' }}" class="w-full h-full object-cover {{ $category->image ? '' : 'hidden' }} transition-transform group-hover:scale-110 duration-700">
                            <div id="defaultIconPreview" class="flex flex-col items-center gap-2 text-slate-300 dark:text-slate-700 {{ $category->image ? 'hidden' : '' }}">
                                <span class="text-4xl" id="emojiIconPreview">{{ $category->icon ?: '🎮' }}</span>
                                <span class="text-[8px] font-black uppercase tracking-widest">Icon Mode</span>
                            </div>
                        </div>
                        
                        <div class="p-6 text-center">
                            <h4 id="namePreview" class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tighter truncate">{{ $category->name }}</h4>
                            <p id="descriptionPreview" class="text-[10px] text-slate-400 mt-2 line-clamp-2 leading-relaxed">{{ $category->description ?: 'No description provided' }}</p>
                            <div class="mt-6 pt-6 border-t border-slate-50 dark:border-slate-700 flex items-center justify-between px-2">
                                <div class="text-left">
                                    <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest">Order</span>
                                    <span id="orderPreview" class="text-sm font-black text-emerald-500">{{ $category->order }}</span>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                    <svg class="w-5 h-5"><use href="#icon-check-badge"></use></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-3">
                        <button type="submit" class="w-full py-5 rounded-[1.8rem] bg-emerald-500 hover:bg-emerald-600 text-white font-black uppercase tracking-[0.2em] text-[11px] shadow-xl shadow-emerald-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 group">
                            <span>Simpan Perubahan</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"><use href="#icon-check"></use></svg>
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="block w-full py-5 rounded-[1.8rem] bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-black uppercase tracking-[0.2em] text-[11px] text-center hover:bg-rose-500 hover:text-white transition-all active:scale-95">
                            Batal & Kembali
                        </a>
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
            const colorInput = document.getElementById('color');
            const colorPicker = document.getElementById('colorPicker');
            const iconSelect = document.getElementById('icon');

            // Preview Selectors
            const namePreview = document.getElementById('namePreview');
            const descriptionPreview = document.getElementById('descriptionPreview');
            const orderPreview = document.getElementById('orderPreview');
            const statusPreviewBadge = document.getElementById('statusPreviewBadge');
            const imagePreview = document.getElementById('imagePreview');
            const cardPreviewImg = document.getElementById('cardPreviewImg');
            const defaultIconPreview = document.getElementById('defaultIconPreview');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const emojiIconPreview = document.getElementById('emojiIconPreview');
            const charCount = document.getElementById('charCount');
            const livePreviewCard = document.getElementById('livePreviewCard');

            // 1. Live Name & Description
            nameInput.addEventListener('input', () => namePreview.textContent = nameInput.value || 'Nama Kategori');
            const updateDesc = () => {
                charCount.textContent = `${descriptionInput.value.length}/500`;
                descriptionPreview.textContent = descriptionInput.value || 'No description provided';
            };
            descriptionInput.addEventListener('input', updateDesc);
            updateDesc();

            // 2. Color Sync
            const updateColor = (hex) => {
                colorInput.value = hex;
                colorPicker.value = hex;
                document.getElementById('colorPreview').style.backgroundColor = hex;
                livePreviewCard.style.borderColor = hex + '33'; // Add transparency
            };
            colorInput.addEventListener('input', (e) => updateColor(e.target.value));
            colorPicker.addEventListener('input', (e) => updateColor(e.target.value));

            // 3. Icon Selection
            iconSelect.addEventListener('change', () => emojiIconPreview.textContent = iconSelect.value || '🎮');

            // 4. Image Upload
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagePreview.src = e.target.result;
                        cardPreviewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        cardPreviewImg.classList.remove('hidden');
                        defaultIconPreview.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('removeImageBtn').addEventListener('click', () => {
                imageInput.value = '';
                previewContainer.classList.add('hidden');
                cardPreviewImg.classList.add('hidden');
                defaultIconPreview.classList.remove('hidden');
            });

            // 5. Status & Order
            isActiveInput.addEventListener('change', function() {
                const active = this.checked;
                statusPreviewBadge.textContent = active ? 'Active' : 'Offline';
                statusPreviewBadge.className = `absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-[0.2em] shadow-lg ${active ? 'bg-emerald-500' : 'bg-rose-500'} text-white`;
            });
            orderInput.addEventListener('input', () => orderPreview.textContent = orderInput.value || '0');
        });
    </script>
@endpush
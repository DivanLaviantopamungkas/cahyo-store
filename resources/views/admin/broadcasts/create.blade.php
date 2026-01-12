@extends('admin.layouts.app')

@section('title', 'Buat Broadcast')
@section('breadcrumb')
    <a href="{{ route('admin.broadcasts.index') }}">Broadcast</a>
    <svg class="w-4 h-4 text-slate-400"><use href="#icon-chevron-right"></use></svg>
    <span class="text-slate-600 dark:text-slate-300">Buat Baru</span>
@endsection

@section('actions')
    <a href="{{ route('admin.broadcasts.index') }}" class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
        <svg class="w-4 h-4 mr-2"><use href="#icon-chevron-right"></use></svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="max-w-3xl">
    <x-admin.card>
        <form action="{{ route('admin.broadcasts.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Informasi Broadcast</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Judul *</label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}" 
                                required
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="Contoh: Promo Spesial Akhir Tahun"
                                @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                @input="$el.classList.remove('border-rose-500')"
                            >
                            <div x-show="document.getElementById('title').value === '' && document.getElementById('title').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                                Judul wajib diisi
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pesan *</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6"
                                required
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="Tulis pesan broadcast di sini..."
                                @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                @input="$el.classList.remove('border-rose-500')"
                            >{{ old('message') }}</textarea>
                            <div x-show="document.getElementById('message').value === '' && document.getElementById('message').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                                Pesan wajib diisi
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target & Schedule -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Target & Jadwal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="target" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Target *</label>
                            <select 
                                id="target" 
                                name="target" 
                                required
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                            >
                                <option value="all">Semua Member</option>
                                <option value="active">Member Aktif Saja</option>
                                <option value="inactive">Member Nonaktif Saja</option>
                            </select>
                        </div>

                        <div>
                            <label for="scheduled_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Jadwal Pengiriman</label>
                            <input 
                                type="datetime-local" 
                                id="scheduled_at" 
                                name="scheduled_at" 
                                value="{{ old('scheduled_at') }}" 
                                class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                            >
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kosongkan untuk kirim langsung</p>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div x-data="{ showPreview: false }">
                    <button 
                        type="button" 
                        @click="showPreview = !showPreview"
                        class="flex items-center text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium"
                    >
                        <svg class="w-4 h-4 mr-1"><use href="#icon-eye"></use></svg>
                        <span x-text="showPreview ? 'Sembunyikan Preview' : 'Tampilkan Preview'"></span>
                    </button>
                    
                    <div x-show="showPreview" x-cloak class="mt-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        <h4 class="font-semibold text-slate-800 dark:text-white mb-2">Preview Pesan</h4>
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            <p id="preview-title" class="font-bold text-lg"></p>
                            <p id="preview-message" class="whitespace-pre-line"></p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.broadcasts.index') }}" class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                            Simpan & Kirim Nanti
                        </button>
                        <button 
                            type="submit" 
                            name="send_now" 
                            value="1"
                            class="px-6 py-3 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300"
                            onclick="return confirm('Kirim broadcast sekarang?')"
                        >
                            Kirim Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </x-admin.card>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const messageInput = document.getElementById('message');
        const previewTitle = document.getElementById('preview-title');
        const previewMessage = document.getElementById('preview-message');
        
        function updatePreview() {
            previewTitle.textContent = titleInput.value || '[Judul Broadcast]';
            previewMessage.textContent = messageInput.value || '[Isi pesan broadcast akan ditampilkan di sini]';
        }
        
        titleInput.addEventListener('input', updatePreview);
        messageInput.addEventListener('input', updatePreview);
        
        updatePreview();
    });
</script>
@endsection
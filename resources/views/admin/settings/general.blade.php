@extends('admin.layouts.app')

@section('title', 'Pengaturan Umum')
@section('breadcrumb', 'Kelola Pengaturan Umum')

@section('actions')
    <button type="submit" form="general-form" 
            class="group inline-flex items-center px-6 py-3 rounded-[1.8rem] bg-emerald-500 hover:bg-emerald-600 text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
        <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-125"><use href="#icon-check"></use></svg>
        <span>Simpan Perubahan</span>
    </button>
@endsection

@section('content')
<div class="max-w-4xl mx-auto pb-20 px-2 lg:px-0">
    <form action="{{ route('admin.settings.update.general') }}" method="POST" id="general-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-8">
            {{-- Branding Section --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-500/10 rounded-2xl">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm lg:text-base font-black text-slate-800 dark:text-white uppercase tracking-tight">Identitas Situs</h3>
                            <p class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Nama & Deskripsi utama toko anda</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 lg:p-8 space-y-6">
                    <div class="group">
                        <label for="site_name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                            Nama Situs <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="site_name" name="site_name" 
                               value="{{ old('site_name', $settings['site_name'] ?? 'My Voucher Store') }}"
                               required
                               class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner"
                               placeholder="Contoh: YogaVoucher">
                    </div>
                    
                    <div class="group">
                        <label for="site_description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                            Tagline / Deskripsi
                        </label>
                        <textarea id="site_description" name="site_description" rows="3"
                                  class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner"
                                  placeholder="Deskripsikan toko voucher anda...">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Media Assets Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Logo Card --}}
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-6 lg:p-8 group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 border-l-4 border-emerald-500 pl-3 leading-none">Site Logo</label>
                    
                    <div class="flex flex-col items-center">
                        <div class="relative w-full aspect-video rounded-[2rem] bg-slate-50 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden mb-6 group-hover:border-emerald-500/50 transition-colors">
                            @if($settings['site_logo'] ?? null)
                                <img src="{{ Storage::url($settings['site_logo']) }}" alt="Logo" class="max-h-24 object-contain">
                            @else
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-2"><use href="#icon-photo"></use></svg>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Belum ada logo</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="w-full">
                            <input type="file" id="site_logo" name="site_logo" accept="image/*" class="hidden" onchange="updateFileName(this, 'logo-name')">
                            <label for="site_logo" class="flex items-center justify-center gap-3 w-full py-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-black uppercase text-[10px] tracking-widest cursor-pointer hover:bg-slate-50 transition-all active:scale-95 shadow-sm">
                                <svg class="w-4 h-4 text-emerald-500"><use href="#icon-arrow-up-tray"></use></svg>
                                <span id="logo-name">Upload Logo Baru</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Favicon Card --}}
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm p-6 lg:p-8 group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 border-l-4 border-violet-500 pl-3 leading-none">Favicon</label>
                    
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 rounded-[1.5rem] bg-slate-50 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden mb-6 group-hover:border-violet-500/50 transition-colors">
                            @if($settings['site_favicon'] ?? null)
                                <img src="{{ Storage::url($settings['site_favicon']) }}" alt="Favicon" class="w-12 h-12 object-contain">
                            @else
                                <svg class="w-8 h-8 text-slate-300"><use href="#icon-bolt"></use></svg>
                            @endif
                        </div>
                        
                        <div class="w-full">
                            <input type="file" id="site_favicon" name="site_favicon" accept="image/*" class="hidden" onchange="updateFileName(this, 'favicon-name')">
                            <label for="site_favicon" class="flex items-center justify-center gap-3 w-full py-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-black uppercase text-[10px] tracking-widest cursor-pointer hover:bg-slate-50 transition-all active:scale-95 shadow-sm">
                                <svg class="w-4 h-4 text-violet-500"><use href="#icon-arrow-up-tray"></use></svg>
                                <span id="favicon-name">Upload Favicon</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Security / Contact Section --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 space-y-6">
                    <div class="group">
                        <label for="admin_email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                            Email Admin <span class="text-[9px] lowercase italic font-medium">(Untuk notifikasi sistem)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="w-5 h-5"><use href="#icon-envelope"></use></svg>
                            </div>
                            <input type="email" id="admin_email" name="admin_email" 
                                   value="{{ old('admin_email', $settings['admin_email'] ?? 'admin@example.com') }}"
                                   class="w-full pl-12 pr-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Submit (Mobile Optimized) --}}
            <div class="lg:hidden">
                <button type="submit" 
                        class="w-full py-5 rounded-[1.8rem] bg-emerald-500 text-white font-black uppercase tracking-[0.2em] text-[11px] shadow-xl shadow-emerald-500/20 active:scale-95 transition-all">
                    Simpan Seluruh Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function updateFileName(input, targetId) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById(targetId).textContent = fileName.length > 20 ? fileName.substring(0, 17) + '...' : fileName;
        }
    }
</script>
@endpush
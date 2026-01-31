@extends('admin.layouts.app')

@section('title', 'Pengaturan Kontak')
@section('breadcrumb', 'Kelola Kontak & Dukungan')

@section('actions')
    <button type="submit" form="contact-form" 
            class="group inline-flex items-center px-5 py-2.5 rounded-2xl bg-slate-900 dark:bg-emerald-500 hover:bg-slate-800 dark:hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-slate-200 dark:shadow-emerald-900/20 transition-all duration-300 active:scale-95">
        <svg class="w-4 h-4 mr-2 transition-transform group-hover:rotate-12"><use href="#icon-check"></use></svg>
        <span>Simpan <span class="hidden sm:inline">Perubahan</span></span>
    </button>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto pb-12">
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[2.5rem] blur opacity-10 group-hover:opacity-15 transition duration-1000"></div>
            
            <x-admin.card class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-900/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-7 h-7"><use href="#icon-phone"></use></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Kontak & Dukungan</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Konfigurasi saluran bantuan pelanggan</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('admin.settings.update.contact') }}" method="POST" id="contact-form" class="p-6 sm:p-8 space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label for="whatsapp" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                                WhatsApp Messenger
                            </label>
                            <div class="relative transition-all duration-300">
                                <input type="text" id="whatsapp" name="whatsapp" 
                                    value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
                                    placeholder="6281234567890"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600">
                            </div>
                            <p class="text-[10px] text-slate-400 font-normal mt-2 ml-1 uppercase tracking-tighter">Gunakan kode negara (Contoh: 628...)</p>
                        </div>
                        
                        <div class="group">
                            <label for="telegram" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-blue-500 transition-colors">
                                Telegram Username
                            </label>
                            <div class="relative transition-all duration-300">
                                <input type="text" id="telegram" name="telegram" 
                                    value="{{ old('telegram', $settings['telegram'] ?? '') }}"
                                    placeholder="username_admin"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-rose-500 transition-colors">
                                Alamat Email Support
                            </label>
                            <input type="email" id="email" name="email" 
                                value="{{ old('email', $settings['email'] ?? '') }}"
                                placeholder="support@domain.com"
                                class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all shadow-inner">
                        </div>
                        
                        <div class="group">
                            <label for="working_hours" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-amber-500 transition-colors">
                                Jam Operasional Kerja
                            </label>
                            <input type="text" id="working_hours" name="working_hours" 
                                value="{{ old('working_hours', $settings['working_hours'] ?? '') }}"
                                placeholder="Senin - Minggu (08:00 - 22:00)"
                                class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all shadow-inner">
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" 
                                class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase text-[10px] tracking-[0.2em] hover:opacity-90 transition-all active:scale-95 shadow-xl shadow-slate-200 dark:shadow-emerald-900/20">
                            Update Pengaturan
                        </button>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
@endsection
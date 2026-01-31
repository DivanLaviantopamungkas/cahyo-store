@extends('admin.layouts.app')

@section('title', 'Pengaturan Sosial Media')
@section('breadcrumb', 'Kelola Media Sosial')

@section('actions')
    <button type="submit" form="social-form" 
            class="group inline-flex items-center px-5 py-2.5 rounded-2xl bg-slate-900 dark:bg-emerald-500 hover:bg-slate-800 dark:hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-slate-200 dark:shadow-emerald-900/20 transition-all duration-300 active:scale-95">
        <svg class="w-4 h-4 mr-2 transition-transform group-hover:rotate-12"><use href="#icon-check"></use></svg>
        <span>Simpan <span class="hidden sm:inline">Perubahan</span></span>
    </button>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto pb-12">
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-violet-500 to-emerald-500 rounded-[2.5rem] blur opacity-10 group-hover:opacity-15 transition duration-1000"></div>
            
            <x-admin.card class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-900/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-600 dark:text-violet-400">
                            <svg class="w-7 h-7"><use href="#icon-share"></use></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Media Sosial</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Kelola tautan profil media sosial Anda</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('admin.settings.update.social') }}" method="POST" id="social-form" class="p-6 sm:p-8 space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Facebook
                            </label>
                            <div class="relative transition-all duration-300">
                                <input type="url" id="facebook" name="facebook" 
                                    value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                                    placeholder="https://facebook.com/username"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600">
                            </div>
                        </div>
                        
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Instagram
                            </label>
                            <div class="relative">
                                <input type="url" id="instagram" name="instagram" 
                                    value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                                    placeholder="https://instagram.com/username"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-pink-500/10 focus:border-pink-500 outline-none transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="twitter" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Twitter / X
                            </label>
                            <div class="relative">
                                <input type="url" id="twitter" name="twitter" 
                                    value="{{ old('twitter', $settings['twitter'] ?? '') }}"
                                    placeholder="https://twitter.com/username"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-slate-500/10 focus:border-slate-400 outline-none transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600">
                            </div>
                        </div>
                        
                        <div>
                            <label for="tiktok" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                TikTok
                            </label>
                            <div class="relative">
                                <input type="url" id="tiktok" name="tiktok" 
                                    value="{{ old('tiktok', $settings['tiktok'] ?? '') }}"
                                    placeholder="https://tiktok.com/@username"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="youtube" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            YouTube
                        </label>
                        <div class="relative">
                            <input type="url" id="youtube" name="youtube" 
                                value="{{ old('youtube', $settings['youtube'] ?? '') }}"
                                placeholder="https://youtube.com/c/username"
                                class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-800 dark:text-white font-bold text-sm focus:ring-4 focus:ring-red-500/10 focus:border-red-600 outline-none transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" 
                                class="w-full sm:w-auto px-10 py-4 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase text-[10px] tracking-[0.2em] hover:opacity-90 transition-all active:scale-95 shadow-xl shadow-slate-200 dark:shadow-emerald-900/20">
                            Update Sosial Media
                        </button>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
@endsection
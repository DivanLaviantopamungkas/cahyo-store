@extends('admin.layouts.app')

@section('title', 'Pengaturan Sosial Media')
@section('breadcrumb', 'Kelola Media Sosial')

@section('actions')
    <button type="submit" form="social-form" 
            class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium sm:font-semibold text-sm shadow-lg hover:shadow-xl transition-all duration-300">
        <svg class="w-4 h-4 mr-1 sm:mr-2"><use href="#icon-check"></use></svg>
        <span class="hidden xs:inline">Simpan Perubahan</span>
        <span class="xs:hidden">Simpan</span>
    </button>
@endsection

@section('content')
<div class="pb-6">
    <x-admin.card class="p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-slate-200 dark:border-slate-700">
            Media Sosial
        </h3>
        
        <form action="{{ route('admin.settings.update.social') }}" method="POST" id="social-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 sm:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Facebook -->
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Facebook
                        </label>
                        <input type="url" id="facebook" name="facebook" 
                               value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                               placeholder="https://facebook.com/username"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                    </div>
                    
                    <!-- Instagram -->
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Instagram
                        </label>
                        <input type="url" id="instagram" name="instagram" 
                               value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                               placeholder="https://instagram.com/username"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Twitter -->
                    <div>
                        <label for="twitter" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Twitter / X
                        </label>
                        <input type="url" id="twitter" name="twitter" 
                               value="{{ old('twitter', $settings['twitter'] ?? '') }}"
                               placeholder="https://twitter.com/username"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                    </div>
                    
                    <!-- TikTok -->
                    <div>
                        <label for="tiktok" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            TikTok
                        </label>
                        <input type="url" id="tiktok" name="tiktok" 
                               value="{{ old('tiktok', $settings['tiktok'] ?? '') }}"
                               placeholder="https://tiktok.com/@username"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                    </div>
                </div>
                
                <!-- YouTube -->
                <div>
                    <label for="youtube" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        YouTube
                    </label>
                    <input type="url" id="youtube" name="youtube" 
                           value="{{ old('youtube', $settings['youtube'] ?? '') }}"
                           placeholder="https://youtube.com/username"
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                </div>
            </div>
            
            <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm sm:text-base shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2"><use href="#icon-check"></use></svg>
                    Simpan Pengaturan Sosial Media
                </button>
            </div>
        </form>
    </x-admin.card>
</div>

@if(session('toast'))
    @include('components.admin.toast')
@endif
@endsection
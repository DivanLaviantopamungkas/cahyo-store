@extends('admin.layouts.app')

@section('title', 'Pengaturan Kontak')
@section('breadcrumb', 'Kelola Kontak & Dukungan')

@section('actions')
    <button type="submit" form="contact-form" 
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
            Kontak dan Dukungan
        </h3>
        
        <form action="{{ route('admin.settings.update.contact') }}" method="POST" id="contact-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 sm:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- WhatsApp -->
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            WhatsApp
                        </label>
                        <input type="text" id="whatsapp" name="whatsapp" 
                               value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
                               placeholder="6281234567890"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Format: 628xxxxxxxxxx</p>
                    </div>
                    
                    <!-- Telegram -->
                    <div>
                        <label for="telegram" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Telegram
                        </label>
                        <input type="text" id="telegram" name="telegram" 
                               value="{{ old('telegram', $settings['telegram'] ?? '') }}"
                               placeholder="@username"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                    </div>
                </div>
                
                <!-- Email Support -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Email Support
                    </label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email', $settings['email'] ?? '') }}"
                           placeholder="support@example.com"
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                </div>
                
                <!-- Working Hours -->
                <div>
                    <label for="working_hours" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Jam Operasional
                    </label>
                    <input type="text" id="working_hours" name="working_hours" 
                           value="{{ old('working_hours', $settings['working_hours'] ?? '') }}"
                           placeholder="09:00 - 17:00"
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                </div>
            </div>
            
            <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm sm:text-base shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2"><use href="#icon-check"></use></svg>
                    Simpan Pengaturan Kontak
                </button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
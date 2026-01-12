@extends('admin.layouts.app')

@section('title', 'Pengaturan Umum')
@section('breadcrumb', 'Kelola Pengaturan Umum')

@section('actions')
    <button type="submit" form="general-form" 
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
            Pengaturan Umum
        </h3>
        
        <form action="{{ route('admin.settings.update.general') }}" method="POST" id="general-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 sm:space-y-6">
                <!-- Site Name -->
                <div>
                    <label for="site_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Nama Situs *
                    </label>
                    <input type="text" id="site_name" name="site_name" 
                           value="{{ old('site_name', $settings['site_name'] ?? 'My Voucher Store') }}"
                           required
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                </div>
                
                <!-- Site Description -->
                <div>
                    <label for="site_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Deskripsi Situs
                    </label>
                    <textarea id="site_description" name="site_description" rows="3"
                              class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                </div>
                
                <!-- Site Logo -->
                <div>
                    <label for="site_logo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Logo Situs
                    </label>
                    @if($settings['site_logo'] ?? null)
                    <div class="mb-3">
                        <img src="{{ Storage::url($settings['site_logo']) }}" alt="Logo" class="h-16 sm:h-20 rounded-lg">
                    </div>
                    @endif
                    <input type="file" id="site_logo" name="site_logo" 
                           accept="image/*"
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ukuran maksimal 2MB. Format: JPG, PNG, GIF, WebP</p>
                </div>
                
                <!-- Favicon -->
                <div>
                    <label for="site_favicon" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Favicon
                    </label>
                    @if($settings['site_favicon'] ?? null)
                    <div class="mb-3">
                        <img src="{{ Storage::url($settings['site_favicon']) }}" alt="Favicon" class="h-8 w-8 sm:h-10 sm:w-10 rounded">
                    </div>
                    @endif
                    <input type="file" id="site_favicon" name="site_favicon" 
                           accept="image/*"
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                </div>
                
                <!-- Admin Email -->
                <div>
                    <label for="admin_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Email Admin
                    </label>
                    <input type="email" id="admin_email" name="admin_email" 
                           value="{{ old('admin_email', $settings['admin_email'] ?? 'admin@example.com') }}"
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                </div>
            </div>
            
            <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm sm:text-base shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2"><use href="#icon-check"></use></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </x-admin.card>
</div>

@if(session('toast'))
    @include('components.admin.toast')
@endif
@endsection
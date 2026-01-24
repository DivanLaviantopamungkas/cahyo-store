@extends('admin.layouts.app')

@section('title', 'Pengaturan Pembayaran')
@section('breadcrumb', 'Kelola Pembayaran')

@section('actions')
    <button type="submit" form="payment-form" 
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
            Pengaturan Pembayaran
        </h3>
        
        <form action="{{ route('admin.settings.update.payment') }}" method="POST" id="payment-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 sm:space-y-6">
                <!-- Auto Approve -->
                <div class="flex items-center justify-between p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700">
                    <div>
                        <h4 class="font-semibold text-slate-800 dark:text-white text-sm sm:text-base">Auto Approve Payment</h4>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                            Otomatis approve transaksi setelah pembayaran
                        </p>
                    </div>
                    <div class="relative inline-block w-10 h-5 sm:w-12 sm:h-6">
                        <input type="checkbox" id="auto_approve" name="auto_approve" value="1"
                               {{ old('auto_approve', $settings['auto_approve'] ?? '1') == '1' ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 h-5 sm:w-12 sm:h-6 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-white transition-transform peer-checked:translate-x-5 sm:peer-checked:translate-x-6"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Expired Time -->
                    <div>
                        <label for="expired_time" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Waktu Kadaluarsa (jam)
                        </label>
                        <input type="number" id="expired_time" name="expired_time" 
                               value="{{ old('expired_time', $settings['expired_time'] ?? '24') }}"
                               min="1" max="168"
                               class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Waktu kadaluarsa transaksi dalam jam (1-168)</p>
                    </div>
                    
                    <!-- Min Deposit -->
                    <div>
                        <label for="min_deposit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Minimal Deposit
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 sm:left-4 top-2.5 sm:top-3 text-slate-500 dark:text-slate-400 text-sm sm:text-base">Rp</span>
                            <input type="number" id="min_deposit" name="min_deposit" 
                                   value="{{ old('min_deposit', $settings['min_deposit'] ?? '10000') }}"
                                   min="1000" step="1000"
                                   class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        </div>
                    </div>
                    
                    <!-- Max Deposit -->
                    <div>
                        <label for="max_deposit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Maksimal Deposit
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 sm:left-4 top-2.5 sm:top-3 text-slate-500 dark:text-slate-400 text-sm sm:text-base">Rp</span>
                            <input type="number" id="max_deposit" name="max_deposit" 
                                   value="{{ old('max_deposit', $settings['max_deposit'] ?? '1000000') }}"
                                   min="10000" step="10000"
                                   class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                        </div>
                    </div>
                </div>
                
                <!-- Fee Settings -->
                <div class="pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="font-semibold text-slate-800 dark:text-white mb-4">Biaya Layanan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="service_fee_percent" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Biaya Layanan (%)
                            </label>
                            <div class="relative">
                                <input type="number" id="service_fee_percent" name="service_fee_percent" 
                                       value="{{ old('service_fee_percent', $settings['service_fee_percent'] ?? '0') }}"
                                       min="0" max="100" step="0.1"
                                       class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                                <span class="absolute right-3 sm:right-4 top-2.5 sm:top-3 text-slate-500 dark:text-slate-400">%</span>
                            </div>
                        </div>
                        
                        <div>
                            <label for="min_service_fee" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Biaya Minimum
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 sm:left-4 top-2.5 sm:top-3 text-slate-500 dark:text-slate-400 text-sm sm:text-base">Rp</span>
                                <input type="number" id="min_service_fee" name="min_service_fee" 
                                       value="{{ old('min_service_fee', $settings['min_service_fee'] ?? '0') }}"
                                       min="0"
                                       class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Instructions -->
                <div class="pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="font-semibold text-slate-800 dark:text-white mb-4">Instruksi Pembayaran</h4>
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Instruksi
                        </label>
                        <textarea id="instructions" name="instructions" rows="4"
                                  class="w-full px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm sm:text-base transition-all">{{ old('instructions', $settings['instructions'] ?? '') }}</textarea>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Instruksi pembayaran yang akan ditampilkan ke pengguna</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm sm:text-base shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2"><use href="#icon-check"></use></svg>
                    Simpan Pengaturan Pembayaran
                </button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
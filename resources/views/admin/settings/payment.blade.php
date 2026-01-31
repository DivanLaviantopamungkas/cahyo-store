@extends('admin.layouts.app')

@section('title', 'Pengaturan Pembayaran')
@section('breadcrumb', 'Kelola Pembayaran')

@section('actions')
    <button type="submit" form="payment-form" 
            class="group inline-flex items-center px-5 py-2.5 rounded-2xl bg-slate-900 dark:bg-emerald-500 hover:bg-slate-800 dark:hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-slate-200 dark:shadow-emerald-900/20 transition-all duration-300 active:scale-95">
        <svg class="w-4 h-4 mr-2 transition-transform group-hover:rotate-12"><use href="#icon-check"></use></svg>
        <span>Simpan <span class="hidden sm:inline">Perubahan</span></span>
    </button>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto pb-12 px-2 lg:px-0">
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[2.5rem] blur opacity-10 group-hover:opacity-15 transition duration-1000"></div>
            
            <x-admin.card class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-900/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-7 h-7"><use href="#icon-credit-card"></use></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Financial System</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Konfigurasi gerbang & parameter transaksi</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('admin.settings.update.payment') }}" method="POST" id="payment-form" class="p-6 sm:p-8 space-y-10">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-6 rounded-[2rem] bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 transition-all hover:border-emerald-500/30">
                            <div>
                                <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Auto Approve Payment</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">
                                    Otomatis approve transaksi setelah pembayaran berhasil diverifikasi
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="auto_approve" name="auto_approve" value="1"
                                    {{ old('auto_approve', $settings['auto_approve'] ?? '1') == '1' ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">Expired Time (Jam)</label>
                            <div class="relative">
                                <input type="number" name="expired_time" value="{{ old('expired_time', $settings['expired_time'] ?? '24') }}" min="1" max="168"
                                    class="w-full px-5 py-4 rounded-2xl border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                            </div>
                        </div>
                        
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">Minimal Deposit</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-xs">Rp</div>
                                <input type="number" name="min_deposit" value="{{ old('min_deposit', $settings['min_deposit'] ?? '10000') }}" min="1000" step="1000"
                                    class="w-full pl-12 pr-5 py-4 rounded-2xl border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                            </div>
                        </div>
                        
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">Maksimal Deposit</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-xs">Rp</div>
                                <input type="number" name="max_deposit" value="{{ old('max_deposit', $settings['max_deposit'] ?? '1000000') }}" min="10000" step="10000"
                                    class="w-full pl-12 pr-5 py-4 rounded-2xl border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-8 border-t border-slate-50 dark:border-slate-700/50">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-[0.2em]">Biaya Layanan System</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Fee Percentage (%)</label>
                                <div class="relative">
                                    <input type="number" name="service_fee_percent" value="{{ old('service_fee_percent', $settings['service_fee_percent'] ?? '0') }}" min="0" max="100" step="0.1"
                                        class="w-full px-5 py-4 rounded-2xl border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner pr-12">
                                    <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-slate-400 font-bold text-sm">%</div>
                                </div>
                            </div>
                            
                            <div class="group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Minimum Fee Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-xs">Rp</div>
                                    <input type="number" name="min_service_fee" value="{{ old('min_service_fee', $settings['min_service_fee'] ?? '0') }}" min="0"
                                        class="w-full pl-12 pr-5 py-4 rounded-2xl border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-8 border-t border-slate-50 dark:border-slate-700/50">
                        <div class="group">
                            <label for="instructions" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-emerald-500 transition-colors">
                                Instruksi Pembayaran (Frontend Display)
                            </label>
                            <textarea id="instructions" name="instructions" rows="5"
                                    class="w-full px-5 py-4 rounded-[1.5rem] border-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500/50 transition-all shadow-inner placeholder:text-slate-300 dark:placeholder:text-slate-600"
                                    placeholder="Masukkan panduan langkah demi langkah untuk pengguna...">{{ old('instructions', $settings['instructions'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 mt-4 border-t border-slate-50 dark:border-slate-700/50">
                        <button type="submit" 
                                class="w-full sm:w-auto px-12 py-4 rounded-2xl bg-slate-900 dark:bg-emerald-500 text-white font-black uppercase text-[10px] tracking-[0.2em] hover:opacity-90 transition-all active:scale-95 shadow-xl shadow-slate-200 dark:shadow-emerald-900/20">
                            Simpan Pengaturan Pembayaran
                        </button>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
@endsection
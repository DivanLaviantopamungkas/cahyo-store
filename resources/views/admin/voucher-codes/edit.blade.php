@extends('admin.layouts.app')

@section('title', 'Edit Voucher Code')

@section('breadcrumb')
    <a href="{{ route('admin.voucher-codes.index') }}" class="hover:text-emerald-500 transition-colors">Voucher Codes</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Edit: <span class="font-mono font-bold">{{ $voucherCode->code }}</span></span>
@endsection

@section('actions')
    <a href="{{ route('admin.voucher-codes.index') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs uppercase tracking-widest transition-all shadow-sm">
        <svg class="w-4 h-4 mr-2 transform rotate-180">
            <use href="#icon-chevron-right"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto pb-20">
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden">
            
            <div class="p-8 lg:p-10 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-violet-500/10 text-violet-500 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6"><use href="#icon-pencil-square"></use></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Edit Voucher</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Perbarui informasi kode voucher dan statusnya.</p>
                    </div>
                </div>
            </div>

            <div x-data="voucherForm()" class="p-8 lg:p-10">
                <form action="{{ route('admin.voucher-codes.update', $voucherCode) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-10">
                        <div class="relative">
                            <div class="absolute -left-3 top-0 bottom-0 w-0.5 bg-slate-100 dark:bg-slate-700 hidden lg:block"></div>
                            
                            <div class="flex items-center gap-3 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-xs">1</span>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Pilih Produk & Nominal</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-2 lg:pl-0">
                                <div class="space-y-2">
                                    <label for="product_id" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Produk *</label>
                                    <div class="relative">
                                        <select id="product_id" name="product_id" required x-model="selectedProductId"
                                            class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold appearance-none cursor-pointer"
                                            @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                            @input="$el.classList.remove('border-rose-500')">
                                            <option value="">Pilih Produk</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ old('product_id', $voucherCode->product_id) == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                            <svg class="w-5 h-5"><use href="#icon-chevron-down"></use></svg>
                                        </div>
                                    </div>
                                    @error('product_id')
                                        <p class="mt-1 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="product_nominal_id" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nominal (Opsional)</label>
                                    <div class="relative">
                                        <select id="product_nominal_id" name="product_nominal_id"
                                            x-bind:disabled="!selectedProductId || currentNominals.length === 0"
                                            x-model="selectedNominalId"
                                            class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                            <option value="">Pilih Nominal (Opsional)</option>
                                            <template x-for="nominal in currentNominals" :key="nominal.id">
                                                <option :value="nominal.id" x-text="nominal.name"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                            <svg class="w-5 h-5"><use href="#icon-chevron-down"></use></svg>
                                        </div>
                                    </div>
                                    @error('product_nominal_id')
                                        <p class="mt-1 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute -left-3 top-0 bottom-0 w-0.5 bg-slate-100 dark:bg-slate-700 hidden lg:block"></div>

                            <div class="flex items-center gap-3 mb-6">
                                <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-xs">2</span>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Informasi Voucher</h3>
                            </div>

                            <div class="pl-2 lg:pl-0 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="code" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Code *</label>
                                    <input type="text" id="code" name="code"
                                        value="{{ old('code', $voucherCode->code) }}" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-emerald-500 transition-all placeholder-slate-400 font-bold"
                                        placeholder="VOUCHER-ABC123"
                                        @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                        @input="$el.classList.remove('border-rose-500')">
                                    @error('code')
                                        <p class="mt-1 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="secret" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Secret (Opsional)</label>
                                    <input type="text" id="secret" name="secret"
                                        value="{{ old('secret', $voucherCode->secret) }}"
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-emerald-500 transition-all placeholder-slate-400 font-bold"
                                        placeholder="Secret key atau PIN">
                                    @error('secret')
                                        <p class="mt-1 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="expired_at" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Tanggal Expired (Opsional)</label>
                                    <input type="datetime-local" id="expired_at" name="expired_at"
                                        value="{{ old('expired_at', $voucherCode->expired_at ? $voucherCode->expired_at->format('Y-m-d\TH:i') : '') }}"
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold">
                                    @error('expired_at')
                                        <p class="mt-1 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="status" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Status</label>
                                    <div class="relative">
                                        <select id="status" name="status"
                                            class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold appearance-none cursor-pointer">
                                            <option value="available" {{ old('status', $voucherCode->status) == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="reserved" {{ old('status', $voucherCode->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                            <option value="sold" {{ old('status', $voucherCode->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                            <option value="expired" {{ old('status', $voucherCode->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                            <svg class="w-5 h-5"><use href="#icon-chevron-down"></use></svg>
                                        </div>
                                    </div>
                                    @error('status')
                                        <p class="mt-1 text-xs font-bold text-rose-500 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-slate-100 dark:border-slate-700">
                            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                                <a href="{{ route('admin.voucher-codes.index') }}"
                                    class="px-6 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-black text-xs uppercase tracking-widest text-center hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-8 py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:scale-[1.02] active:scale-95 transition-all">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function voucherForm() {
            return {
                selectedProductId: '{{ old('product_id', $voucherCode->product_id) }}',
                selectedNominalId: '{{ old('product_nominal_id', $voucherCode->product_nominal_id) }}',
                allNominals: @json($allNominals),
                currentNominals: [],

                init() {
                    // Filter nominals awal berdasarkan produk yang dipilih
                    this.filterNominals();

                    // Watch perubahan produk
                    this.$watch('selectedProductId', (value) => {
                        this.filterNominals();

                        // Reset nominal jika produk berubah dan bukan produk lama
                        if (value != '{{ old('product_id', $voucherCode->product_id) }}') {
                            this.selectedNominalId = '';
                        }
                    });
                },

                filterNominals() {
                    if (this.selectedProductId) {
                        this.currentNominals = this.allNominals.filter(
                            nominal => nominal.product_id == this.selectedProductId
                        );
                    } else {
                        this.currentNominals = [];
                    }
                }
            }
        }
    </script>
@endpush
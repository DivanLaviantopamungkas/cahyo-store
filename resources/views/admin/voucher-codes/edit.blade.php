@extends('admin.layouts.app')

@section('title', 'Edit Voucher Code')
@section('breadcrumb')
    <a href="{{ route('admin.voucher-codes.index') }}">Voucher Codes</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Edit: {{ $voucherCode->code }}</span>
@endsection

@section('actions')
    <a href="{{ route('admin.voucher-codes.index') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
        <svg class="w-4 h-4 mr-2">
            <use href="#icon-chevron-right"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="max-w-2xl">
        <x-admin.card>
            <div x-data="voucherForm()">
                <form action="{{ route('admin.voucher-codes.update', $voucherCode) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Product & Nominal Selection -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pilih Produk & Nominal
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="product_id"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Produk
                                        *</label>
                                    <select id="product_id" name="product_id" required x-model="selectedProductId"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
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
                                    @error('product_id')
                                        <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="product_nominal_id"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nominal
                                        (Opsional)</label>
                                    <select id="product_nominal_id" name="product_nominal_id"
                                        x-bind:disabled="!selectedProductId || currentNominals.length === 0"
                                        x-model="selectedNominalId"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        <option value="">Pilih Nominal (Opsional)</option>
                                        <template x-for="nominal in currentNominals" :key="nominal.id">
                                            <option :value="nominal.id" x-text="nominal.name"></option>
                                        </template>
                                    </select>
                                    @error('product_nominal_id')
                                        <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Voucher Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Informasi Voucher</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="code"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Code
                                        *</label>
                                    <input type="text" id="code" name="code"
                                        value="{{ old('code', $voucherCode->code) }}" required
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="VOUCHER-ABC123"
                                        @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                        @input="$el.classList.remove('border-rose-500')">
                                    @error('code')
                                        <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="secret"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Secret
                                        (Opsional)</label>
                                    <input type="text" id="secret" name="secret"
                                        value="{{ old('secret', $voucherCode->secret) }}"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                        placeholder="Secret key atau PIN">
                                    @error('secret')
                                        <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="expired_at"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tanggal
                                        Expired (Opsional)</label>
                                    <input type="datetime-local" id="expired_at" name="expired_at"
                                        value="{{ old('expired_at', $voucherCode->expired_at ? $voucherCode->expired_at->format('Y-m-d\TH:i') : '') }}"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    @error('expired_at')
                                        <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                                    <select id="status" name="status"
                                        class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                        <option value="available"
                                            {{ old('status', $voucherCode->status) == 'available' ? 'selected' : '' }}>
                                            Available</option>
                                        <option value="reserved"
                                            {{ old('status', $voucherCode->status) == 'reserved' ? 'selected' : '' }}>
                                            Reserved</option>
                                        <option value="sold"
                                            {{ old('status', $voucherCode->status) == 'sold' ? 'selected' : '' }}>Sold
                                        </option>
                                        <option value="expired"
                                            {{ old('status', $voucherCode->status) == 'expired' ? 'selected' : '' }}>
                                            Expired</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.voucher-codes.index') }}"
                                    class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                    Update Voucher
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </x-admin.card>
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

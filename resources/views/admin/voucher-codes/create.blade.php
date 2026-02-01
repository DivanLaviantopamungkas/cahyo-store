@extends('admin.layouts.app')

@section('title', 'Tambah Kode Voucher Massal')

@section('breadcrumb')
    <a href="{{ route('admin.voucher-codes.index') }}" class="hover:text-emerald-500 transition-colors">Voucher Codes</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Tambah Massal</span>
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
            <form action="{{ route('admin.voucher-codes.store') }}" method="POST" enctype="multipart/form-data" class="p-8 lg:p-10">
                @csrf

                @if ($errors->any())
                    <div class="mb-8 p-5 rounded-3xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 flex gap-4">
                        <div class="shrink-0 text-rose-500">
                            <svg class="w-6 h-6"><use href="#icon-exclamation-circle"></use></svg>
                        </div>
                        <div>
                            <h4 class="text-rose-700 dark:text-rose-400 font-bold text-sm uppercase tracking-wide mb-2">Terdapat Kesalahan</h4>
                            <ul class="list-disc list-inside text-sm text-rose-600 dark:text-rose-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

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
                                    <select id="product_id" name="product_id" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold appearance-none cursor-pointer">
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                        <svg class="w-5 h-5"><use href="#icon-chevron-down"></use></svg>
                                    </div>
                                </div>
                                @error('product_id') <span class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div id="nominal-section" class="hidden space-y-2">
                                <label for="product_nominal_id" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nominal *</label>
                                <div class="relative">
                                    <select id="product_nominal_id" name="product_nominal_id" required
                                        class="block w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-none text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-all font-bold appearance-none cursor-pointer">
                                        <option value="">Pilih Nominal</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                        <svg class="w-5 h-5"><use href="#icon-chevron-down"></use></svg>
                                    </div>
                                </div>
                                @error('product_nominal_id') <span class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div id="code-section" class="hidden relative">
                        <div class="absolute -left-3 top-0 bottom-0 w-0.5 bg-slate-100 dark:bg-slate-700 hidden lg:block"></div>

                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-xs">2</span>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Input Kode Voucher</h3>
                        </div>

                        <div class="pl-2 lg:pl-0 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" id="method-upload" name="code_method" value="upload" class="peer sr-only" {{ old('code_method') == 'upload' ? 'checked' : '' }}>
                                    <div class="p-5 rounded-3xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 dark:peer-checked:bg-emerald-900/20 transition-all hover:border-slate-300 dark:hover:border-slate-600">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-500 peer-checked:bg-emerald-500 peer-checked:text-white flex items-center justify-center transition-colors">
                                                <svg class="w-5 h-5"><use href="#icon-document-text"></use></svg>
                                            </div>
                                            <div>
                                                <span class="block text-sm font-black text-slate-800 dark:text-white uppercase tracking-wide">Upload File TXT</span>
                                                <span class="block text-xs text-slate-500 dark:text-slate-400 mt-1">Upload file berisi daftar kode</span>
                                            </div>
                                            <div class="ml-auto opacity-0 peer-checked:opacity-100 text-emerald-500">
                                                <svg class="w-6 h-6"><use href="#icon-check-circle"></use></svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer group">
                                    <input type="radio" id="method-manual" name="code_method" value="manual" class="peer sr-only" {{ old('code_method') == 'manual' ? 'checked' : '' }}>
                                    <div class="p-5 rounded-3xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 dark:peer-checked:bg-emerald-900/20 transition-all hover:border-slate-300 dark:hover:border-slate-600">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-violet-50 dark:bg-slate-700 text-violet-500 peer-checked:bg-emerald-500 peer-checked:text-white flex items-center justify-center transition-colors">
                                                <svg class="w-5 h-5"><use href="#icon-pencil-square"></use></svg>
                                            </div>
                                            <div>
                                                <span class="block text-sm font-black text-slate-800 dark:text-white uppercase tracking-wide">Input Manual</span>
                                                <span class="block text-xs text-slate-500 dark:text-slate-400 mt-1">Copy-paste kode langsung</span>
                                            </div>
                                            <div class="ml-auto opacity-0 peer-checked:opacity-100 text-emerald-500">
                                                <svg class="w-6 h-6"><use href="#icon-check-circle"></use></svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div id="upload-section" class="hidden">
                                <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-[2rem] border border-slate-100 dark:border-slate-700">
                                    <label for="code_file" class="flex flex-col items-center justify-center w-full h-auto min-h-[10rem] py-8 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl cursor-pointer hover:border-emerald-500 dark:hover:border-emerald-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all group">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-500 transition-colors"><use href="#icon-arrow-up-tray"></use></svg>
                                            </div>
                                            
                                            <p class="mb-2 text-sm text-slate-500 dark:text-slate-400 font-medium text-center px-4 leading-relaxed">
                                                <span class="font-bold text-slate-700 dark:text-slate-200">Klik untuk upload</span> 
                                                <span class="hidden sm:inline">atau drag and drop</span>
                                            </p>
                                            
                                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">File .TXT (Max 10MB)</p>
                                        </div>
                                        <input type="file" id="code_file" name="code_file" accept=".txt" class="hidden" />
                                    </label>
                                    
                                    <div id="file-preview" class="mt-6 hidden">
                                        <div class="flex items-center justify-between mb-3 px-1">
                                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Preview Kode</span>
                                            <span id="code-count" class="px-2 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wide"></span>
                                        </div>
                                        <div id="code-preview" class="max-h-48 overflow-y-auto bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 font-mono text-xs text-slate-600 dark:text-slate-300 space-y-1">
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <div id="manual-section" class="hidden">
                                <div class="relative">
                                    <label for="codes_input" class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 block mb-2">Masukkan Kode (Satu per baris)</label>
                                    <textarea id="codes_input" name="codes_input" rows="8"
                                        class="block w-full px-5 py-4 rounded-3xl bg-slate-50 dark:bg-slate-900 border-none text-slate-800 dark:text-white font-mono text-sm focus:ring-2 focus:ring-emerald-500 transition-all placeholder-slate-400"
                                        placeholder="CODE123&#10;CODE456&#10;CODE789">{{ old('codes_input') }}</textarea>
                                    
                                    <div id="manual-code-count" class="absolute bottom-4 right-4 hidden">
                                        <span class="px-3 py-1.5 bg-slate-200 dark:bg-slate-700 rounded-xl text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide">
                                            Total: <span id="manual-count">0</span>
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-3 font-medium ml-1">
                                    <svg class="w-3 h-3 inline mr-1"><use href="#icon-information-circle"></use></svg>
                                    Tekan Enter untuk membuat baris baru untuk setiap kode.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="submit-section" class="hidden pt-8 border-t border-slate-100 dark:border-slate-700">
                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('admin.voucher-codes.index') }}"
                                class="px-6 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-black text-xs uppercase tracking-widest text-center hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors">
                                Batal
                            </a>
                            <button type="submit" id="submit-button"
                                class="px-8 py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                                <span id="submit-text" class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2"><use href="#icon-check"></use></svg>
                                    Simpan Kode Voucher
                                </span>
                                <span id="loading-text" class="hidden">
                                    <svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const nominalSection = document.getElementById('nominal-section');
            const codeSection = document.getElementById('code-section');
            const submitSection = document.getElementById('submit-section');
            const nominalSelect = document.getElementById('product_nominal_id');
            const codeMethods = document.querySelectorAll('input[name="code_method"]');
            const uploadSection = document.getElementById('upload-section');
            const manualSection = document.getElementById('manual-section');
            const codeFileInput = document.getElementById('code_file');
            const filePreview = document.getElementById('file-preview');
            const codePreview = document.getElementById('code-preview');
            const codeCount = document.getElementById('code-count');
            const codesInput = document.getElementById('codes_input');
            const manualCodeCount = document.getElementById('manual-code-count');
            const manualCount = document.getElementById('manual-count');
            const submitButton = document.getElementById('submit-button');
            const submitText = document.getElementById('submit-text');
            const loadingText = document.getElementById('loading-text');

            // Handle product selection change
            productSelect.addEventListener('change', async function() {
                const productId = this.value;

                // Reset
                nominalSection.classList.add('hidden');
                codeSection.classList.add('hidden');
                submitSection.classList.add('hidden');
                nominalSelect.innerHTML = '<option value="">Loading...</option>';
                nominalSelect.disabled = true;
                codeFileInput.value = '';
                filePreview.classList.add('hidden');
                codesInput.value = '';
                manualCodeCount.classList.add('hidden');
                document.querySelectorAll('input[name="code_method"]').forEach(radio => {
                    radio.checked = false;
                });
                uploadSection.classList.add('hidden');
                manualSection.classList.add('hidden');

                if (productId) {
                    try {
                        const response = await fetch(`/admin/voucher-codes/nominals/${productId}`);
                        const nominals = await response.json();

                        if (nominals.length > 0) {
                            nominalSelect.innerHTML = '<option value="">Pilih Nominal</option>';
                            nominals.forEach(nominal => {
                                const option = document.createElement('option');
                                option.value = nominal.id;
                                option.textContent = `${nominal.name} - Rp ${formatNumber(nominal.price)}`;
                                if (nominal.discount_price) {
                                    option.textContent += ` (Diskon: Rp ${formatNumber(nominal.discount_price)})`;
                                }
                                nominalSelect.appendChild(option);
                            });
                            nominalSelect.disabled = false;
                            nominalSection.classList.remove('hidden');
                        } else {
                            alert('Produk ini belum memiliki nominal.');
                            productSelect.value = '';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Gagal memuat data nominal');
                    }
                }
            });

            // Handle nominal selection change
            nominalSelect.addEventListener('change', function() {
                if (this.value) {
                    codeSection.classList.remove('hidden');
                    submitSection.classList.remove('hidden');
                    if (!document.querySelector('input[name="code_method"]:checked')) {
                        document.getElementById('method-upload').checked = true;
                        uploadSection.classList.remove('hidden');
                    }
                } else {
                    codeSection.classList.add('hidden');
                    submitSection.classList.add('hidden');
                }
            });

            // Handle code method selection
            codeMethods.forEach(method => {
                method.addEventListener('change', function() {
                    uploadSection.classList.add('hidden');
                    manualSection.classList.add('hidden');

                    if (this.value === 'upload') {
                        uploadSection.classList.remove('hidden');
                        codeFileInput.setAttribute('required', 'required');
                        codesInput.removeAttribute('required');
                    } else if (this.value === 'manual') {
                        manualSection.classList.remove('hidden');
                        updateManualCodeCount();
                        codeFileInput.removeAttribute('required');
                        codesInput.setAttribute('required', 'required');
                    }
                });
            });

            // Handle file upload preview
            codeFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 10 * 1024 * 1024) {
                        alert('Ukuran file maksimal 10MB');
                        this.value = '';
                        filePreview.classList.add('hidden');
                        return;
                    }
                    if (file.type !== 'text/plain' && !file.name.toLowerCase().endsWith('.txt')) {
                        alert('Hanya file TXT yang diperbolehkan');
                        this.value = '';
                        filePreview.classList.add('hidden');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const content = e.target.result;
                        const lines = content.split('\n').map(line => line.trim()).filter(line => line.length > 0);

                        if (lines.length === 0) {
                            alert('File kosong');
                            codeFileInput.value = '';
                            filePreview.classList.add('hidden');
                            return;
                        }
                        if (lines.length > 10000) {
                            alert('Maksimal 10000 kode per batch.');
                            codeFileInput.value = '';
                            filePreview.classList.add('hidden');
                            return;
                        }

                        codePreview.innerHTML = lines.slice(0, 50).map(line => 
                            `<div class="py-1.5 px-3 mb-1 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-100 dark:border-slate-700 font-mono text-xs font-bold text-slate-600 dark:text-slate-300 truncate">${line}</div>`
                        ).join('');

                        if (lines.length > 50) {
                            codePreview.innerHTML += `<div class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest py-2">... dan ${lines.length - 50} kode lainnya</div>`;
                        }

                        codeCount.textContent = `${lines.length} KODE DITEMUKAN`;
                        filePreview.classList.remove('hidden');
                    };
                    reader.readAsText(file);
                } else {
                    filePreview.classList.add('hidden');
                }
            });

            // Count manual input codes
            codesInput.addEventListener('input', updateManualCodeCount);

            function updateManualCodeCount() {
                const codes = codesInput.value.split('\n').map(line => line.trim()).filter(line => line.length > 0);
                if (codes.length > 0) {
                    manualCount.textContent = codes.length;
                    manualCodeCount.classList.remove('hidden');
                } else {
                    manualCodeCount.classList.add('hidden');
                }
            }

            // Handle form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const productId = productSelect.value;
                const nominalId = nominalSelect.value;
                const codeMethod = document.querySelector('input[name="code_method"]:checked')?.value;

                if (!productId || !nominalId || !codeMethod) {
                    e.preventDefault();
                    alert('Harap lengkapi semua field');
                    return;
                }
                if (codeMethod === 'upload' && !codeFileInput.files[0]) {
                    e.preventDefault();
                    alert('Harap pilih file');
                    return;
                }
                if (codeMethod === 'manual' && !codesInput.value.trim()) {
                    e.preventDefault();
                    alert('Harap masukkan kode');
                    return;
                }

                submitButton.disabled = true;
                submitText.classList.add('hidden');
                loadingText.classList.remove('hidden');
            });

            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Initialize old data
            if (productSelect.value) {
                productSelect.dispatchEvent(new Event('change'));
                setTimeout(() => {
                    if ('{{ old('product_nominal_id') }}') {
                        nominalSelect.value = '{{ old('product_nominal_id') }}';
                        nominalSelect.dispatchEvent(new Event('change'));
                    }
                    if ('{{ old('code_method') }}' === 'manual') {
                        document.getElementById('method-manual').checked = true;
                        manualSection.classList.remove('hidden');
                        updateManualCodeCount();
                    } else if ('{{ old('code_method') }}' === 'upload') {
                        document.getElementById('method-upload').checked = true;
                        uploadSection.classList.remove('hidden');
                    }
                }, 500);
            }
        });
    </script>
@endsection
@extends('admin.layouts.app')

@section('title', 'Tambah Kode Voucher Massal')
@section('breadcrumb')
    <a href="{{ route('admin.voucher-codes.index') }}">Voucher Codes</a>
    <svg class="w-4 h-4 text-slate-400">
        <use href="#icon-chevron-right"></use>
    </svg>
    <span class="text-slate-600 dark:text-slate-300">Tambah Massal</span>
@endsection

@section('actions')
    <a href="{{ route('admin.voucher-codes.index') }}"
        class="inline-flex items-center px-4 py-2 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
        <svg class="w-4 h-4 mr-2">
            <use href="#icon-chevron-left"></use>
        </svg>
        Kembali
    </a>
@endsection

@section('content')
    <div class="max-w-3xl">
        <x-admin.card>
            <form action="{{ route('admin.voucher-codes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div
                        class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800">
                        <h4 class="text-rose-700 dark:text-rose-300 font-semibold mb-2">Terdapat kesalahan:</h4>
                        <ul class="list-disc list-inside text-sm text-rose-600 dark:text-rose-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Product Selection -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Pilih Produk & Nominal</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="product_id"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Produk
                                    *</label>
                                <select id="product_id" name="product_id" required
                                    class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('product_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Nominal Selection -->
                            <div id="nominal-section" class="hidden">
                                <div>
                                    <label for="product_nominal_id"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pilih
                                        Nominal *</label>
                                    <select id="product_nominal_id" name="product_nominal_id" required
                                        class="block w-full px-4 py-3 rounded-2xl border {{ $errors->has('product_nominal_id') ? 'border-rose-500' : 'border-slate-300 dark:border-slate-600' }} bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                        <option value="">Pilih Nominal</option>
                                    </select>
                                    @error('product_nominal_id')
                                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Code Input Section -->
                    <div id="code-section" class="hidden">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Input Kode</h3>
                        <div class="space-y-6">
                            <!-- Upload File Method -->
                            <div class="border border-slate-300 dark:border-slate-600 rounded-2xl p-4">
                                <div class="flex items-center mb-4">
                                    <input type="radio" id="method-upload" name="code_method" value="upload"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded"
                                        {{ old('code_method') == 'upload' ? 'checked' : '' }}>
                                    <label for="method-upload"
                                        class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Upload File TXT
                                    </label>
                                </div>

                                <div id="upload-section" class="hidden pl-6">
                                    <div class="mb-4">
                                        <label for="code_file"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            File Kode (.txt) *
                                        </label>
                                        <input type="file" id="code_file" name="code_file" accept=".txt"
                                            class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-900/30 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 dark:hover:file:bg-emerald-900/50">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                            Format: satu kode per baris. Maksimal 10MB
                                        </p>
                                        <div id="file-preview" class="mt-4 hidden">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Preview
                                                    kode:</span>
                                                <span id="code-count" class="text-xs text-slate-500"></span>
                                            </div>
                                            <div id="code-preview"
                                                class="max-h-60 overflow-y-auto border border-slate-200 dark:border-slate-700 rounded-xl p-3 bg-slate-50 dark:bg-slate-800/50 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Input Method -->
                            <div class="border border-slate-300 dark:border-slate-600 rounded-2xl p-4">
                                <div class="flex items-center mb-4">
                                    <input type="radio" id="method-manual" name="code_method" value="manual"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded"
                                        {{ old('code_method') == 'manual' ? 'checked' : '' }}>
                                    <label for="method-manual"
                                        class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Input Manual
                                    </label>
                                </div>

                                <div id="manual-section" class="hidden pl-6">
                                    <div class="mb-4">
                                        <label for="codes_input"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            Kode (satu per baris) *
                                        </label>
                                        <textarea id="codes_input" name="codes_input" rows="6"
                                            class="block w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                            placeholder="Masukkan kode, satu per baris. Contoh:&#10;CODE123&#10;CODE456&#10;CODE789">{{ old('codes_input') }}</textarea>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                            Tekan Enter untuk baris baru.
                                        </p>
                                        <div id="manual-code-count" class="mt-2 hidden">
                                            <span class="text-xs text-slate-500">Total kode: <span
                                                    id="manual-count">0</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div id="submit-section" class="hidden pt-6 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.voucher-codes.index') }}"
                                class="px-6 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors">
                                Batal
                            </a>
                            <button type="submit" id="submit-button"
                                class="px-6 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="submit-text">Simpan Kode</span>
                                <span id="loading-text" class="hidden">
                                    <svg class="animate-spin h-5 w-5 inline-block mr-2" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </x-admin.card>
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

            // Cache untuk menyimpan data nominal per produk
            const nominalsCache = {};

            // Handle product selection change
            productSelect.addEventListener('change', async function() {
                const productId = this.value;

                // Reset semua section
                nominalSection.classList.add('hidden');
                codeSection.classList.add('hidden');
                submitSection.classList.add('hidden');
                nominalSelect.innerHTML = '<option value="">Loading...</option>';
                nominalSelect.disabled = true;

                // Reset file input
                codeFileInput.value = '';
                filePreview.classList.add('hidden');

                // Reset textarea
                codesInput.value = '';
                manualCodeCount.classList.add('hidden');

                // Reset radio buttons
                document.querySelectorAll('input[name="code_method"]').forEach(radio => {
                    radio.checked = false;
                });
                uploadSection.classList.add('hidden');
                manualSection.classList.add('hidden');

                if (productId) {
                    try {
                        // Load nominals untuk produk ini
                        const response = await fetch(`/admin/voucher-codes/nominals/${productId}`);
                        const nominals = await response.json();

                        if (nominals.length > 0) {
                            // Populate nominal dropdown
                            nominalSelect.innerHTML = '<option value="">Pilih Nominal</option>';
                            nominals.forEach(nominal => {
                                const option = document.createElement('option');
                                option.value = nominal.id;
                                option.textContent =
                                    `${nominal.name} - Rp ${formatNumber(nominal.price)}`;
                                if (nominal.discount_price) {
                                    option.textContent +=
                                        ` (Diskon: Rp ${formatNumber(nominal.discount_price)})`;
                                }
                                nominalSelect.appendChild(option);
                            });

                            nominalSelect.disabled = false;
                            // Show nominal section
                            nominalSection.classList.remove('hidden');
                        } else {
                            alert(
                                'Produk ini belum memiliki nominal. Silahkan buat nominal terlebih dahulu.'
                            );
                            productSelect.value = '';
                        }
                    } catch (error) {
                        console.error('Error loading nominals:', error);
                        alert('Gagal memuat data nominal');
                        productSelect.value = '';
                    }
                }
            });

            // Handle nominal selection change
            nominalSelect.addEventListener('change', function() {
                if (this.value) {
                    codeSection.classList.remove('hidden');
                    submitSection.classList.remove('hidden');

                    // Set default method jika belum dipilih
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
                    } else if (this.value === 'manual') {
                        manualSection.classList.remove('hidden');
                        updateManualCodeCount();
                    }

                    // Update required attribute
                    if (this.value === 'upload') {
                        codeFileInput.setAttribute('required', 'required');
                        codesInput.removeAttribute('required');
                    } else {
                        codeFileInput.removeAttribute('required');
                        codesInput.setAttribute('required', 'required');
                    }
                });
            });

            // Handle file upload preview
            codeFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validasi ukuran file (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        alert('Ukuran file maksimal 10MB');
                        this.value = '';
                        filePreview.classList.add('hidden');
                        return;
                    }

                    // Validasi tipe file
                    if (file.type !== 'text/plain' && !file.name.toLowerCase().endsWith('.txt')) {
                        alert('Hanya file TXT yang diperbolehkan');
                        this.value = '';
                        filePreview.classList.add('hidden');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const content = e.target.result;
                        const lines = content.split('\n')
                            .map(line => line.trim())
                            .filter(line => line.length > 0);

                        if (lines.length === 0) {
                            alert('File kosong atau tidak mengandung kode yang valid');
                            codeFileInput.value = '';
                            filePreview.classList.add('hidden');
                            return;
                        }

                        // Validasi jumlah kode (maksimal 10000)
                        if (lines.length > 10000) {
                            alert(
                                `Maksimal 10000 kode per batch. File Anda berisi ${lines.length} kode.`);
                            codeFileInput.value = '';
                            filePreview.classList.add('hidden');
                            return;
                        }

                        codePreview.innerHTML = lines.slice(0, 50)
                            .map(line =>
                                `<div class="py-1 px-2 mb-1 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 font-mono text-xs">${line}</div>`
                            )
                            .join('');

                        if (lines.length > 50) {
                            codePreview.innerHTML +=
                                `<div class="text-center text-sm text-slate-500 py-2">... dan ${lines.length - 50} kode lainnya</div>`;
                        }

                        codeCount.textContent = `${lines.length} kode ditemukan`;
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
                const codes = codesInput.value.split('\n')
                    .map(line => line.trim())
                    .filter(line => line.length > 0);

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
                    alert('Harap lengkapi semua field yang diperlukan');
                    return;
                }

                if (codeMethod === 'upload' && !codeFileInput.files[0]) {
                    e.preventDefault();
                    alert('Harap pilih file untuk diupload');
                    return;
                }

                if (codeMethod === 'manual' && !codesInput.value.trim()) {
                    e.preventDefault();
                    alert('Harap masukkan kode');
                    return;
                }

                // Show loading state
                submitButton.disabled = true;
                submitText.classList.add('hidden');
                loadingText.classList.remove('hidden');
            });

            // Format number dengan separator ribuan
            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Initialize jika ada data old
            if (productSelect.value) {
                productSelect.dispatchEvent(new Event('change'));

                // Set nominal jika ada old value
                setTimeout(() => {
                    if ('{{ old('product_nominal_id') }}') {
                        nominalSelect.value = '{{ old('product_nominal_id') }}';
                        nominalSelect.dispatchEvent(new Event('change'));
                    }

                    // Set method jika ada old value
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

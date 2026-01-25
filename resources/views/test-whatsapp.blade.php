<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing WhatsApp Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #25D366, #128C7E);
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .whatsapp-color {
            color: #25D366;
        }

        .btn-whatsapp {
            background: #25D366;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-whatsapp:hover {
            background: #128C7E;
            transform: translateY(-2px);
        }

        .status-success {
            color: #25D366;
            font-weight: bold;
        }

        .status-failed {
            color: #dc3545;
            font-weight: bold;
        }

        .phone-input {
            font-family: monospace;
            font-size: 18px;
        }

        .message-box {
            min-height: 120px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .test-results {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="text-white fw-bold">
                        <i class="bi bi-whatsapp"></i> WhatsApp Service Testing
                    </h1>
                    <p class="text-white">Test WhatsApp notification dari sistem Anda</p>
                </div>

                <!-- Main Card -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="mb-0 whatsapp-color">
                            <i class="bi bi-send-check"></i> Kirim Pesan Test
                        </h4>
                    </div>

                    <div class="card-body">
                        <!-- Form Testing -->
                        <form id="whatsappTestForm">
                            @csrf

                            <!-- Nomor Tujuan -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-phone"></i> Nomor WhatsApp
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" class="form-control phone-input" name="phone"
                                        placeholder="81234567890" value="{{ old('phone', '81234567890') }}" required>
                                </div>
                                <small class="text-muted">Contoh: 81234567890 (tanpa +62)</small>
                            </div>

                            <!-- Pesan -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-chat-text"></i> Pesan
                                </label>
                                <textarea class="form-control message-box" name="message" rows="4" placeholder="Tulis pesan yang ingin dikirim..."
                                    required>🚀 Testing WhatsApp Service
📱 Dari: {{ config('app.name') }}
⏰ Waktu: {{ now()->format('d/m/Y H:i:s') }}
✅ Ini adalah pesan testing</textarea>
                            </div>

                            <!-- Template Pesan Cepat -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-lightning"></i> Template Cepat
                                </label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-success btn-sm template-btn"
                                        data-template="payment">
                                        💳 Pembayaran Berhasil
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm template-btn"
                                        data-template="voucher">
                                        🎫 Kode Voucher
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm template-btn"
                                        data-template="reminder">
                                        ⏰ Pengingat
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm template-btn"
                                        data-template="error">
                                        ❌ Error System
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-whatsapp btn-lg" id="submitBtn">
                                    <i class="bi bi-send"></i> Kirim Pesan Test
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="testFormatBtn">
                                    <i class="bi bi-gear"></i> Test Format Nomor
                                </button>
                            </div>
                        </form>

                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="text-center mt-4 d-none">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Mengirim pesan ke WhatsApp...</p>
                        </div>

                        <!-- Results Section -->
                        <div id="results" class="mt-4 d-none">
                            <div class="test-results">
                                <h5 class="whatsapp-color">
                                    <i class="bi bi-clipboard-check"></i> Hasil Testing
                                </h5>
                                <div id="resultContent"></div>
                            </div>
                        </div>

                        <!-- Phone Format Results -->
                        <div id="formatResults" class="mt-4 d-none">
                            <div class="test-results">
                                <h5 class="text-primary">
                                    <i class="bi bi-phone"></i> Hasil Format Nomor
                                </h5>
                                <div id="formatResultContent"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Card -->
                    <div class="card-footer text-muted">
                        <div class="row">
                            <div class="col-md-6">
                                <small>
                                    <i class="bi bi-info-circle"></i>
                                    Service:
                                    {{ config('services.whatsapp.phone_number_id') ? '✅ Terkonfigurasi' : '❌ Belum dikonfigurasi' }}
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small>
                                    <i class="bi bi-clock"></i>
                                    {{ now()->format('d/m/Y H:i:s') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="fw-bold">
                            <i class="bi bi-question-circle"></i> Cara Penggunaan
                        </h6>
                        <ol class="mb-0">
                            <li>Masukkan nomor WhatsApp tujuan (contoh: 81234567890)</li>
                            <li>Tulis pesan atau pilih template cepat</li>
                            <li>Klik "Kirim Pesan Test"</li>
                            <li>Tunggu hasil pengiriman</li>
                            <li>Periksa log di <code>storage/logs/laravel.log</code></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Template pesan
        const templates = {
            payment: `✅ *PEMBAYARAN BERHASIL*

Invoice: INV-TEST-12345
Produk: Pulsa 50.000
Status: Sukses
Waktu: {{ now()->format('d/m/Y H:i:s') }}

Terima kasih telah berbelanja!`,

            voucher: `🎫 *KODE VOUCHER ANDA*

Kode: VOUCH-{{ strtoupper(\Illuminate\Support\Str::random(10)) }}
Produk: {{ config('app.name') }} Premium
Expired: {{ now()->addDays(30)->format('d F Y') }}

Simpan kode ini untuk klaim.`,

            reminder: `⏰ *PENGINGAT TRANSAKSI*

Anda memiliki transaksi yang belum selesai:
Invoice: INV-PENDING-001
Jumlah: Rp 50.000
Batas waktu: {{ now()->addHours(1)->format('H:i') }}

Segera selesaikan pembayaran.`,

            error: `❌ *NOTIFIKASI ERROR*

Sistem mendeteksi masalah:
Service: WhatsApp API
Error: Timeout connection
Waktu: {{ now()->format('H:i:s') }}

Mohon cek sistem.`
        };

        // Template button click
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const template = this.dataset.template;
                document.querySelector('[name="message"]').value = templates[template];
            });
        });

        // Test format nomor
        document.getElementById('testFormatBtn').addEventListener('click', function() {
            const phone = document.querySelector('[name="phone"]').value;

            fetch('/api/test-format-phone', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        phone: phone
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const formatDiv = document.getElementById('formatResults');
                    const contentDiv = document.getElementById('formatResultContent');

                    contentDiv.innerHTML = `
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Input:</strong></td>
                            <td><code>${data.original}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Formatted:</strong></td>
                            <td><code class="${data.valid ? 'text-success' : 'text-danger'}">${data.formatted}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                ${data.valid ?
                                    '<span class="badge bg-success">Valid</span>' :
                                    '<span class="badge bg-danger">Invalid</span>'}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Pola:</strong></td>
                            <td><small>${data.pattern}</small></td>
                        </tr>
                    </table>
                `;

                    formatDiv.classList.remove('d-none');
                });
        });

        // Kirim pesan form
        document.getElementById('whatsappTestForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const resultsDiv = document.getElementById('results');
            const resultContent = document.getElementById('resultContent');

            // Show loading, hide button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
            loadingSpinner.classList.remove('d-none');
            resultsDiv.classList.add('d-none');

            // Collect form data
            const formData = new FormData(this);

            // Send request
            fetch('/api/test-whatsapp-send', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading
                    loadingSpinner.classList.add('d-none');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send"></i> Kirim Pesan Test';

                    // Show results
                    let resultHtml = '';

                    if (data.success) {
                        resultHtml = `
                        <div class="alert alert-success">
                            <h6><i class="bi bi-check-circle"></i> Pesan Terkirim!</h6>
                            <p>${data.message}</p>
                            <hr>
                            <p class="mb-0">
                                <strong>Detail:</strong><br>
                                📱 Ke: ${data.phone}<br>
                                ⏰ Waktu: ${data.timestamp}<br>
                                📝 Panjang: ${data.message_length} karakter
                            </p>
                        </div>
                    `;
                    } else {
                        resultHtml = `
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-x-circle"></i> Gagal Mengirim</h6>
                            <p>${data.message}</p>
                            ${data.error ? `<small class="text-muted">Error: ${data.error}</small>` : ''}
                            <hr>
                            <p class="mb-0">
                                <strong>Tips:</strong><br>
                                1. Cek koneksi internet<br>
                                2. Verifikasi nomor WhatsApp<br>
                                3. Periksa log sistem
                            </p>
                        </div>
                    `;
                    }

                    resultContent.innerHTML = resultHtml;
                    resultsDiv.classList.remove('d-none');

                    // Scroll to results
                    resultsDiv.scrollIntoView({
                        behavior: 'smooth'
                    });
                })
                .catch(error => {
                    loadingSpinner.classList.add('d-none');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send"></i> Kirim Pesan Test';

                    resultContent.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-exclamation-triangle"></i> Error Jaringan</h6>
                        <p>Gagal terhubung ke server: ${error.message}</p>
                    </div>
                `;
                    resultsDiv.classList.remove('d-none');
                });
        });
    </script>
</body>

</html>

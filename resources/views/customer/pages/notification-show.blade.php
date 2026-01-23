@extends('customer.layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('notifications.index') }}"
                            class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-300">
                            <i class='bx bx-arrow-back text-xl text-gray-600'></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Detail Notifikasi</h1>
                            <p class="text-gray-600">Lihat informasi lengkap notifikasi</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">
                            <i class='bx bx-calendar mr-1'></i>
                            {{ now()->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notification Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start gap-4">
                            <div class="{{ $notification->color }} w-16 h-16 rounded-xl flex items-center justify-center">
                                <i class="bx {{ $notification->icon }} text-2xl text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $notification->title }}</h2>
                                <div class="flex items-center flex-wrap gap-3 mt-2">
                                    <span class="text-sm text-gray-600">
                                        <i class='bx bx-time mr-1'></i>
                                        {{ $notification->created_at->translatedFormat('d F Y, H:i') }}
                                    </span>
                                    <span
                                        class="text-xs px-3 py-1 rounded-full capitalize font-medium
                                        {{ $notification->type === 'promo' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $notification->type === 'transaction' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $notification->type === 'system' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ $notification->type === 'promo' ? 'Promo' : '' }}
                                        {{ $notification->type === 'transaction' ? 'Transaksi' : '' }}
                                        {{ $notification->type === 'system' ? 'Sistem' : '' }}
                                    </span>
                                    @if ($notification->is_read)
                                        <span class="text-xs px-3 py-1 bg-gray-100 text-gray-600 rounded-full">
                                            <i class='bx bx-check mr-1'></i> Sudah dibaca
                                        </span>
                                    @else
                                        <span class="text-xs px-3 py-1 bg-blue-100 text-blue-600 rounded-full">
                                            <i class='bx bx-bell mr-1'></i> Belum dibaca
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Message -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Pesan</h3>
                        <div class="prose max-w-none">
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <p class="text-gray-700 text-base leading-relaxed whitespace-pre-line">
                                    {{ $notification->message }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Data (if any) -->
                    @if ($notification->data)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Tambahan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $additionalData = json_decode($notification->data, true);
                                @endphp

                                @if (is_array($additionalData))
                                    @foreach ($additionalData as $key => $value)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="text-sm text-gray-600 mb-1 capitalize">
                                                {{ str_replace('_', ' ', $key) }}</div>
                                            <div class="font-medium text-gray-800">{{ $value }}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Related Information -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Terkait</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-user text-blue-600'></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Penerima</div>
                                        <div class="font-medium text-gray-800">{{ $notification->user->name ?? 'User' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-calendar-check text-green-600'></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Waktu Dikirim</div>
                                        <div class="font-medium text-gray-800">
                                            {{ $notification->created_at->translatedFormat('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class='bx bx-hash text-purple-600'></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">ID Notifikasi</div>
                                        <div class="font-medium text-gray-800 text-sm">{{ $notification->id }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    @if ($notification->link)
                        <a href="{{ $notification->link }}"
                            class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class='bx bx-link-external'></i>
                            Buka Link Terkait
                        </a>
                    @endif

                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')"
                            class="w-full flex items-center justify-center gap-2 border border-red-300 text-red-600 hover:bg-red-50 px-6 py-3 rounded-xl font-medium transition-all duration-200">
                            <i class='bx bx-trash'></i>
                            Hapus Notifikasi
                        </button>
                    </form>

                    <a href="{{ route('notifications.index') }}"
                        class="flex-1 flex items-center justify-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl font-medium transition-all duration-200">
                        <i class='bx bx-arrow-back'></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Related Notifications -->
            @if ($relatedNotifications->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Notifikasi Terkait</h3>
                    <div class="space-y-3">
                        @foreach ($relatedNotifications as $related)
                            <a href="{{ route('notifications.show', $related->id) }}"
                                class="block bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="{{ $related->color }} w-10 h-10 rounded-lg flex items-center justify-center">
                                            <i class="bx {{ $related->icon }} text-lg text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ Str::limit($related->title, 50) }}
                                            </h4>
                                            <p class="text-sm text-gray-600">{{ $related->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <i class='bx bx-chevron-right text-gray-400 text-xl'></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Help Card -->
            <div class="mt-8 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-help-circle text-blue-600 text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 mb-2">Pertanyaan tentang notifikasi?</h3>
                        <p class="text-sm text-gray-700 mb-3">
                            Jika Anda memiliki pertanyaan atau masalah terkait notifikasi ini,
                            silakan hubungi tim support kami.
                        </p>
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-medium">
                            <i class='bx bxl-whatsapp'></i>
                            Hubungi Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto scroll to top when page loads
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Handle delete confirmation
            const deleteForms = document.querySelectorAll('form[action*="destroy"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    <style>
        .prose {
            color: #374151;
        }

        .prose p {
            margin-bottom: 1em;
        }

        .prose p:last-child {
            margin-bottom: 0;
        }

        .bg-gradient-to-r {
            background-size: 200% 100%;
            background-position: 100% 0;
            transition: background-position 0.5s;
        }

        .bg-gradient-to-r:hover {
            background-position: 0 0;
        }
    </style>
@endsection

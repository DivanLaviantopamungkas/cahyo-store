@extends('customer.layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div x-data="{
        filter: '{{ request('type', 'all') }}',
        showUnreadOnly: {{ request('unread') == '1' ? 'true' : 'false' }},
        unreadCount: {{ $unreadCount }},
    
        markAsRead(id) {
            fetch(`/notifikasi/${id}`, {
                    method: 'GET',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(response => {
                    if (response.redirected) window.location.href = response.url;
                });
        },
    
        markAllAsRead() {
            Swal.fire({
                title: 'Tandai semua dibaca?',
                text: 'Semua notifikasi akan ditandai sebagai sudah dibaca',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', 
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Ya, Tandai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/notifikasi/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        Toast.fire({ icon: 'success', title: data.message });
                        
                        setTimeout(() => location.reload(), 1000);
                    });
                }
            });
        },
    
        clearAll() {
            Swal.fire({
                title: 'Hapus semua notifikasi?',
                text: 'Tindakan ini tidak dapat dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', 
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/notifikasi/clear', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire('Dihapus!', data.message, 'success')
                            .then(() => location.reload());
                    });
                }
            });
        },
    
        applyFilter() {
            const baseUrl = '{{ route('notifications.index') }}';
            const params = new URLSearchParams();
            
            params.set('type', this.filter);
            
            if (this.showUnreadOnly) {
                params.set('unread', '1');
            }
            
            window.location.href = `${baseUrl}?${params.toString()}`;
        },
        
        toggleUnread() {
            this.applyFilter();
        }

    }" class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
                        <p class="text-gray-600">Lihat update terbaru dan promo menarik</p>
                    </div>

                    <div class="relative">
                        <div x-show="unreadCount > 0"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                            <span x-text="unreadCount"></span>
                        </div>
                        <i class='bx bx-bell text-3xl text-gray-600'></i>
                    </div>
                </div>
            </div>

            @auth
                <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="grid grid-cols-2 gap-2 w-full md:flex md:w-auto">
                            @foreach(['all' => 'Semua', 'promo' => 'Promo', 'transaction' => 'Transaksi', 'system' => 'Sistem'] as $key => $label)
                                <button @click="filter = '{{ $key }}'; applyFilter()"
                                    :class="filter === '{{ $key }}' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-4 py-2 rounded-lg font-medium transition-all text-center">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="flex items-center cursor-pointer select-none">
                                <div class="relative">
                                    <input type="checkbox" class="sr-only" 
                                           x-model="showUnreadOnly" 
                                           @change="toggleUnread()">
                                    
                                    <div :class="showUnreadOnly ? 'bg-primary' : 'bg-gray-300'"
                                        class="block w-12 h-6 rounded-full transition-colors duration-200"></div>
                                    
                                    <div :class="showUnreadOnly ? 'translate-x-6' : 'translate-x-1'"
                                        class="absolute left-0 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 shadow-sm"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Belum dibaca</span>
                            </label>

                            <button @click="markAllAsRead()" :disabled="unreadCount === 0"
                                :class="unreadCount === 0 ? 'opacity-50 cursor-not-allowed text-gray-400' : 'text-primary hover:text-primary-dark'"
                                class="font-medium text-sm transition-colors">
                                Tandai semua dibaca
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($notifications as $notification)
                        <div @click="markAsRead({{ $notification->id }})"
                            class="cursor-pointer {{ $notification->is_read ? 'bg-white' : 'bg-blue-50 border-l-4 border-primary' }} rounded-xl shadow-sm p-4 border border-gray-200 hover:shadow-md transition-all">
                            <div class="flex items-start">
                                <div class="{{ $notification->color }} w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                                    <i class="bx {{ $notification->icon }} text-xl"></i>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="font-bold text-gray-800">{{ $notification->title }}</h3>
                                            <div class="flex items-center mt-1">
                                                <span class="text-sm text-gray-600 mr-3">{{ $notification->time_ago }}</span>
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full capitalize
                                                    {{ $notification->type === 'promo' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $notification->type === 'transaction' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $notification->type === 'system' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                    {{ $notification->type === 'promo' ? 'Promo' : '' }}
                                                    {{ $notification->type === 'transaction' ? 'Transaksi' : '' }}
                                                    {{ $notification->type === 'system' ? 'Sistem' : '' }}
                                                </span>
                                            </div>
                                        </div>

                                        @if (!$notification->is_read)
                                            <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                        @endif
                                    </div>

                                    <p class="text-gray-700 text-sm">{{ $notification->message }}</p>

                                    @if ($notification->link)
                                        <div class="mt-3">
                                            <span
                                                class="inline-flex items-center text-primary hover:text-primary-dark text-sm font-medium">
                                                Lihat Detail
                                                <i class='bx bx-chevron-right ml-1'></i>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class='bx bx-bell text-gray-400 text-4xl'></i>
                            </div>

                            <h3 class="text-xl font-bold text-gray-800 mb-3">
                                @if (request('unread') == '1')
                                    Tidak ada notifikasi belum dibaca
                                @elseif(request('type') && request('type') !== 'all')
                                    Tidak ada notifikasi {{ request('type') }}
                                @else
                                    Tidak ada notifikasi
                                @endif
                            </h3>

                            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                @if (request('unread') == '1')
                                    Semua notifikasi sudah Anda baca. Kembali nanti untuk update terbaru!
                                @else
                                    Belum ada notifikasi. Nantikan promo menarik dan update sistem kami!
                                @endif
                            </p>

                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('notifications.index') }}"
                                    class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium transition-all">
                                    Lihat Semua Notifikasi
                                </a>

                                @if ($notifications->total() > 0)
                                    <button @click="clearAll()"
                                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-all">
                                        Hapus Semua
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforelse

                    @if ($notifications->hasPages())
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class='bx bx-lock-alt text-gray-400 text-4xl'></i>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-3">Login untuk Melihat Notifikasi</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        Silakan login terlebih dahulu untuk melihat notifikasi dan promo menarik
                    </p>

                    <a href="{{ route('login') }}"
                        class="inline-block bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-medium transition-all">
                        <i class='bx bx-log-in mr-2'></i> Login Sekarang
                    </a>
                </div>
            @endauth
        </div>
    </div>
@endsection
@extends('customer.layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div x-data="{
        notifications: [
            { id: 1, type: 'promo', title: 'Diskon Spesial 25%', message: 'Dapatkan diskon 25% untuk semua produk Mobile Legends!', time: '2 jam lalu', read: false, icon: 'bx-gift', color: 'bg-red-100 text-red-600' },
            { id: 2, type: 'transaction', title: 'Pembayaran Berhasil', message: 'Pembelian Mobile Legends Diamonds 172+8 berhasil diproses', time: '5 jam lalu', read: true, icon: 'bx-check-circle', color: 'bg-green-100 text-green-600' },
            { id: 3, type: 'system', title: 'Maintenance System', message: 'Akan ada maintenance system pada 25 Januari pukul 02:00-04:00 WIB', time: '1 hari lalu', read: false, icon: 'bx-cog', color: 'bg-blue-100 text-blue-600' },
            { id: 4, type: 'promo', title: 'Bonus Diamond 10%', message: 'Dapatkan bonus diamond tambahan 10% untuk pembelian pertama!', time: '2 hari lalu', read: true, icon: 'bx-diamond', color: 'bg-purple-100 text-purple-600' },
            { id: 5, type: 'transaction', title: 'Order Dibatalkan', message: 'Order #MLBB-00128 dibatalkan karena waktu pembayaran habis', time: '3 hari lalu', read: true, icon: 'bx-x-circle', color: 'bg-gray-100 text-gray-600' },
            { id: 6, type: 'system', title: 'Update Aplikasi', message: 'Versi terbaru aplikasi sudah tersedia, update sekarang!', time: '1 minggu lalu', read: true, icon: 'bx-refresh', color: 'bg-yellow-100 text-yellow-600' },
        ],
        filter: 'all',
        showUnreadOnly: false,

        get filteredNotifications() {
            let filtered = this.notifications;

            if (this.showUnreadOnly) {
                filtered = filtered.filter(n => !n.read);
            }

            if (this.filter !== 'all') {
                filtered = filtered.filter(n => n.type === this.filter);
            }

            return filtered;
        },

        get unreadCount() {
            return this.notifications.filter(n => !n.read).length;
        },

        markAsRead(id) {
            const notification = this.notifications.find(n => n.id === id);
            if (notification && !notification.read) {
                notification.read = true;
                showToast('Notifikasi ditandai sudah dibaca', 'success');
            }
        },

        markAllAsRead() {
            this.notifications.forEach(n => n.read = true);
            showToast('Semua notifikasi ditandai sudah dibaca', 'success');
        },

        deleteNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
            showToast('Notifikasi dihapus', 'info');
        },

        clearAll() {
            if (confirm('Hapus semua notifikasi?')) {
                this.notifications = [];
                showToast('Semua notifikasi dihapus', 'info');
            }
        }
    }" class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
                        <p class="text-gray-600">Lihat update terbaru dan promo menarik</p>
                    </div>

                    <!-- Badge -->
                    <div class="relative">
                        <div x-show="unreadCount > 0"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                            <span x-text="unreadCount"></span>
                        </div>
                        <i class='bx bx-bell text-3xl text-gray-600'></i>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button @click="filter = 'all'"
                            :class="filter === 'all' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-lg font-medium transition-all">
                            Semua
                        </button>
                        <button @click="filter = 'promo'"
                            :class="filter === 'promo' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-lg font-medium transition-all">
                            Promo
                        </button>
                        <button @click="filter = 'transaction'"
                            :class="filter === 'transaction' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-lg font-medium transition-all">
                            Transaksi
                        </button>
                        <button @click="filter = 'system'"
                            :class="filter === 'system' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-lg font-medium transition-all">
                            Sistem
                        </button>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Unread Toggle -->
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" class="sr-only" x-model="showUnreadOnly">
                                <div :class="showUnreadOnly ? 'bg-primary' : 'bg-gray-300'"
                                    class="block w-12 h-6 rounded-full"></div>
                                <div :class="showUnreadOnly ? 'translate-x-6' : 'translate-x-1'"
                                    class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition"></div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Belum dibaca</span>
                        </label>

                        <!-- Mark All as Read -->
                        <button @click="markAllAsRead()" :disabled="unreadCount === 0"
                            :class="unreadCount === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                            class="text-primary hover:text-primary-dark font-medium">
                            Tandai semua dibaca
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="space-y-4">
                <template x-if="filteredNotifications.length > 0">
                    <template x-for="notification in filteredNotifications" :key="notification.id">
                        <div :class="notification.read ? 'bg-white' : 'bg-blue-50 border-l-4 border-primary'"
                            class="rounded-xl shadow-sm p-4 border border-gray-200">
                            <div class="flex items-start">
                                <!-- Icon -->
                                <div :class="notification.color"
                                    class="w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                                    <i :class="'bx ' + notification.icon" class="text-xl"></i>
                                </div>

                                <!-- Content -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="font-bold text-gray-800" x-text="notification.title"></h3>
                                            <div class="flex items-center mt-1">
                                                <span class="text-sm text-gray-600 mr-3" x-text="notification.time"></span>
                                                <span
                                                    :class="{
                                                        'bg-red-100 text-red-800': notification.type === 'promo',
                                                        'bg-green-100 text-green-800': notification
                                                            .type === 'transaction',
                                                        'bg-blue-100 text-blue-800': notification.type === 'system'
                                                    }"
                                                    class="text-xs px-2 py-1 rounded-full capitalize">
                                                    <span
                                                        x-text="notification.type === 'promo' ? 'Promo' :
                                                             notification.type === 'transaction' ? 'Transaksi' : 'Sistem'"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2">
                                            <!-- Mark as Read -->
                                            <button @click="markAsRead(notification.id)" x-show="!notification.read"
                                                class="text-gray-400 hover:text-primary">
                                                <i class='bx bx-check text-xl'></i>
                                            </button>

                                            <!-- Delete -->
                                            <button @click="deleteNotification(notification.id)"
                                                class="text-gray-400 hover:text-red-500">
                                                <i class='bx bx-trash text-xl'></i>
                                            </button>
                                        </div>
                                    </div>

                                    <p class="text-gray-700" x-text="notification.message"></p>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-3 mt-4">
                                        <template x-if="notification.type === 'promo'">
                                            <a href="{{ url('/kategori') }}"
                                                class="inline-flex items-center bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                                Lihat Promo
                                                <i class='bx bx-chevron-right ml-1'></i>
                                            </a>
                                        </template>

                                        <template x-if="notification.type === 'transaction'">
                                            <a href="{{ url('/pesanan') }}"
                                                class="inline-flex items-center border border-primary text-primary hover:bg-primary hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                                Lihat Detail
                                                <i class='bx bx-show ml-1'></i>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>

                <!-- Empty State -->
                <template x-if="filteredNotifications.length === 0">
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class='bx bx-bell text-gray-400 text-4xl'></i>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-3"
                            x-text="
                        showUnreadOnly ? 'Tidak ada notifikasi belum dibaca' :
                        filter !== 'all' ? `Tidak ada notifikasi ${filter}` :
                        'Tidak ada notifikasi'
                    ">
                        </h3>

                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            <template x-if="showUnreadOnly">
                                Semua notifikasi sudah Anda baca. Kembali nanti untuk update terbaru!
                            </template>
                            <template x-if="!showUnreadOnly && filter !== 'all'">
                                Tidak ada notifikasi dengan tipe ini. Coba filter lain atau lihat semua notifikasi.
                            </template>
                            <template x-if="!showUnreadOnly && filter === 'all'">
                                Belum ada notifikasi. Nantikan promo menarik dan update sistem kami!
                            </template>
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button @click="filter = 'all'; showUnreadOnly = false"
                                class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium transition-all">
                                Lihat Semua Notifikasi
                            </button>

                            <button @click="clearAll()" x-show="notifications.length > 0"
                                class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-all">
                                Hapus Semua
                            </button>

                            <a href="{{ url('/kategori') }}"
                                class="border border-primary text-primary hover:bg-primary hover:text-white px-6 py-3 rounded-lg font-medium transition-all">
                                Lihat Promo
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Load More -->
            <div class="text-center mt-8">
                <button
                    class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-all">
                    Muat Lebih Banyak
                </button>
            </div>

            <!-- Notification Settings -->
            <div class="mt-12">
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Pengaturan Notifikasi</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">Notifikasi Promo</h4>
                                <p class="text-sm text-gray-600">Dapatkan info promo & diskon terbaru</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only" checked>
                                <div class="w-12 h-6 bg-primary rounded-full"></div>
                                <div
                                    class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform translate-x-6">
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">Notifikasi Transaksi</h4>
                                <p class="text-sm text-gray-600">Update status pesanan & pembayaran</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only" checked>
                                <div class="w-12 h-6 bg-primary rounded-full"></div>
                                <div
                                    class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform translate-x-6">
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">Notifikasi Sistem</h4>
                                <p class="text-sm text-gray-600">Info maintenance & update sistem</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only" checked>
                                <div class="w-12 h-6 bg-primary rounded-full"></div>
                                <div
                                    class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform translate-x-6">
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-800">Email Notifikasi</h4>
                                <p class="text-sm text-gray-600">Kirim notifikasi ke email juga</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only">
                                <div class="w-12 h-6 bg-gray-300 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

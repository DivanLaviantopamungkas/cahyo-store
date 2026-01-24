{{-- <div class="bg-white border-t border-gray-200 shadow-lg">
    <div class="flex justify-around items-center h-16">
        <!-- Beranda -->
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-primary">
            <i class='bx bx-home text-2xl'></i>
            <span class="text-xs mt-1">Beranda</span>
        </a>

        <!-- Pesanan -->
        <a href="{{ route('orders.index') }}"
            class="flex flex-col items-center justify-center text-gray-600 hover:text-primary">
            <i class='bx bx-package text-2xl'></i>
            <span class="text-xs mt-1">Pesanan</span>
        </a>

        <!-- Bantuan -->
        <a href="{{ route('help.index') }}"
            class="flex flex-col items-center justify-center text-gray-600 hover:text-primary">
            <i class='bx bx-support text-2xl'></i>
            <span class="text-xs mt-1">Bantuan</span>
        </a>

        <!-- Notifikasi -->
        <a href="{{ route('notifications.index') }}"
            class="flex flex-col items-center justify-center text-gray-600 hover:text-primary relative">
            <i class='bx bx-bell text-2xl'></i>
            <span class="text-xs mt-1">Notifikasi</span>
            <span
                class="absolute top-0 right-3 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                3
            </span>
        </a>

        <!-- Profil -->
        <a href="{{ route('user.index') }}"
            class="flex flex-col items-center justify-center text-gray-600 hover:text-primary">
            <i class='bx bx-user text-2xl'></i>
            <span class="text-xs mt-1">Profil</span>
        </a>
    </div>
</div> --}}




<!-- Claude -->
<div class="bg-white border-t border-gray-200 shadow-lg" 
     x-data="{ 
         unreadCount: 0,
         checkNotifications() {
             @auth
                 fetch('{{ route('notifications.unread-count') }}')
                     .then(res => res.json())
                     .then(data => {
                         this.unreadCount = data.count;
                     })
                     .catch(err => console.log('Gagal ambil notif', err));
             @endauth
         }
     }" 
     x-init="checkNotifications(); setInterval(() => checkNotifications(), 10000);">
    <div class="flex justify-around items-center h-16">
        <!-- Beranda -->
        <a href="{{ route('home') }}" 
           class="flex flex-col items-center justify-center {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-600' }} hover:text-primary transition-colors">
            <i class='bx bx-home text-2xl'></i>
            <span class="text-xs mt-1">Beranda</span>
        </a>

        <!-- Pesanan -->
        <a href="{{ route('orders.index') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('orders.*') ? 'text-primary' : 'text-gray-600' }} hover:text-primary transition-colors">
            <i class='bx bx-package text-2xl'></i>
            <span class="text-xs mt-1">Pesanan</span>
        </a>

        <!-- Bantuan -->
        <a href="{{ route('help.index') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('help.*') ? 'text-primary' : 'text-gray-600' }} hover:text-primary transition-colors">
            <i class='bx bx-support text-2xl'></i>
            <span class="text-xs mt-1">Bantuan</span>
        </a>

        <!-- Notifikasi dengan Badge Dinamis -->
        <a href="{{ route('notifications.index') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('notifications.*') ? 'text-primary' : 'text-gray-600' }} hover:text-primary transition-colors relative">
            <i class='bx bx-bell text-2xl'></i>
            <span class="text-xs mt-1">Notifikasi</span>
            
            <!-- Badge hanya muncul jika ada notifikasi belum dibaca -->
            <span x-show="unreadCount > 0" 
                  x-transition.scale
                  x-text="unreadCount"
                  class="absolute top-0 right-3 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-bold shadow-sm border border-white">
            </span>
        </a>

        <!-- Profil -->
        <a href="{{ route('user.index') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('user.*') ? 'text-primary' : 'text-gray-600' }} hover:text-primary transition-colors">
            <i class='bx bx-user text-2xl'></i>
            <span class="text-xs mt-1">Profil</span>
        </a>
    </div>
</div>
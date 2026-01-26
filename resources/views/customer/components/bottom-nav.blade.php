<div class="bg-white border-t border-gray-200 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] sticky bottom-0 z-[100]" 
     x-data="{ 
         unreadCount: 0,
         showProfileMenu: false,
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
     x-init="checkNotifications(); setInterval(() => checkNotifications(), 30000);">
    
    <div class="flex justify-around items-center h-16 relative">
        <a href="{{ route('home') }}" 
           class="flex flex-col items-center justify-center {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-gray-400' }} transition-all">
            <i class='bx {{ request()->routeIs('home') ? 'bxs-home' : 'bx-home' }} text-2xl'></i>
            <span class="text-[10px] mt-1">Beranda</span>
        </a>

        <a href="{{ route('orders.index') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('orders.*') ? 'text-primary font-bold' : 'text-gray-400' }} transition-all">
            <i class='bx {{ request()->routeIs('orders.*') ? 'bxs-package' : 'bx-package' }} text-2xl'></i>
            <span class="text-[10px] mt-1">Pesanan</span>
        </a>

        <a href="{{ route('notifications.index') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('notifications.*') ? 'text-primary font-bold' : 'text-gray-400' }} transition-all relative">
            <i class='bx {{ request()->routeIs('notifications.*') ? 'bxs-bell' : 'bx-bell' }} text-2xl'></i>
            <span class="text-[10px] mt-1">Notifikasi</span>
            
            <span x-show="unreadCount > 0" 
                  x-text="unreadCount"
                  class="absolute top-0 right-3 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold border-2 border-white">
            </span>
        </a>

        <a href="{{ route('help.index') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('help.*') ? 'text-primary font-bold' : 'text-gray-400' }} transition-all">
            <i class='bx {{ request()->routeIs('help.*') ? 'bxs-help-circle' : 'bx-help-circle' }} text-2xl'></i>
            <span class="text-[10px] mt-1">Bantuan</span>
        </a>

        <button @click="showProfileMenu = true"
            class="flex flex-col items-center justify-center {{ request()->routeIs('user.*') ? 'text-primary font-bold' : 'text-gray-400' }} transition-all">
            <i class='bx {{ request()->routeIs('user.*') ? 'bxs-user-circle' : 'bx-user-circle' }} text-2xl'></i>
            <span class="text-[10px] mt-1">Akun</span>
        </button>
    </div>

    <div x-show="showProfileMenu" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[110] flex items-end"
         style="display: none;">
        
        <div @click.away="showProfileMenu = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="bg-white w-full rounded-t-[2rem] p-6 shadow-xl">
            
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 px-2">Menu Akun</h3>
                
                <div class="grid grid-cols-1 gap-2">
                    @auth
                        <div class="flex items-center p-4 mb-2 bg-blue-50 rounded-2xl border border-blue-100">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-sm">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-blue-600">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <a href="{{ route('user.index') }}" class="flex items-center p-4 hover:bg-gray-50 rounded-2xl transition-all border border-gray-50">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center mr-4">
                                <i class='bx bxs-user text-xl'></i>
                            </div>
                            <span class="font-semibold text-gray-700">Profil Saya</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center p-4 bg-red-50 hover:bg-red-100 rounded-2xl transition-all border border-red-100 text-red-600">
                                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center mr-4">
                                    <i class='bx bx-log-out-circle text-xl'></i>
                                </div>
                                <span class="font-bold">Keluar Aplikasi</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center p-4 bg-primary text-white rounded-2xl transition-all shadow-lg shadow-primary/20">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mr-4">
                                <i class='bx bx-log-in-circle text-xl'></i>
                            </div>
                            <span class="font-bold">Masuk / Daftar Akun</span>
                        </a>
                    @endauth
                </div>

                <button @click="showProfileMenu = false" class="w-full py-3 text-gray-400 text-sm font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
{{-- <nav class="bg-white shadow-md sticky top-0 z-40">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2">
                @if(setting('site_logo'))
                    <img src="{{ App\Helpers\SettingHelper::getImage('site_logo') }}" 
                        alt="{{ setting('site_name', 'CahyoStore') }}" 
                        class="h-8 w-auto">
                @else
                    <span class="text-xl font-bold text-gray-800">
                        {{ setting('site_name', 'Cahyo') }}<span class="text-primary">Store</span>
                    </span>
                @endif
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" 
                   class="{{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-gray-600 font-medium' }} hover:text-primary transition-colors flex items-center">
                    <i class='bx bx-home text-lg mr-1'></i> Beranda
                </a>

                <a href="{{ route('orders.index') }}"
                    class="{{ request()->routeIs('orders.*') ? 'text-primary font-bold' : 'text-gray-600 font-medium' }} hover:text-primary transition-colors flex items-center">
                    <i class='bx bx-package text-lg mr-1'></i> Pesanan
                </a>

                <a href="{{ route('help.index') }}"
                    class="{{ request()->routeIs('help.*') ? 'text-primary font-bold' : 'text-gray-600 font-medium' }} hover:text-primary transition-colors flex items-center">
                    <i class='bx bx-support text-lg mr-1'></i> Bantuan
                </a>

                <!-- Notifikasi -->
                <div x-data="{ 
                    unreadCount: 0,
                    checkNotifications() {
                        fetch('{{ route('notifications.unread-count') }}')
                            .then(res => res.json())
                            .then(data => {
                                this.unreadCount = data.count;
                            })
                            .catch(err => console.log('Gagal ambil notif', err));
                    }
                }" 
                x-init="
                    @auth
                        checkNotifications(); 
                        setInterval(() => checkNotifications(), 10000); // Cek setiap 10 detik
                    @endauth
                " class="relative flex items-center">
                    
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-600 hover:text-primary transition-colors">
                        <i class='bx bx-bell text-2xl'></i>
                        
                        <span x-show="unreadCount > 0" 
                            x-transition.scale
                            x-text="unreadCount"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-sm border border-white">
                        </span>
                    </a>
                </div>

                <!-- Login/Profile -->
                <div x-data="{ mobileMenuOpen: false }">
                    <!-- Desktop View -->
                    <div class="hidden md:flex items-center space-x-3">
                        <!-- Logged In - Desktop -->
                        @auth
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <div class="font-medium">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        <span>Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <!-- Logout Form -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Not Logged In - Desktop -->
                            <a href="{{ route('login') }}"
                                class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-lg font-medium transition-all">
                                <i class='bx bx-log-in mr-2'></i>Masuk
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden flex items-center space-x-3">
                        @auth
                            <!-- Logged In - Mobile -->
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                    <span class="text-primary font-semibold text-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="max-w-[100px]">
                                    <div class="font-medium text-sm truncate">{{ Auth::user()->name }}</div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class='bx bx-log-out text-lg'></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- OPSI 1: JavaScript Force Redirect -->
                            <button onclick="goToLogin()"
                                class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg font-medium transition-all text-sm">
                                <i class='bx bx-log-in'></i>
                            </button>

                            <!-- OPSI 2: Link dengan preventDefault -->
                            <a href="{{ route('login') }}"
                                onclick="event.preventDefault(); window.location.href='{{ route('login') }}';"
                                class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg font-medium transition-all text-sm ml-2">
                                <i class='bx bx-log-in'></i> Link
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    function goToLogin() {
        console.log('Going to login page...');
        console.log('Route:', '{{ route('login') }}');
        console.log('URL:', '/login');

        // Coba route dulu
        try {
            window.location.href = '{{ route('login') }}';
        } catch (e) {
            console.error('Route failed:', e);
            // Fallback ke URL langsung
            window.location.href = '/login';
        }
    }
</script> --}}




<!-- Claude -->
<nav class="bg-white shadow-md sticky top-0 z-40">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2">
                @if(setting('site_logo'))
                    <img src="{{ App\Helpers\SettingHelper::getImage('site_logo') }}" 
                        alt="{{ setting('site_name', 'CahyoStore') }}" 
                        class="h-8 w-auto">
                @else
                    <span class="text-xl font-bold text-gray-800">
                        {{ setting('site_name', 'Cahyo') }}<span class="text-primary">Store</span>
                    </span>
                @endif
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" 
                   class="{{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-gray-600 font-medium' }} hover:text-primary transition-colors flex items-center">
                    <i class='bx bx-home text-lg mr-1'></i> Beranda
                </a>

                <a href="{{ route('orders.index') }}"
                    class="{{ request()->routeIs('orders.*') ? 'text-primary font-bold' : 'text-gray-600 font-medium' }} hover:text-primary transition-colors flex items-center">
                    <i class='bx bx-package text-lg mr-1'></i> Pesanan
                </a>

                <a href="{{ route('help.index') }}"
                    class="{{ request()->routeIs('help.*') ? 'text-primary font-bold' : 'text-gray-600 font-medium' }} hover:text-primary transition-colors flex items-center">
                    <i class='bx bx-support text-lg mr-1'></i> Bantuan
                </a>

                <!-- Notifikasi -->
                <div x-data="{ 
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
                x-init="checkNotifications(); setInterval(() => checkNotifications(), 10000);" 
                class="relative flex items-center">
                    
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-600 hover:text-primary transition-colors">
                        <i class='bx bx-bell text-2xl'></i>
                        
                        <span x-show="unreadCount > 0" 
                            x-transition.scale
                            x-text="unreadCount"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-sm border border-white">
                        </span>
                    </a>
                </div>

                <!-- Login/Profile Dropdown -->
                <div x-data="{ profileOpen: false }" class="relative">
                    @auth
                        <!-- Profile Button -->
                        <button @click="profileOpen = !profileOpen" 
                                class="flex items-center space-x-3 hover:bg-gray-50 px-3 py-2 rounded-lg transition-all">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center shadow-md">
                                <span class="text-white font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="text-left">
                                <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm text-gray-500">
                                    Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                                </div>
                            </div>
                            <i class='bx bx-chevron-down text-gray-400' :class="{ 'rotate-180': profileOpen }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="profileOpen" 
                             @click.away="profileOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                            
                            <!-- User Info Header -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm text-gray-500">{{ Auth::user()->whatsapp }}</div>
                                <div class="mt-2 inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                    <i class='bx bx-wallet mr-1'></i>
                                    Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-2">
                                <a href="{{ route('user.index') }}" 
                                   class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class='bx bx-user text-xl mr-3 text-primary'></i>
                                    <span>Profile Saya</span>
                                </a>
                                
                                <a href="{{ route('orders.index') }}" 
                                   class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class='bx bx-package text-xl mr-3 text-primary'></i>
                                    <span>Pesanan Saya</span>
                                </a>
                                
                                <a href="{{ route('notifications.index') }}" 
                                   class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class='bx bx-bell text-xl mr-3 text-primary'></i>
                                    <span>Notifikasi</span>
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="border-t border-gray-100 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center px-4 py-2.5 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class='bx bx-log-out text-xl mr-3'></i>
                                        <span class="font-medium">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Not Logged In -->
                        <a href="{{ route('login') }}"
                            class="bg-primary hover:bg-primary-dark text-white px-6 py-2.5 rounded-lg font-medium transition-all shadow-sm hover:shadow-md">
                            <i class='bx bx-log-in mr-2'></i>Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.2s;
    }
</style>
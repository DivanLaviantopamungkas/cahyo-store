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
</script>

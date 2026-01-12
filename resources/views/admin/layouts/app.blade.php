<!DOCTYPE html>
<html lang="id" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    sidebarOpen: false,
    mobileMenuOpen: false,
    settingsMenu: false,
    settingsMenuMobile: false
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val));
if (window.location.pathname.includes('/admin/settings')) {
    settingsMenu = true;
    settingsMenuMobile = true;
}" :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                    },
                    borderRadius: {
                        '2xl': '1rem',
                        '3xl': '1.5rem',
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                        },
                        violet: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                        },
                        rose: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            500: '#f43f5e',
                            600: '#e11d48',
                        },
                        slate: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Icons (Heroicons) -->
    <script src="https://unpkg.com/heroicons@latest/24/outline/index.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>

    @stack('styles')
</head>

<body
    class="font-sans bg-slate-50 text-slate-800 dark:bg-slate-900 dark:text-slate-200 min-h-screen transition-colors duration-300">
    <!-- Desktop Sidebar -->
    <aside x-show="!mobileMenuOpen"
        class="hidden lg:flex flex-col fixed left-0 top-0 h-screen w-64 bg-white dark:bg-slate-800 shadow-xl z-40 border-r border-slate-200 dark:border-slate-700">
        <!-- Logo -->
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-violet-500 flex items-center justify-center">
                    <span class="text-white font-bold text-xl">AP</span>
                </div>
                <div>
                    <h1 class="font-bold text-lg dark:text-white">Admin Panel</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Dashboard</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto scrollbar-thin">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-home"></use>
                </svg>
                <span>Dashboard</span>
            </a>

            <div
                class="px-4 pt-4 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Manajemen</div>

            <a href="{{ route('admin.categories.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.categories.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-tag"></use>
                </svg>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.products.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-shopping-bag"></use>
                </svg>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.voucher-codes.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.voucher-codes.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-ticket"></use>
                </svg>
                <span>Voucher Codes</span>
            </a>

            <a href="{{ route('admin.transactions.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.transactions.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-credit-card"></use>
                </svg>
                <span>Transaksi</span>
            </a>

            <a href="{{ route('admin.members.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.members.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-users"></use>
                </svg>
                <span>Member</span>
            </a>

            <div
                class="px-4 pt-4 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Konfigurasi</div>

            <!-- Settings dengan dropdown -->
            <div>
                <button type="button" @click="settingsMenu = !settingsMenu"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-600 dark:text-slate-300' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5">
                            <use href="#icon-cog-6-tooth"></use>
                        </svg>
                        <span>Pengaturan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="settingsMenu ? 'rotate-180' : ''">
                        <use href="#icon-chevron-down"></use>
                    </svg>
                </button>

                <!-- Settings Submenu -->
                <div x-show="settingsMenu" x-cloak
                    class="mt-1 ml-4 pl-4 border-l border-slate-200 dark:border-slate-700 space-y-1">
                    <a href="{{ route('admin.settings.general') }}"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.general') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-cog-6-tooth"></use>
                        </svg>
                        <span>Umum</span>
                    </a>

                    <a href="{{ route('admin.settings.landing') }}"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.landing') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-home"></use>
                        </svg>
                        <span>Landing Page</span>
                    </a>

                    <a href="{{ route('admin.settings.providers') }}"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.providers') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-bolt"></use>
                        </svg>
                        <span>Provider</span>
                    </a>

                    <a href="{{ route('admin.settings.contact') }}"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.contact') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-phone"></use>
                        </svg>
                        <span>Kontak</span>
                    </a>

                    <a href="{{ route('admin.settings.social') }}"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.social') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-share"></use>
                        </svg>
                        <span>Sosial Media</span>
                    </a>

                    <a href="{{ route('admin.settings.payment') }}"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.payment') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-credit-card"></use>
                        </svg>
                        <span>Pembayaran</span>
                    </a>
                </div>
            </div>

            <!-- Broadcast -->
            <a href="{{ route('admin.broadcasts.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.broadcasts.*') ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-megaphone"></use>
                </svg>
                <span>Broadcast</span>
            </a>
        </nav>

        <!-- User Menu -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <button @click="darkMode = !darkMode"
                    class="p-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <svg x-show="!darkMode" class="w-5 h-5 text-slate-600 dark:text-slate-300">
                        <use href="#icon-moon"></use>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5 text-slate-600 dark:text-slate-300">
                        <use href="#icon-sun"></use>
                    </svg>
                </button>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="p-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        title="Logout">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-300">
                            <use href="#icon-arrow-right-on-rectangle"></use>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Topbar -->
    <header x-show="!mobileMenuOpen"
        class="lg:hidden fixed top-0 left-0 right-0 bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-700 z-30">
        <div class="px-4 py-3 flex items-center justify-between">
            <button @click="sidebarOpen = true" class="p-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700">
                <svg class="w-6 h-6 text-slate-700 dark:text-slate-300">
                    <use href="#icon-bars-3"></use>
                </svg>
            </button>

            <div class="flex items-center space-x-2">
                <h1 class="font-bold text-lg dark:text-white">@yield('title', 'Admin')</h1>
            </div>

            <button @click="darkMode = !darkMode" class="p-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700">
                <svg x-show="!darkMode" class="w-5 h-5 text-slate-600 dark:text-slate-300">
                    <use href="#icon-moon"></use>
                </svg>
                <svg x-show="darkMode" class="w-5 h-5 text-slate-600 dark:text-slate-300">
                    <use href="#icon-sun"></use>
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" class="lg:hidden fixed inset-0 bg-black/50 z-50" @click="sidebarOpen = false"></div>

    <!-- Mobile Sidebar -->
    <aside x-show="sidebarOpen"
        class="lg:hidden fixed left-0 top-0 h-screen w-64 bg-white dark:bg-slate-800 shadow-xl z-50 transform transition-transform"
        :class="{ '-translate-x-full': !sidebarOpen }">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-violet-500 flex items-center justify-center">
                        <span class="text-white font-bold text-xl">AP</span>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg dark:text-white">Admin Panel</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Dashboard</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false"
                    class="p-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700">
                    <svg class="w-5 h-5">
                        <use href="#icon-x-mark"></use>
                    </svg>
                </button>
            </div>
        </div>

        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-140px)]">
            <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-home"></use>
                </svg>
                <span>Dashboard</span>
            </a>

            <div
                class="px-4 pt-4 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Manajemen</div>

            <a href="{{ route('admin.categories.index') }}" @click="sidebarOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.categories.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-tag"></use>
                </svg>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.products.index') }}" @click="sidebarOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.products.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-shopping-bag"></use>
                </svg>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.voucher-codes.index') }}" @click="sidebarOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.voucher-codes.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-ticket"></use>
                </svg>
                <span>Voucher Codes</span>
            </a>

            <a href="{{ route('admin.transactions.index') }}" @click="sidebarOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.transactions.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-credit-card"></use>
                </svg>
                <span>Transaksi</span>
            </a>

            <a href="{{ route('admin.members.index') }}" @click="sidebarOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.members.*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-users"></use>
                </svg>
                <span>Member</span>
            </a>

            <div
                class="px-4 pt-4 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Konfigurasi</div>

            <!-- Settings dengan dropdown Mobile -->
            <div>
                <button type="button" @click="settingsMenuMobile = !settingsMenuMobile"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'text-slate-600 dark:text-slate-300' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5">
                            <use href="#icon-cog-6-tooth"></use>
                        </svg>
                        <span>Pengaturan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="settingsMenuMobile ? 'rotate-180' : ''">
                        <use href="#icon-chevron-down"></use>
                    </svg>
                </button>

                <!-- Settings Submenu Mobile -->
                <div x-show="settingsMenuMobile" x-cloak
                    class="mt-1 ml-4 pl-4 border-l border-slate-200 dark:border-slate-700 space-y-1">
                    <a href="{{ route('admin.settings.general') }}" @click="sidebarOpen = false"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.general') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-cog-6-tooth"></use>
                        </svg>
                        <span>Umum</span>
                    </a>

                    <a href="{{ route('admin.settings.landing') }}" @click="sidebarOpen = false"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.landing') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-home"></use>
                        </svg>
                        <span>Landing Page</span>
                    </a>

                    <a href="{{ route('admin.settings.providers') }}" @click="sidebarOpen = false"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.providers') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-bolt"></use>
                        </svg>
                        <span>Provider</span>
                    </a>

                    <a href="{{ route('admin.settings.contact') }}" @click="sidebarOpen = false"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.contact') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-phone"></use>
                        </svg>
                        <span>Kontak</span>
                    </a>

                    <a href="{{ route('admin.settings.social') }}" @click="sidebarOpen = false"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.social') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-share"></use>
                        </svg>
                        <span>Sosial Media</span>
                    </a>

                    <a href="{{ route('admin.settings.payment') }}" @click="sidebarOpen = false"
                        class="flex items-center space-x-3 px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.settings.payment') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg class="w-4 h-4">
                            <use href="#icon-credit-card"></use>
                        </svg>
                        <span>Pembayaran</span>
                    </a>
                </div>
            </div>

            <!-- Broadcast Mobile -->
            <a href="{{ route('admin.broadcasts.index') }}" @click="sidebarOpen = false"
                class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ request()->routeIs('admin.broadcasts.*') ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400' : 'text-slate-600 dark:text-slate-300' }}">
                <svg class="w-5 h-5">
                    <use href="#icon-megaphone"></use>
                </svg>
                <span>Broadcast</span>
            </a>
        </nav>
    </aside>

    <!-- Mobile Bottom Navigation -->
    <nav
        class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/90 dark:bg-slate-800/90 backdrop-blur-lg border-t border-slate-200 dark:border-slate-700 z-30">
        <div class="flex justify-around py-2">
            <a href="{{ route('admin.dashboard') }}"
                class="flex flex-col items-center p-2 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500' }}">
                <svg class="w-6 h-6">
                    <use href="#icon-home"></use>
                </svg>
                <span class="text-xs mt-1">Home</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="flex flex-col items-center p-2 rounded-2xl {{ request()->routeIs('admin.products.*') ? 'text-violet-600 dark:text-violet-400' : 'text-slate-500' }}">
                <svg class="w-6 h-6">
                    <use href="#icon-shopping-bag"></use>
                </svg>
                <span class="text-xs mt-1">Produk</span>
            </a>

            <a href="{{ route('admin.voucher-codes.index') }}"
                class="flex flex-col items-center p-2 rounded-2xl {{ request()->routeIs('admin.voucher-codes.*') ? 'text-violet-600 dark:text-violet-400' : 'text-slate-500' }}">
                <svg class="w-6 h-6">
                    <use href="#icon-ticket"></use>
                </svg>
                <span class="text-xs mt-1">Voucher</span>
            </a>

            <a href="{{ route('admin.transactions.index') }}"
                class="flex flex-col items-center p-2 rounded-2xl {{ request()->routeIs('admin.transactions.*') ? 'text-violet-600 dark:text-violet-400' : 'text-slate-500' }}">
                <svg class="w-6 h-6">
                    <use href="#icon-credit-card"></use>
                </svg>
                <span class="text-xs mt-1">Transaksi</span>
            </a>

            <button @click="mobileMenuOpen = true" class="flex flex-col items-center p-2 rounded-2xl text-slate-500">
                <svg class="w-6 h-6">
                    <use href="#icon-bars-3"></use>
                </svg>
                <span class="text-xs mt-1">Menu</span>
            </button>
        </div>
    </nav>

    <!-- Mobile Full Menu -->
    <div x-show="mobileMenuOpen" class="lg:hidden fixed inset-0 bg-white dark:bg-slate-900 z-40 overflow-y-auto">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <h1 class="font-bold text-xl dark:text-white">Menu</h1>
                <button @click="mobileMenuOpen = false"
                    class="p-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-6 h-6">
                        <use href="#icon-x-mark"></use>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-4 space-y-4">
            <!-- Settings Section -->
            <div>
                <h2 class="font-semibold text-lg mb-3 text-slate-700 dark:text-slate-300">Pengaturan</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.settings.general') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 mb-2">
                            <use href="#icon-cog-6-tooth"></use>
                        </svg>
                        <span class="font-medium text-sm">Umum</span>
                    </a>

                    <a href="{{ route('admin.settings.landing') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 mb-2">
                            <use href="#icon-home"></use>
                        </svg>
                        <span class="font-medium text-sm">Landing</span>
                    </a>

                    <a href="{{ route('admin.settings.providers') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 mb-2">
                            <use href="#icon-bolt"></use>
                        </svg>
                        <span class="font-medium text-sm">Provider</span>
                    </a>

                    <a href="{{ route('admin.settings.contact') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 mb-2">
                            <use href="#icon-phone"></use>
                        </svg>
                        <span class="font-medium text-sm">Kontak</span>
                    </a>

                    <a href="{{ route('admin.settings.social') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 mb-2">
                            <use href="#icon-share"></use>
                        </svg>
                        <span class="font-medium text-sm">Sosial Media</span>
                    </a>

                    <a href="{{ route('admin.settings.payment') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 mb-2">
                            <use href="#icon-credit-card"></use>
                        </svg>
                        <span class="font-medium text-sm">Pembayaran</span>
                    </a>
                </div>
            </div>

            <!-- Lainnya Section -->
            <div>
                <h2 class="font-semibold text-lg mb-3 text-slate-700 dark:text-slate-300">Lainnya</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.categories.index') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-violet-600 dark:text-violet-400 mb-2">
                            <use href="#icon-tag"></use>
                        </svg>
                        <span class="font-medium text-sm">Kategori</span>
                    </a>

                    <a href="{{ route('admin.members.index') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-violet-600 dark:text-violet-400 mb-2">
                            <use href="#icon-users"></use>
                        </svg>
                        <span class="font-medium text-sm">Member</span>
                    </a>

                    <a href="{{ route('admin.broadcasts.index') }}" @click="mobileMenuOpen = false"
                        class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-8 h-8 text-rose-600 dark:text-rose-400 mb-2">
                            <use href="#icon-megaphone"></use>
                        </svg>
                        <span class="font-medium text-sm">Broadcast</span>
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST" class="col-span-2">
                        @csrf
                        <button type="submit" @click="mobileMenuOpen = false"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors w-full">
                            <svg class="w-8 h-8 text-rose-600 dark:text-rose-400 mb-2">
                                <use href="#icon-arrow-right-on-rectangle"></use>
                            </svg>
                            <span class="font-medium text-sm">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="lg:ml-64 pt-16 lg:pt-0 pb-20 lg:pb-0 min-h-screen transition-all duration-300">
        <div class="p-4 lg:p-6">
            <!-- Page Header -->
            <div class="mb-6 lg:mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 dark:text-white">@yield('title', 'Dashboard')
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">@yield('subtitle', 'Overview & analytics')</p>
                    </div>

                    <div class="flex items-center space-x-3">
                        @yield('actions')
                    </div>
                </div>

                @if (!request()->routeIs('admin.dashboard'))
                    <div class="mt-4 flex items-center space-x-2 text-sm">
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300">Dashboard</a>
                        <svg class="w-4 h-4 text-slate-400">
                            <use href="#icon-chevron-right"></use>
                        </svg>
                        <span class="text-slate-700 dark:text-slate-300">Pengaturan</span>
                        <svg class="w-4 h-4 text-slate-400">
                            <use href="#icon-chevron-right"></use>
                        </svg>
                        @yield('breadcrumb')
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="space-y-6">
                @include('admin.partials.form-errors')
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    @include('admin.partials.toast')

    <!-- Icons Definitions -->
    @include('admin.partials.icon')

    @stack('scripts')
</body>

</html>

<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Admin Panel</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                            500: '#10b981',
                            600: '#059669',
                        },
                        violet: {
                            50: '#f5f3ff',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 min-h-screen flex items-center justify-center p-4 transition-colors duration-300">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-violet-500 flex items-center justify-center mx-auto mb-4 shadow-xl">
                <span class="text-white font-bold text-3xl">AP</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Admin Panel</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Masuk ke dashboard admin</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-8">
            @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-rose-500 dark:text-rose-400 mr-2"><use href="#icon-exclamation-circle"></use></svg>
                    <p class="text-rose-700 dark:text-rose-300 text-sm">Email atau password salah</p>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400"><use href="#icon-envelope"></use></svg>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required
                                class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="admin@example.com"
                                @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                @input="$el.classList.remove('border-rose-500')"
                            >
                        </div>
                        <div x-show="document.getElementById('email').value === '' && document.getElementById('email').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                            Email wajib diisi
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400"><use href="#icon-lock-closed"></use></svg>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="block w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="••••••••"
                                @blur="if(!$el.value) $el.classList.add('border-rose-500')"
                                @input="$el.classList.remove('border-rose-500')"
                            >
                        </div>
                        <div x-show="document.getElementById('password').value === '' && document.getElementById('password').matches(':focus')" class="text-xs text-rose-600 dark:text-rose-400 mt-1" x-cloak>
                            Password wajib diisi
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-600 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800"
                    >
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>

            <!-- Dark Mode Toggle -->
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-center">
                <button 
                    @click="darkMode = !darkMode" 
                    class="flex items-center space-x-2 px-4 py-2 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                >
                    <svg x-show="!darkMode" class="w-5 h-5 text-slate-600 dark:text-slate-300"><use href="#icon-moon"></use></svg>
                    <svg x-show="darkMode" class="w-5 h-5 text-slate-600 dark:text-slate-300"><use href="#icon-sun"></use></svg>
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'" class="text-sm font-medium"></span>
                </button>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-500 dark:text-slate-400 text-sm mt-6">
            &copy; {{ date('Y') }} Admin Panel. All rights reserved.
        </p>
    </div>

    <!-- Icons -->
    <svg xmlns="http://www.w3.org/2000/svg" class="hidden">
        <symbol id="icon-envelope" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </symbol>
        <symbol id="icon-lock-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </symbol>
        <symbol id="icon-exclamation-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </symbol>
        <symbol id="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </symbol>
        <symbol id="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </symbol>
    </svg>
</body>
</html>
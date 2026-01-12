<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">{{ config('app.name') }}</h1>
            <p class="text-gray-600 mt-2">Masuk ke akun Anda</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- WhatsApp Field -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2 font-medium">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class='bx bxl-whatsapp text-green-500 text-xl'></i>
                        </div>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="0812 3456 7890"
                            required
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2 font-medium">Password</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class='bx bx-lock-alt text-xl'></i>
                        </div>
                        <input type="password" name="password" required placeholder="Masukkan password"
                            class="w-full pl-12 pr-10 py-3 border border-gray-300 rounded-lg
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded text-blue-500">
                        <span class="ml-2 text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold
                           transition-all shadow-lg flex items-center justify-center">
                    <i class='bx bx-log-in mr-2'></i>
                    <span>Masuk ke Akun</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-gray-500 text-sm">atau</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <!-- Guest Access -->
            <a href="{{ url('/') }}"
                class="block w-full text-center border-2 border-blue-600 text-blue-600
                       hover:bg-blue-600 hover:text-white py-3 rounded-lg font-medium transition-all mb-4">
                Lanjut sebagai Tamu
            </a>

            <!-- Register Link -->
            <div class="text-center">
                <span class="text-gray-600">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium ml-1">
                    Daftar di sini
                </a>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-800">
                <i class='bx bx-arrow-back mr-2'></i>Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const whatsapp = this.querySelector('[name="whatsapp"]').value.trim();
            const password = this.querySelector('[name="password"]').value.trim();

            if (!whatsapp) {
                e.preventDefault();
                alert('Masukkan nomor WhatsApp');
                return;
            }

            if (!password) {
                e.preventDefault();
                alert('Masukkan password');
                return;
            }
        });
    </script>
</body>

</html>

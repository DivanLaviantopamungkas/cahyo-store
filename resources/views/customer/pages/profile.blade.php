{{-- @extends('customer.layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div x-data="$store.app.init()" class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
                <p class="text-gray-600">Kelola akun dan informasi pribadi Anda</p>
            </div>

            <!-- Login/Profile Content -->
            <template x-if="!$store.app.isLoggedIn">
                <div class="max-w-md mx-auto">
                    <!-- Login Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                        <div class="text-center mb-8">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-primary to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class='bx bx-user text-white text-3xl'></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Masuk ke Akun Anda</h2>
                            <p class="text-gray-600 mt-2">Masuk dengan nomor WhatsApp untuk mengakses semua fitur</p>
                        </div>

                        <!-- WhatsApp Login Form -->
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2 font-medium">Nomor WhatsApp</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                    <i class='bx bxl-whatsapp text-green-500 text-xl'></i>
                                </div>
                                <input type="tel" id="loginPhone" placeholder="0812 3456 7890"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Kami akan mengirim kode verifikasi ke WhatsApp</p>
                        </div>

                        <!-- Login Button -->
                        <button
                            onclick="
                        const phone = document.getElementById('loginPhone').value;
                        if(phone) {
                            $store.app.login(phone);
                            showToast('Berhasil login!', 'success');
                        } else {
                            showToast('Masukkan nomor WhatsApp', 'warning');
                        }
                    "
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-3 rounded-lg font-semibold shadow-lg transition-all mb-4">
                            <i class='bx bxl-whatsapp mr-2'></i> Masuk dengan WhatsApp
                        </button>

                        <div class="text-center">
                            <p class="text-gray-600">Belum punya akun?
                                <button onclick="document.getElementById('loginPhone').focus()"
                                    class="text-primary hover:text-primary-dark font-medium">
                                    Daftar sekarang
                                </button>
                            </p>
                        </div>
                    </div>

                    <!-- Guest Features -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h3 class="font-bold text-gray-800 mb-4">Fitur untuk Tamu</h3>
                        <div class="space-y-4">
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class='bx bx-shopping-bag text-blue-600'></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Beli Produk</h4>
                                    <p class="text-sm text-gray-600">Tetap bisa beli produk tanpa login</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class='bx bx-support text-purple-600'></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Bantuan 24/7</h4>
                                    <p class="text-sm text-gray-600">Akses live chat WhatsApp kapan saja</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Profile Content (Logged In) -->
            <template x-if="$store.app.isLoggedIn">
                <div class="max-w-4xl mx-auto">
                    <!-- Profile Header -->
                    <div class="bg-gradient-to-r from-primary to-blue-600 rounded-2xl p-8 text-white mb-8 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30">
                                    <i class='bx bx-user text-3xl'></i>
                                </div>
                                <div class="ml-6">
                                    <h2 class="text-2xl font-bold" x-text="$store.app.userPhone"></h2>
                                    <p class="opacity-90">Member sejak Januari 2024</p>
                                </div>
                            </div>
                            <button @click="$store.app.logout()"
                                class="bg-white/20 hover:bg-white/30 backdrop-blur-sm px-6 py-2 rounded-lg font-medium transition-all">
                                <i class='bx bx-log-out mr-2'></i> Logout
                            </button>
                        </div>
                    </div>

                    <!-- Profile Content Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Profile Info -->
                        <div class="md:col-span-2">
                            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-6">Informasi Profil</h3>
                                <div class="space-y-6">
                                    <!-- WhatsApp Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <i class='bx bxl-whatsapp text-green-500 text-xl mr-3'></i>
                                            <span class="font-medium" x-text="$store.app.userPhone"></span>
                                            <span
                                                class="ml-auto bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                                Terverifikasi
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Additional Info -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Total
                                                Transaksi</label>
                                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                                <div class="text-2xl font-bold text-blue-600">28</div>
                                                <p class="text-sm text-gray-600">Pembelian berhasil</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Saldo Bonus</label>
                                            <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                                                <div class="text-2xl font-bold text-green-600">Rp 45.000</div>
                                                <p class="text-sm text-gray-600">Dapat digunakan untuk pembelian</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Orders -->
                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xl font-bold text-gray-800">Pesanan Terbaru</h3>
                                    <a href="{{ url('/pesanan') }}"
                                        class="text-primary hover:text-primary-dark font-medium">
                                        Lihat Semua
                                    </a>
                                </div>
                                <div class="space-y-4">
                                    @for ($i = 1; $i <= 3; $i++)
                                        <div
                                            class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center mr-4">
                                                    <i class='bx bxs-crown text-white'></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-800">Mobile Legends Diamonds</h4>
                                                    <p class="text-sm text-gray-600">INV/2024/001{{ $i }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-gray-800">Rp 50.000</div>
                                                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Menu -->
                        <div class="space-y-6">
                            <!-- Quick Actions -->
                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Cepat</h3>
                                <div class="space-y-3">
                                    <a href="{{ url('/pesanan') }}"
                                        class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all">
                                        <i class='bx bx-package mr-3 text-primary'></i>
                                        <span>Pesanan Saya</span>
                                    </a>
                                    <a href="{{ url('/notifikasi') }}"
                                        class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all">
                                        <i class='bx bx-bell mr-3 text-yellow-500'></i>
                                        <span>Notifikasi</span>
                                        <span
                                            class="ml-auto bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">3</span>
                                    </a>
                                    <a href="{{ url('/bantuan') }}"
                                        class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all">
                                        <i class='bx bx-support mr-3 text-green-500'></i>
                                        <span>Bantuan</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Membership -->
                            <div
                                class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl shadow-lg p-6 text-white">
                                <h3 class="text-lg font-bold mb-4">Status Membership</h3>
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <span>Silver Member</span>
                                        <span>Level 2</span>
                                    </div>
                                    <div class="w-full bg-white/30 rounded-full h-2 mb-1">
                                        <div class="bg-white h-2 rounded-full" style="width: 60%"></div>
                                    </div>
                                    <p class="text-sm opacity-90">60% menuju Gold Member</p>
                                </div>
                                <button
                                    class="w-full bg-white text-purple-600 hover:bg-gray-100 py-2 rounded-lg font-medium transition-all">
                                    Lihat Benefit
                                </button>
                            </div>

                            <!-- Settings -->
                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Pengaturan</h3>
                                <div class="space-y-3">
                                    <button
                                        class="flex items-center w-full p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all text-left">
                                        <i class='bx bx-bell mr-3 text-gray-600'></i>
                                        <span>Notifikasi</span>
                                    </button>
                                    <button
                                        class="flex items-center w-full p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all text-left">
                                        <i class='bx bx-shield mr-3 text-gray-600'></i>
                                        <span>Keamanan</span>
                                    </button>
                                    <button
                                        class="flex items-center w-full p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all text-left">
                                        <i class='bx bx-help-circle mr-3 text-gray-600'></i>
                                        <span>Pusat Bantuan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection --}}




<!-- Claude -->
@extends('customer.layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 md:py-8">
    <div class="max-w-4xl mx-auto px-4">
        
        <!-- Header Profile Card -->
        <div class="bg-gradient-to-br from-primary to-blue-600 rounded-2xl shadow-lg p-6 md:p-8 mb-6 text-white">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                <!-- Avatar -->
                <div class="relative">
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 flex items-center justify-center shadow-xl">
                        <span class="text-4xl md:text-5xl font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-green-500 w-8 h-8 rounded-full border-4 border-white flex items-center justify-center">
                        <i class='bx bx-check text-white text-sm'></i>
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ Auth::user()->name }}</h1>
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-2 md:space-y-0 md:space-x-4 text-white/90">
                        <div class="flex items-center">
                            <i class='bx bx-phone mr-2'></i>
                            <span>{{ Auth::user()->whatsapp }}</span>
                        </div>
                        @if(Auth::user()->email)
                        <div class="flex items-center">
                            <i class='bx bx-envelope mr-2'></i>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Balance Card -->
                    <div class="mt-4 inline-flex items-center bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full border border-white/30">
                        <i class='bx bx-wallet text-2xl mr-3'></i>
                        <div class="text-left">
                            <div class="text-xs text-white/80">Saldo Saya</div>
                            <div class="text-xl font-bold">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div x-data="{ activeTab: 'info' }" class="bg-white rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <div class="flex">
                    <button @click="activeTab = 'info'" 
                            :class="activeTab === 'info' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-6 text-center font-medium border-b-2 transition-colors">
                        <i class='bx bx-info-circle mr-2'></i>
                        <span class="hidden sm:inline">Informasi</span>
                    </button>
                    <button @click="activeTab = 'edit'" 
                            :class="activeTab === 'edit' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-6 text-center font-medium border-b-2 transition-colors">
                        <i class='bx bx-edit mr-2'></i>
                        <span class="hidden sm:inline">Edit Profile</span>
                    </button>
                    <button @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-6 text-center font-medium border-b-2 transition-colors">
                        <i class='bx bx-shield mr-2'></i>
                        <span class="hidden sm:inline">Keamanan</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6 md:p-8">
                
                <!-- Info Tab -->
                <div x-show="activeTab === 'info'" x-transition.opacity>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class='bx bx-user-circle text-primary mr-2'></i>
                        Informasi Akun
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mr-4 flex-shrink-0">
                                <i class='bx bx-user text-primary text-xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-1">Nama Lengkap</div>
                                <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4 flex-shrink-0">
                                <i class='bx bxl-whatsapp text-green-600 text-xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-1">WhatsApp</div>
                                <div class="font-semibold text-gray-800">{{ Auth::user()->whatsapp }}</div>
                            </div>
                        </div>

                        @if(Auth::user()->email)
                        <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4 flex-shrink-0">
                                <i class='bx bx-envelope text-blue-600 text-xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-1">Email</div>
                                <div class="font-semibold text-gray-800">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4 flex-shrink-0">
                                <i class='bx bx-wallet text-yellow-600 text-xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-1">Saldo</div>
                                <div class="font-bold text-gray-800 text-lg">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-gray-50 rounded-xl">
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-4 flex-shrink-0">
                                <i class='bx bx-check-circle text-purple-600 text-xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-1">Status Akun</div>
                                <div class="font-semibold">
                                    @if(Auth::user()->is_active)
                                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                            <i class='bx bx-check-circle mr-1'></i> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                                            <i class='bx bx-x-circle mr-1'></i> Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Tab -->
                <div x-show="activeTab === 'edit'" x-transition.opacity>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class='bx bx-edit text-primary mr-2'></i>
                        Edit Profile
                    </h2>
                    
                    <form method="POST" action="{{ route('user.index') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-user mr-1'></i> Nama Lengkap
                            </label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bxl-whatsapp mr-1'></i> WhatsApp
                            </label>
                            <input type="text" name="whatsapp" value="{{ Auth::user()->whatsapp }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Format: 08xxxxxxxxxx (10-15 digit)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-envelope mr-1'></i> Email (Opsional)
                            </label>
                            <input type="email" name="email" value="{{ Auth::user()->email }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        </div>

                        <button type="submit" 
                                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-lg transition-all shadow-sm hover:shadow-md">
                            <i class='bx bx-save mr-2'></i>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" x-transition.opacity>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class='bx bx-shield text-primary mr-2'></i>
                        Ubah Password
                    </h2>
                    
                    <form method="POST" action="{{ route('user.index') }}/change-password" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-lock mr-1'></i> Password Lama
                            </label>
                            <input type="password" name="current_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-lock-alt mr-1'></i> Password Baru
                            </label>
                            <input type="password" name="new_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-lock-open mr-1'></i> Konfirmasi Password Baru
                            </label>
                            <input type="password" name="new_password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   required>
                        </div>

                        <button type="submit" 
                                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-lg transition-all shadow-sm hover:shadow-md">
                            <i class='bx bx-key mr-2'></i>
                            Ubah Password
                        </button>
                    </form>

                    <!-- Security Info -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class='bx bx-info-circle text-blue-600 text-xl mr-3 mt-0.5'></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Tips Keamanan:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-700">
                                    <li>Gunakan password yang kuat dan unik</li>
                                    <li>Jangan bagikan password ke siapapun</li>
                                    <li>Ubah password secara berkala</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-4 mt-6">
            <a href="{{ route('orders.index') }}" 
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all text-center group">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <i class='bx bx-package text-primary text-2xl'></i>
                </div>
                <div class="font-semibold text-gray-800">Pesanan Saya</div>
                <div class="text-xs text-gray-500 mt-1">Lihat riwayat pesanan</div>
            </a>

            <a href="{{ route('help.index') }}" 
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all text-center group">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200 transition-colors">
                    <i class='bx bx-support text-green-600 text-2xl'></i>
                </div>
                <div class="font-semibold text-gray-800">Bantuan</div>
                <div class="text-xs text-gray-500 mt-1">Hubungi customer service</div>
            </a>
        </div>

    </div>
</div>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@endsection
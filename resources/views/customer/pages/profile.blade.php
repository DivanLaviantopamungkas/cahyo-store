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
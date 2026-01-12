<!-- Login Modal -->
<div x-data="loginModal()" x-show="showModal" x-cloak @keydown.escape="closeModal"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50" @click="closeModal"></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all"
            @click.outside="closeModal">

            <!-- Header -->
            <div class="px-8 pt-8 pb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800"
                        x-text="isRegister ? 'Daftar Akun Baru' : 'Masuk ke Akun Anda'"></h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>

                <!-- Toggle -->
                <div class="flex mb-6">
                    <button @click="showLoginForm"
                        :class="!isRegister ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700'"
                        class="flex-1 py-3 rounded-l-lg font-medium transition-all">
                        Masuk
                    </button>
                    <button @click="showRegisterForm"
                        :class="isRegister ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700'"
                        class="flex-1 py-3 rounded-r-lg font-medium transition-all">
                        Daftar Baru
                    </button>
                </div>

                <!-- Register Form -->
                <template x-if="isRegister">
                    <div>
                        <!-- Name Field -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class='bx bx-user text-xl'></i>
                                </div>
                                <input type="text" x-model="registerData.name" placeholder="Masukkan nama lengkap"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <!-- WhatsApp Field -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Nomor WhatsApp</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class='bx bxl-whatsapp text-green-500 text-xl'></i>
                                </div>
                                <input type="tel" x-model="registerData.whatsapp" placeholder="0812 3456 7890"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class='bx bx-lock-alt text-xl'></i>
                                </div>
                                <input :type="showRegisterPassword ? 'text' : 'password'"
                                    x-model="registerData.password" placeholder="Buat password"
                                    class="w-full pl-12 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <button type="button" @click="showRegisterPassword = !showRegisterPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i :class="showRegisterPassword ? 'bx bx-hide' : 'bx bx-show'" class="text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class='bx bx-lock-alt text-xl'></i>
                                </div>
                                <input :type="showRegisterConfirmPassword ? 'text' : 'password'"
                                    x-model="registerData.password_confirmation" placeholder="Ulangi password"
                                    class="w-full pl-12 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <button type="button"
                                    @click="showRegisterConfirmPassword = !showRegisterConfirmPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i :class="showRegisterConfirmPassword ? 'bx bx-hide' : 'bx bx-show'"
                                        class="text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button @click="submitRegister" :disabled="isLoading"
                            :class="isLoading ? 'opacity-75 cursor-not-allowed' : ''"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3 rounded-lg font-semibold transition-all shadow-lg flex items-center justify-center">
                            <template x-if="isLoading">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            <i class='bx bx-user-plus mr-2' x-show="!isLoading"></i>
                            <span x-text="isLoading ? 'Memproses...' : 'Daftar Sekarang'"></span>
                        </button>
                    </div>
                </template>

                <!-- Login Form -->
                <template x-if="!isRegister">
                    <div>
                        <!-- WhatsApp Field -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Nomor WhatsApp</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class='bx bxl-whatsapp text-green-500 text-xl'></i>
                                </div>
                                <input type="tel" x-model="loginData.whatsapp" placeholder="0812 3456 7890"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class='bx bx-lock-alt text-xl'></i>
                                </div>
                                <input :type="showLoginPassword ? 'text' : 'password'" x-model="loginData.password"
                                    placeholder="Masukkan password"
                                    class="w-full pl-12 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <button type="button" @click="showLoginPassword = !showLoginPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i :class="showLoginPassword ? 'bx bx-hide' : 'bx bx-show'" class="text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="text-right mb-6">
                            <button @click="showForgotPassword"
                                class="text-primary hover:text-primary-dark text-sm font-medium">
                                Lupa Password?
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <button @click="submitLogin" :disabled="isLoading"
                            :class="isLoading ? 'opacity-75 cursor-not-allowed' : ''"
                            class="w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white py-3 rounded-lg font-semibold transition-all shadow-lg flex items-center justify-center">
                            <template x-if="isLoading">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            <i class='bx bx-log-in mr-2' x-show="!isLoading"></i>
                            <span x-text="isLoading ? 'Memproses...' : 'Masuk ke Akun'"></span>
                        </button>
                    </div>
                </template>

                <!-- Divider -->
                <div class="flex items-center my-6" x-show="!isRegister">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-gray-500 text-sm">atau</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <!-- Guest Access -->
                <button x-show="!isRegister" @click="closeModal"
                    class="w-full border-2 border-primary text-primary hover:bg-primary hover:text-white py-3 rounded-lg font-medium transition-all">
                    Lanjut sebagai Tamu
                </button>

                <!-- Switch Form Link -->
                <div class="text-center mt-6">
                    <span class="text-gray-600"
                        x-text="isRegister ? 'Sudah punya akun?' : 'Belum punya akun?'"></span>
                    <button @click="toggleForm" class="text-primary hover:text-primary-dark font-medium ml-1">
                        <span x-text="isRegister ? 'Masuk di sini' : 'Daftar di sini'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi Toast Notification
    function showToast(message, type = 'info') {
        // Hapus toast lama jika ada
        const oldToast = document.querySelector('.alpine-toast');
        if (oldToast) oldToast.remove();

        const toast = document.createElement('div');
        toast.className = `alpine-toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        } text-white`;
        toast.textContent = message;
        document.body.appendChild(toast);

        // Animasi masuk
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);

        // Hapus setelah 3 detik
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Alpine.js Data untuk modal
    document.addEventListener('alpine:init', () => {
        Alpine.data('loginModal', () => ({
            showModal: false,
            isRegister: false,
            isLoading: false,
            showLoginPassword: false,
            showRegisterPassword: false,
            showRegisterConfirmPassword: false,

            loginData: {
                whatsapp: '',
                password: ''
            },

            registerData: {
                name: '',
                whatsapp: '',
                password: '',
                password_confirmation: ''
            },

            init() {
                // Listen untuk event open modal dari store
                Alpine.effect(() => {
                    if (Alpine.store('app')?.showLoginModal) {
                        this.openModal();
                    }
                });
            },

            openModal() {
                this.showModal = true;
                document.body.style.overflow = 'hidden';
            },

            closeModal() {
                this.showModal = false;
                this.resetForms();
                document.body.style.overflow = 'auto';

                // Update store
                if (Alpine.store('app')) {
                    Alpine.store('app').showLoginModal = false;
                }
            },

            showLoginForm() {
                this.isRegister = false;
                this.resetForms();
            },

            showRegisterForm() {
                this.isRegister = true;
                this.resetForms();
            },

            toggleForm() {
                this.isRegister = !this.isRegister;
                this.resetForms();
            },

            resetForms() {
                this.loginData = {
                    whatsapp: '',
                    password: ''
                };
                this.registerData = {
                    name: '',
                    whatsapp: '',
                    password: '',
                    password_confirmation: ''
                };
                this.showLoginPassword = false;
                this.showRegisterPassword = false;
                this.showRegisterConfirmPassword = false;
            },

            validateLogin() {
                if (!this.loginData.whatsapp.trim()) {
                    showToast('Masukkan nomor WhatsApp', 'warning');
                    return false;
                }

                if (!this.loginData.password.trim()) {
                    showToast('Masukkan password', 'warning');
                    return false;
                }

                return true;
            },

            validateRegister() {
                if (!this.registerData.name.trim()) {
                    showToast('Masukkan nama lengkap', 'warning');
                    return false;
                }

                if (!this.registerData.whatsapp.trim()) {
                    showToast('Masukkan nomor WhatsApp', 'warning');
                    return false;
                }

                if (!this.registerData.password.trim()) {
                    showToast('Masukkan password', 'warning');
                    return false;
                }

                if (this.registerData.password !== this.registerData.password_confirmation) {
                    showToast('Password tidak cocok', 'error');
                    return false;
                }

                if (this.registerData.password.length < 6) {
                    showToast('Password minimal 6 karakter', 'warning');
                    return false;
                }

                return true;
            },

            async submitLogin() {
                if (this.isLoading || !this.validateLogin()) return;

                this.isLoading = true;

                try {
                    const response = await fetch('/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify(this.loginData)
                    });

                    const data = await response.json();
                    console.log('Login response:', data);

                    if (data.success) {
                        // Simpan ke localStorage
                        localStorage.setItem('token', data.data.token);
                        localStorage.setItem('user', JSON.stringify(data.data.user));

                        // Simpan ke store
                        if (Alpine.store('app')) {
                            Alpine.store('app').token = data.data.token;
                            Alpine.store('app').user = data.data.user;
                            Alpine.store('app').isLoggedIn = true;

                            console.log('Store updated after login:', {
                                token: Alpine.store('app').token ? 'Yes' : 'No',
                                user: Alpine.store('app').user,
                                isLoggedIn: Alpine.store('app').isLoggedIn
                            });
                        }

                        showToast('Login berhasil!', 'success');
                        this.closeModal();

                        // Reload halaman untuk update UI
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        if (data.errors) {
                            const firstError = Object.values(data.errors)[0][0];
                            showToast(firstError, 'error');
                        } else {
                            showToast(data.message || 'Login gagal', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    showToast('Terjadi kesalahan jaringan', 'error');
                } finally {
                    this.isLoading = false;
                }
            }

            async submitRegister() {
                if (this.isLoading || !this.validateRegister()) return;

                this.isLoading = true;

                try {
                    const response = await fetch('/auth/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify(this.registerData)
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Simpan data ke store dan localStorage
                        if (Alpine.store('app')) {
                            Alpine.store('app').token = data.data.token;
                            Alpine.store('app').user = data.data.user;
                            Alpine.store('app').isLoggedIn = true;
                        }

                        localStorage.setItem('token', data.data.token);
                        localStorage.setItem('user', JSON.stringify(data.data.user));
                        localStorage.setItem('isLoggedIn', 'true');

                        showToast('Pendaftaran berhasil!', 'success');
                        this.closeModal();

                        // Reload halaman untuk update UI
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        if (data.errors) {
                            const firstError = Object.values(data.errors)[0][0];
                            showToast(firstError, 'error');
                        } else {
                            showToast(data.message || 'Pendaftaran gagal', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Register error:', error);
                    showToast('Terjadi kesalahan jaringan', 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            showForgotPassword() {
                this.closeModal();
                showToast('Fitur lupa password akan segera hadir', 'info');
            }
        }));

        // Store utama untuk aplikasi
        Alpine.store('app', {
            showLoginModal: false,
            user: JSON.parse(localStorage.getItem('user')) || null,
            token: localStorage.getItem('token') || null,
            isLoggedIn: localStorage.getItem('isLoggedIn') === 'true' || false,
            cartItems: [],

            init() {
                console.log('App store initializing...');

                // Cek dan sync localStorage dengan store - PENTING!
                this.syncFromLocalStorage();

                // Cek token validitas jika ada
                if (this.token && this.user) {
                    this.verifyTokenSilently();
                }
            },

            syncFromLocalStorage() {
                console.log('Syncing from localStorage...');

                // Ambil data dari localStorage
                const storedToken = localStorage.getItem('token');
                const storedUser = localStorage.getItem('user');

                console.log('LocalStorage token:', storedToken ? 'Found' : 'Not found');
                console.log('LocalStorage user:', storedUser ? 'Found' : 'Not found');

                // Update store dengan data dari localStorage
                this.token = storedToken;

                if (storedUser) {
                    try {
                        this.user = JSON.parse(storedUser);
                    } catch (e) {
                        console.error('Failed to parse user from localStorage:', e);
                        this.user = null;
                    }
                } else {
                    this.user = null;
                }

                // Update isLoggedIn berdasarkan token DAN user
                this.isLoggedIn = !!(this.token && this.user);

                console.log('After sync - Token:', this.token ? 'Yes' : 'No');
                console.log('After sync - User:', this.user);
                console.log('After sync - isLoggedIn:', this.isLoggedIn);
            },

            async verifyTokenSilently() {
                try {
                    const response = await fetch('/user/profile', {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${this.token}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        // Token invalid, bersihkan
                        this.clearAuthState();
                        return false;
                    }

                    const data = await response.json();
                    if (data.success) {
                        // Update user data jika ada perubahan
                        this.user = data.data.user;
                        localStorage.setItem('user', JSON.stringify(this.user));
                        return true;
                    }
                } catch (error) {
                    // Jika ada error jaringan, anggap token masih valid
                    // Tapi jangan bersihkan, biarkan user tetap login
                    console.warn('Token verification failed (network):', error);
                    return true;
                }
            },

            async getUserProfile() {
                try {
                    const response = await fetch('/user/profile', {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${this.token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.user = data.data.user;
                        localStorage.setItem('user', JSON.stringify(this.user));
                    } else {
                        // Jika gagal, mungkin token expired
                        this.clearAuthState();
                    }
                } catch (error) {
                    console.error('Get profile error:', error);
                    this.clearAuthState();
                }
            },

            logout() {
                const token = this.token || localStorage.getItem('token');

                if (token) {
                    // Kirim request logout ke server
                    fetch('/auth/logout', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.content || ''
                            }
                        })
                        .then(response => response.json())
                        .catch(error => {
                            console.error('Logout API error:', error);
                        })
                        .finally(() => {
                            // Tetap bersihkan local storage meskipun server error
                            this.clearAuthState();
                            this.redirectAfterLogout();
                        });
                } else {
                    this.clearAuthState();
                    this.redirectAfterLogout();
                }
            },

            clearAuthState() {
                // Hapus semua data auth dari localStorage
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.removeItem('isLoggedIn');

                // Reset state di store
                this.token = null;
                this.user = null;
                this.isLoggedIn = false;

                // Hapus juga dari sessionStorage untuk memastikan
                sessionStorage.removeItem('token');
                sessionStorage.removeItem('user');
                sessionStorage.removeItem('isLoggedIn');
            },

            redirectAfterLogout() {
                showToast('Logout berhasil', 'success');

                // Redirect ke halaman utama setelah 500ms
                setTimeout(() => {
                    // Jika tidak di halaman utama, redirect ke halaman utama
                    if (window.location.pathname !== '/') {
                        window.location.href = '/';
                    } else {
                        // Jika sudah di halaman utama, reload saja
                        window.location.reload();
                    }
                }, 500);
            },

            addToCart(item) {
                this.cartItems.push(item);
                showToast('Ditambahkan ke keranjang', 'success');
            }
        });
    });

    // Tambahkan event listener untuk sinkronisasi state
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi store jika belum ada
        if (window.Alpine && Alpine.store('app')) {
            Alpine.store('app').init();
        }

        // Listen untuk storage changes (jika ada multiple tabs)
        window.addEventListener('storage', function(e) {
            if (e.key === 'isLoggedIn' || e.key === 'token' || e.key === 'user') {
                // Force reload untuk sinkronisasi semua tab
                window.location.reload();
            }
        });
    });
</script>

<style>
    [x-cloak] {
        display: none !important;
    }

    .alpine-toast {
        transform: translateX(100%);
    }
</style>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ isLoggedIn: false, showLoginModal: false, cartCount: 0 }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TopUp Gaming') - Top Up Game & Voucher</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎮</text></svg>">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        'primary-dark': '#1d4ed8',
                        secondary: '#f59e0b',
                        'secondary-dark': '#d97706',
                        dark: '#1f2937',
                        light: '#f9fafb'
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif']
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'pulse-slow': 'pulse 2s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        slideDown: {
                            '0%': {
                                transform: 'translateY(-20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-poppins min-h-screen">
    <!-- Desktop Navigation -->
    <div class="hidden md:block">
        @include('customer.components.navbar')
    </div>

    <!-- Main Content -->
    <main class="pb-16 md:pb-0 min-h-screen">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 md:hidden z-50">
        @include('customer.components.bottom-nav')
    </div>

    <!-- Custom Scripts -->
    <script>
        // Simple state management
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                isLoggedIn: false,
                cartItems: [],
                userPhone: null,

                login(phone) {
                    this.isLoggedIn = true;
                    this.userPhone = phone;
                    localStorage.setItem('userPhone', phone);
                    localStorage.setItem('isLoggedIn', 'true');
                    showToast('Berhasil login!', 'success');
                },

                logout() {
                    this.isLoggedIn = false;
                    this.userPhone = null;
                    localStorage.removeItem('userPhone');
                    localStorage.removeItem('isLoggedIn');
                    showToast('Berhasil logout!', 'info');
                },

                addToCart(item) {
                    this.cartItems.push(item);
                    showToast('Ditambahkan ke keranjang', 'success');
                },

                init() {
                    const savedLogin = localStorage.getItem('isLoggedIn');
                    const savedPhone = localStorage.getItem('userPhone');

                    if (savedLogin === 'true' && savedPhone) {
                        this.isLoggedIn = true;
                        this.userPhone = savedPhone;
                    }
                }
            });

            Alpine.data('productDetail', () => ({
                selectedNominal: null,
                showPayment: false,
                showQRIS: false,

                selectNominal(nominal) {
                    this.selectedNominal = nominal;
                    this.showPayment = true;
                    this.showQRIS = false;

                    // Scroll to payment section
                    setTimeout(() => {
                        document.getElementById('payment-section').scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 100);
                },

                proceedToPayment() {
                    this.showQRIS = true;
                    showToast('QRIS berhasil dibuat!', 'success');

                    setTimeout(() => {
                        document.getElementById('qris-section').scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 100);
                }
            }));
        });

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            } text-white`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 3000);
        }
    </script>
</body>

</html>

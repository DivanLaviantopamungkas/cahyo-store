<?php

use Illuminate\Support\Facades\Route;

// CUSTOMER
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CategoryController as CustomerCategoryController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;

// PAYMENT
use App\Http\Controllers\Payment\TokopayController;

// WEBHOOK
use App\Http\Controllers\Webhook\TokopayWebhookController;

// ADMIN
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductNominalController;
use App\Http\Controllers\Admin\VoucherCodeController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DigiflazzController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PaymentWebhookController;
use App\Http\Controllers\Customer\CustomerControllers;
use App\Http\Controllers\Customer\HelpController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\ProductController as CustomerGuiProductController;
use App\Http\Controllers\Customer\CategoryController as CustomerGuiCategoryController;
use App\Http\Controllers\Customer\UserController;
use App\Http\Middleware\CheckCheckoutAccess;

/*
|--------------------------------------------------------------------------
| Public (tanpa login)
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
// Halaman Utama (Bisa diakses tanpa login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.redirect');

// routes/web.php
Route::name('products.')->group(function () {
    // Halaman utama produk
    Route::get('/produk', [CustomerGuiProductController::class, 'index'])->name('index');

    // Route untuk produk manual
    Route::get('/produk/manual/{slug}', [CustomerGuiProductController::class, 'showManual'])->name('manual.show');

    // Route untuk produk digiflazz
    Route::get('/produk/digiflazz/{slug}', [CustomerGuiProductController::class, 'showDigiflazz'])->name('digiflazz.show');

    // Kategori produk
    Route::get('/kategori', [CustomerGuiProductController::class, 'category'])->name('category.index');
    Route::get('/kategori/{slug}', [CustomerGuiProductController::class, 'categoryShow'])->name('category.show');

    // Pencarian produk
    Route::get('/cari', [CustomerGuiProductController::class, 'search'])->name('search');

    // API endpoints
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/featured-products', [CustomerGuiProductController::class, 'featuredProducts'])->name('featured');
        Route::get('/check-stock/{id}', [CustomerGuiProductController::class, 'checkStock'])->name('check-stock');
        Route::get('/{slug}/reviews', [CustomerGuiProductController::class, 'reviews'])->name('reviews');
    });
});

// Kategori
Route::prefix('kategori')->name('categories.')->group(function () {
    Route::get('/', [CustomerGuiCategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [CustomerGuiCategoryController::class, 'show'])->name('show');
});

// Bantuan & Support (Bisa diakses tanpa login)
Route::prefix('bantuan')->name('help.')->group(function () {
    Route::get('/', [HelpController::class, 'index'])->name('index');
    Route::get('/faq', [HelpController::class, 'faq'])->name('faq');
    Route::get('/kontak', [HelpController::class, 'contact'])->name('contact');
    Route::get('/cara-order', [HelpController::class, 'howToOrder'])->name('how-to-order');
});

// Notifikasi (Bisa diakses tanpa login, tapi kosong kalau belum login)
Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');

// ==================== AUTH ROUTES ====================
// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.post');
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->name('register.post');
});

Route::post('/payment/webhook/midtrans', [PaymentWebhookController::class, 'midtrans'])
    ->name('webhook.midtrans');
Route::post('/payment/webhook/test', [PaymentWebhookController::class, 'testWebhook'])
    ->name('webhook.test');
Route::get('/checkout/handle-midtrans-return', [CheckoutController::class, 'handleMidtransReturn'])
    ->name('checkout.handle.midtrans.return');

Route::get('/checkout/{product_slug}', [CheckoutController::class, 'create'])
    ->middleware([CheckCheckoutAccess::class])
    ->name('checkout.create');

// Logout
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Route::get('/checkout/{product_slug}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failed/{order_id}', [CheckoutController::class, 'failed'])->name('checkout.failed');
    Route::get('/checkout/validate', [CheckoutController::class, 'validatePayment'])->name('checkout.validate');

    // Pesanan (WAJIB LOGIN)
    Route::prefix('pesanan')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order_id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order_id}/invoice', [OrderController::class, 'invoice'])->name('invoice');
        Route::post('/{order_id}/reorder', [OrderController::class, 'reorder'])->name('reorder');
    });

    // Profil (WAJIB LOGIN untuk edit, view bisa semua)
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/profile', [UserController::class, 'profile'])->name('index');
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/logout', [UserController::class, 'logout']);
    });

    // Notifikasi Detail (WAJIB LOGIN)
    Route::prefix('notifikasi')->name('notifications.')->group(function () {
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
        Route::delete('/clear', [NotificationController::class, 'clear'])->name('clear');
    });
});

// ==================== PROTECTED ROUTES (WAJIB LOGIN) ====================



/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
| Catatan: sementara pakai middleware auth biasa.
| Nanti kalau sudah bikin guard admin, ganti middleware ke 'auth:admin'.
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // hanya untuk guest admin
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // logout hanya untuk admin yang sudah login
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master data
    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');

        // Manual Product Routes
        Route::get('/create/manual', [ProductController::class, 'createManual'])->name('create.manual');
        Route::post('/store/manual', [ProductController::class, 'storeManual'])->name('store.manual');

        // Digiflazz Product Routes
        Route::get('/create/digiflazz', [ProductController::class, 'createDigiflazz'])->name('create.digiflazz');
        Route::post('/store/digiflazz', [ProductController::class, 'storeDigiflazz'])->name('store.digiflazz');

        // Common routes
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');

        // Custom routes untuk products
        Route::post('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{product}/order-up', [ProductController::class, 'orderUp'])->name('order-up');
        Route::post('/{product}/order-down', [ProductController::class, 'orderDown'])->name('order-down');
        Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/providers/{provider}/products', [ProductController::class, 'getProviderProducts'])
            ->name('provider-products');
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');

        // Custom routes
        Route::post('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{category}/order-up', [CategoryController::class, 'orderUp'])->name('order-up');
        Route::post('/{category}/order-down', [CategoryController::class, 'orderDown'])->name('order-down');
        Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('bulk-action');
    });

    // Nominals
    Route::prefix('nominals')->name('nominals.')->group(function () {
        Route::get('/', [ProductNominalController::class, 'index'])->name('index');
        Route::get('/create', [ProductNominalController::class, 'create'])->name('create');
        Route::post('/', [ProductNominalController::class, 'store'])->name('store');
        Route::get('/{nominal}', [ProductNominalController::class, 'show'])->name('show');
        Route::get('/{nominal}/edit', [ProductNominalController::class, 'edit'])->name('edit');
        Route::put('/{nominal}', [ProductNominalController::class, 'update'])->name('update');
        Route::delete('/{nominal}', [ProductNominalController::class, 'destroy'])->name('destroy');

        // Custom routes untuk nominals
        Route::post('/{nominal}/toggle-status', [ProductNominalController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{nominal}/order-up', [ProductNominalController::class, 'orderUp'])->name('order-up');
        Route::post('/{nominal}/order-down', [ProductNominalController::class, 'orderDown'])->name('order-down');
        Route::post('/bulk-action', [ProductNominalController::class, 'bulkAction'])->name('bulk-action');

        // Export/Import
        Route::get('/export', [ProductNominalController::class, 'export'])->name('export');
        Route::get('/import', [ProductNominalController::class, 'importForm'])->name('import.form');
        Route::post('/import', [ProductNominalController::class, 'import'])->name('import');
    });

    Route::resource('voucher-codes', \App\Http\Controllers\Admin\VoucherCodeController::class);

    // Voucher Codes Additional Routes
    Route::get('voucher-codes/nominals/{product}', [VoucherCodeController::class, 'getNominalsByProduct'])
        ->name('voucher-codes.nominals');

    Route::post('voucher-codes/import', [VoucherCodeController::class, 'import'])
        ->name('voucher-codes.import');

    Route::post('voucher-codes/{voucherCode}/reserve', [VoucherCodeController::class, 'reserve'])
        ->name('voucher-codes.reserve');

    Route::post('voucher-codes/{voucherCode}/unreserve', [VoucherCodeController::class, 'unreserve'])
        ->name('voucher-codes.unreserve');



    // Transaksi (monitor)
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

    // Transaksi (aksi manual)
    Route::post('transactions/{transaction}/mark-processing', [TransactionController::class, 'markProcessing'])->name('transactions.mark-processing');
    Route::post('transactions/{transaction}/mark-completed', [TransactionController::class, 'markCompleted'])->name('transactions.mark-completed');

    // Broadcast
    Route::resource('broadcasts', BroadcastController::class);
    Route::post('broadcasts/{broadcast}/send', [BroadcastController::class, 'send'])->name('broadcasts.send');

    Route::prefix('settings')->name('settings.')->group(function () {
        // Halaman utama dengan tab (opsional, bisa dihapus nanti)
        Route::get('/', [SettingController::class, 'index'])->name('index');

        // Halaman spesifik per kategori
        Route::get('/general', [SettingController::class, 'general'])->name('general');
        Route::put('/general', [SettingController::class, 'updateGeneral'])->name('update.general');

        Route::get('/landing', [SettingController::class, 'landing'])->name('landing');
        Route::put('/landing', [SettingController::class, 'updateLanding'])->name('update.landing');

        Route::get('/providers', [SettingController::class, 'providers'])->name('providers');

        Route::get('/contact', [SettingController::class, 'contact'])->name('contact');
        Route::put('/contact', [SettingController::class, 'updateContact'])->name('update.contact');

        Route::get('/social', [SettingController::class, 'social'])->name('social');
        Route::put('/social', [SettingController::class, 'updateSocial'])->name('update.social');

        Route::get('/payment', [SettingController::class, 'payment'])->name('payment');
        Route::put('/payment', [SettingController::class, 'updatePayment'])->name('update.payment');

        // Provider routes
        Route::prefix('providers')->name('providers.')->group(function () {
            Route::put('/{code}', [SettingController::class, 'updateProvider'])->name('update');
            Route::post('/{code}/test', [SettingController::class, 'testProvider'])->name('test');
            Route::get('/{code}/sync', [SettingController::class, 'syncProvider'])->name('sync');
        });
    });

    // DigiFlazz sync (ambil daftar harga/produk)
    Route::post('digiflazz/sync', [DigiflazzController::class, 'syncPriceList'])->name('digiflazz.sync');

    Route::get('members', [MemberController::class, 'index'])->name('members.index');
    Route::post('members/{user}/toggle', [MemberController::class, 'toggle'])->name('members.toggle');
});

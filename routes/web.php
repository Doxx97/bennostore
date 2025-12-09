<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController; 
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;       
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\OrderController;


// UTILITY: Storage Link (Hanya untuk developer)
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully!';
});

// === 1. HALAMAN DEPAN ===
Route::get('/', [HomeController::class, 'index'])->name('home');


// === 2. ROUTE GUEST (BELUM LOGIN) ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// === 3. ROUTE USER (SUDAH LOGIN) ===
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Pembayaran (Payment) & Upload Bukti
    // PERBAIKAN: Menggunakan OrderController karena logika Timer & Upload ada di sana
    Route::get('/checkout/payment/{id}', [OrderController::class, 'showPayment'])->name('checkout.payment');
    Route::post('/checkout/confirm/{id}', [OrderController::class, 'confirmPayment'])->name('checkout.confirm');

    // Riwayat Pesanan Saya
    Route::get('/my-orders', [OrderController::class, 'index'])->name('my.orders');
    Route::post('/order/{id}/complete', [OrderController::class, 'markAsCompleted'])->name('order.complete');
    
    // Cetak Struk / Invoice
    Route::get('/order/{id}/print', [OrderController::class, 'printInvoice'])->name('order.print');

    // Notifikasi User
    Route::get('/user-notifications/read', [OrderController::class, 'markNotificationRead'])->name('user.notif.read');
});


// === 4. ROUTE ADMIN ===
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    // Pastikan user biasa tidak bisa akses ini (biasanya pakai middleware 'admin' tambahan)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifications/read', [AdminController::class, 'markAsRead'])->name('notifications.read');

    // Manajemen Order
    Route::post('/order/{id}/confirm', [AdminController::class, 'confirmPayment'])->name('order.confirm');
    Route::post('/order/{id}/ship', [AdminController::class, 'markAsShipped'])->name('order.ship');
    // Jika admin bisa menyelesaikan order manual:
    Route::post('/order/{id}/complete', [OrderController::class, 'markAsCompleted'])->name('order.complete');

    // Manajemen Produk (CRUD)
    Route::resource('products', AdminProductController::class);
    
    // Export Laporan
    Route::get('/orders/export', [AdminController::class, 'exportOrders'])->name('orders.export'); 
});

// --- PASSWORD RESET ROUTES ---
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
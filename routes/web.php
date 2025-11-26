<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController; 
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;       
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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

    // Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Pembayaran
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/confirm/{id}', [CheckoutController::class, 'confirmPayment'])->name('checkout.confirm');

    // Riwayat Pesanan User
    Route::get('/my-orders', [OrderController::class, 'index'])->name('my.orders');
    Route::post('/order/{id}/complete', [OrderController::class, 'markAsCompleted'])->name('order.complete');
    
    // Notifikasi User
    Route::get('/user-notifications/read', [OrderController::class, 'markNotificationRead'])->name('user.notif.read');

    // ===> ROUTE CETAK STRUK (INI YANG BARU) <===
    Route::get('/order/{id}/print', [OrderController::class, 'printInvoice'])->name('order.print');

});


// === 4. ROUTE ADMIN ===
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Notifikasi
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifications/read', [AdminController::class, 'markAsRead'])->name('notifications.read');

    // Manajemen Order
    Route::post('/order/{id}/confirm', [AdminController::class, 'confirmPayment'])->name('order.confirm');
    Route::post('/order/{id}/ship', [AdminController::class, 'markAsShipped'])->name('order.ship');

    // Manajemen Produk
    Route::resource('products', AdminProductController::class);
    // Export to excell
    Route::get('/orders/export', [AdminController::class, 'exportOrders'])->name('orders.export'); 
});
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\UserNotification; 
use App\Models\Cart; // <--- JANGAN LUPA IMPORT MODEL CART

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrap();

        // LOGIKA SHARE DATA KE NAVBAR (LAYOUT)
        View::composer('layout', function ($view) {
            if (Auth::check()) {
                // 1. DATA NOTIFIKASI (YANG LAMA)
                $notifs = UserNotification::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                
                $unread = UserNotification::where('user_id', Auth::id())
                            ->where('is_read', false)
                            ->count();

                // 2. DATA KERANJANG (YANG BARU)
                // Menghitung jumlah baris di tabel cart milik user ini
                $cartCount = Cart::where('user_id', Auth::id())->count(); 
                
                // Kirim semua variabel ke View
                $view->with('userNotifications', $notifs)
                     ->with('userUnread', $unread)
                     ->with('cartCount', $cartCount); // <--- Kirim variable ini
            }
        });
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\AdminNotification;
use Carbon\Carbon; // PENTING: Untuk mengolah tanggal grafik
use App\Models\UserNotification; // <--- Jangan lupa import ini di paling atas
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class AdminController extends Controller
{
    // =================================================
    // 1. DASHBOARD UTAMA (Statistik, Grafik, Tabel)
    // =================================================
    public function dashboard(Request $request) // <--- Tambahkan Request
    {
        // 1. STATISTIK DASAR (Tetap Sama)
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'waiting_confirmation')->count();
        $totalRevenue = Order::where('status', 'paid')->sum('total_price');

        // 2. LOGIKA GRAFIK DINAMIS
        $range = $request->input('range', '7days'); // Default 7 hari
        $chartData = [];
        $chartLabels = [];

        if ($range == 'yearly') {
            // --- TAHUNAN (Jan - Des) ---
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = date('M', mktime(0, 0, 0, $i, 1)); // Jan, Feb, ...
                
                $sum = Order::whereYear('created_at', date('Y'))
                            ->whereMonth('created_at', $i)
                            ->where('status', 'paid')
                            ->sum('total_price');
                $chartData[] = $sum;
            }
        } 
        elseif ($range == 'monthly') {
            // --- BULAN INI (Tanggal 1 - Hari Ini) ---
            $daysInMonth = Carbon::now()->daysInMonth;
            
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = Carbon::createFromDate(null, null, $i);
                
                // Hanya tampilkan sampai hari ini agar grafik tidak panjang kosong ke kanan
                if ($date->gt(Carbon::now())) break; 

                $chartLabels[] = $i . ' ' . $date->format('M'); // 1 Nov, 2 Nov...
                
                $sum = Order::whereDate('created_at', $date->format('Y-m-d'))
                            ->where('status', 'paid')
                            ->sum('total_price');
                $chartData[] = $sum;
            }
        } 
        else {
            // --- 7 HARI TERAKHIR (Default) ---
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chartLabels[] = $date->format('d M');
                
                $sum = Order::whereDate('created_at', $date->format('Y-m-d'))
                            ->where('status', 'paid')
                            ->sum('total_price');
                $chartData[] = $sum;
            }
        }

        // 3. DATA LAINNYA (Tetap Sama)
        $notifications = AdminNotification::orderBy('created_at', 'desc')->take(10)->get();
        $unreadCount = AdminNotification::where('is_read', false)->count();
        
        $orders = Order::with('user')
                    // Urutkan berdasarkan WAKTU DIBUAT (Terbaru di atas)
                    ->orderBy('created_at', 'desc') 
                    ->get();

        $products = Product::all(); 

        return view('admin.index', compact(
            'totalProducts', 'totalOrders', 'pendingOrders', 
            'totalRevenue', 'chartLabels', 'chartData', 
            'notifications', 'unreadCount', 'orders', 'products',
            'range' // <--- Kirim status range agar tombol bisa aktif
        ));
    }


    // =================================================
    // 2. TERIMA PEMBAYARAN (Tombol 'Terima' di Tabel)
    // =================================================
    public function confirmPayment($id)
    {
        // Cari order berdasarkan ID
        $order = Order::findOrFail($id);

        // Ubah status jadi 'paid' (Lunas)
        $order->update(['status' => 'paid']);

        // Tandai notifikasi terkait order ini jadi terbaca (Biar admin ga bingung)
        AdminNotification::where('order_id', $id)->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi! Order #' . $order->invoice_number . ' kini LUNAS.');
    }


    // =================================================
    // 3. TANDAI SEMUA NOTIFIKASI DIBACA
    // =================================================
    public function markAsRead()
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back();
    } 


    public function markAsShipped($id) 
    {
        $order = Order::findOrFail($id);

        if ($order->delivery_method == 'delivery') {
            $order->update(['status' => 'shipped']); 
            
            // 2. KIRIM NOTIFIKASI KE USER (TITIK MERAH NYALA)
            \App\Models\UserNotification::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'title' => '📦 Pesanan Sedang Diantar',
                'message' => 'Pesanan #' . $order->invoice_number . ' sedang dibawa kurir.',
                'is_read' => false // Penting!
            ]);
        } else {
            $order->update(['status' => 'ready_pickup']); 
            
            // 2. KIRIM NOTIFIKASI KE USER
            \App\Models\UserNotification::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'title' => '🛍️ Barang Siap Diambil',
                'message' => 'Pesanan #' . $order->invoice_number . ' sudah siap di toko.',
                'is_read' => false // Penting!
            ]);
        }
        return redirect()->back()->with('success', 'Status diupdate & Notifikasi dikirim.');
    }
    public function exportOrders()
    {
        // Nama file saat didownload
        return Excel::download(new OrdersExport, 'laporan-penjualan-'.date('d-m-Y').'.xlsx');
    }
}
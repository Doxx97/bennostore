<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\AdminNotification;
use Carbon\Carbon;
use App\Exports\OrdersExport; // Pastikan ini ada jika pakai fitur export
use Maatwebsite\Excel\Facades\Excel; // Pastikan ini ada

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // === 1. DEFINISI STATUS YANG DIHITUNG SEBAGAI PENDAPATAN ===
        // Kita hitung semua status kecuali 'pending' (belum bayar) dan 'waiting_confirmation'
        $validStatuses = ['paid', 'shipped', 'ready_pickup', 'completed'];

        // === 2. STATISTIK DASAR (DIPERBAIKI) ===
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'waiting_confirmation')->count();
        
        // Perbaikan: Hitung total dari semua status valid (bukan cuma 'paid')
        $totalRevenue  = Order::whereIn('status', $validStatuses)->sum('total_price');

        // === 3. LOGIKA GRAFIK DINAMIS (DIPERBAIKI) ===
        $range = $request->input('range', '7days'); // Default 7 hari
        $chartData = [];
        $chartLabels = [];

        if ($range == 'yearly') {
            // --- TAHUNAN (Jan - Des) ---
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = date('M', mktime(0, 0, 0, $i, 1)); 
                
                $sum = Order::whereYear('created_at', date('Y'))
                            ->whereMonth('created_at', $i)
                            ->whereIn('status', $validStatuses) // <--- Pakai whereIn
                            ->sum('total_price');
                $chartData[] = $sum;
            }
        } 
        elseif ($range == 'monthly') {
            // --- BULAN INI (Tanggal 1 - Hari Ini) ---
            $daysInMonth = Carbon::now()->daysInMonth;
            
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = Carbon::createFromDate(null, null, $i);
                if ($date->gt(Carbon::now())) break; 

                $chartLabels[] = $i . ' ' . $date->format('M'); 
                
                $sum = Order::whereDate('created_at', $date->format('Y-m-d'))
                            ->whereIn('status', $validStatuses) // <--- Pakai whereIn
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
                            ->whereIn('status', $validStatuses) // <--- Pakai whereIn
                            ->sum('total_price');
                $chartData[] = $sum;
            }
        }

        // === 4. DATA LAINNYA ===
        $notifications = AdminNotification::orderBy('created_at', 'desc')->take(10)->get();
        $unreadCount = AdminNotification::where('is_read', false)->count();
        
        // Urutkan pesanan dari yang terbaru
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        $products = Product::all(); 

        return view('admin.index', compact(
            'totalProducts', 'totalOrders', 'pendingOrders', 
            'totalRevenue', 'chartLabels', 'chartData', 
            'notifications', 'unreadCount', 'orders', 'products',
            'range'
        ));
    }

    // Fungsi Tandai Notifikasi Dibaca
    public function markAsRead() {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back();
    }

    // Fungsi Konfirmasi Pembayaran
    public function confirmPayment($id) {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'paid']);
        
        // Kirim notifikasi ke User
        \App\Models\UserNotification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => 'Pembayaran Diterima ✅',
            'message' => 'Pesanan #' . $order->invoice_number . ' sedang diproses penjual.',
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Pembayaran diterima.');
    }

    // Fungsi Kirim Barang / Siap Pickup
    public function markAsShipped($id) {
        $order = Order::findOrFail($id);

        if ($order->delivery_method == 'delivery') {
            $order->update(['status' => 'shipped']); 
            $title = '📦 Pesanan Sedang Diantar';
            $msg = 'Pesanan #' . $order->invoice_number . ' sedang dibawa kurir.';
        } else {
            $order->update(['status' => 'ready_pickup']); 
            $title = '🛍️ Barang Siap Diambil';
            $msg = 'Pesanan #' . $order->invoice_number . ' sudah siap di toko.';
        }

        // Notif User
        \App\Models\UserNotification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => $title,
            'message' => $msg,
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    // Fungsi Export Excel
    public function exportOrders()
    {
        return Excel::download(new OrdersExport, 'laporan-penjualan-'.date('d-m-Y').'.xlsx');
    }
}
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserNotification;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('user.orders', compact('orders'));
    }

    public function markAsCompleted($id)
    {
        // 1. Cari order milik user yang sedang login
        $order = Order::where('user_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($id);

        // 2. Validasi: Hanya bisa selesai jika statusnya 'shipped' (Diantar) atau 'ready_pickup' (Siap Diambil)
        if ($order->status == 'shipped' || $order->status == 'ready_pickup') {
            
            // 3. Update Status jadi Selesai
            $order->update(['status' => 'completed']);

            // 4. Kirim Notifikasi ke Admin (Bahwa transaksi beres)
            \App\Models\AdminNotification::create([
                'type' => 'order_completed',
                'message' => '✅ Pesanan #' . $order->invoice_number . ' telah diterima oleh pembeli.',
                'order_id' => $order->id,
                'is_read' => false
            ]);

            return redirect()->back()->with('success', 'Terima kasih! Transaksi telah selesai.');
        }

        return redirect()->back()->with('error', 'Pesanan belum dapat diselesaikan.');
    }

    // 3. MATIKAN TITIK MERAH
    public function markNotificationRead() {
        UserNotification::where('user_id', Auth::id())->update(['is_read' => true]);
        return redirect()->back(); // Tetap di halaman yang sama
    }

    public function printInvoice($id)
    {
        // Ambil data order beserta detail itemnya
        $order = Order::with('items.product')->findOrFail($id);

        // Keamanan: Pastikan yang print adalah Pemilik Order ATAU Admin
        if (Auth::user()->role !== 'admin' && $order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke struk ini.');
        }

        return view('user.invoice', compact('order'));
    }
}
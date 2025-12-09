<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\UserNotification;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OrderController extends Controller
{
    // 1. MENAMPILKAN DAFTAR ORDER
    public function index() {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('user.orders', compact('orders'));
    }

    // 2. TAMPILAN HALAMAN BAYAR + LOGIKA TIMER & TIMEOUT
    public function showPayment($id) 
    {
    $order = Order::with('items')->findOrFail($id);

    // 1. ATUR WAKTU DISINI: 30 Menit dari waktu order dibuat
    $timeoutMinutes = 30;
    $deadline = \Carbon\Carbon::parse($order->created_at)->addMinutes($timeoutMinutes);

    // 2. Cek apakah waktu sudah habis?
    if (\Carbon\Carbon::now()->greaterThan($deadline) && $order->status == 'pending') {
        
        // Kembalikan stok ke keranjang (Opsional)
        foreach ($order->items as $item) {
            $existingCart = Cart::where('user_id', $order->user_id)
                                ->where('product_id', $item->product_id)
                                ->first();
            if ($existingCart) {
                $existingCart->increment('quantity', $item->quantity);
            } else {
                Cart::create([
                    'user_id' => $order->user_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);
            }
        }

        // Batalkan pesanan
        $order->status = 'cancelled';
        $order->save();

        // Redirect ke 'my.orders' agar SweetAlert muncul disana
        return redirect()->route('my.orders')->with('alert_timeout', 'Waktu pembayaran telah habis! Pesanan dibatalkan otomatis.');
    }

    // Jika order sudah dibatalkan/kadaluarsa sebelumnya
    if ($order->status == 'cancelled') {
        return redirect()->route('my.orders')->with('alert_timeout', 'Pesanan ini telah kadaluarsa.');
    }

    // 3. KONVERSI KE TIMESTAMP (PENTING AGAR JS TIDAK ERROR)
    // Mengubah waktu deadline menjadi angka milidetik
    $deadlineTimestamp = $deadline->timestamp * 1000; 

    // Kirim $deadlineTimestamp ke view
    return view('checkout.payment', compact('order', 'deadline', 'deadlineTimestamp')); 
    }

    // 3. PROSES UPLOAD BUKTI BAYAR
    public function confirmPayment(Request $request, $id)
    {
        // Validasi gambar
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($id);

        // Cek input file
        if ($request->hasFile('payment_proof')) {
            // Hapus gambar lama jika ada
            if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            // Simpan gambar baru
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            // Update database
            $order->payment_proof = $path;
            
            // Opsional: Ubah status jika perlu
            // $order->status = 'waiting_confirmation'; 
            
            $order->save();
            
            // Notifikasi ke Admin
            AdminNotification::create([
                'type' => 'payment_uploaded',
                'message' => '📸 Bukti pembayaran baru untuk pesanan #' . $order->invoice_number,
                'order_id' => $order->id,
                'is_read' => false
            ]);
        }

        // REDIRECT KE DASHBOARD (Sesuai permintaan Alert -> OK -> Dashboard)
        return redirect()->route('my.orders')->with('alert_success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi admin.');
    } 

    // 4. SELESAIKAN PESANAN (DITERIMA PEMBELI)
    public function markAsCompleted($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status == 'shipped' || $order->status == 'ready_pickup') {
            
            $order->update(['status' => 'completed']);

            AdminNotification::create([
                'type' => 'order_completed',
                'message' => '✅ Pesanan #' . $order->invoice_number . ' telah diterima oleh pembeli.',
                'order_id' => $order->id,
                'is_read' => false
            ]);

            return redirect()->back()->with('success', 'Terima kasih! Transaksi telah selesai.');
        }

        return redirect()->back()->with('error', 'Pesanan belum dapat diselesaikan.');
    }

    // 5. MATIKAN NOTIFIKASI (TITIK MERAH)
    public function markNotificationRead() {
        UserNotification::where('user_id', Auth::id())->update(['is_read' => true]);
        return redirect()->back(); 
    }

    // 6. CETAK INVOICE
    public function printInvoice($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        if (Auth::user()->role !== 'admin' && $order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke struk ini.');
        }

        return view('user.invoice', compact('order'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * 1. MENAMPILKAN HALAMAN FORMULIR CHECKOUT
     */
    public function index(Request $request)
    {
        // Ambil ID dari URL (?ids=1,2,3)
        $ids = $request->query('ids');
        $userId = Auth::id();

        if ($ids) {
            // JIKA ADA PILIHAN: Ambil keranjang sesuai ID yang dipilih
            $idArray = explode(',', $ids);
            
            $cartItems = Cart::where('user_id', $userId)
                            ->whereIn('id', $idArray) // <--- Filter Barang
                            ->with('product')
                            ->get();
        } else {
            // JIKA TIDAK ADA PILIHAN: Ambil Semua (Fallback)
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();
            $ids = $cartItems->pluck('id')->implode(',');
        }

        // Cek jika kosong
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Tidak ada barang yang diproses.');
        }

        // ===> PERBAIKAN DISINI <===
        // Hitung total HANYA dari $cartItems yang sudah difilter di atas
        $grandTotal = 0;
        foreach ($cartItems as $item) {
            $grandTotal += $item->product->price * $item->quantity;
        }

        return view('checkout.index', compact('cartItems', 'grandTotal', 'ids'));
    }

    /**
     * 2. PROSES SIMPAN ORDER KE DATABASE
     */
    public function process(Request $request)
    {
        // Validasi input (tambah validasi cart_ids)
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'delivery_method' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:qris,bni',
            'cart_ids'       => 'required|string', // <--- Validasi baru
        ]);

        // ... (Validasi Alamat biarkan sama) ...

        $orderId = null;

        DB::transaction(function () use ($request, &$orderId) {
            
            // Ambil ID Cart yang mau diproses
            $idArray = explode(',', $request->cart_ids);

            // Ambil item keranjang sesuai ID yang dipilih tadi
            $cartItems = Cart::where('user_id', Auth::id())
                            ->whereIn('id', $idArray) // <--- Filter lagi disini
                            ->with('product')
                            ->get();
            
            if ($cartItems->isEmpty()) return; // Stop jika kosong

            // Hitung Total (Hanya item terpilih)
            $total = 0;
            foreach ($cartItems as $item) { 
                $total += $item->product->price * $item->quantity; 
            }

            // ... (Proses Buat Order: SAMA SEPERTI KODE SEBELUMNYA) ...
            // ... (Copy paste bagian Order::create sampai $orderId = $order->id ...) 
            // SAYA TULIS ULANG SINGKAT BIAR JELAS POSISINYA:
            $order = Order::create([
                'user_id' => Auth::id(),
                'recipient_name' => $request->recipient_name,
                'address' => $request->delivery_method == 'pickup' ? 'Ambil di Toko' : $request->address,
                'payment_method' => $request->payment_method,
                'delivery_method' => $request->delivery_method, 
                'total_price' => $total,
                'status' => 'pending',
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
            ]);
            $orderId = $order->id;

            // Loop Item & Hapus Cart
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sold', $item->quantity);
                
                // HAPUS ITEM DARI KERANJANG (Satu per satu)
                // Jangan hapus semua cart user, tapi hapus item ini saja ($item->delete())
                $item->delete(); 
            }

            // ... (Bagian Notifikasi Admin: SAMA SEPERTI SEBELUMNYA) ...
            $msg = 'Pesanan Baru #' . $order->invoice_number;
            if ($request->delivery_method == 'delivery') { $msg .= ' (Minta Diantar)'; } 
            else { $msg .= ' (Ambil Sendiri)'; }

            AdminNotification::create([
                'type' => 'new_order',
                'message' => $msg,
                'order_id' => $order->id,
                'is_read' => false
            ]);
        });

        // Redirect
        if($orderId) {
            return redirect()->route('checkout.payment', $orderId);
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * 3. MENAMPILKAN HALAMAN INSTRUKSI PEMBAYARAN
     */
    public function payment($id)
    {
        // Pastikan order tersebut milik user yang sedang login
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        return view('checkout.payment', compact('order'));
    }

    /**
     * 4. PROSES KONFIRMASI PEMBAYARAN (User Klik "Saya Sudah Bayar")
     */
    public function confirmPayment($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Ubah status jadi 'waiting_confirmation'
        $order->update(['status' => 'waiting_confirmation']);

        // Kirim Notifikasi ke Admin (Bahwa user sudah bayar)
        AdminNotification::create([
            'type' => 'payment_confirmation',
            'message' => 'User ' . Auth::user()->name . ' telah melakukan pembayaran untuk #' . $order->invoice_number,
            'order_id' => $order->id,
            'is_read' => false
        ]);

        return redirect()->route('home')->with('success', 'Konfirmasi terkirim! Admin akan mengecek pembayaran Anda.');
    }
}
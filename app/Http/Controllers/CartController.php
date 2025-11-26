<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. TAMPILKAN KERANJANG
    public function index()
    {
        // Ambil keranjang milik user yang sedang login
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        
        return view('cart.index', compact('cartItems'));
    }

    // 2. TAMBAH KE KERANJANG (Logika Tombol +)
    public function addToCart(Request $request)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $productId = $request->product_id;
        $userId = Auth::id();

        // Cek apakah produk sudah ada di keranjang user ini?
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan jumlahnya
            $cartItem->increment('quantity');
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // 3. UPDATE QUANTITY (Tombol - dan + di halaman cart)
    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        
        if ($request->type == 'increment') {
            $cartItem->increment('quantity');
        } else {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            }
        }
        return redirect()->back();
    }

    // 4. HAPUS ITEM
    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Item dihapus');
    }
}
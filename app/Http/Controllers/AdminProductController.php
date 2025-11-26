<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // Ini dipakai jika Anda mengakses /admin/products (Opsional jika pakai dashboard gabungan)
    public function index()
    {
        $products = Product::all();
        // Arahkan ke dashboard utama saja biar tidak bingung
        return redirect()->route('admin.dashboard'); 
    }

    // 1. MENAMPILKAN FORM TAMBAH (Dipanggil saat klik tombol "Tambah Produk")
    public function create()
    {
        return view('admin.create'); // Mengarah ke file create.blade.php yang baru kita buat
    }

    // 2. PROSES SIMPAN KE DATABASE
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Upload Gambar
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    // ... (Fungsi edit, update, destroy biarkan seperti sebelumnya) ...
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Produk diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Produk dihapus!');
    }
}
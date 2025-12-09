<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Daftar Kategori (Statis untuk Menu Ikon)
        $categories = [
            ['name' => 'Pulsa & Kuota', 'icon' => 'bi-phone'],
            ['name' => 'Token Listrik', 'icon' => 'bi-lightning-charge-fill'],
            ['name' => 'Makanan', 'icon' => 'bi-egg-fried'],
            ['name' => 'Barang Dapur', 'icon' => 'bi-fire'],
            ['name' => 'Keperluan Ibu & Anak', 'icon' => 'bi-people-fill'],
            ['name' => 'Barang Kebersihan', 'icon' => 'bi-stars'],
            ['name' => 'Kesehatan', 'icon' => 'bi-heart-pulse-fill'],
            ['name' => 'Alat Rumah', 'icon' => 'bi-house-door-fill'],
            ['name' => 'Top Up', 'icon' => 'bi-wallet2'],
            ['name' => 'Lain-lain', 'icon' => 'bi-grid-fill']
        ];

        // 2. Data Produk "Paling Laris" (Auto Scroll)
        // Diambil dari 10 produk dengan penjualan terbanyak
        $bestSellerProducts = Product::orderBy('sold', 'desc')->limit(10)->get();

        // 3. Inisialisasi Variabel Default agar tidak Error di View
        $filteredProducts = null;
        $categoryName = null;
        $sembakoProducts = collect(); // Koleksi kosong default
        $barangProducts = collect();  // Koleksi kosong default
        $products = collect();        // Koleksi kosong default (jika view minta $products)


        // === LOGIKA A: JIKA SEDANG MENCARI (SEARCH) ===
        if ($request->has('search') && $request->search != null) {
            $keyword = $request->search;
            $filteredProducts = Product::where('name', 'LIKE', "%{$keyword}%")->get();
            $categoryName = "Hasil Pencarian: '{$keyword}'";
        }
        
        // === LOGIKA B: JIKA SEDANG FILTER KATEGORI ===
        elseif ($request->has('category')) {
            $categoryName = $request->category;
            $filteredProducts = Product::where('category', $categoryName)->get();
        }
        
        // === LOGIKA C: JIKA TIDAK ADA FILTER (TAMPILAN AWAL) ===
        else {
            // Ambil produk per kategori utama untuk ditampilkan di section masing-masing
            $sembakoProducts = Product::where('category', 'Sembako')->latest()->get();
            $barangProducts = Product::where('category', 'Barang')->latest()->get();
            $products = Product::inRandomOrder()->limit(12)->get();
        }

        // 4. Kirim SEMUA variabel ke View (Compact)
        return view('home.index', compact(
            'categories', 
            'bestSellerProducts', 
            'filteredProducts', 
            'categoryName', 
            'sembakoProducts', 
            'barangProducts',
            'products'
        ));
    }
}
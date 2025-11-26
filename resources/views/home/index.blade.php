@extends('layout')

@section('content')

<div class="container mt-3 mb-5">

    @if($categoryName)
        <div class="alert alert-light shadow-sm border-0 d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-info-circle-fill text-info"></i>
            <div>
                Menampilkan produk untuk: <span class="fw-bold text-dark">"{{ $categoryName }}"</span>
                <a href="{{ route('home') }}" class="small ms-2 text-danger text-decoration-none fw-bold"><i class="bi bi-x-circle"></i> Reset Filter</a>
            </div>
        </div>
    @endif

    @if(!$categoryName)
        <div class="mb-5">
            <h5 class="fw-bold mb-3" style="color: #1B3C53;">Kategori Pilihan</h5>
            
            <div class="d-flex gap-3 overflow-x-auto pb-3" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat['name']]) }}" class="text-decoration-none text-center group-category" style="min-width: 85px;">
                    
                    <div class="cat-icon bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                         style="width: 65px; height: 65px; transition: all 0.3s ease;">
                        <i class="bi {{ $cat['icon'] }} fs-3" style="color: #234C6A;"></i>
                    </div>
                    
                    <small class="fw-bold text-dark d-block lh-sm" style="font-size: 0.75rem;">{{ $cat['name'] }}</small>
                </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($filteredProducts)
        <div class="row g-3">
            @forelse($filteredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('home.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-search fs-1 text-muted opacity-50 mb-3"></i>
                        <h5 class="text-muted fw-bold">Produk tidak ditemukan</h5>
                        <p class="text-muted small">Coba kata kunci lain atau cek kategori kami.</p>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm mt-2 rounded-pill px-4">
                            Lihat Semua Produk
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    @else
        
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold" style="color: #1B3C53;"><i class="bi bi-trophy-fill text-warning me-2"></i> Paling Laris</h5>
            </div>
            
            <div class="d-flex gap-3 overflow-x-auto pb-4 ps-1" style="scrollbar-width: thin;">
                @foreach($bestSellerProducts as $product)
                    <div style="min-width: 180px; max-width: 180px;">
                        @include('home.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>

        @if($sembakoProducts->count() > 0)
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold" style="color: #1B3C53;"><i class="bi bi-basket2-fill text-success me-2"></i> Kebutuhan Pokok</h5>
                <a href="{{ route('home', ['category' => 'Sembako']) }}" class="small text-decoration-none fw-bold">Lihat Semua</a>
            </div>
            <div class="row g-3">
                @foreach($sembakoProducts->take(4) as $product)
                    <div class="col-6 col-md-3">
                        @include('home.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mb-4">
            <h5 class="fw-bold mb-3" style="color: #1B3C53;">Rekomendasi Untukmu</h5>
            <div class="row g-3">
                @foreach($products as $product)
                    <div class="col-6 col-md-3">
                        @include('home.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>

    @endif

</div>

<style>
    /* Hover effect kategori */
    .group-category:hover .cat-icon {
        background-color: #234C6A !important;
        transform: translateY(2px);
    }
    .group-category:hover .cat-icon i {
        color: #fff !important;
    }

    /* Product Card Style */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        background: #fff;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(27, 60, 83, 0.15) !important;
    }
    .product-img { transition: transform 0.5s ease; }
    .product-card:hover .product-img { transform: scale(1.05); }

    /* Tombol Tambah Keranjang */
    .btn-add-cart {
        border: none;
        background: linear-gradient(135deg, #1B3C53 0%, #234C6A 100%);
        color: white;
        padding: 8px 0;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center; gap: 5px;
        transition: all 0.3s ease; width: 100%;
    }
    .btn-add-cart:hover {
        background: linear-gradient(135deg, #234C6A 0%, #456882 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(27, 60, 83, 0.3);
    }
</style>

@endsection
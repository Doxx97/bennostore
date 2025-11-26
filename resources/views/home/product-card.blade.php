<div class="card h-100 border-0 shadow-sm product-card">
    
    <div class="position-relative overflow-hidden bg-light" style="height: 180px;">
        @if($product->stock < 5)
            <span class="position-absolute top-0 start-0 bg-danger text-white badge m-2 shadow-sm" style="font-size: 0.6rem;">Sisa {{ $product->stock }}</span>
        @endif

        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top w-100 h-100 object-fit-cover product-img" alt="{{ $product->name }}">
        @else
            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                <i class="bi bi-image fs-1 opacity-25"></i>
            </div>
        @endif
    </div>

    <div class="card-body d-flex flex-column p-3">
        <small class="text-muted text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ $product->category }}</small>
        
        <h6 class="card-title fw-bold text-dark mb-1 text-truncate" style="font-size: 0.95rem;">{{ $product->name }}</h6>
        
        <div class="mb-3">
            <span class="fw-bold" style="color: #1B3C53;">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
        </div>

        <div class="mt-auto">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn-add-cart">
                    <i class="bi bi-cart-plus-fill"></i> Tambah
                </button>
            </form>
        </div>
    </div>
</div>
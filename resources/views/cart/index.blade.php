@extends('layout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4 mb-5">
    
    <h3 class="fw-bold mb-4">
        <i class="bi bi-cart-check me-2 text-primary"></i> Keranjang Belanja
    </h3>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="5%">Pilih</th>
                                    <th width="40%">Produk</th>
                                    <th width="25%">Jumlah</th>
                                    <th width="20%">Total</th>
                                    <th width="10%">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cartItems as $item)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" class="form-check-input item-checkbox" 
                                               value="{{ $item->id }}" 
                                               data-subtotal="{{ $item->product->price * $item->quantity }}"
                                               style="transform: scale(1.3); cursor: pointer;"
                                               checked 
                                               onchange="updateCartState()">
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/'.$item->product->image) }}" width="60" height="60" class="rounded object-fit-cover border">
                                            @else
                                                <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <small>No IMG</small>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                                                <small class="text-muted">Rp{{ number_format($item->product->price, 0, ',', '.') }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="type" value="decrement">
                                                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-dash"></i></button>
                                            </form>
                                            
                                            <input type="text" class="form-control text-center bg-white" value="{{ $item->quantity }}" readonly>
                                            
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="type" value="increment">
                                                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-plus"></i></button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="fw-bold text-primary">
                                        Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            
                                            <button type="button" class="btn btn-sm text-danger border-0 bg-transparent p-0 fs-5" onclick="confirmDelete(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                                        <p class="mt-3 text-muted">Keranjang belanja Anda kosong.</p>
                                        <a href="{{ route('home') }}" class="btn btn-primary">Mulai Belanja</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px; z-index: 1;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ringkasan Belanja</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Barang</span>
                        <span class="fw-bold" id="totalItemsDisplay">0 Item</span>
                    </div>
                    
                    <hr>

                    <div class="d-flex justify-content-between mb-4 align-items-center">
                        <span class="fw-bold fs-5">Total Harga</span>
                        <span class="fw-bold fs-4 text-primary" id="displayTotal">Rp 0</span>
                    </div>

                    <a href="#" id="btn-checkout" class="btn btn-warna w-100 py-2 fw-bold fs-5 mb-2">
                        Checkout Sekarang
                    </a>

                    <a href="{{ route('home') }}" class="btn btn-outline-primary w-100 py-2 fw-bold fs-5">
                        <i class="bi bi-arrow-left me-2"></i> Lanjut Belanja
                    </a>
                    
                    <small class="text-muted text-center d-block mt-3">
                        <i class="bi bi-shield-lock-fill"></i> Transaksi Aman & Terpercaya
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.btn-checkout {
    color: #234C6A
}
</style>
<script>
    // --- 3. SCRIPT SWEETALERT UNTUK KONFIRMASI HAPUS ---
    function confirmDelete(button) {
        Swal.fire({
            title: 'Hapus barang ini?',
            text: "Barang akan dihapus dari keranjang Anda.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Cari form terdekat dari tombol yang diklik, lalu submit
                button.closest('.delete-form').submit();
            }
        });
    }

    // --- SCRIPT UPDATE TOTAL HARGA ---
    function updateCartState() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        let selectedIds = [];
        let grandTotal = 0;
        let selectedCount = 0;

        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                selectedIds.push(checkbox.value);
                let subtotal = parseFloat(checkbox.getAttribute('data-subtotal'));
                grandTotal += subtotal;
                selectedCount++;
            }
        });

        const formattedTotal = "Rp" + grandTotal.toLocaleString('id-ID');
        document.getElementById('displayTotal').innerText = formattedTotal;
        document.getElementById('totalItemsDisplay').innerText = selectedCount + " Item";

        const btnCheckout = document.getElementById('btn-checkout');
        
        if (selectedCount === 0) {
            btnCheckout.classList.add('disabled', 'btn-secondary');
            btnCheckout.classList.remove('btn-primary');
            btnCheckout.href = "javascript:void(0)";
            btnCheckout.innerText = "Pilih Produk Dulu";
        } else {
            btnCheckout.classList.remove('disabled', 'btn-secondary');
            btnCheckout.classList.add('btn-primary');
            btnCheckout.innerText = "Checkout (" + selectedCount + ")";
            btnCheckout.href = "{{ route('checkout.index') }}?ids=" + selectedIds.join(',');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateCartState();
    });
</script>
@endsection
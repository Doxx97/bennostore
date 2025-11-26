@extends('layout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-5 mb-5">
    <div class="row">
        
        <div class="col-md-7">
            
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf
                
                <input type="hidden" name="cart_ids" value="{{ $ids }}">

                <h4 class="fw-bold mb-3">Informasi Pengiriman</h4>
                <div class="mb-3">
                    <label class="form-label">Nama Penerima</label>
                    <input type="text" name="recipient_name" class="form-control" value="{{ auth()->user()->name }}" required>
                </div>

                <h5 class="fw-bold mb-3 mt-4">Metode Pengiriman</h5>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-check p-3 border rounded bg-white">
                            <input class="form-check-input" type="radio" name="delivery_method" id="methodPickup" value="pickup" checked onclick="toggleAddress(false)">
                            <label class="form-check-label fw-bold w-100" for="methodPickup" style="cursor: pointer;">
                                <i class="bi bi-shop text-primary me-2"></i> Ambil di Toko
                            </label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check p-3 border rounded bg-white">
                            <input class="form-check-input" type="radio" name="delivery_method" id="methodDelivery" value="delivery" onclick="toggleAddress(true)">
                            <label class="form-check-label fw-bold w-100" for="methodDelivery" style="cursor: pointer;">
                                <i class="bi bi-truck text-success me-2"></i> Antar ke Rumah
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-4" id="addressBox" style="display: none;">
                    <label class="form-label fw-bold">Alamat Lengkap</label>
                    <textarea name="address" id="addressInput" class="form-control" rows="3" placeholder="Nama Jalan, RT/RW, Kecamatan, Kota..."></textarea>
                    <small class="text-muted">Wajib diisi jika barang ingin diantar.</small>
                </div>

                <h5 class="fw-bold mb-3 mt-4">Metode Pembayaran</h5>
                <div class="card p-3 border-0 shadow-sm bg-light mb-4">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="payQris" value="qris" checked>
                        <label class="form-check-label fw-bold" for="payQris">
                            <i class="bi bi-qr-code-scan me-2 text-primary"></i> QRIS (Dana, GoPay, ShopeePay)
                        </label>
                        <div class="payment-info mt-1 ps-4 text-muted small">
                            Scan kode QR yang akan muncul setelah Anda klik "Buat Pesanan".
                        </div>
                    </div>
                    <hr>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payBni" value="bni">
                        <label class="form-check-label fw-bold" for="payBni">
                            <i class="bi bi-bank me-2 text-warning"></i> Transfer Bank BNI
                        </label>
                        <div class="payment-info mt-1 ps-4 text-muted small">
                            Nomor Virtual Account BNI akan diberikan setelah checkout.
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-md-5 mt-4 mt-md-0">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 fw-bold">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center gap-2">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/'.$item->product->image) }}" width="50" height="50" class="rounded object-fit-cover">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <small>No IMG</small>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="my-0 small fw-bold">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->quantity }} x Rp{{ number_format($item->product->price, 0, ',', '.') }}</small>
                                </div>
                            </div>
                            <span class="text-muted">Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total Bayar</span>
                        <span class="text-warna">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" form="checkoutForm" class="btn btn-primary w-100 py-2 fs-5 fw-bold mb-2 btn-hover-effect" style="background-color: #234C6A; border: none;">
                        Buat Pesanan
                    </button>

                    <button type="button" onclick="cancelTransaction()" class="btn btn-outline-danger w-100 py-2 fs-5 fw-bold btn-hover-effect">
                        <i class="bi bi-x-circle me-2"></i> Batalkan Transaksi
                    </button>

                    <small class="d-block text-center mt-2 text-muted">Pastikan data sudah benar sebelum checkout.</small>
                </div>
            </div>
        </div>

    </div>
</div>
<style>
    .text-warna {
        color: #456882;
    }
</style>
<script>
    // Logika Alamat (Show/Hide)
    function toggleAddress(show) {
        const box = document.getElementById('addressBox');
        const input = document.getElementById('addressInput');
        
        if (show) {
            box.style.display = 'block';
            input.setAttribute('required', 'required');
            if(input.value === '-') input.value = ''; 
        } else {
            box.style.display = 'none';
            input.removeAttribute('required'); 
            input.value = '-'; 
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const isDelivery = document.getElementById('methodDelivery').checked;
        toggleAddress(isDelivery);
    });

    // === LOGIKA BATALKAN TRANSAKSI (SWEETALERT) ===
    function cancelTransaction() {
        Swal.fire({
            title: 'Batalkan Transaksi?',
            text: "Anda akan kembali ke keranjang belanja.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak, Lanjut'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect kembali ke Cart
                window.location.href = "{{ route('cart.index') }}";
            }
        });
    }
</script>

<style>
    .btn-hover-effect {
        transition: all 0.3s ease;
    }

    /* Efek Hover: Naik sedikit & Bayangan */
    .btn-hover-effect:hover {
        transform: translateY(-4px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        filter: brightness(1.05);
    }

    /* Efek Klik */
    .btn-hover-effect:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
</style>
@endsection
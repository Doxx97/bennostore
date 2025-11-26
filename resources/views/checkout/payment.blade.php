@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 text-center p-4">
                
                <div class="mb-4">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 4rem;"></i>
                    <h4 class="fw-bold mt-2">Menunggu Pembayaran</h4>
                    <p class="text-muted">Kode Pesanan: #{{ $order->invoice_number }}</p>
                </div>

                <div class="alert alert-warning">
                    Selesaikan pembayaran sebesar: <br>
                    <strong class="fs-4">Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong>
                    <br><small>Status saat ini: <strong>PENDING</strong></small>
                </div>

                @if($order->payment_method == 'qris')
                    <div class="payment-section mt-4">
                        <h5 class="fw-bold">Scan QRIS Berikut:</h5>
                        <div class="border p-2 d-inline-block rounded my-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS Code" width="200">
                        </div>
                    </div>
                @elseif($order->payment_method == 'bni')
                    @endif
                
                <hr class="my-4">

                <form action="{{ route('checkout.confirm', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check-circle"></i> Saya Sudah Bayar
                    </button>
                </form>

                <a href="https://wa.me/6281234567890" class="btn btn-success w-100">
                    <i class="bi bi-whatsapp"></i> Bantuan Admin
                </a>

            </div>
        </div>
    </div>
</div>
@endsection
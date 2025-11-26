@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">
            <i class="bi bi-bag-check me-2 text-primary"></i> Riwayat Pesanan Saya
        </h3>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Home
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Invoice</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status Pesanan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">
                                {{ $order->invoice_number }}<br>
                                <small class="text-muted fw-normal" style="font-size: 0.8rem;">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </small>
                            </td>
                            <td class="fw-bold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if($order->delivery_method == 'delivery')
                                    <span class="badge bg-info text-dark"><i class="bi bi-truck"></i> Diantar</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-shop"></i> Ambil Sendiri</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-secondary">Menunggu Pembayaran</span>
                                @elseif($order->status == 'waiting_confirmation')
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi Admin</span>
                                @elseif($order->status == 'paid')
                                    <span class="badge bg-primary">Sedang Dikemas</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-info text-dark">Sedang Diantar Kurir</span>
                                @elseif($order->status == 'ready_pickup')
                                    <span class="badge bg-info text-dark">Siap Diambil di Toko</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai / Diterima</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
    
                                <a href="{{ route('order.print', $order->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm mb-1" title="Cetak Struk">
                                    <i class="bi bi-printer"></i>
                                </a>

                                @if($order->status == 'shipped' || $order->status == 'ready_pickup')
                                    
                                    <form action="{{ route('order.complete', $order->id) }}" method="POST" class="d-inline complete-form">
                                        @csrf
                                        <button type="button" class="btn btn-success btn-sm fw-bold text-white mb-1" onclick="confirmReceived(this)">
                                            <i class="bi bi-check-circle-fill me-1"></i> Pesanan Diterima
                                        </button>
                                    </form>

                                @elseif($order->status == 'completed')
                                    <div class="badge bg-success mt-1">
                                        <i class="bi bi-star-fill"></i> Transaksi Selesai
                                    </div>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada riwayat pesanan. <br>
                                <a href="{{ route('home') }}" class="btn btn-sm btn-primary mt-2">Belanja Sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmReceived(button) {
        Swal.fire({
            title: 'Sudah Terima Barang?',
            text: "Pastikan barang sudah Anda terima dengan baik. Transaksi akan diselesaikan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754', // Hijau
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Sudah Terima',
            cancelButtonText: 'Belum'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('.complete-form').submit();
            }
        });
    }
</script>
@endsection
@extends('layout')

@section('content')
<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Dashboard Admin</h2>
            <p class="text-muted m-0">Ringkasan aktivitas toko Anda minggu ini.</p>
        </div>
        
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-white shadow-sm border position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell-fill text-secondary"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-2 shadow" style="width: 320px; max-height: 400px; overflow-y:auto;">
                    <li class="d-flex justify-content-between align-items-center px-2 mb-2">
                        <span class="fw-bold small text-uppercase text-muted">Notifikasi</span>
                        <a href="{{ route('admin.notifications.read') }}" class="small text-decoration-none">Tandai Baca Semua</a>
                    </li>
                    @forelse($notifications as $notif)
                        <li>
                            <a class="dropdown-item small border-bottom py-2 {{ $notif->is_read ? 'text-muted' : 'fw-bold bg-light' }}" href="#">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                                    <div>
                                        <div style="white-space: normal;">{{ $notif->message }}</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="dropdown-item text-center text-muted py-3">Tidak ada notifikasi baru</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold mb-2 mb-md-0">
                        <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                        Tren Penjualan 
                        <span class="text-muted small fw-normal">
                            (@if($range == 'yearly') Tahun Ini @elseif($range == 'monthly') Bulan Ini @else 7 Hari Terakhir @endif)
                        </span>
                    </h6>
                    
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.dashboard', ['range' => '7days']) }}" class="btn btn-outline-primary {{ $range == '7days' ? 'active' : '' }}">7 Hari</a>
                        <a href="{{ route('admin.dashboard', ['range' => 'monthly']) }}" class="btn btn-outline-primary {{ $range == 'monthly' ? 'active' : '' }}">Bulan Ini</a>
                        <a href="{{ route('admin.dashboard', ['range' => 'yearly']) }}" class="btn btn-outline-primary {{ $range == 'yearly' ? 'active' : '' }}">Tahun Ini</a>
                    </div>
                </div>

                <div class="card-body">
                    <canvas id="salesChart" height="130"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="d-flex flex-column gap-3 h-100">
                
                <div class="card border-0 shadow-sm bg-primary text-white flex-grow-1">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-white bg-opacity-25 p-2 rounded">
                                <i class="bi bi-wallet2 fs-3"></i>
                            </div>
                            <h6 class="text-uppercase opacity-75 m-0">Total Pendapatan</h6>
                        </div>
                        <h2 class="fw-bold display-6 m-0">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        <small class="opacity-75 mt-2 border-top pt-2 border-white border-opacity-25">
                            Total dari pesanan berstatus "Lunas"
                        </small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm bg-white h-100">
                            <div class="card-body text-center p-3">
                                <h4 class="fw-bold m-0 text-primary">{{ $totalOrders }}</h4>
                                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Total Order</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm bg-white h-100">
                            <div class="card-body text-center p-3">
                                <h4 class="fw-bold m-0 text-warning">{{ $pendingOrders }}</h4>
                                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-body d-flex align-items-center justify-content-between p-3">
                        <div>
                            <h6 class="text-muted small text-uppercase m-0">Total Produk</h6>
                            <h4 class="fw-bold m-0">{{ $totalProducts }} Item</h4>
                        </div>
                        <i class="bi bi-box-seam fs-1 text-secondary opacity-50"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-5">
        
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i> Daftar Semua Pesanan</h5>
            <a href="{{ route('admin.orders.export') }}" class="btn btn-success btn-sm fw-bold text-white">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Aksi</th>
                            <th>Invoice</th>
                            <th>Info Pengiriman</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 text-nowrap">
                                
                                @if($order->status != 'pending')
                                    <a href="{{ route('order.print', $order->id) }}" target="_blank" class="btn btn-sm btn-light border me-1" title="Cetak Struk">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                @endif

                                @if($order->status == 'waiting_confirmation')
                                    <form action="{{ route('admin.order.confirm', $order->id) }}" method="POST" class="d-inline action-form">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="confirmPayment(this)">
                                            <i class="bi bi-check-lg"></i> Terima
                                        </button>
                                    </form>

                                @elseif($order->status == 'paid')
                                    <form action="{{ route('admin.order.ship', $order->id) }}" method="POST" class="d-inline action-form">
                                        @csrf
                                        @if($order->delivery_method == 'delivery')
                                            <button type="button" class="btn btn-sm btn-info text-white fw-bold" onclick="confirmShip(this)">
                                                <i class="bi bi-send-fill me-1"></i> Kirim
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-warning text-dark fw-bold" onclick="confirmReady(this)">
                                                <i class="bi bi-box-seam-fill me-1"></i> Siap
                                            </button>
                                        @endif
                                    </form>

                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-info text-dark border border-info">
                                        <i class="bi bi-truck"></i> OTW
                                    </span>

                                @elseif($order->status == 'ready_pickup')
                                    <span class="badge bg-warning text-dark border border-warning">
                                        <i class="bi bi-shop"></i> Menunggu
                                    </span>

                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> Selesai
                                    </span>
                                @endif
                            </td>

                            <td class="fw-bold text-primary">{{ $order->invoice_number }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->recipient_name }}</div>
                                @if($order->delivery_method == 'delivery')
                                    <span class="badge bg-info text-dark mt-1"><i class="bi bi-truck"></i> Diantar</span>
                                    <div class="small text-muted mt-1 text-truncate" style="max-width: 150px;" title="{{ $order->address }}">
                                        <i class="bi bi-geo-alt"></i> {{ $order->address }}
                                    </div>
                                @else
                                    <span class="badge bg-secondary mt-1"><i class="bi bi-shop"></i> Ambil Sendiri</span>
                                @endif
                            </td>
                            <td class="fw-bold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'paid')
                                    <span class="badge bg-success">Sudah Bayar</span>
                                @elseif($order->status == 'waiting_confirmation')
                                    <span class="badge bg-warning text-dark blink-badge">Cek Bukti</span>
                                @else
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ $order->status }}</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
        <h5 class="fw-bold m-0"><i class="bi bi-box-seam me-2 text-success"></i> Daftar Produk</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" width="50" height="50" class="rounded object-fit-cover border">
                                @else
                                    <span class="badge bg-light text-dark border">No IMG</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td><span class="badge bg-outline-secondary border text-secondary">{{ $product->category }}</span></td>
                            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                @if($product->stock < 5)
                                    <span class="text-danger fw-bold">{{ $product->stock }} (Tipis)</span>
                                @else
                                    {{ $product->stock }}
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning text-white me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. CHART CONFIG
    const ctx = document.getElementById('salesChart').getContext('2d');
    const labels = {!! json_encode($chartLabels) !!};
    const data = {!! json_encode($chartData) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Penjualan', 
                data: data,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0d6efd',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if(value >= 1000000) return 'Rp ' + (value/1000000) + 'Jt';
                            if(value >= 1000) return 'Rp ' + (value/1000) + 'k';
                            return value;
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. SWEETALERT LOGIC
    function confirmPayment(button) {
        Swal.fire({
            title: 'Terima Pembayaran?',
            text: "Pastikan bukti transfer valid!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Terima!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('.action-form').submit();
            }
        });
    }

    function confirmShip(button) {
        Swal.fire({
            title: 'Kirim Pesanan?',
            text: "Status akan berubah menjadi 'Sedang Diantar'.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0dcaf0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('.action-form').submit();
            }
        });
    }

    function confirmReady(button) {
        Swal.fire({
            title: 'Barang Siap?',
            text: "Notifikasi akan dikirim ke user.",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Siap!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('.action-form').submit();
            }
        });
    }
</script>

<style>
    .blink-badge { animation: blinker 1.5s linear infinite; }
    @keyframes blinker { 50% { opacity: 0.5; } }
</style>
@endsection
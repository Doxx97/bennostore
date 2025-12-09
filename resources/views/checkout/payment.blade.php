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

                    <div class="alert alert-danger d-inline-block px-4 py-2 mt-2">
                        Sisa Waktu Pembayaran: <br>
                        <span id="countdown" class="fw-bold fs-3">00:00:00</span>
                    </div>
                </div>

                <div class="alert alert-warning">
                    Selesaikan pembayaran sebesar: <br>
                    <strong class="fs-4">Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong>
                    <br><small>Status saat ini: <strong>{{ strtoupper($order->status) }}</strong></small>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($order->payment_method == 'qris')
                    <div class="payment-section mt-4">
                        <h5 class="fw-bold">Scan QRIS Berikut:</h5>
                        <div class="border p-2 d-inline-block rounded my-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS Code" width="200">
                        </div>
                    </div>
                @elseif($order->payment_method == 'bni')
                    <div class="payment-section mt-4">
                        <h5 class="fw-bold">Transfer Bank BNI:</h5>
                        <div class="alert alert-info">
                            No. Rek: <strong>1866023436</strong><br>
                            A.n: Raihan Gusta
                        </div>
                    </div>
                @endif
                
                <hr class="my-4">

                <form action="{{ route('checkout.confirm', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3 text-start">
                        <label for="payment_proof" class="form-label fw-bold">Upload Bukti Transfer</label>
                        
                        <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof" accept="image/*" required>
                        
                        @error('payment_proof')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <div class="form-text text-muted">
                            Format: JPG, JPEG, PNG. Maksimal ukuran file: 2MB.
                        </div>
                    </div>

                    @if($order->payment_proof)
                        <div class="alert alert-info py-2 mb-3">
                            <small><i class="bi bi-info-circle"></i> Anda sudah mengunggah bukti. Upload lagi jika ingin mengganti.</small>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-upload"></i> Kirim Bukti Pembayaran
                    </button>
                </form>

                <a href="https://wa.me/6281234567890" class="btn btn-success w-100 mt-2">
                    <i class="bi bi-whatsapp"></i> Hubungi Admin jika Kendala
                </a>

            </div>
        </div>
    </div>
</div>
<script>
    // Ambil waktu deadline dalam bentuk Timestamp (Milidetik)
    // Ini lebih akurat daripada membaca string tanggal
    var countDownDate = {{ $deadlineTimestamp }};

    // Update hitungan mundur setiap 1 detik
    var x = setInterval(function() {

        // Ambil waktu sekarang
        var now = new Date().getTime();

        // Cari selisih waktu
        var distance = countDownDate - now;

        // Kalkulasi jam, menit, detik
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Tambahkan angka 0 di depan jika angka < 10
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Tampilkan hasil di elemen id="countdown"
        document.getElementById("countdown").innerHTML = hours + ":" + minutes + ":" + seconds;

        // JIKA WAKTU HABIS
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "00:00:00";
            
            // --- PERUBAHAN PENTING ---
            // Saya HAPUS alert("Waktu habis") disini supaya tidak menahan layar.
            // Langsung reload halaman. 
            // Controller akan mendeteksi waktu habis -> Redirect ke Pesanan Saya -> Muncul SweetAlert.
            
            window.location.reload(); 
        }
    }, 1000);
</script>
@endsection
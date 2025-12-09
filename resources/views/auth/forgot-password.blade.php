<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Benno Store</title>
    <link rel="icon" href="{{ asset('images/2.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #eef2f5;
            font-family: 'Poppins', sans-serif;
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin: 0; overflow: hidden;
            background-image: url("{{ asset('images/bg.png') }}");
            background-size: cover; background-position: center; background-repeat: no-repeat;
            position: relative;
        }
        .bg-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(27, 60, 83, 0.85); z-index: 1;
        }
        .card-box {
            background: white; border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden; max-width: 500px; width: 90%; 
            position: relative; z-index: 2; padding: 40px; text-align: center;
        }
        .btn-custom {
            background: #234C6A; border: none; color: white; border-radius: 10px; padding: 12px;
            font-weight: 600; width: 100%; transition: 0.3s;
        }
        .btn-custom:hover { background: #2b8bc4; transform: translateY(-2px); }
        .text-warna{ color: #234C6A; }
    </style>
</head>
<body>

<div class="bg-overlay"></div>

<div class="card-box">
    <div class="mb-4">
        <img src="{{ asset('images/2.png') }}" alt="Logo" style="width: 80px;">
        <h3 class="fw-bold text-warna mt-3">Reset Password</h3>
        <p class="text-muted small">Masukkan email Anda, kami akan mengirimkan link untuk mereset password.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small p-2 mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-floating mb-4">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email Address" required value="{{ old('email') }}">
            <label for="email">Email Terdaftar</label>
            @error('email')
                <div class="invalid-feedback text-start">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-custom mb-3">Kirim Link Reset</button>
        
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('login') }}" class="text-decoration-none text-secondary small">
                <i class="bi bi-arrow-left"></i> Kembali Login
            </a>
        </div>
    </form>
</div>

</body>
</html>
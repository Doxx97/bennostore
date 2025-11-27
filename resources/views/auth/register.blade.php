<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Benno Store</title>
    <link rel="icon" href="{{ asset('images/2.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #eef2f5;
            font-family: 'Poppins', sans-serif;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            margin: 0;
            overflow: hidden; /* Biar animasi ga bikin scroll */
            
            /* Background Gambar Utama */
            background-image: url("{{ asset('images/bg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }

        /* === CSS ANIMASI CAHAYA === */
        .svg-animation-container {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; /* Di atas gambar, di bawah overlay */
            pointer-events: none;
        }
        .light-path {
            fill: none; stroke: #ffffff; stroke-width: 2; stroke-linecap: round;
            filter: drop-shadow(0 0 5px #00ebff);
            stroke-dasharray: 2000; stroke-dashoffset: 2000;
            animation: drawLight 10s linear infinite;
        }
        @keyframes drawLight {
            0% { stroke-dashoffset: 2000; opacity: 0; }
            10% { opacity: 1; } 90% { opacity: 1; }
            100% { stroke-dashoffset: -2000; opacity: 0; }
        }
        /* Variasi Kecepatan Ombak */
        .path-1 { animation-duration: 9s; animation-delay: 0s; }
        .path-2 { animation-duration: 12s; animation-delay: 1s; }
        .path-3 { animation-duration: 10s; animation-delay: 0.5s; }
        .path-4 { animation-duration: 14s; animation-delay: 2s; }

        /* Overlay Biru Transparan */
        .bg-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(27, 60, 83, 0.85);
            z-index: 1; /* Di atas animasi */
        }

        /* Kartu Form */
        .card-box {
            background: white; border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden; max-width: 900px; width: 90%;
            position: relative;
            z-index: 2; /* Paling atas agar bisa diklik */
        }

        .left-side {
            background: linear-gradient(135deg, #234C6A 0%, #2b8bc4 100%); 
            color: white; display: flex; flex-direction: column; 
            justify-content: center; align-items: center; text-align: center; padding: 40px;
        }
        .right-side { padding: 50px; }
        
        .input-group-text { background: transparent; border-left: none; border-radius: 0 10px 10px 0; cursor: pointer; }
        .input-group .form-floating .form-control { border-right: none; border-radius: 10px 0 0 10px; }
        .form-control:focus { box-shadow: none; border-color: #ced4da; }

        .btn-custom {
            background: #234C6A; border: none; color: white; border-radius: 10px; padding: 12px;
            font-weight: 600; width: 100%; transition: 0.3s;
        }
        .btn-custom:hover { background: #2b8bc4; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(100, 197, 240, 0.4); }
        .text-warna{ color: #234C6A; }

        @media (max-width: 768px) {
            .left-side { display: none; }
            .right-side { padding: 30px; }
        }
    </style>
</head>
<body>

<div class="svg-animation-container">
    <svg width="100%" height="100%" viewBox="0 0 1920 1080" preserveAspectRatio="none">
        <path class="light-path path-1" d="M-10,900 C 300,800 600,950 900,850 C 1200,750 1500,900 1930,800" />
        <path class="light-path path-2" d="M-10,700 C 400,600 700,750 1000,650 C 1300,550 1600,700 1930,600" />
        <path class="light-path path-3" d="M-10,500 C 200,450 500,550 800,450 C 1100,350 1400,500 1930,400" />
        <path class="light-path path-4" d="M-10,300 C 500,200 800,350 1100,250 C 1400,150 1700,300 1930,200" />
    </svg>
</div>

<div class="bg-overlay"></div>

<div class="card-box">
    <div class="row g-0">
        <div class="col-md-6 left-side">
            <img src="{{ asset('images/2.png') }}" alt="Logo" style="width: 150px; height: auto; margin-bottom: 20px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));">
            <h2 class="fw-bold">Gabung Sekarang!</h2>
            <p class="small opacity-75">Nikmati kemudahan berbelanja dengan akun Benno Store.</p>
        </div>
        <div class="col-md-6 right-side">
            <h3 class="fw-bold text-warna mb-4">Daftar Akun</h3>
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm p-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="name" placeholder="Username" required value="{{ old('name') }}">
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                    <label for="email">Alamat Email</label>
                </div>
                <div class="input-group mb-3">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="pass1" name="password" placeholder="Password" required>
                        <label for="pass1">Password</label>
                    </div>
                    <span class="input-group-text text-muted" onclick="togglePassword('pass1', this)"><i class="bi bi-eye-slash"></i></span>
                </div>
                <div class="input-group mb-4">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="pass2" name="password_confirmation" placeholder="Konfirmasi" required>
                        <label for="pass2">Ulangi Password</label>
                    </div>
                    <span class="input-group-text text-muted" onclick="togglePassword('pass2', this)"><i class="bi bi-eye-slash"></i></span>
                </div>
                <button type="submit" class="btn btn-custom mb-3">DAFTAR</button>
                <div class="text-center small">
                    Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none text-warna">Login Disini</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconSpan) {
        const input = document.getElementById(inputId);
        const icon = iconSpan.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
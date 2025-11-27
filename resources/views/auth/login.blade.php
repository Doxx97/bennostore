<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Benno Store</title>
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
            min-height: 100dvh; /* Fix untuk browser HP */
            margin: 0;
            overflow: hidden; /* Mencegah scroll karena animasi */
            
            /* Background Gambar Statis */
            background-image: url("{{ asset('images/bg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }

        /* === 1. ANIMASI CAHAYA (LAYER PALING BAWAH) === */
        .svg-animation-container {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; /* Di atas background gambar */
            pointer-events: none;
        }
        
        .light-path {
            fill: none; 
            stroke: #ffffff; 
            stroke-width: 2; 
            stroke-linecap: round;
            filter: drop-shadow(0 0 5px #00ebff);
            stroke-dasharray: 2000; 
            stroke-dashoffset: 2000;
            animation: drawLight 10s linear infinite;
        }

        @keyframes drawLight {
            0% { stroke-dashoffset: 2000; opacity: 0; }
            10% { opacity: 1; } 
            90% { opacity: 1; }
            100% { stroke-dashoffset: -2000; opacity: 0; }
        }

        /* Variasi Kecepatan Ombak */
        .path-1 { animation-duration: 12s; animation-delay: 0s; }
        .path-2 { animation-duration: 15s; animation-delay: 2s; }
        .path-3 { animation-duration: 10s; animation-delay: 1s; }
        .path-4 { animation-duration: 18s; animation-delay: 0.5s; }

        /* === 2. OVERLAY GELAP (LAYER TENGAH) === */
        .bg-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(27, 60, 83, 0.85);
            z-index: 1; /* Di atas animasi, di bawah kartu */
        }

        /* === 3. KARTU LOGIN (LAYER ATAS) === */
        .card-box {
            background: white; border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden; max-width: 900px; width: 90%; 
            position: relative; z-index: 2; /* Paling atas */
        }

        .left-side {
            background: linear-gradient(135deg, #234C6A 0%, #2b8bc4 100%); 
            color: white; display: flex; flex-direction: column; 
            justify-content: center; align-items: center;
            text-align: center; padding: 40px;
        }
        
        .right-side { padding: 50px; }
        
        .input-group-text { background: transparent; border-left: none; cursor: pointer; }
        .input-group .form-floating .form-control { border-right: none; }
        .form-control:focus { box-shadow: none; border-color: #ced4da; }

        .btn-custom {
            background: #234C6A; border: none; color: white; border-radius: 10px; padding: 12px;
            font-weight: 600; width: 100%; transition: 0.3s;
        }
        .btn-custom:hover { background: #2b8bc4; transform: translateY(-2px); }
        .text-warna{ color: #234C6A; }

        /* === RESPONSIVE HP === */
        @media (max-width: 768px) {
            .left-side { display: none; }
            .card-box { width: 95%; margin: 20px 0; }
            .right-side { padding: 30px 20px; }
            .mobile-logo { display: block !important; margin-bottom: 1rem; }
        }
        .mobile-logo { display: none; }
    </style>
</head>
<body>

<div class="svg-animation-container">
    <svg width="100%" height="100%" viewBox="0 0 1920 1080" preserveAspectRatio="none">
        <path class="light-path path-1" d="M-10,950 C 300,850 600,1000 900,900 C 1200,800 1500,950 1930,850" />
        <path class="light-path path-2" d="M-10,750 C 400,650 700,800 1000,700 C 1300,600 1600,750 1930,650" />
        <path class="light-path path-3" d="M-10,550 C 200,500 500,600 800,500 C 1100,400 1400,550 1930,450" />
        <path class="light-path path-4" d="M-10,350 C 500,250 800,400 1100,300 C 1400,200 1700,350 1930,250" />
    </svg>
</div>

<div class="bg-overlay"></div>

<div class="card-box">
    <div class="row g-0">
        
        <div class="col-md-6 left-side">
            <img src="{{ asset('images/2.png') }}" alt="Logo" style="width: 150px; margin-bottom: 20px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));">
            <h2 class="fw-bold">Benno Store</h2>
            <p class="small opacity-75">Belanja hemat & terpercaya.</p>
        </div>

        <div class="col-md-6 right-side">
            
            <div class="text-center mobile-logo">
                <img src="{{ asset('images/2.png') }}" alt="Logo" style="width: 80px;">
                <h4 class="fw-bold mt-2 text-warna">Benno Store</h4>
            </div>

            <h3 class="fw-bold text-warna mb-4">Login Akun</h3>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm p-2 small">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('loginError'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm p-2 small">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ session('loginError') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="name" placeholder="Username" required value="{{ old('name') }}">
                    <label for="username">Username</label>
                </div>

                <div class="input-group mb-4">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
                        <label for="passwordInput">Password</label>
                    </div>
                    <span class="input-group-text text-muted" onclick="togglePassword('passwordInput', this)">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 small">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label text-muted" for="remember">Ingat Saya</label>
                    </div>
                    <a href="#" class="text-decoration-none text-warna">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-custom mb-3">MASUK</button>

                <div class="text-center small">
                    Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-warna">Daftar</a>
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Benno Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #eef2f5; /* Background abu-abu lembut */
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Style Kartu Utama */
        .card-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden; /* Agar gambar tidak keluar border radius */
            max-width: 900px;
            width: 90%;
        }

        /* Bagian Kiri (Gambar/Brand) */
        .left-side {
            background: linear-gradient(135deg, #234C6A 0%, #2b8bc4 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            position: relative;
        }
        
        /* Bagian Kanan (Form) */
        .right-side {
            padding: 50px;
        }

        /* Custom Input Group untuk Password */
        .input-group-text {
            background: transparent;
            border-left: none;
            border-radius: 0 10px 10px 0;
            cursor: pointer;
        }
        .input-group .form-floating .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        /* Tombol Login */
        .btn-custom {
            background: #234C6A;
            border: none;
            color: white;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: #2b8bc4;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(100, 197, 240, 0.4);
        }

        /* Responsive: Sembunyikan kiri di HP */
        @media (max-width: 768px) {
            .left-side { display: none; }
            .right-side { padding: 30px; }
        }
        .text-warna{
            color: #234C6A;
        }
    </style>
</head>
<body>

<div class="card-box">
    <div class="row g-0">
        
        <div class="col-md-6 left-side">
            <img src="{{ asset('images/2.png') }}" alt="Logo Benno Store" 
                style="width: 150px; height: auto; margin-bottom: 20px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));">
            
            <h2 class="fw-bold">Benno Store</h2>
            <p class="small opacity-75">Tempat belanja kebutuhan harian termurah dan terlengkap.</p>
        </div>

        <div class="col-md-6 right-side">
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
                    Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-warna">Daftar Sekarang</a>
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Benno Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        /* Menggunakan Style yang SAMA dengan Login agar konsisten */
        body {
            background-color: #eef2f5;
            font-family: 'Poppins', sans-serif;
            display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0;
        }
        .card-box {
            background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden; max-width: 900px; width: 90%;
        }
        .left-side {
            background: linear-gradient(135deg, #234C6A 0%, #2b8bc4 100%); color: white;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            text-align: center; padding: 40px;
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
            <h2 class="fw-bold">Gabung Sekarang!</h2>
            <p class="small opacity-75">Nikmati kemudahan berbelanja dengan akun Benno Store.</p>
        </div>

        <div class="col-md-6 right-side">
            <h3 class="fw-bold text-warna mb-4">Daftar Akun</h3>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm p-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
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
                    <span class="input-group-text text-muted" onclick="togglePassword('pass1', this)">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>

                <div class="input-group mb-4">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="pass2" name="password_confirmation" placeholder="Konfirmasi" required>
                        <label for="pass2">Ulangi Password</label>
                    </div>
                    <span class="input-group-text text-muted" onclick="togglePassword('pass2', this)">
                        <i class="bi bi-eye-slash"></i>
                    </span>
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
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Benno Store</title>
    <link rel="icon" href="{{ asset('images/2.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Color Palette */
            --primary-dark: #1B3C53;
            --primary-mid: #234C6A;
            --primary-light: #456882;
            --bg-light: #F5F7F9; /* Background dibuat sedikit lebih terang dari E3E3E3 agar teks mudah dibaca */
            --text-gray: #E3E3E3;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }
        
        /* === NAVBAR STYLE === */
        .navbar {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-mid));
            box-shadow: 0 4px 20px rgba(27, 60, 83, 0.15);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        /* Brand/Logo */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 1px;
            color: #fff !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Search Bar Modern */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 550px;
        }
        .search-input {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 10px 20px;
            padding-right: 50px; /* Space for icon */
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .search-input::placeholder { color: rgba(255, 255, 255, 0.6); }
        .search-input:focus {
            background-color: #fff;
            color: var(--primary-dark);
            border-color: #fff;
            box-shadow: 0 0 15px rgba(255,255,255,0.3);
        }
        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-light);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }
        .search-btn:hover { background: #fff; color: var(--primary-dark); }

        /* Nav Icons Hover Effect */
        .nav-icon-btn {
            color: rgba(255,255,255,0.85);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-block;
            text-decoration: none;
        }
        .nav-icon-btn:hover {
            color: #fff;
            transform: translateY(-3px) scale(1.1);
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        /* Dropdown Styling */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-top: 10px !important;
            overflow: hidden;
        }
        .dropdown-item:hover {
            background-color: #f0f4f8;
            color: var(--primary-dark);
        }

        /* === FOOTER STYLE === */
        footer {
            background-color: var(--primary-dark);
            color: var(--text-gray);
            margin-top: auto; /* Push footer to bottom */
        }
        
        /* === WHATSAPP FLOAT === */
        .wa-float {
            position: fixed;
            bottom: 40px;
            left: 30px; /* Dipindah ke kiri sesuai permintaan awal, atau ganti 'right' jika mau di kanan */
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: white;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .wa-float:hover {
            background-color: #128C7E;
            transform: scale(1.1) rotate(10deg);
            color: white;
        }
        /* Pulse Animation */
        .wa-float::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #25d366;
            opacity: 0.5;
            z-index: -1;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        /* Mobile Adjustments */
        @media (max-width: 991px) {
            .search-container { margin: 15px 0; }
            .navbar-collapse {
                background: rgba(27, 60, 83, 0.95);
                padding: 20px;
                border-radius: 15px;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/2.png') }}" alt="Logo" width="40" height="40" class="d-inline-block align-text-top" style="padding-right: px">
                BENNO STORE
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                
                <form action="{{ route('home') }}" method="GET" class="mx-auto search-container">
                    <input class="form-control search-input" type="search" name="search" placeholder="Cari produk impianmu..." value="{{ request('search') }}">
                    <button class="search-btn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0 justify-content-center">
                    
                    <a href="{{ route('cart.index') }}" class="nav-icon-btn position-relative" title="Keranjang">
                        <i class="bi bi-cart-fill fs-4"></i>
                        
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">
                                {{ $cartCount }}
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        @endif
                    </a>

                    @auth
                        <div class="dropdown">
                            <a class="nav-icon-btn position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell-fill fs-4"></i>
                                @if(isset($userUnread) && $userUnread > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                        <span class="visually-hidden">New alerts</span>
                                    </span>
                                @endif
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-end p-0 shadow" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                                    <span class="fw-bold text-dark small">NOTIFIKASI</span>
                                    <a href="{{ route('user.notif.read') }}" class="small text-decoration-none text-primary">Tandai Baca</a>
                                </li>
                                @if(isset($userNotifications) && $userNotifications->count() > 0)
                                    @foreach($userNotifications as $notif)
                                    <li>
                                        <a class="dropdown-item py-3 border-bottom {{ $notif->is_read ? 'text-muted' : 'fw-bold bg-white border-start border-4 border-info' }}" href="{{ route('my.orders') }}">
                                            <div class="d-flex gap-3">
                                                <div class="flex-shrink-0 mt-1">
                                                    @if(str_contains($notif->title, 'Siap'))
                                                        <i class="bi bi-shop text-primary fs-5"></i>
                                                    @elseif(str_contains($notif->title, 'Diantar'))
                                                        <i class="bi bi-truck text-info fs-5"></i>
                                                    @else
                                                        <i class="bi bi-info-circle text-secondary fs-5"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div style="font-size: 0.9rem;">{{ $notif->title }}</div>
                                                    <div class="small text-muted text-truncate" style="max-width: 200px;">{{ $notif->message }}</div>
                                                    <small class="text-secondary" style="font-size: 0.65rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                @else
                                    <li class="text-center py-4 text-muted small">Belum ada notifikasi</li>
                                @endif
                            </ul>
                        </div>

                        <div class="dropdown">
                            <a class="text-decoration-none dropdown-toggle fw-bold d-flex align-items-center gap-2 text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                                <span class="d-none d-lg-block">{{ Str::limit(auth()->user()->name, 10) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow p-2">
                                @if(auth()->user()->role === 'admin' || auth()->user()->email === 'admin@gmail.com') 
                                    <li>
                                        <a class="dropdown-item rounded" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard Admin
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item rounded" href="{{ route('my.orders') }}">
                                        <i class="bi bi-bag-check me-2 text-success"></i> Riwayat Pesanan
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                    @else
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold">Masuk</a>
                            <a href="{{ route('register') }}" class="btn btn-light text-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">Daftar</a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        @yield('content')
    </main>

    <footer class="py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3"><img src="{{ asset('images/2.png') }}" alt="Logo Benno Store" style="width: 30px; height: auto; margin-bottom: 5px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));"></img> Benno Store</h5>
                    <p class="small opacity-75">Tempat belanja kebutuhan harian paling lengkap, murah, dan terpercaya. Melayani dengan sepenuh hati.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                    <ul class="list-unstyled small opacity-75">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none text-reset">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('cart.index') }}" class="text-decoration-none text-reset">Keranjang</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-reset">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                    <ul class="list-unstyled small opacity-75">
                        <li class="mb-2"><i class="bi bi-whatsapp me-2"></i> 0851-4101-0721</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> cs@bennostore.com</li>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> RT 31 RW 11 Desa Gembleb Kecamatan Pogalan Kabupaten Trenggalek, Indonesia 66371</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="opacity-25 my-4">
            <div class="text-center small opacity-50">
                &copy; {{ date('Y') }} Benno Store. All Rights Reserved.<br>Support by Hostinger
            </div>
        </div>
    </footer>

    <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20mau%20tanya%20produk..." class="wa-float" target="_blank" title="Chat Admin">
        <i class="bi bi-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Logic Toast Notification
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            const ToastError = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            ToastError.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
    </script>

</body>
</html>
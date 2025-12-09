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
            --primary-dark: #1B3C53;
            --primary-mid: #234C6A;
            --primary-light: #456882;
            --bg-light: #eef2f5; 
            --text-gray: #E3E3E3;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
            padding-bottom: 80px; 
        }

        @media (min-width: 992px) {
            body { padding-bottom: 0; } 
        }
        
        /* NAVBAR */
        .navbar {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-mid));
            box-shadow: 0 4px 20px rgba(27, 60, 83, 0.15);
            padding: 12px 0;
        }
        .navbar-brand { font-weight: 700; font-size: 1.3rem; letter-spacing: 1px; color: #fff !important; }

        /* SEARCH BAR */
        .search-container-desktop { position: relative; width: 100%; max-width: 550px; }
        .search-input {
            background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff; padding: 10px 20px; padding-right: 50px; border-radius: 50px;
        }
        .search-input:focus { background-color: #fff; color: var(--primary-dark); border-color: #fff; }
        .search-btn {
            position: absolute; right: 5px; top: 50%; transform: translateY(-50%);
            background: var(--primary-light); border: none; border-radius: 50%;
            width: 35px; height: 35px; color: #fff; display: flex; align-items: center; justify-content: center;
        }

        /* MOBILE SEARCH */
        #mobileSearchOverlay {
            display: none; background: var(--primary-mid); padding: 10px 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); animation: slideDown 0.3s ease;
        }
        @keyframes slideDown { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* BOTTOM NAV */
        .mobile-bottom-nav {
            position: fixed; bottom: 0; left: 0; width: 100%;
            background-color: #ffffff;
            box-shadow: 0 -2px 20px rgba(0,0,0,0.1);
            display: flex; justify-content: space-around; align-items: center;
            height: 70px; z-index: 1050;
            border-top-left-radius: 20px; border-top-right-radius: 20px;
        }
        .bottom-nav-item {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-decoration: none; color: #aaa; font-size: 0.75rem; width: 25%; height: 100%;
            transition: 0.2s; position: relative;
        }
        .bottom-nav-item i { font-size: 1.4rem; margin-bottom: 2px; transition: 0.2s; }
        .bottom-nav-item.active { color: var(--primary-mid); font-weight: 600; }
        .bottom-nav-item.active i { transform: translateY(-3px); }
        .nav-badge {
            position: absolute; top: 10px; right: 25%;
            background-color: #dc3545; color: white; border-radius: 50%;
            width: 18px; height: 18px; font-size: 0.6rem;
            display: flex; align-items: center; justify-content: center; border: 2px solid white;
        }

        /* WA FLOAT */
        .wa-float {
            position: fixed; bottom: 85px; right: 20px;
            width: 55px; height: 55px;
            background-color: #25d366; color: white; border-radius: 50%;
            text-align: center; font-size: 28px; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            z-index: 999; display: flex; align-items: center; justify-content: center; text-decoration: none;
        }

        .nav-icon-btn { color: rgba(255,255,255,0.85); text-decoration: none; font-size: 1.2rem; transition: 0.3s; }
        .nav-icon-btn:hover { color: #fff; transform: translateY(-2px); }
        .bg-color{
            background-color: #234C6A;
        }

    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/2.png') }}" alt="Logo" width="35" height="35" class="d-inline-block align-text-top me-2">
                BENNO STORE
            </a>

            <button class="btn text-white fs-4 d-lg-none border-0 p-0" onclick="toggleMobileSearch()">
                <i class="bi bi-search"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <form action="{{ route('home') }}" method="GET" class="mx-auto search-container-desktop">
                    <input class="form-control search-input" type="search" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                    <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>
                </form>

                <div class="d-flex align-items-center gap-4">
                    <a href="{{ route('cart.index') }}" class="nav-icon-btn position-relative" title="Keranjang">
                        <i class="bi bi-cart-fill fs-4"></i>
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth
                        <div class="dropdown">
                            <a class="nav-icon-btn position-relative" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-bell-fill fs-4"></i>
                                @if(isset($userUnread) && $userUnread > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                @endif
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-end p-0 shadow border-0" style="width: 360px; max-height: 500px; overflow-y: auto; border-radius: 15px;">
                                
                                <li class="sticky-top bg-white border-bottom z-1">
                                    <div class="d-flex justify-content-between align-items-center p-3">
                                        <h6 class="m-0 fw-bold text-dark">Notifikasi</h6>
                                        <a href="{{ route('user.notif.read') }}" class="small text-decoration-none text-primary fw-bold">Tandai sudah dibaca</a>
                                    </div>
                                </li>

                                @if(isset($userNotifications) && $userNotifications->count() > 0)
                                    @foreach($userNotifications as $notif)
                                        <li>
                                            <a class="dropdown-item py-3 border-bottom" href="{{ route('my.orders') }}" style="white-space: normal; background-color: {{ $notif->is_read ? '#fff' : '#f8f9fa' }};">
                                                <div class="d-flex gap-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 45px; height: 45px; background-color: {{ $notif->is_read ? '#f1f3f5' : '#e7f5ff' }}; color: {{ $notif->is_read ? '#adb5bd' : '#0d6efd' }};">
                                                            @if(str_contains(strtolower($notif->title), 'kirim') || str_contains(strtolower($notif->title), 'antar'))
                                                                <i class="bi bi-truck fs-5"></i>
                                                            @elseif(str_contains(strtolower($notif->title), 'bayar') || str_contains(strtolower($notif->title), 'lunas'))
                                                                <i class="bi bi-cash-coin fs-5"></i>
                                                            @elseif(str_contains(strtolower($notif->title), 'selesai') || str_contains(strtolower($notif->title), 'diterima'))
                                                                <i class="bi bi-check-circle-fill fs-5"></i>
                                                            @else
                                                                <i class="bi bi-bag-fill fs-5"></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <span class="fw-bold text-dark {{ $notif->is_read ? 'opacity-75' : '' }}" style="font-size: 0.95rem;">{{ $notif->title }}</span>
                                                            @if(!$notif->is_read)
                                                                <span class="badge bg-danger rounded-circle p-1 ms-2"></span>
                                                            @endif
                                                        </div>
                                                        <p class="text-muted small mb-1 lh-sm" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                            {{ $notif->message }}
                                                        </p>
                                                        <span class="text-secondary" style="font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="p-5 text-center">
                                        <i class="bi bi-bell-slash fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                        <span class="text-muted small">Belum ada notifikasi</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="dropdown">
                            <a class="text-decoration-none dropdown-toggle fw-bold d-flex align-items-center gap-2 text-white" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                                <span class="d-none d-lg-block">{{ Str::limit(auth()->user()->name, 8) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow p-2">
                                @if(auth()->user()->role === 'admin' || auth()->user()->email === 'admin@gmail.com') 
                                    <li><a class="dropdown-item rounded" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                                @endif
                                <li><a class="dropdown-item rounded" href="{{ route('my.orders') }}">Pesanan Saya</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">@csrf <button class="dropdown-item rounded text-danger">Logout</button></form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-light text-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div id="mobileSearchOverlay" class="d-lg-none">
        <form action="{{ route('home') }}" method="GET" class="position-relative">
            <input class="form-control rounded-pill border-0" type="search" name="search" placeholder="Cari produk..." value="{{ request('search') }}" style="padding-right: 40px;">
            <button class="btn position-absolute top-50 end-0 translate-middle-y text-primary pe-3" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <main class="flex-grow-1 py-3 px-2">
        @yield('content')
    </main>

    <footer class="py-5 d-none d-lg-block bg-color">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3">
                        <img src="{{ asset('images/2.png') }}" width="30" height="30" class="me-2"> Benno Store
                    </h5>
                    <p class="small opacity-75 text-white">Tempat belanja kebutuhan harian paling lengkap dan terpercaya.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                    <ul class="list-unstyled small opacity-75">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-white">Beranda</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-decoration-none text-white">Keranjang</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                    <ul class="list-unstyled small opacity-75">
                        <li class="text-white"><i class="bi bi-whatsapp me-2" style="color: #25d366"></i> 0851-4101-0721</li>
                        <li class="text-white"><i class="bi bi-geo-alt me-2"></i> Trenggalek, Indonesia</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>
            <hr class="opacity-25 my-4">
            <div class="text-center text-white small opacity-50">&copy; {{ date('Y') }} Benno Store. All Rights Reserved.<br>Support by Hostinger</div>
        </div>
    </footer>

    <div class="mobile-bottom-nav d-lg-none">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ Route::is('home') ? 'active' : '' }}">
            <i class="bi {{ Route::is('home') ? 'bi-house-door-fill' : 'bi-house-door' }}"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('cart.index') }}" class="bottom-nav-item {{ Route::is('cart.index') ? 'active' : '' }}">
            <i class="bi {{ Route::is('cart.index') ? 'bi-cart-fill' : 'bi-cart' }}"></i>
            <span>Keranjang</span>
            @if(isset($cartCount) && $cartCount > 0)
                <span class="nav-badge">{{ $cartCount }}</span>
            @endif
        </a>
        @auth
            <a href="{{ route('my.orders') }}" class="bottom-nav-item {{ Route::is('my.orders') ? 'active' : '' }}">
                <i class="bi {{ (isset($userUnread) && $userUnread > 0) ? 'bi-bell-fill' : 'bi-bell' }}"></i>
                <span>Notifikasi</span>
                @if(isset($userUnread) && $userUnread > 0)
                    <span class="nav-badge" style="background-color: #ffc107; color: #000;">!</span>
                @endif
            </a>
            <a href="#" class="bottom-nav-item" onclick="showMobileAccountMenu()">
                <i class="bi bi-person-circle"></i>
                <span>Akun</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="bottom-nav-item">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Masuk</span>
            </a>
        @endauth
    </div>

    <a href="https://wa.me/6285141010721" class="wa-float" target="_blank"><i class="bi bi-whatsapp"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function toggleMobileSearch() {
            const sb = document.getElementById('mobileSearchOverlay');
            sb.style.display = (sb.style.display === 'block') ? 'none' : 'block';
            if(sb.style.display === 'block') sb.querySelector('input').focus();
        }

        function showMobileAccountMenu() {
            Swal.fire({
                title: 'Halo, {{ auth()->check() ? auth()->user()->name : "Tamu" }}',
                showConfirmButton: false, showCancelButton: true, cancelButtonText: 'Tutup',
                html: `
                    <div class="d-grid gap-2">
                        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->email === 'admin@gmail.com'))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary"><i class="bi bi-speedometer2"></i> Dashboard Admin</a>
                        @endif
                        <a href="{{ route('my.orders') }}" class="btn btn-outline-secondary"><i class="bi bi-bag-check"></i> Pesanan Saya</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-grid">
                            @csrf <button class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </div>
                `
            });
        }

        /* --- BAGIAN ALERT BAWAAN (TOAST) --- */
        @if(session('success'))
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif
        @if(session('error'))
            const ToastError = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
            ToastError.fire({ icon: 'error', title: '{{ session('error') }}' });
        @endif

        /* --- BAGIAN TAMBAHAN BARU: ALERT MODAL (POP-UP OK) --- */
        
        // 1. Muncul ketika berhasil upload bukti bayar
        @if(session('alert_success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('alert_success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#234C6A'
            });
        @endif

        // 2. Muncul ketika waktu habis (Timeout)
        @if(session('alert_timeout'))
            Swal.fire({
                icon: 'warning',
                title: 'Waktu Habis',
                text: '{{ session('alert_timeout') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif
    </script>
</body>
</html>
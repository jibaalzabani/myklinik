<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyKlinik - Sistem Temu Janji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 80px 0;
        }
        .card-role {
            transition: transform 0.3s;
            cursor: pointer;
            border: 0;
            border-radius: 1rem;
        }
        .card-role:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">🏥 MyKlinik</a>
            
            @auth
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 text-muted small">Halo, <b>{{ Auth::user()->name }}</b> ({{ ucfirst(Auth::user()->role) }})</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Layanan Kesehatan Terpercaya</h1>
            <p class="lead mb-4">Buat janji temu dengan dokter spesialis kami dengan mudah dan cepat.</p>
        </div>
    </section>

    <section class="container py-5">
        
        @if(session('status'))
            <div class="alert alert-success text-center mb-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-12 text-center mb-5">
                <h3 class="fw-bold">Masuk Sebagai</h3>
                <p class="text-muted">Silakan pilih peran Anda untuk melanjutkan</p>
            </div>

            <div class="col-md-5 mb-4">
                <div class="card h-100 shadow-sm card-role">
                    <div class="card-body p-5 text-center">
                        <div class="display-1 mb-3">🤒</div>
                        <h4 class="fw-bold mb-3">Pasien</h4>
                        <p class="text-muted mb-4">Daftar berobat, lihat riwayat, dan cek jadwal dokter.</p>
                        
                        @if(Auth::check() && Auth::user()->role == 'pasien')
                            <a href="{{ url('/pasien/dashboard') }}" class="btn btn-success w-100 fw-bold rounded-pill py-2">Buka Dashboard Pasien</a>
                        @else
                            <div class="d-grid gap-2">
                                <a href="{{ url('/login-pasien') }}" class="btn btn-primary fw-bold rounded-pill py-2">Login Pasien</a>
                                <a href="{{ url('/register') }}" class="btn btn-outline-primary fw-bold rounded-pill py-2">Daftar Akun Baru</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-5 mb-4">
                <div class="card h-100 shadow-sm card-role">
                    <div class="card-body p-5 text-center">
                        <div class="display-1 mb-3">👨‍⚕️</div>
                        <h4 class="fw-bold mb-3">Dokter & Admin</h4>
                        <p class="text-muted mb-4">Portal login untuk Dokter Spesialis dan Administrator Klinik.</p>
                        
                        @if(Auth::check())
                            @if(Auth::user()->role == 'dokter')
                                <a href="{{ url('/dokter/dashboard') }}" class="btn btn-success w-100 fw-bold rounded-pill py-2">Buka Dashboard Dokter</a>
                            @elseif(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                                <a href="{{ url('/staff/dashboard') }}" class="btn btn-success w-100 fw-bold rounded-pill py-2">Buka Dashboard Admin</a>
                            @endif
                        @else
                            <a href="{{ url('/login-dokter') }}" class="btn btn-outline-primary w-100 fw-bold rounded-pill py-2 mt-auto">Login Dokter / Admin</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>

    <footer class="bg-light text-center py-4 mt-5 border-top">
        <small class="text-muted">&copy; {{ date('Y') }} MyKlinik. All Rights Reserved.</small>
    </footer>

</body>
</html>
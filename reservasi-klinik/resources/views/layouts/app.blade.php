<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyKlinik System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh; background: #fff; 
            box-shadow: 2px 0 10px rgba(0,0,0,0.05); z-index: 1000;
        }
        .sidebar .brand {
            font-size: 1.5rem; font-weight: 800; color: #0d6efd; 
            padding: 20px; border-bottom: 1px solid #eee; display: block; text-decoration: none;
        }
        .nav-link {
            color: #6c757d; font-weight: 500; padding: 12px 20px; 
            margin: 5px 10px; border-radius: 8px; transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #eef2ff; color: #0d6efd; transform: translateX(5px);
        }
        .nav-link i { margin-right: 10px; font-size: 1.1rem; }

        /* Main Content Styling */
        .main-content { padding: 30px; }
        .header-bar {
            background: #fff; padding: 15px 30px; border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 30px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); background: #fff; overflow: hidden; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-3 col-lg-2 sidebar p-0 d-none d-md-block">
            <a href="#" class="brand"><i class="bi bi-hospital-fill"></i> MyKlinik</a>
            
            <div class="nav flex-column mt-3">
                @if(Auth::user()->role == 'pasien')
                    @include('layouts.menu-pasien')
                @elseif(Auth::user()->role == 'dokter')
                    @include('layouts.menu-dokter')
                @elseif(Auth::user()->role == 'staff' || Auth::user()->role == 'admin')
                    @include('layouts.menu-staff')
                @endif

                <form action="{{ route('logout') }}" method="POST" class="mt-4 px-3">
                    @csrf
                    <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="col-md-9 col-lg-10 p-0">
            <div class="header-bar m-3">
                <h5 class="fw-bold mb-0 text-secondary">@yield('page-title')</h5>
                <div class="d-flex align-items-center">
                    <div class="text-end me-3">
                        <small class="d-block text-muted">Login sebagai</small>
                        <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>

            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
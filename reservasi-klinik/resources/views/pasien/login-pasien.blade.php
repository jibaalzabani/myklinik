<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien - MyKlinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="card p-4 shadow" style="width: 400px;">
    <h3 class="text-center fw-bold text-primary mb-4">Login Pasien</h3>

    @if(session('success'))
        <div class="alert alert-success small text-center">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold small">Email</label>
            <input type="email" name="email" class="form-control" placeholder="pasien@gmail.com" required value="{{ old('email') }}">
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-bold small">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100 fw-bold">Masuk</button>
    </form>
    
    <div class="text-center mt-3 small">
        Belum punya akun? <a href="{{ url('/register') }}" class="text-decoration-none fw-bold">Daftar disini</a>
    </div>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - My Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f3f6fb; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        .reg-card { width: 500px; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="reg-card">
    <h3 class="fw-bold text-center text-primary mb-4">Buat Akun Baru</h3>
    
    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger small">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label class="form-label fw-bold small">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">NIK</label>
            <input type="number" name="nik" class="form-control" value="{{ old('nik') }}" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold small">Nomor HP</label>
            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Alamat</label>
            <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small">Jenis Kelamin</label>
                <select name="gender" class="form-select" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold small">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Daftar Sekarang</button>
    </form>

    <div class="text-center mt-3">
        Sudah punya akun? <a href="{{ url('/login-pasien') }}" class="text-decoration-none fw-bold">Login disini</a>
    </div>
</div>

</body>
</html>
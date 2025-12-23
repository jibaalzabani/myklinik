<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Sehat - Sistem Temu Janji</title>
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
        }
        .card-role:hover {
            transform: translateY(-10px);
            border-color: #0d6efd;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">🏥 Klinik Sehat</a>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Layanan Kesehatan Terpercaya</h1>
            <p class="lead mb-4">Buat janji temu dengan dokter spesialis kami dengan mudah dan cepat tanpa antri.</p>
        </div>
    </section>

    <section class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-12 mb-4">
                <h3 class="fw-bold">Masuk Sebagai</h3>
                <p class="text-muted">Silakan pilih peran Anda untuk melanjutkan</p>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm card-role border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="display-1 mb-3">🤒</div>
                        <h4 class="fw-bold">Pasien</h4>
                        <p class="text-muted">Daftar berobat, lihat riwayat, dan cek jadwal dokter.</p>
                        <a href="pasien/login-pasien.php" class="btn btn-primary w-100 fw-bold rounded-pill mt-3">Login Pasien</a>
                        <div class="mt-2 small">
                            Belum punya akun? <a href="pasien/register.php">Daftar</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm card-role border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="display-1 mb-3">👨‍⚕️</div>
                        <h4 class="fw-bold">Dokter</h4>
                        <p class="text-muted">Kelola jadwal praktek dan konfirmasi pasien masuk.</p>
                        <a href="dokter/login-dokter.php" class="btn btn-outline-primary w-100 fw-bold rounded-pill mt-3">Login Dokter</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <footer class="bg-light text-center py-4 mt-5 border-top">
        <small class="text-muted">&copy; 2024 Klinik Sehat. All Rights Reserved.</small>
    </footer>

</body>
</html>
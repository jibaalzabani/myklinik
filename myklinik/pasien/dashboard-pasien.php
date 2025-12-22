<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien - My Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7ff; }
        .card-menu {
            border: none; border-radius: 12px; padding: 25px; text-align: center;
            background: #fff; transition: 0.3s; cursor: pointer; text-decoration: none; color: inherit;
            display: block; box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .card-menu:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); color: #0b4dff; }
        .card-menu img { width: 50px; margin-bottom: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4 shadow-sm">
    <a class="navbar-brand fw-bold" href="#">My Klinik</a>
    
    <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3 fw-bold" id="navName">Halo, Pasien</span>
        
        <button onclick="logout()" class="btn btn-light btn-sm text-primary fw-bold px-3">
            Logout
        </button>
    </div>
</nav>

<div class="container py-5">
    <div class="mb-4">
        <h2 class="fw-bold">Selamat Datang!</h2>
        <p class="text-muted">Apa yang ingin Anda lakukan hari ini?</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="reservasi-kunjungan.php" class="card-menu">
                <img src="https://cdn-icons-png.flaticon.com/512/3652/3652191.png">
                <h5 class="fw-bold">Buat Janji Temu</h5>
                <p class="small text-muted mb-0">Booking dokter secara online</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="riwayat-reservasi.php" class="card-menu">
                <img src="https://cdn-icons-png.flaticon.com/512/2965/2965567.png">
                <h5 class="fw-bold">Riwayat Saya</h5>
                <p class="small text-muted mb-0">Lihat status pemeriksaan</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="profil.php" class="card-menu">
                <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png">
                <h5 class="fw-bold">Profil Akun</h5>
                <p class="small text-muted mb-0">Update data diri anda</p>
            </a>
        </div>
    </div>
</div>

<script>
    // 1. Ambil Data dari Penyimpanan Browser
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');
    const nama = localStorage.getItem('nama');

    // DEBUGGING: Tampilkan apa yang dibaca sistem di Console (Tekan F12 -> Console)
    console.log("Token:", token);
    console.log("Role:", role);

    // 2. Cek Keamanan
    // Jika Token Kosong ATAU Role bukan 'pasien'
    if (!token || role !== 'pasien') {
        
        // Tampilkan Pesan Detail (Supaya kita tahu salahnya dimana)
        alert("Akses Ditolak!\n\nData yang terbaca:\nToken: " + (token ? "ADA" : "KOSONG") + "\nRole: " + role);
        
        // Bersihkan data yang mungkin error
        localStorage.clear();
        
        // Kembalikan ke login
        window.location.href = 'login-pasien.php';
    } else {
        // Jika Aman, Tampilkan Nama
        if (nama) {
            document.getElementById('navName').innerText = "Halo, " + nama;
        }
    }

    // 3. Fungsi Logout
    function logout() {
        if(confirm("Yakin ingin keluar?")) {
            localStorage.clear();
            window.location.href = '../index.php'; // Atau '../index.php' jika file ini ada di dalam folder
        }
    }
</script>

</body>
</html>
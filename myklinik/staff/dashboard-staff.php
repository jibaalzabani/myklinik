<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-warning px-4">
        <a class="navbar-brand fw-bold text-white" href="#">Admin Klinik</a>
        <div class="ms-auto">
            <span class="text-white me-3 fw-bold" id="navName">Halo, Staff</span>
            <button onclick="logout()" class="btn btn-danger btn-sm">Keluar</button>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card border-0 shadow-sm p-4">
            <h2 class="fw-bold text-warning">Dashboard Staff</h2>
            <p class="text-muted">Kelola data master dan antrian klinik.</p>
            <hr>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-3 text-center border-warning h-100">
                        <h5>Kelola User</h5>
                        <p class="small text-muted">Tambah/Hapus user manual</p>
                        <button class="btn btn-warning text-white btn-sm">Buka</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. CEK KEAMANAN
        const token = localStorage.getItem('token');
        const role = localStorage.getItem('role');
        const nama = localStorage.getItem('nama');

        // Jika bukan staff, tendang!
        if (!token || role !== 'staff') {
            alert("Akses Ditolak! Anda bukan Staff.");
            window.location.href = 'login-staff.php';
        }

        // 2. Tampilkan Nama
        if(nama) {
            document.getElementById('navName').innerText = "Halo, " + nama;
        }

        // 3. Logout
        function logout() {
            if(confirm("Keluar dari sistem?")) {
                localStorage.clear();
                window.location.href = '../index.php';
            }
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7ff; }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-selesai { background-color: #198754; color: #fff; }
        .badge-batal { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary px-4 mb-4">
    <span class="navbar-brand fw-bold">Riwayat Saya</span>
    <a href="dashboard-pasien.php" class="btn btn-sm btn-light text-primary fw-bold">Kembali ke Dashboard</a>
</nav>

<div class="container">
    <div class="card shadow border-0 rounded-4 p-4">
        <h4 class="fw-bold mb-4 text-primary">📅 Daftar Kunjungan Anda</h4>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal & Jam</th>
                        <th>Dokter Tujuan</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="tabelRiwayat">
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Sedang memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // 1. Ambil ID User yang sedang login
    const userId = localStorage.getItem('id_user');

    if (!userId) {
        alert("Anda belum login!");
        window.location.href = 'login-pasien.php';
    }

    // 2. Fungsi Ambil Data dari Backend
    async function loadRiwayat() {
        const tbody = document.getElementById('tabelRiwayat');
        
        try {
            // Panggil API dengan ID User
            const res = await fetch(`http://localhost:5000/appointments/${userId}`);
            const data = await res.json();

            // Jika data kosong
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4">Belum ada riwayat reservasi. <br> <a href="reservasi-kunjungan.php">Buat Janji Sekarang</a></td></tr>`;
                return;
            }

            // Jika ada data, buat baris tabel (Looping)
            let html = '';
            data.forEach(item => {
                // Atur warna badge status
                let badgeClass = 'bg-secondary';
                if(item.status === 'pending') badgeClass = 'badge-pending';
                if(item.status === 'selesai') badgeClass = 'badge-selesai';
                if(item.status === 'batal') badgeClass = 'badge-batal';

                // Format Tanggal (Biar cantik, misal: 2023-12-01)
                const dateObj = new Date(item.date).toLocaleDateString('id-ID', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                });

                // Potong detik pada jam (10:00:00 -> 10:00)
                const timeClean = item.time.substring(0, 5);

                html += `
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">${dateObj}</div>
                            <div class="small text-muted">Pukul ${timeClean} WIB</div>
                        </td>
                        <td>${item.doctor_name}</td>
                        <td>${item.complaint}</td>
                        <td><span class="badge ${badgeClass} px-3 py-2 rounded-pill">${item.status.toUpperCase()}</span></td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

        } catch (err) {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger fw-bold">Gagal memuat data server.</td></tr>`;
        }
    }

    // Jalankan saat halaman dibuka
    loadRiwayat();
</script>

</body>
</html>
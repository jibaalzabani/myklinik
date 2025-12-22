<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f3f4f6; }
        .card-header-custom { background: linear-gradient(to right, #0d6efd, #0043a8); color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 shadow-sm">
    <a class="navbar-brand fw-bold" href="#">👨‍⚕️ Panel Dokter</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-white small" id="navName">Halo, Dokter</span>
        <button id="btnKeluar" class="btn btn-outline-light btn-sm px-3">Logout</button>
    </div>
</nav>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-dark">Daftar Pasien Masuk</h2>
            <p class="text-muted">Kelola antrian pasien Anda di sini.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header card-header-custom p-3">
            <h5 class="mb-0 fw-bold">📅 Jadwal Praktek</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="p-3">Tanggal & Jam</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelPasien">
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Sedang memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // --- 1. CEK KEAMANAN & DATA DIRI ---
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');
    const doctorId = localStorage.getItem('id_user'); // ID Dokter dari Login
    const nama = localStorage.getItem('nama');

    // Jika tidak ada token atau bukan dokter, tendang keluar
    if (!token || role !== 'dokter') {
        alert("Akses Ditolak! Silakan Login sebagai Dokter.");
        window.location.href = '../login-dokter.php'; // Sesuaikan path login Anda
    }

    if (nama) document.getElementById('navName').innerText = "Halo, " + nama;

    // --- 2. LOAD DATA PASIEN ---
    async function loadAppointments() {
        const tbody = document.getElementById('tabelPasien');
        
        try {
            // Mengambil data berdasarkan ID Dokter yang sedang login
            const res = await fetch(`http://localhost:5000/doctor/appointments/${doctorId}`);
            const data = await res.json();

            // Jika data kosong
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5">Belum ada pasien yang mendaftar.</td></tr>`;
                return;
            }

            // Jika ada data, buat tabel
            let html = '';
            data.forEach(item => {
                // Formatting Tanggal (Indonesia)
                const dateObj = new Date(item.date).toLocaleDateString('id-ID', { dateStyle: 'long' });
                const timeObj = item.time.substring(0, 5); // Ambil jam:menit saja

                // Logic Warna Badge Status
                let badge = 'bg-secondary';
                if(item.status === 'pending') badge = 'bg-warning text-dark';
                if(item.status === 'selesai') badge = 'bg-success';
                if(item.status === 'batal') badge = 'bg-danger';

                // Logic Tombol Aksi (Hanya muncul jika status masih PENDING)
                let tombolAksi = '';
                if (item.status === 'pending') {
                    tombolAksi = `
                        <button class="btn btn-sm btn-success me-1" onclick="updateStatus(${item.id}, 'selesai')" title="Tandai Selesai">
                            ✅ Selesai
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="updateStatus(${item.id}, 'batal')" title="Batalkan">
                            ❌ Batal
                        </button>
                    `;
                } else {
                    tombolAksi = `<span class="text-muted small"><i>Selesai</i></span>`;
                }

                html += `
                    <tr>
                        <td class="p-3 fw-bold">
                            ${dateObj} <br> 
                            <small class="fw-normal text-muted">Pukul ${timeObj}</small>
                        </td>
                        <td class="fw-bold text-primary">${item.patient_name}</td>
                        <td>${item.complaint}</td>
                        <td><span class="badge ${badge} rounded-pill px-3">${item.status.toUpperCase()}</span></td>
                        <td>${tombolAksi}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;

        } catch (err) {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Gagal terhubung ke server backend.</td></tr>`;
        }
    }

    // --- 3. FUNGSI UPDATE STATUS (Dipanggil saat tombol diklik) ---
    async function updateStatus(id, newStatus) {
        let textKonfirmasi = newStatus === 'selesai' ? "Selesaikan kunjungan ini?" : "Batalkan janji temu ini?";
        
        if (!confirm(textKonfirmasi)) return;

        try {
            const res = await fetch(`http://localhost:5000/appointments/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: newStatus })
            });

            if (res.ok) {
                alert("Status berhasil diperbarui!");
                loadAppointments(); // Refresh tabel otomatis tanpa reload halaman
            } else {
                alert("Gagal update status.");
            }
        } catch (err) {
            console.error(err);
            alert("Error koneksi server.");
        }
    }
    
    // Jalankan fungsi load saat halaman dibuka
    loadAppointments();

    // --- 4. LOGOUT ---
    document.getElementById('btnKeluar').addEventListener('click', () => {
        if(confirm("Yakin ingin keluar?")) {
            localStorage.clear();
            window.location.href = '../index.php'; // Arahkan ke landing page
        }
    });
</script>

</body>
</html>
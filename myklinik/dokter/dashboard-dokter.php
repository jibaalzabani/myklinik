<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - Klinik Sehat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 10px; }
        .text-capitalize { text-transform: capitalize !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">👨‍⚕️ Panel Dokter</a>
            <div class="d-flex align-items-center">
                <span id="navName" class="text-white me-3">Halo, Dokter</span>
                <button id="btnKeluar" class="btn btn-danger btn-sm">Keluar</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            
            <div class="col-lg-8 mb-4">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3 text-secondary">📋 Daftar Antrian Pasien</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Pasien</th>
                                    <th>Keluhan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabelPasien">
                                <tr><td colspan="5" class="text-center text-muted">Sedang memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-3 mb-3">
                    <h6 class="fw-bold mb-3">🕒 Jadwal Praktik Saya</h6>
                    <ul class="list-group list-group-flush" id="scheduleList">
                        <li class="list-group-item text-muted small">Memuat jadwal...</li>
                    </ul>
                    <div class="mt-3">
                        <small class="text-muted fst-italic" style="font-size: 0.8rem;">
                            *Hubungi Admin jika jadwal tidak sesuai.
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalDiagnosa" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">🩺 Diagnosa Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idJanjiTemu">
                    <div class="mb-3">
                        <label class="fw-bold">Pasien Mengeluh:</label>
                        <p id="textKeluhan" class="text-muted fst-italic">...</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Hasil Pemeriksaan / Diagnosa:</label>
                        <textarea id="inputDiagnosa" class="form-control" rows="4" placeholder="Tulis hasil pemeriksaan dan resep obat di sini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="simpanDiagnosa()" class="btn btn-success fw-bold">💾 Simpan & Selesai</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRiwayat" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">📜 Riwayat Medis: <span id="judulNamaPasien"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keluhan</th>
                                    <th>Diagnosa Dokter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="isiTabelRiwayat"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'http://localhost:5000';
        
        // Cek Login & Identitas
        const doctorId = localStorage.getItem('id_user') || localStorage.getItem('user_id') || localStorage.getItem('id');
        const doctorName = localStorage.getItem('nama') || localStorage.getItem('name');

        if (!doctorId) {
            alert("Anda belum login.");
            window.location.href = 'index.php'; 
        }

        if(doctorName) document.getElementById('navName').innerText = "Dr. " + doctorName;

        // --- 1. LOAD DAFTAR PASIEN ---
        async function loadAppointments() {
            const tbody = document.getElementById('tabelPasien');
            try {
                const res = await fetch(`${API_URL}/doctor/appointments/${doctorId}`);
                const data = await res.json();

                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Belum ada pasien yang mendaftar.</td></tr>`;
                    return;
                }

                let html = '';
                data.forEach(d => {
                    const tgl = new Date(d.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                    const jam = d.time ? d.time.substring(0, 5) : '-';

                    // Tombol Aksi
                    let aksi = '';
                    if(d.status === 'pending' || d.status === 'menunggu'){
                        aksi = `
                            <button class="btn btn-sm btn-success me-1" onclick="bukaModalDiagnosa(${d.id}, '${d.complaint}')">✅ Periksa</button>
                            <button class="btn btn-sm btn-danger" onclick="batalkan(${d.id})">❌</button>
                        `;
                    } else {
                        aksi = `<span class="badge bg-light text-dark border text-capitalize">${d.status}</span>`;
                    }

                    // Warna Badge Status
                    let badgeColor = 'bg-secondary';
                    if (d.status === 'selesai') badgeColor = 'bg-success';
                    else if (d.status === 'batal') badgeColor = 'bg-danger';
                    else badgeColor = 'bg-warning text-dark';

                    html += `
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">${tgl}</div>
                                <small class="text-muted">${jam}</small>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">${d.patient_name}</span>
                                <div class="mt-1">
                                    <button type="button" onclick="lihatRiwayat(${d.user_id}, '${d.patient_name}')" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size: 0.8rem;">
                                        📜 Lihat Riwayat
                                    </button>
                                </div>
                            </td>
                            <td>${d.complaint}</td>
                            <td><span class="badge ${badgeColor} text-capitalize">${d.status}</span></td>
                            <td>${aksi}</td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;

            } catch (err) {
                console.error(err);
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Gagal terhubung ke server.</td></tr>`;
            }
        }

        // --- 2. LOAD JADWAL SAYA (YANG SEMPAT HILANG) ---
        async function loadMySchedule() {
            const list = document.getElementById('scheduleList');
            try {
                const res = await fetch(`${API_URL}/schedules/${doctorId}`);
                const data = await res.json();

                if (data.length === 0) {
                    list.innerHTML = '<li class="list-group-item text-center text-danger small">Jadwal belum diatur Admin.</li>';
                    return;
                }

                let html = '';
                data.forEach(s => {
                    const start = s.start_time.substring(0, 5);
                    const end = s.end_time.substring(0, 5);

                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">${s.day}</span>
                            <span class="badge bg-primary rounded-pill">${start} - ${end}</span>
                        </li>
                    `;
                });
                list.innerHTML = html;

            } catch (err) {
                console.error(err);
            }
        }

        // --- 3. LOGIKA DIAGNOSA ---
        const modalDiagnosa = new bootstrap.Modal(document.getElementById('modalDiagnosa'));

        function bukaModalDiagnosa(id, keluhan) {
            document.getElementById('idJanjiTemu').value = id;
            document.getElementById('textKeluhan').innerText = keluhan;
            document.getElementById('inputDiagnosa').value = ''; 
            modalDiagnosa.show();
        }

        async function simpanDiagnosa() {
            const id = document.getElementById('idJanjiTemu').value;
            const diagnosa = document.getElementById('inputDiagnosa').value;

            if(!diagnosa) { alert("Mohon isi diagnosa dokter."); return; }

            try {
                await fetch(`${API_URL}/appointments/${id}`, {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ status: 'selesai', diagnosis: diagnosa })
                });
                modalDiagnosa.hide();
                loadAppointments(); // Refresh tabel
            } catch (err) { alert("Gagal menyimpan diagnosa"); }
        }

        async function batalkan(id) {
            if(!confirm("Batalkan pasien ini?")) return;
            await fetch(`${API_URL}/appointments/${id}`, {
                method: 'PUT',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ status: 'batal' })
            });
            loadAppointments();
        }

        // --- 4. LOGIKA RIWAYAT ---
        const modalRiwayat = new bootstrap.Modal(document.getElementById('modalRiwayat'));

        async function lihatRiwayat(patientId, namaPasien) {
            document.getElementById('judulNamaPasien').innerText = namaPasien;
            const tbody = document.getElementById('isiTabelRiwayat');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Sedang memuat riwayat...</td></tr>';
            modalRiwayat.show();

            try {
                // Mengambil riwayat spesifik dengan dokter ini
                const res = await fetch(`${API_URL}/appointments/history/${patientId}/${doctorId}`);
                const data = await res.json();

                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Belum ada riwayat sebelumnya dengan Anda.</td></tr>';
                } else {
                    let html = '';
                    data.forEach(r => {
                        const tgl = new Date(r.date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
                        const diagnosa = r.diagnosis ? r.diagnosis : '<span class="text-muted fst-italic">- Belum ada diagnosa -</span>';
                        let badge = r.status === 'selesai' ? 'bg-success' : 'bg-secondary';
                        
                        html += `
                            <tr>
                                <td>${tgl}</td>
                                <td>${r.complaint}</td>
                                <td class="text-primary">${diagnosa}</td>
                                <td><span class="badge ${badge} text-capitalize">${r.status}</span></td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                }
            } catch (err) {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data.</td></tr>';
            }
        }

        // --- 5. LOGOUT ---
        document.getElementById('btnKeluar').addEventListener('click', () => {
            if(confirm("Yakin ingin keluar?")) {
                localStorage.clear();
                window.location.href = '../index.php'; 
            }
        });

        // Jalankan saat load
        loadAppointments();
        loadMySchedule(); 
    </script>
</body>
</html>
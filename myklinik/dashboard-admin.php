<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manajemen Dokter & Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand fw-bold text-warning">⚡ Admin Panel</span>
    <button onclick="logout()" class="btn btn-outline-light btn-sm">Logout</button>
</nav>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white fw-bold">➕ Tambah Dokter Baru</div>
                <div class="card-body">
                    <form id="formAddDoctor">
                        <div class="mb-2"><input type="text" id="name" class="form-control" required placeholder="Nama Lengkap"></div>
                        <div class="mb-2"><input type="text" id="specialist" class="form-control" required placeholder="Spesialis (mis: Umum)"></div>
                        <div class="mb-2"><input type="email" id="email" class="form-control" required placeholder="Email Login"></div>
                        <div class="mb-2"><input type="password" id="password" class="form-control" required placeholder="Password"></div>
                        <div class="mb-3"><input type="text" id="phone" class="form-control" required placeholder="No HP"></div>

                        <div class="border-top pt-3">
                            <label class="fw-bold small mb-2">Set Jadwal Awal (Opsional)</label>
                            <div id="scheduleContainer">
                                </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100 dashed-border" onclick="addScheduleRow()">+ Tambah Hari Praktik</button>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Simpan Dokter & Jadwal</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-header bg-white fw-bold">👨‍⚕️ Daftar Dokter</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Nama</th><th>Spesialis</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="doctorsTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJadwal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📅 Kelola Jadwal Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="manageDoctorId">
                <div class="mb-3">
                    <label class="small fw-bold">Tambah Jadwal Baru:</label>
                    <div class="input-group">
                        <select id="newDay" class="form-select">
                            <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option>
                        </select>
                        <input type="time" id="newStart" class="form-control">
                        <input type="time" id="newEnd" class="form-control">
                        <button class="btn btn-primary" onclick="addNewSchedule()">+</button>
                    </div>
                </div>
                <hr>
                <h6>Jadwal Saat Ini:</h6>
                <ul class="list-group" id="listJadwalModal"></ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const token = localStorage.getItem('token_admin'); 
    // (Jika Anda pakai token, masukan di header fetch, jika tidak abaikan saja)

    async function loadDoctors() {
        const res = await fetch('http://localhost:5000/doctors');
        const data = await res.json();
        const tbody = document.getElementById('doctorsTable');
        tbody.innerHTML = '';
        
        data.forEach(doc => {
            tbody.innerHTML += `
                <tr>
                    <td>${doc.name}<br><small class="text-muted">${doc.email}</small></td>
                    <td>${doc.specialist}</td>
                    <td>
                        <button onclick="openScheduleModal(${doc.id})" class="btn btn-sm btn-info text-white mb-1">Jadwal</button>
                        <button onclick="hapusDokter(${doc.id})" class="btn btn-sm btn-danger mb-1">Hapus</button>
                    </td>
                </tr>`;
        });
    }

    // --- LOGIKA FORM TAMBAH DOKTER ---
    function addScheduleRow() {
        const div = document.createElement('div');
        div.className = 'input-group mb-2 schedule-row';
        div.innerHTML = `
            <select class="form-select form-select-sm s-day">
                <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option>
            </select>
            <input type="time" class="form-control form-control-sm s-start">
            <span class="input-group-text">-</span>
            <input type="time" class="form-control form-control-sm s-end">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove()">x</button>
        `;
        document.getElementById('scheduleContainer').appendChild(div);
    }

    document.getElementById('formAddDoctor').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Ambil data jadwal dari form dinamis
        const scheduleRows = document.querySelectorAll('.schedule-row');
        let schedules = [];
        scheduleRows.forEach(row => {
            schedules.push({
                day: row.querySelector('.s-day').value,
                start_time: row.querySelector('.s-start').value,
                end_time: row.querySelector('.s-end').value
            });
        });

        const data = {
            name: document.getElementById('name').value,
            specialist: document.getElementById('specialist').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            phone: document.getElementById('phone').value,
            schedules: schedules // Kirim array jadwal
        };

        const res = await fetch('http://localhost:5000/admin/doctors', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });

        if(res.ok) {
            alert("Berhasil!");
            document.getElementById('formAddDoctor').reset();
            document.getElementById('scheduleContainer').innerHTML = ''; // Clear jadwal rows
            loadDoctors();
        } else {
            const msg = await res.json();
            alert(msg.msg || "Gagal");
        }
    });

    // --- LOGIKA MODAL KELOLA JADWAL (EDIT) ---
    const modalJadwal = new bootstrap.Modal(document.getElementById('modalJadwal'));

    async function openScheduleModal(id) {
        document.getElementById('manageDoctorId').value = id;
        await loadSpecificSchedule(id);
        modalJadwal.show();
    }

    async function loadSpecificSchedule(doctorId) {
        const res = await fetch(`http://localhost:5000/schedules/${doctorId}`);
        const data = await res.json();
        const list = document.getElementById('listJadwalModal');
        list.innerHTML = '';
        
        if(data.length === 0) list.innerHTML = '<li class="list-group-item text-center text-muted">Belum ada jadwal.</li>';

        data.forEach(s => {
            list.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><b>${s.day}</b> (${s.start_time.slice(0,5)} - ${s.end_time.slice(0,5)})</span>
                    <button onclick="hapusJadwal(${s.id}, ${s.doctor_id})" class="btn btn-sm btn-outline-danger">Hapus</button>
                </li>
            `;
        });
    }

    async function addNewSchedule() {
        const docId = document.getElementById('manageDoctorId').value;
        const data = {
            doctor_id: docId,
            day: document.getElementById('newDay').value,
            start_time: document.getElementById('newStart').value,
            end_time: document.getElementById('newEnd').value
        };
        
        if(!data.start_time || !data.end_time) return alert("Jam harus diisi");

        await fetch('http://localhost:5000/schedules', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        loadSpecificSchedule(docId); // Reload list di modal
    }

    async function hapusJadwal(scheduleId, docId) {
        if(!confirm("Hapus jam praktik ini?")) return;
        await fetch(`http://localhost:5000/schedules/${scheduleId}`, { method: 'DELETE' });
        loadSpecificSchedule(docId);
    }

    async function hapusDokter(id) {
        if(confirm("Hapus Dokter?")) {
            await fetch(`http://localhost:5000/admin/doctors/${id}`, { method: 'DELETE' });
            loadDoctors();
        }
    }

    function logout() { localStorage.clear(); window.location.href = 'index.php'; }
    
    loadDoctors();
</script>
</body>
</html>
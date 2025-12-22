<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #212529;">

<nav class="navbar navbar-dark bg-dark border-bottom border-secondary px-4">
    <span class="navbar-brand fw-bold text-warning">⚡ Admin Panel</span>
    <button onclick="logout()" class="btn btn-outline-light btn-sm">Logout</button>
</nav>

<div class="container py-5">
    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    ➕ Tambah Dokter Baru
                </div>
                <div class="card-body">
                    <form id="formAddDoctor">
                        <div class="mb-2">
                            <label>Nama Dokter</label>
                            <input type="text" id="name" class="form-control" required placeholder="Dr. Siapa">
                        </div>
                        <div class="mb-2">
                            <label>Spesialis</label>
                            <input type="text" id="specialist" class="form-control" required placeholder="Jantung / Gigi">
                        </div>
                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Password</label>
                            <input type="text" id="password" class="form-control" required value="123456">
                        </div>
                        <div class="mb-3">
                            <label>No HP</label>
                            <input type="text" id="phone" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Dokter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white fw-bold text-dark">
                    👨‍⚕️ Daftar Dokter Aktif
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Spesialis</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelDokter">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // 1. Cek Apakah Dia Admin?
    const role = localStorage.getItem('role');
    if (role !== 'admin') {
        alert("AREA TERLARANG! Khusus Admin.");
        window.location.href = '../index.php';
    }

    // 2. Load Daftar Dokter
    async function loadDoctors() {
        // Kita pakai API get doctors yang sudah ada sebelumnya
        const res = await fetch('http://localhost:5000/doctors'); 
        const data = await res.json();
        
        const tbody = document.getElementById('tabelDokter');
        tbody.innerHTML = '';

        data.forEach(doc => {
            tbody.innerHTML += `
                <tr>
                    <td class="fw-bold">${doc.name}</td>
                    <td><span class="badge bg-info text-dark">${doc.specialist}</span></td>
                    <td>${doc.email}</td>
                    <td>
                        <button onclick="hapusDokter(${doc.id})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }
    loadDoctors();

    // 3. Fungsi Tambah Dokter
    document.getElementById('formAddDoctor').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = {
            name: document.getElementById('name').value,
            specialist: document.getElementById('specialist').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            phone: document.getElementById('phone').value
        };

        const res = await fetch('http://localhost:5000/admin/doctors', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });

        if(res.ok) {
            alert("Dokter Berhasil Ditambahkan!");
            document.getElementById('formAddDoctor').reset();
            loadDoctors(); // Refresh tabel
        } else {
            alert("Gagal. Email mungkin sudah dipakai.");
        }
    });

    // 4. Fungsi Hapus Dokter
    async function hapusDokter(id) {
        if(!confirm("Yakin ingin menghapus dokter ini?")) return;

        const res = await fetch(`http://localhost:5000/admin/doctors/${id}`, {
            method: 'DELETE'
        });

        if(res.ok) {
            loadDoctors();
        } else {
            alert("Gagal menghapus.");
        }
    }

    // 5. Logout
    function logout() {
        localStorage.clear();
        window.location.href = 'index.php';
    }
</script>

</body>
</html>
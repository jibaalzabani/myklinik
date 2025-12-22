<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Janji Temu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: #f5f7ff;">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 p-4 rounded-4">
                <h4 class="fw-bold text-primary mb-4">🏥 Form Janji Temu</h4>
                
                <div id="alertBox" class="alert d-none"></div>

                <form id="formReservasi">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Dokter</label>
                        <select id="doctor_id" class="form-select" required>
                            <option value="">-- Sedang memuat dokter... --</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" id="date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jam</label>
                            <input type="time" id="time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keluhan / Gejala</label>
                        <textarea id="complaint" class="form-control" rows="3" placeholder="Contoh: Demam tinggi sudah 3 hari..." required></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="dashboard-pasien.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" id="btnSubmit" class="btn btn-primary fw-bold w-100">Kirim Reservasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Cek Login
    const userId = localStorage.getItem('id_user');
    if (!userId) {
        alert("Sesi habis, silakan login kembali.");
        window.location.href = 'login-pasien.php';
    }

    // 2. Load Daftar Dokter saat halaman dibuka
    async function loadDoctors() {
        try {
            const res = await fetch('http://localhost:5000/doctors');
            const doctors = await res.json();
            
            const select = document.getElementById('doctor_id');
            select.innerHTML = '<option value="">-- Pilih Dokter --</option>'; // Reset

            if(doctors.length === 0) {
                 select.innerHTML = '<option value="">Belum ada dokter tersedia</option>';
            }

            doctors.forEach(doc => {
                // Tampilkan Nama & Spesialis (jika ada)
                const spesialis = doc.specialist ? `(Spesialis ${doc.specialist})` : '(Dokter Umum)';
                select.innerHTML += `<option value="${doc.id}">${doc.name} ${spesialis}</option>`;
            });
        } catch (err) {
            console.error("Gagal ambil data dokter:", err);
            alert("Gagal memuat daftar dokter. Pastikan server backend nyala.");
        }
    }
    loadDoctors(); // Jalankan fungsi

    // 3. Kirim Data Reservasi
    document.getElementById('formReservasi').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = document.getElementById('btnSubmit');
        const alertBox = document.getElementById('alertBox');
        
        btn.disabled = true;
        btn.innerText = "Mengirim...";

        const data = {
            user_id: userId, // Ambil dari localStorage
            doctor_id: document.getElementById('doctor_id').value,
            date: document.getElementById('date').value,
            time: document.getElementById('time').value,
            complaint: document.getElementById('complaint').value
        };

        try {
            const res = await fetch('http://localhost:5000/reservasi', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            if(res.ok) {
                alertBox.className = 'alert alert-success';
                alertBox.innerText = "✅ Reservasi Berhasil! Menunggu konfirmasi.";
                alertBox.classList.remove('d-none');
                
                // Reset form
                document.getElementById('formReservasi').reset();
                setTimeout(() => window.location.href = 'dashboard-pasien.php', 2000);
            } else {
                throw new Error("Gagal mengirim reservasi");
            }
        } catch (err) {
            alertBox.className = 'alert alert-danger';
            alertBox.innerText = "❌ Terjadi kesalahan server.";
            alertBox.classList.remove('d-none');
        } finally {
            btn.disabled = false;
            btn.innerText = "Kirim Reservasi";
        }
    });
</script>

</body>
</html>
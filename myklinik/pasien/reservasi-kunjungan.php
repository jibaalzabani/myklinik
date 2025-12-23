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
                        <div id="infoJadwal" class="mt-2"></div>
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
    // --- SETUP AWAL ---
    const userId = localStorage.getItem('id_user');
    let currentSchedule = []; // Menyimpan jadwal dokter yang sedang dipilih

    // Mapping Hari JS (0-6) ke Bahasa Indonesia (Sesuai Database)
    const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    if (!userId) {
        alert("Sesi habis, silakan login kembali.");
        window.location.href = 'login-pasien.php';
    }

    // --- 1. LOAD DOKTER ---
    async function loadDoctors() {
        try {
            const res = await fetch('http://localhost:5000/doctors');
            const doctors = await res.json();
            
            const select = document.getElementById('doctor_id');
            select.innerHTML = '<option value="">-- Pilih Dokter --</option>';

            doctors.forEach(doc => {
                const spesialis = doc.specialist ? `(${doc.specialist})` : '';
                select.innerHTML += `<option value="${doc.id}">${doc.name} ${spesialis}</option>`;
            });
        } catch (err) {
            console.error("Error load doctors:", err);
        }
    }
    loadDoctors();

    // --- 2. EVENT SAAT DOKTER DIPILIH (Load Jadwal) ---
    document.getElementById('doctor_id').addEventListener('change', async function() {
        const doctorId = this.value;
        const infoDiv = document.getElementById('infoJadwal'); // Pastikan div ini ada (lihat langkah html di bawah)
        
        // Reset
        currentSchedule = [];
        if (!infoDiv) return; // Guard clause
        infoDiv.innerHTML = '<small class="text-muted">Memuat jadwal...</small>';

        if (!doctorId) {
            infoDiv.innerHTML = '';
            return;
        }

        try {
            // Panggil API baru yang kita buat di index.js
            const res = await fetch(`http://localhost:5000/schedules/${doctorId}`);
            const data = await res.json();
            currentSchedule = data;

            if (data.length === 0) {
                infoDiv.innerHTML = '<div class="alert alert-warning py-1"><small>Dokter ini belum memiliki jadwal terperinci di sistem.</small></div>';
            } else {
                let html = '<div class="alert alert-info py-2 mb-0"><small><strong>Jadwal Praktik:</strong><br>';
                data.forEach(s => {
                    // Potong detik dari waktu (08:00:00 -> 08:00)
                    const start = s.start_time.substring(0, 5);
                    const end = s.end_time.substring(0, 5);
                    html += `• ${s.day}: ${start} - ${end}<br>`;
                });
                html += '</small></div>';
                infoDiv.innerHTML = html;
            }
        } catch (err) {
            console.error(err);
            infoDiv.innerHTML = '<small class="text-danger">Gagal memuat jadwal.</small>';
        }
    });

    // --- 3. KIRIM RESERVASI DENGAN VALIDASI ---
    document.getElementById('formReservasi').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = document.getElementById('btnSubmit');
        const alertBox = document.getElementById('alertBox');
        const tglInput = document.getElementById('date').value;
        const jamInput = document.getElementById('time').value;

        // --- VALIDASI HARI & JAM ---
        if (currentSchedule.length > 0) {
            const tglDipilih = new Date(tglInput);
            const hariIniIndo = namaHari[tglDipilih.getDay()]; // Ambil nama hari (Senin, Selasa, dll)
            
            // Cek apakah hari ini ada di jadwal dokter
            const jadwalHariIni = currentSchedule.find(s => s.day === hariIniIndo);

            if (!jadwalHariIni) {
                alert(`Dokter tidak praktik pada hari ${hariIniIndo}. Silakan cek info jadwal.`);
                return;
            }

            // Cek Jam (Sederhana)
            // Format jamInput "10:00", jadwal "08:00:00"
            if (jamInput < jadwalHariIni.start_time || jamInput > jadwalHariIni.end_time) {
                alert(`Jam praktik hari ${hariIniIndo} adalah ${jadwalHariIni.start_time.substring(0,5)} - ${jadwalHariIni.end_time.substring(0,5)}.`);
                return;
            }
        }
        // --- END VALIDASI ---

        btn.disabled = true;
        btn.innerText = "Mengirim...";

        const data = {
            user_id: userId,
            doctor_id: document.getElementById('doctor_id').value,
            date: tglInput,
            time: jamInput,
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
                document.getElementById('formReservasi').reset();
                document.getElementById('infoJadwal').innerHTML = ''; // Reset info
                setTimeout(() => window.location.href = 'dashboard-pasien.php', 2000);
            } else {
                throw new Error("Gagal mengirim");
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - My Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
    background: #f3f6fb; 
    min-height: 100vh; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    padding-top: 3rem;    /* Ini arti dari py-5 */
    padding-bottom: 3rem; /* Ini arti dari py-5 */
        }
        .reg-card { width: 500px; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="reg-card">
    <h3 class="fw-bold text-center text-primary mb-4">Buat Akun Baru</h3>
    
    <div id="alertBox" class="alert d-none"></div>

    <form id="formReg">
        <div class="mb-3">
            <label class="form-label fw-bold small">Nama Lengkap</label>
            <input type="text" id="nama" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold small">Nomor HP</label>
            <input type="number" id="no_hp" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Email</label>
            <input type="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Password</label>
            <input type="password" id="password" class="form-control" required>
        </div>

        <button type="submit" id="btnDaftar" class="btn btn-primary w-100 fw-bold py-2">Daftar Sekarang</button>
    </form>

    <div class="text-center mt-3">
        Sudah punya akun? <a href="login-pasien.php" class="text-decoration-none fw-bold">Login disini</a>
    </div>
</div>

<script>
    document.getElementById('formReg').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Ambil data dari form
        const dataForm = {
            nama: document.getElementById('nama').value,
            no_hp: document.getElementById('no_hp').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        const btn = document.getElementById('btnDaftar');
        const alertBox = document.getElementById('alertBox');

        btn.innerText = "Memproses...";
        btn.disabled = true;

        try {
            const res = await fetch('http://localhost:5000/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataForm)
            });

            const hasil = await res.json();

            if (res.ok) {
                // Sukses
                alertBox.className = "alert alert-success";
                alertBox.innerText = hasil.msg;
                alertBox.classList.remove('d-none');
                
                // Tunggu 2 detik lalu pindah ke login
                setTimeout(() => {
                    window.location.href = 'login-pasien.php';
                }, 2000);
            } else {
                // Gagal (Misal email kembar)
                throw new Error(hasil.msg);
            }

        } catch (err) {
            alertBox.className = "alert alert-danger";
            alertBox.innerText = err.message;
            alertBox.classList.remove('d-none');
            btn.innerText = "Daftar Sekarang";
            btn.disabled = false;
        }
    });
</script>

</body>
</html>
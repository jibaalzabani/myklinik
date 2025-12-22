<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="card p-4 shadow" style="width: 400px;">
    <h3 class="text-center fw-bold text-primary">Login Pasien</h3>
    <div id="alertBox" class="alert alert-danger d-none small"></div>

    <form id="formLogin">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" id="email" class="form-control" value="pasien@gmail.com" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" id="password" class="form-control" value="123456" required>
        </div>
        <button type="submit" id="btnLogin" class="btn btn-primary w-100">Masuk</button>
    </form>
</div>

<script>
    document.getElementById('formLogin').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const btn = document.getElementById('btnLogin');
        const alertBox = document.getElementById('alertBox');

        btn.innerText = "Mengecek Server...";
        btn.disabled = true;
        alertBox.classList.add('d-none');

        try {
            const res = await fetch('http://localhost:5000/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();

            // --- BAGIAN DIAGNOSA (PENTING) ---
            console.log("Respon Server:", data); // Cek di Console F12

            if (res.ok) {
                // Cek apakah Token benar-benar ada?
                if (!data.token) {
                    throw new Error("Gawat! Server tidak mengirim Token!");
                }
                if (!data.user || !data.user.role) {
                    throw new Error("Gawat! Server tidak mengirim Role!");
                }

                // Tampilkan Alert Sukses & Isi Datanya (Supaya kita yakin)
                alert("LOGIN SUKSES!\n\nData yang diterima:\nToken: " + data.token.substring(0, 10) + "...\nRole: " + data.user.role);

                // Simpan Data
                localStorage.setItem('token', data.token);
                localStorage.setItem('nama', data.user.name);
                localStorage.setItem('role', data.user.role);
                localStorage.setItem('id_user', data.user.id);

                // Pindah Halaman
                window.location.href = 'dashboard-pasien.php';
            } else {
                throw new Error(data.msg || "Login Gagal");
            }

        } catch (err) {
            alertBox.innerText = err.message;
            alertBox.classList.remove('d-none');
            btn.innerText = "Masuk";
            btn.disabled = false;
        }
    });
</script>
</body>
</html>
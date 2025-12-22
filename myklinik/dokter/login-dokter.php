<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { width: 400px; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="login-box">
        <h3 class="text-center fw-bold mb-4 text-primary">Portal Dokter</h3>
        
        <div id="alertBox" class="alert alert-danger d-none small"></div>

        <form id="formLogin">
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" id="email" class="form-control" placeholder="dokter@klinik.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Masukan password" required>
            </div>
            <button type="submit" id="btnLogin" class="btn btn-primary w-100 py-2">Masuk</button>
        </form>
    </div>

    <script>
        document.getElementById('formLogin').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Reset Alert
        const alertBox = document.getElementById('alertBox'); // Pastikan ada div id='alertBox' di HTML
        alertBox.classList.add('d-none');

        try {
            const res = await fetch('http://localhost:5000/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();

            if (res.ok) {
                // Simpan data user
                localStorage.setItem('token', data.token);
                localStorage.setItem('role', data.user.role);
                localStorage.setItem('nama', data.user.name);
                localStorage.setItem('id_user', data.user.id);

                alert("Login Berhasil! Selamat datang " + data.user.role);

                // --- PENGALIHAN ARAH (REDIRECT) ---
                if (data.user.role === 'admin') {
                    // Jika Admin, ke Dashboard Admin
                    window.location.href = '../dashboard-admin.php'; 
                } else {
                    // Jika Dokter, ke Dashboard Dokter
                    window.location.href = 'dashboard-dokter.php'; 
                }
                
            } else {
                throw new Error(data.msg || "Login Gagal");
            }

        } catch (err) {
            alert("Gagal: " + err.message);
        }
    });
    </script>
</body>
</html>
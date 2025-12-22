<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff8e1; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { width: 400px; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #ff9800; }
    </style>
</head>
<body>

    <div class="login-box">
        <h3 class="text-center fw-bold mb-4 text-warning">Login Staff</h3>
        
        <div id="alertBox" class="alert alert-danger d-none small"></div>

        <form id="formLogin">
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" id="email" class="form-control" placeholder="staff@klinik.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Masukan password" required>
            </div>
            <button type="submit" id="btnLogin" class="btn btn-warning text-white w-100 py-2 fw-bold">Masuk</button>
        </form>
    </div>

    <script>
        document.getElementById('formLogin').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const alertBox = document.getElementById('alertBox');
            const btn = document.getElementById('btnLogin');

            btn.innerText = "Memproses...";
            btn.disabled = true;

            try {
                // Hubungi Backend
                const res = await fetch('http://localhost:5000/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const data = await res.json();

                if (res.ok) {
                    // Cek Role Staff
                    if(data.user.role !== 'staff') {
                        throw new Error("Anda bukan Staff!");
                    }

                    // Simpan Token
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('nama', data.user.name);
                    localStorage.setItem('role', data.user.role);

                    // Pindah Halaman
                    window.location.href = 'dashboard-staff.php';
                } else {
                    throw new Error(data.msg);
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
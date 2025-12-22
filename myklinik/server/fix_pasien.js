const mysql = require('mysql2');
const bcrypt = require('bcryptjs');

const db = mysql.createConnection({
    host: 'localhost', user: 'root', password: '', database: 'klinik_db'
});

// Password: 123456
const hashBaru = bcrypt.hashSync('123456', 10);

db.connect(err => {
    if (err) throw err;
    console.log("Database Terhubung. Reset akun Pasien...");

    // Pastikan user pasien@gmail.com ada, lalu update passwordnya
    // Jika belum ada, kita INSERT sekalian biar aman
    const cekUser = "SELECT * FROM users WHERE email = 'pasien@gmail.com'";

    db.query(cekUser, (err, result) => {
        if (result.length === 0) {
            // Buat baru jika tidak ada
            const insert = "INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, 'pasien', ?)";
            db.query(insert, ['Asep Pasien', 'pasien@gmail.com', hashBaru, '08123456789'], () => {
                console.log("SUKSES! Akun Pasien Dibuat: pasien@gmail.com / 123456");
                process.exit();
            });
        } else {
            // Update jika sudah ada
            const update = "UPDATE users SET password = ? WHERE email = 'pasien@gmail.com'";
            db.query(update, [hashBaru], () => {
                console.log("SUKSES! Password Pasien Di-reset: pasien@gmail.com / 123456");
                process.exit();
            });
        }
    });
});
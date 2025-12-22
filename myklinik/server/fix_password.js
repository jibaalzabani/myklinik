const mysql = require('mysql2');
const bcrypt = require('bcryptjs');

// Koneksi ke Database
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'klinik_db'
});

// Password yang kita inginkan
const passwordBaru = '123456';
// Enkripsi password tersebut sekarang juga
const hashBaru = bcrypt.hashSync(passwordBaru, 10);

db.connect(err => {
    if (err) { console.error("Database Gagal Konek:", err); return; }
    
    console.log("Database Terhubung. Sedang memperbaiki akun Dokter...");

    // Update password dokter menjadi 123456 yang valid
    const sql = `UPDATE users SET password = ? WHERE email = 'dokter@klinik.com'`;
    
    db.query(sql, [hashBaru], (err, result) => {
        if (err) {
            console.error("Gagal Update:", err);
        } else {
            console.log("========================================");
            console.log("SUKSES! Password Dokter sudah di-reset.");
            console.log("Silakan Login dengan:");
            console.log("Email: dokter@klinik.com");
            console.log("Pass : 123456");
            console.log("========================================");
        }
        process.exit();
    });
});
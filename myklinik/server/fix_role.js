const mysql = require('mysql2');
const db = mysql.createConnection({
    host: 'localhost', user: 'root', password: '', database: 'klinik_db'
});

db.connect(() => {
    console.log("Memperbaiki Role User...");
    // Paksa semua role jadi huruf kecil agar cocok dengan kodingan
    db.query("UPDATE users SET role = 'pasien' WHERE email = 'pasien@gmail.com'", () => {
        console.log("Role Pasien sudah diperbaiki jadi 'pasien' (huruf kecil).");
        process.exit();
    });
});
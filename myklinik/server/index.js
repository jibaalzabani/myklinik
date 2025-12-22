const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');
const cors = require('cors');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const app = express();
app.use(cors()); // Agar website boleh akses backend
app.use(express.json()); // Agar bisa baca data kiriman

// --- KONEKSI DATABASE ---
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',      // User default Laragon/XAMPP
    password: '',      // Password default (kosong)
    database: 'klinik_db' // Nama database yang kita buat tadi
});

// Cek koneksi
db.connect((err) => {
    if (err) {
        console.error('Gagal konek database:', err);
    } else {
        console.log('Berhasil terhubung ke Database MySQL...');
    }
});

const JWT_SECRET = 'rahasia_klinik_123'; // Kunci rahasia token



// --- 1. ROUTE LOGIN (PENTING) ---
// --- LOGIN USER (Dokter/Pasien/Staff) ---
app.post('/login', (req, res) => {
    const { email, password } = req.body;
    
    // Ambil data user berdasarkan email
    const sql = "SELECT * FROM users WHERE email = ?";
    
    db.query(sql, [email], (err, result) => {
        if (err) return res.status(500).json({ msg: "Error Database" });
        
        // Cek apakah user ada?
        if (result.length === 0) {
            return res.status(404).json({ msg: "Email tidak ditemukan!" });
        }
        
        // Cek Password
        const user = result[0];
        const cekPass = bcrypt.compareSync(password, user.password);
        
        if (!cekPass) {
            return res.status(401).json({ msg: "Password Salah!" });
        }
        
        // Buat Token
        const token = jwt.sign({ id: user.id, role: user.role }, 'rahasia_negara', { expiresIn: '1d' });
        
        // --- BAGIAN PENTING: KIRIM DATA LENGKAP KE FRONTEND ---
        res.json({
            token: token,
            msg: "Login Berhasil",
            user: {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role, // <--- PASTIKAN INI ADA!
                phone: user.phone
            }
        });
    });
});

// --- 4. ROUTE REGISTER PASIEN BARU ---
app.post('/register', (req, res) => {
    const { nama, email, password, no_hp } = req.body;

    // 1. Cek apakah email sudah dipakai?
    const cekSql = "SELECT * FROM users WHERE email = ?";
    db.query(cekSql, [email], (err, result) => {
        if(err) return res.status(500).json({msg: "Error Server"});
        
        if(result.length > 0) {
            return res.status(400).json({msg: "Email sudah terdaftar!"});
        }

        // 2. Kalau belum, enkripsi password & simpan
        const hash = bcrypt.hashSync(password, 10);
        const sql = "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'pasien')";
        
        db.query(sql, [nama, email, hash, no_hp], (err, result) => {
            if (err) return res.status(500).json({ msg: "Gagal Mendaftar" });
            
            res.status(201).json({ msg: "Registrasi Berhasil! Silakan Login." });
        });
    });
});

// --- 4. AMBIL DATA DOKTER ---
app.get('/doctors', (req, res) => {
    // PERHATIKAN: Saya menambahkan koma dan kata "email" di dalam SELECT
    const sql = "SELECT id, name, specialist, email FROM users WHERE role = 'dokter'";
    
    db.query(sql, (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result);
    });
});

// --- 6. SIMPAN RESERVASI BARU ---
app.post('/reservasi', (req, res) => {
    const { user_id, doctor_id, date, time, complaint } = req.body;

    const sql = "INSERT INTO appointments (user_id, doctor_id, date, time, complaint) VALUES (?, ?, ?, ?, ?)";
    
    db.query(sql, [user_id, doctor_id, date, time, complaint], (err, result) => {
        if (err) {
            console.error(err); // Cek error di terminal jika gagal
            return res.status(500).json({ msg: "Gagal menyimpan reservasi" });
        }
        res.status(201).json({ msg: "Reservasi Berhasil Dibuat!" });
    });
});

// --- 7. AMBIL RIWAYAT RESERVASI (Spesifik per Pasien) ---
app.get('/appointments/:userId', (req, res) => {
    const userId = req.params.userId;
    
    // Kita pakai JOIN supaya yang muncul bukan ID Dokter (misal: 5), 
    // tapi Nama Dokternya (misal: Dr. Strange)
    const sql = `
        SELECT a.id, a.date, a.time, a.complaint, a.status, u.name AS doctor_name 
        FROM appointments a 
        JOIN users u ON a.doctor_id = u.id 
        WHERE a.user_id = ? 
        ORDER BY a.date DESC
    `;
    
    db.query(sql, [userId], (err, result) => {
        if (err) {
            console.error(err);
            return res.status(500).json({ msg: "Error mengambil riwayat" });
        }
        res.json(result);
    });
});

// --- 8. DASHBOARD DOKTER (Lihat Pasien yang Booking ke Dia) ---
app.get('/doctor/appointments/:doctorId', (req, res) => {
    const doctorId = req.params.doctorId;
    
    // Kita JOIN tabel appointments dengan users (tapi kali ini ambil nama PASIEN)
    const sql = `
        SELECT a.id, a.date, a.time, a.complaint, a.status, u.name AS patient_name 
        FROM appointments a 
        JOIN users u ON a.user_id = u.id 
        WHERE a.doctor_id = ? 
        ORDER BY a.date ASC, a.time ASC
    `;
    
    db.query(sql, [doctorId], (err, result) => {
        if (err) {
            console.error("❌ Error SQL",err);
            return res.status(500).json({ msg: "Error Database Dokter" });
        }


        res.json(result);
    });
});

// --- 9. UPDATE STATUS JANJI TEMU (Selesai/Batal) ---
app.put('/appointments/:id', (req, res) => {
    const id = req.params.id;      // ID Janji Temu
    const { status } = req.body;   // Status Baru ('selesai' / 'batal')

    const sql = "UPDATE appointments SET status = ? WHERE id = ?";

    db.query(sql, [status, id], (err, result) => {
        if (err) return res.status(500).json({ msg: "Gagal update status" });
        res.json({ msg: "Status berhasil diperbarui!" });
    });
});

// --- 10. ADMIN: TAMBAH DOKTER BARU ---
app.post('/admin/doctors', async (req, res) => {
    const { name, email, password, specialist, phone } = req.body;
    
    // 1. Enkripsi Password Dulu!
    const hashedPassword = await bcrypt.hash(password, 10);
    
    // 2. Masukkan ke Database (Password yang sudah di-hash)
    const sql = "INSERT INTO users (name, email, password, role, specialist, phone) VALUES (?, ?, ?, 'dokter', ?, ?)";
    
    db.query(sql, [name, email, hashedPassword, specialist, phone], (err, result) => {
        if (err) {
            console.error(err);
            return res.status(500).json({ msg: "Gagal menambah dokter. Email mungkin kembar." });
        }
        res.status(201).json({ msg: "Dokter berhasil ditambahkan!" });
    });
});

// --- 11. ADMIN: HAPUS DOKTER ---
app.delete('/admin/doctors/:id', (req, res) => {
    const id = req.params.id;
    
    const sql = "DELETE FROM users WHERE id = ?";
    
    db.query(sql, [id], (err, result) => {
        if (err) return res.status(500).json({ msg: "Gagal menghapus" });
        res.json({ msg: "Dokter berhasil dihapus" });
    });
});

// --- 2. JALANKAN SERVER ---
app.listen(5000, () => {
    console.log("Server Backend berjalan di http://localhost:5000");
});
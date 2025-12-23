const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const app = express();
app.use(cors()); 
app.use(express.json()); 

// =========================================================
//  KONFIGURASI DATABASE
// =========================================================
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',      
    password: '',      
    database: 'klinik_db' 
});

db.connect((err) => {
    if (err) {
        console.error('❌ Gagal konek database:', err);
    } else {
        console.log('✅ Berhasil terhubung ke Database MySQL...');
    }
});

// =========================================================
//  AUTH (LOGIN & REGISTER)
// =========================================================

// --- 1. LOGIN (Pasien, Dokter, Admin) ---
app.post('/login', (req, res) => {
    const { email, password } = req.body;
    const sql = "SELECT * FROM users WHERE email = ?";
    
    db.query(sql, [email], (err, result) => {
        if (err) return res.status(500).json({ msg: "Error Database" });
        if (result.length === 0) return res.status(404).json({ msg: "Email tidak ditemukan!" });
        
        const user = result[0];
        const cekPass = bcrypt.compareSync(password, user.password);
        if (!cekPass) return res.status(401).json({ msg: "Password Salah!" });
        
        const token = jwt.sign({ id: user.id, role: user.role }, 'rahasia_negara', { expiresIn: '1d' });
        
        res.json({
            token: token,
            msg: "Login Berhasil",
            user: {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role, 
                phone: user.phone
            }
        });
    });
});

// --- 2. REGISTER PASIEN ---
app.post('/register', (req, res) => {
    const { nama, email, password, no_hp } = req.body;
    
    // Cek email dulu
    const cekSql = "SELECT * FROM users WHERE email = ?";
    db.query(cekSql, [email], (err, result) => {
        if(err) return res.status(500).json({msg: "Error Server"});
        if(result.length > 0) return res.status(400).json({msg: "Email sudah terdaftar!"});

        const hash = bcrypt.hashSync(password, 10);
        const sql = "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'pasien')";
        
        db.query(sql, [nama, email, hash, no_hp], (err, result) => {
            if (err) return res.status(500).json({ msg: "Gagal Mendaftar" });
            res.status(201).json({ msg: "Registrasi Berhasil! Silakan Login." });
        });
    });
});

// =========================================================
//  PASIEN: RESERVASI & RIWAYAT
// =========================================================

// --- 3. AMBIL DAFTAR DOKTER ---
app.get('/doctors', (req, res) => {
    const sql = "SELECT id, name, specialist, email, phone FROM users WHERE role = 'dokter'";
    db.query(sql, (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result);
    });
});

// --- 4. AMBIL JADWAL SPESIFIK DOKTER (Untuk Validasi di Frontend) ---
app.get('/schedules/:doctorId', (req, res) => {
    const doctorId = req.params.doctorId;
    // Mengurutkan hari agar rapi (Senin s/d Minggu)
    const sql = `
        SELECT * FROM schedules 
        WHERE doctor_id = ? 
        ORDER BY FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), start_time
    `;
    db.query(sql, [doctorId], (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result);
    });
});

// --- 5. SIMPAN RESERVASI BARU ---
app.post('/reservasi', (req, res) => {
    const { user_id, doctor_id, date, time, complaint } = req.body;
    const sql = "INSERT INTO appointments (user_id, doctor_id, date, time, complaint) VALUES (?, ?, ?, ?, ?)";
    
    db.query(sql, [user_id, doctor_id, date, time, complaint], (err, result) => {
        if (err) return res.status(500).json({ msg: "Gagal menyimpan reservasi" });
        res.status(201).json({ msg: "Reservasi Berhasil Dibuat!" });
    });
});

// --- 6. AMBIL RIWAYAT PASIEN (DASHBOARD PASIEN) ---
// Update: Mengambil kolom 'diagnosis' juga
app.get('/appointments/:userId', (req, res) => {
    const userId = req.params.userId;
    const sql = `
        SELECT a.id, a.date, a.time, a.complaint, a.diagnosis, a.status, u.name AS doctor_name 
        FROM appointments a 
        JOIN users u ON a.doctor_id = u.id 
        WHERE a.user_id = ? 
        ORDER BY a.date DESC
    `;
    db.query(sql, [userId], (err, result) => {
        if (err) return res.status(500).json({ msg: "Error mengambil riwayat" });
        res.json(result);
    });
});

// =========================================================
//  ADMIN: MANAJEMEN DOKTER & JADWAL
// =========================================================

// --- 7. TAMBAH DOKTER + JADWAL BULK ---
app.post('/admin/doctors', async (req, res) => {
    const { name, email, password, specialist, phone, schedules } = req.body;
    
    // Cek Email Duplikat
    db.query("SELECT * FROM users WHERE email = ?", [email], async (err, result) => {
        if(err) return res.status(500).json({msg: "Error cek email"});
        if(result.length > 0) return res.status(400).json({msg: "Email sudah terdaftar!"});

        // Insert User Dokter
        const hashedPassword = await bcrypt.hash(password, 10);
        const sqlDoc = "INSERT INTO users (name, email, password, role, specialist, phone) VALUES (?, ?, ?, 'dokter', ?, ?)";
        
        db.query(sqlDoc, [name, email, hashedPassword, specialist, phone], (err, result) => {
            if (err) return res.status(500).json({ msg: "Gagal menambah dokter." });
            
            const newDoctorId = result.insertId;

            // Insert Jadwal (Jika ada)
            if (schedules && schedules.length > 0) {
                const scheduleValues = schedules.map(s => [newDoctorId, s.day, s.start_time, s.end_time]);
                const sqlSched = "INSERT INTO schedules (doctor_id, day, start_time, end_time) VALUES ?";
                
                db.query(sqlSched, [scheduleValues], (err, resSched) => {
                    if(err) console.error("Gagal simpan jadwal:", err);
                });
            }

            res.status(201).json({ msg: "Dokter berhasil ditambahkan!" });
        });
    });
});

// --- 8. HAPUS DOKTER ---
app.delete('/admin/doctors/:id', (req, res) => {
    const id = req.params.id;
    const sql = "DELETE FROM users WHERE id = ?";
    db.query(sql, [id], (err, result) => {
        if (err) return res.status(500).json({ msg: "Gagal menghapus" });
        res.json({ msg: "Dokter berhasil dihapus" });
    });
});

// --- 9. UPDATE INFO DOKTER ---
app.put('/admin/doctors/:id', (req, res) => {
    const id = req.params.id;
    const { name, specialist, phone } = req.body;
    const sql = "UPDATE users SET name=?, specialist=?, phone=? WHERE id=?";
    db.query(sql, [name, specialist, phone, id], (err, result) => {
        if (err) return res.status(500).json({ msg: "Gagal update dokter" });
        res.json({ msg: "Data dokter berhasil diperbarui!" });
    });
});

// --- 10. TAMBAH SATU JADWAL (MANAJEMEN JADWAL) ---
app.post('/schedules', (req, res) => {
    const { doctor_id, day, start_time, end_time } = req.body;
    const sql = "INSERT INTO schedules (doctor_id, day, start_time, end_time) VALUES (?, ?, ?, ?)";
    db.query(sql, [doctor_id, day, start_time, end_time], (err, result) => {
        if (err) return res.status(500).json(err);
        res.status(201).json({ msg: "Jadwal berhasil ditambah" });
    });
});

// --- 11. HAPUS SATU JADWAL ---
app.delete('/schedules/:id', (req, res) => {
    const id = req.params.id;
    const sql = "DELETE FROM schedules WHERE id = ?";
    db.query(sql, [id], (err, result) => {
        if (err) return res.status(500).json(err);
        res.json({ msg: "Jadwal dihapus" });
    });
});

// =========================================================
//  DASHBOARD DOKTER
// =========================================================

// --- 12. LIHAT PASIEN (Dashboard Dokter) ---
// Update: Mengambil kolom diagnosis
app.get('/doctor/appointments/:id', (req, res) => {
    const doctorId = req.params.id;
    const sql = `
        SELECT a.id, a.date, a.time, a.status, a.complaint, a.diagnosis, a.user_id, u.name AS patient_name 
        FROM appointments a 
        JOIN users u ON a.user_id = u.id 
        WHERE a.doctor_id = ?
    `;
    db.query(sql, [doctorId], (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result);
    });
});

// --- 13. LIHAT RIWAYAT SPESIFIK (Popup Riwayat) ---
// Filter berdasarkan Pasien DAN Dokter (Data Privacy)
app.get('/appointments/history/:patientId/:doctorId', (req, res) => {
    const { patientId, doctorId } = req.params;

    if(!patientId || !doctorId) return res.status(400).json({msg: "Data tidak lengkap"});

    const sql = "SELECT date, complaint, diagnosis, status FROM appointments WHERE user_id = ? AND doctor_id = ? ORDER BY date DESC";
    
    db.query(sql, [patientId, doctorId], (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result);
    });
});

// --- 14. UPDATE STATUS & DIAGNOSA ---
app.put('/appointments/:id', (req, res) => {
    const id = req.params.id;
    const { status, diagnosis } = req.body; 
    
    // Logika Dinamis: Jika ada diagnosa, simpan diagnosa. Jika tidak, cuma update status.
    let sql = "UPDATE appointments SET status = ? WHERE id = ?";
    let params = [status, id];

    if (diagnosis) {
        sql = "UPDATE appointments SET status = ?, diagnosis = ? WHERE id = ?";
        params = [status, diagnosis, id];
    }

    db.query(sql, params, (err, result) => {
        if (err) return res.status(500).json({ msg: "Gagal update status" });
        res.json({ msg: "Update berhasil!" });
    });
});

// =========================================================
//  JALANKAN SERVER
// =========================================================
const PORT = 5000;
app.listen(PORT, () => {
    console.log(`🚀 Server Backend berjalan di http://localhost:${PORT}`);
});
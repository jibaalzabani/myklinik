const User = require('../models/UserModel');
const Patient = require('../models/PatientModel'); 
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const db = require('../config/database'); // Pastikan path ini sesuai dengan file konfigurasi DB Anda

exports.Register = async (req, res) => {
    const { name, email, password, confPassword, nik, phone, address, birth_date, gender } = req.body;

    // Validasi password match
    if (password !== confPassword) return res.status(400).json({ msg: "Password dan Confirm Password tidak cocok" });

    // Gunakan Transaction agar jika salah satu gagal, semua dibatalkan
    const t = await db.transaction();

    try {
        // 1. Cek apakah email sudah ada (opsional, tapi disarankan)
        const userExists = await User.findOne({ where: { email: email } });
        if (userExists) {
            await t.rollback(); // Batalkan transaksi
            return res.status(400).json({ msg: "Email sudah terdaftar" });
        }

        // 2. Buat Akun User
        const salt = await bcrypt.genSalt();
        const hashPassword = await bcrypt.hash(password, salt);
        
        const user = await User.create({
            email: email,
            password: hashPassword,
            role: 'pasien'
        }, { transaction: t }); // Masukkan ke dalam transaksi

        // 3. Buat Data Profil Pasien
        await Patient.create({
            name: name,
            nik: nik,
            phone: phone,
            address: address,
            birth_date: birth_date,
            gender: gender,
            userId: user.id
        }, { transaction: t }); // Masukkan ke dalam transaksi

        // Jika semua berhasil, simpan permanen
        await t.commit();

        res.status(201).json({ msg: "Registrasi Berhasil! Silakan Login." });
    } catch (error) {
        // Jika ada error, batalkan semua perubahan database
        await t.rollback();
        res.status(500).json({ msg: error.message });
    }
}

exports.Login = async (req, res) => {
    try {
        const user = await User.findOne({ where: { email: req.body.email } });
        if (!user) return res.status(404).json({ msg: "User tidak ditemukan" });

        const match = await bcrypt.compare(req.body.password, user.password);
        if (!match) return res.status(400).json({ msg: "Password salah" });

        const token = jwt.sign({ uuid: user.uuid, role: user.role }, process.env.JWT_SECRET, { expiresIn: '1d' });
        
        res.status(200).json({ token, role: user.role });
    } catch (error) {
        res.status(500).json({ msg: error.message });
    }
}
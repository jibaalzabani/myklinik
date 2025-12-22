const Doctor = require('../models/DoctorModel');
const User = require('../models/UserModel');

// 1. Menambahkan Data Dokter (Profil)
// Syarat: Harus sudah ada User dengan role 'dokter'
exports.createDoctorProfile = async (req, res) => {
    try {
        const { name, specialization, phone, userId } = req.body;

        // Cek apakah user ini benar-benar ada
        const user = await User.findByPk(userId);
        if(!user) return res.status(404).json({msg: "User ID tidak ditemukan"});

        const newDoctor = await Doctor.create({
            name: name,
            specialization: specialization,
            phone: phone,
            userId: userId
        });

        res.status(201).json({msg: "Profil Dokter Berhasil Dibuat", data: newDoctor});
    } catch (error) {
        res.status(500).json({msg: error.message});
    }
}

// 2. Melihat Semua Dokter
exports.getAllDoctors = async (req, res) => {
    try {
        const response = await Doctor.findAll({
            include: [{model: User, attributes: ['email', 'role']}] 
        });
        res.status(200).json(response);
    } catch (error) {
        res.status(500).json({msg: error.message});
    }
}
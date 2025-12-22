const Schedule = require('../models/ScheduleModel');
const Doctor = require('../models/DoctorModel');

// 1. Membuat Jadwal Baru (Misal: Dr. Budi, Senin, 08:00 - 12:00)
exports.createSchedule = async (req, res) => {
    try {
        const { day, start_time, end_time, quota, doctorId } = req.body;

        // Cek apakah dokternya ada
        const doctor = await Doctor.findByPk(doctorId);
        if(!doctor) return res.status(404).json({msg: "Dokter tidak ditemukan"});

        const schedule = await Schedule.create({
            day: day,
            start_time: start_time,
            end_time: end_time,
            quota: quota,
            doctorId: doctorId
        });

        res.status(201).json({msg: "Jadwal Berhasil Ditambahkan", data: schedule});
    } catch (error) {
        res.status(500).json({msg: error.message});
    }
}

// 2. Melihat Jadwal Dokter Tertentu
exports.getSchedules = async (req, res) => {
    try {
        const response = await Schedule.findAll({
            include: [{ model: Doctor, attributes: ['name', 'specialization'] }]
        });
        res.status(200).json(response);
    } catch (error) {
        res.status(500).json({msg: error.message});
    }
}
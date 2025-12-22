const express = require('express');
const { createDoctorProfile, getAllDoctors } = require('../controllers/DoctorController');
const { createSchedule, getSchedules } = require('../controllers/ScheduleController');

const router = express.Router();

// Route Dokter
router.post('/doctors', createDoctorProfile); // Harusnya dilindungi middleware admin
router.get('/doctors', getAllDoctors);

// Route Jadwal
router.post('/schedules', createSchedule);
router.get('/schedules', getSchedules);

module.exports = router;
const express = require('express');
const { createReservation, getReservations, approveReservation } = require('../controllers/ReservationController');
const { verifyToken } = require('../middleware/AuthMiddleware');

const router = express.Router();

// 1. GET: Melihat daftar reservasi 
// (Pasien melihat punya sendiri, Dokter melihat semua)
router.get('/reservations', verifyToken, getReservations);

// 2. POST: Membuat reservasi baru
// (Biasanya dilakukan oleh Pasien)
router.post('/reservations', verifyToken, createReservation);

// 3. PATCH: Mengubah status reservasi (Konfirmasi/Tolak)
// (Biasanya dilakukan oleh Dokter/Admin)
// PENTING: Menggunakan parameter /:id agar sistem tahu reservasi mana yang diubah
router.patch('/reservations/:id', verifyToken, approveReservation);

module.exports = router;
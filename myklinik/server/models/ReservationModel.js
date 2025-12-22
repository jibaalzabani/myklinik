const { DataTypes } = require('sequelize');
const db = require('../config/database');
const Patient = require('./PatientModel');
const Schedule = require('./ScheduleModel');

const Reservation = db.define('reservations', {
    reservation_date: {
        type: DataTypes.DATEONLY, // Tanggal reservasi
        allowNull: false
    },
    queue_number: DataTypes.INTEGER, // Nomor antrian
    status: {
        type: DataTypes.ENUM('pending', 'confirmed', 'completed', 'cancelled'),
        defaultValue: 'pending'
    },
    symptoms: DataTypes.TEXT // Keluhan/Gejala
}, { freezeTableName: true });

// Relasi: Reservasi butuh data Pasien dan Jadwal yang dipilih
Reservation.belongsTo(Patient, { foreignKey: 'patientId' });
Reservation.belongsTo(Schedule, { foreignKey: 'scheduleId' });

module.exports = Reservation;
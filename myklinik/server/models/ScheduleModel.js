const { DataTypes } = require('sequelize');
const db = require('../config/database');
const Doctor = require('./DoctorModel');

const Schedule = db.define('schedules', {
    day: { 
        type: DataTypes.STRING, // Contoh: "Senin", "Selasa"
        allowNull: false
    },
    start_time: {
        type: DataTypes.TIME,
        allowNull: false
    },
    end_time: {
        type: DataTypes.TIME,
        allowNull: false
    },
    quota: { 
        type: DataTypes.INTEGER,
        defaultValue: 20 // Batas pasien per hari
    }
}, { freezeTableName: true });

// Relasi: Jadwal milik Dokter tertentu
Schedule.belongsTo(Doctor, { foreignKey: 'doctorId' });

module.exports = Schedule;
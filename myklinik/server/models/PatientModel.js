const { DataTypes } = require('sequelize');
const db = require('../config/database');
const User = require('./UserModel');

const Patient = db.define('patients', {
    nik: {
        type: DataTypes.STRING,
        allowNull: false,
        unique: true
    },
    name: {
        type: DataTypes.STRING,
        allowNull: false
    },
    birth_date: DataTypes.DATEONLY, // Tanggal Lahir
    address: DataTypes.TEXT,        // Alamat
    phone: DataTypes.STRING,        // Nomor Kontak
    gender: DataTypes.ENUM('L', 'P')
}, { freezeTableName: true });

// Relasi: User (Akun) punya 1 Profil Pasien
User.hasOne(Patient, { foreignKey: 'userId', onDelete: 'CASCADE' });
Patient.belongsTo(User, { foreignKey: 'userId' });

module.exports = Patient;
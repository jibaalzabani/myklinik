// server/models/DoctorModel.js
const { DataTypes } = require('sequelize');
const db = require('../config/database');
const User = require('./UserModel');

const Doctor = db.define('doctors', {
    name: {
        type: DataTypes.STRING,
        allowNull: false
    },
    specialization: { // Spesialisasi (Umum, Gigi, Anak)
        type: DataTypes.STRING,
        allowNull: false
    },
    phone: DataTypes.STRING
}, { freezeTableName: true });

User.hasOne(Doctor, { foreignKey: 'userId', onDelete: 'CASCADE' });
Doctor.belongsTo(User, { foreignKey: 'userId' });

module.exports = Doctor;
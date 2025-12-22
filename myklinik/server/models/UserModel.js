// server/models/UserModel.js
const { DataTypes } = require('sequelize');
const db = require('../config/database');

const User = db.define('users', {
    uuid: {
        type: DataTypes.STRING,
        defaultValue: DataTypes.UUIDV4,
        allowNull: false,
        validate: { notEmpty: true }
    },
    email: {
        type: DataTypes.STRING,
        allowNull: false,
        unique: true,
        validate: { isEmail: true }
    },
    password: {
        type: DataTypes.STRING,
        allowNull: false
    },
    role: {
        type: DataTypes.ENUM('admin', 'dokter', 'pasien', 'manajer'),
        allowNull: false
    }
}, { freezeTableName: true });

module.exports = User;
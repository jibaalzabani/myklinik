const User = require('../models/UserModel');

// Fungsi untuk melihat semua user (hanya contoh)
exports.getUsers = async (req, res) => {
    try {
        const response = await User.findAll();
        res.status(200).json(response);
    } catch (error) {
        console.log(error.message);
    }
}

// Fungsi dummy untuk tes server jalan
exports.home = (req, res) => {
    res.send("Server Klinik Berjalan!");
}
const express = require('express');
const { Login, Register, Me, logOut } = require('../controllers/AuthController'); 
// Pastikan file AuthController.js Anda sudah ada fungsi Login & Register nya
// Jika error "Login is not defined", cek kembali AuthController.js

const router = express.Router();

router.post('/login', Login);
router.post('/register', Register);
// router.delete('/logout', logOut); // Opsional jika nanti ada fitur logout
// router.get('/me', Me); // Opsional untuk cek user yang sedang login

module.exports = router;
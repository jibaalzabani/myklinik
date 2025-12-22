const express = require('express');
const { getUsers, home } = require('../controllers/UserController');

const router = express.Router();

router.get('/', home);
router.get('/users', getUsers);

module.exports = router;
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;

// 1. HALAMAN PUBLIK
Route::get('/', function () { return view('index'); });

// 2. AUTHENTICATION ROUTES
Route::get('/login-pasien', function () { return view('pasien.login-pasien'); })->name('login.pasien');
Route::get('/login-dokter', function () { return view('dokter.login-dokter'); })->name('login.dokter');
Route::get('/register', function () { return view('pasien.register'); })->name('register');

// Mengarahkan form login/register ke Controller
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 3. DASHBOARD PASIEN
Route::middleware(['auth', 'role:pasien'])->group(function () {
    
    // Dashboard
    Route::get('/pasien/dashboard', [PatientController::class, 'dashboard']);
    
    // Reservasi (Formulir)
    Route::get('/pasien/reservasi', [PatientController::class, 'showReservationForm']);
    Route::post('/pasien/reservasi', [PatientController::class, 'storeReservation']);
    
    // Riwayat Kunjungan
    Route::get('/pasien/riwayat', [PatientController::class, 'riwayat']); 
    
    // Profil
    Route::get('/pasien/profil', [PatientController::class, 'profil']);
    Route::post('/pasien/profil', [PatientController::class, 'updateProfil']);
    Route::post('/pasien/ubah-password', [PatientController::class, 'updatePassword']);
});


// 4. DASHBOARD DOKTER
Route::middleware(['auth', 'role:dokter'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dokter/dashboard', [DoctorController::class, 'dashboard']);
    
    // Jadwal Saya
    Route::get('/dokter/jadwal', [DoctorController::class, 'jadwal']);
    
    // Daftar Pasien
    Route::get('/dokter/pasien', [DoctorController::class, 'pasien']);
    
    // Riwayat Pemeriksaan
    Route::get('/dokter/riwayat', [DoctorController::class, 'riwayat']);

    // Route untuk Update Status Reservasi (Terima/Tolak)
    Route::patch('/dokter/reservasi/{id}', [DoctorController::class, 'updateStatus']);

     // Route Form Diagnosa
    Route::get('/dokter/periksa/{id}', [DoctorController::class, 'formPeriksa']);
    
    // Route Simpan Diagnosa
    Route::patch('/dokter/periksa/{id}', [DoctorController::class, 'simpanPemeriksaan']);

    // CRUD JADWAL
    Route::get('/dokter/jadwal', [DoctorController::class, 'jadwal']); // Lihat
    Route::post('/dokter/jadwal', [DoctorController::class, 'storeSchedule']); // Tambah
    Route::patch('/dokter/jadwal/{id}', [DoctorController::class, 'updateSchedule']); // Edit
    Route::delete('/dokter/jadwal/{id}', [DoctorController::class, 'deleteSchedule']); // Hapus
});

// 5. DASHBOARD STAFF / ADMIN
Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    
    // Dashboard
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard']);
    
    // Verifikasi Reservasi
    Route::patch('/staff/reservasi/{id}', [StaffController::class, 'verifikasiReservasi']);

    // Kelola Dokter (CRUD)
    Route::get('/staff/dokter', [StaffController::class, 'kelolaDokter']);
    Route::post('/staff/dokter', [StaffController::class, 'storeDokter']); // Tambah
    Route::delete('/staff/dokter/{id}', [StaffController::class, 'destroyDokter']); // Hapus
    
    // Kelola Pasien (Read Only sesuai SRS)
    Route::get('/staff/pasien', [StaffController::class, 'kelolaPasien']);
});
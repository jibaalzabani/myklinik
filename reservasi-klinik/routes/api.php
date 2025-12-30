<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReservationController;

// ===========================================
// 1. PUBLIC ROUTES (Bisa diakses siapa saja)
// ===========================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ===========================================
// 2. PROTECTED ROUTES (Harus Login dulu)
// ===========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['msg' => 'Logout berhasil']);
    });

    // Cek Profil User Sendiri
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // ------------------------------------------
    // KHUSUS DOKTER (Middleware Role)
    // ------------------------------------------
    Route::middleware(['role:dokter'])->group(function () {
        Route::post('/doctors/profile', [DoctorController::class, 'createDoctorProfile']);
        Route::post('/schedules', [ScheduleController::class, 'createSchedule']);
        Route::put('/reservations/{id}/approve', [ReservationController::class, 'approveReservation']);
    });

    // ------------------------------------------
    // KHUSUS PASIEN
    // ------------------------------------------
    Route::middleware(['role:pasien'])->group(function () {
        Route::post('/reservations', [ReservationController::class, 'createReservation']);
    });

    // ------------------------------------------
    // UMUM (Bisa Dokter & Pasien)
    // ------------------------------------------
    Route::get('/doctors', [DoctorController::class, 'getAllDoctors']);
    Route::get('/schedules', [ScheduleController::class, 'getSchedules']);
    Route::get('/reservations', [ReservationController::class, 'getReservations']);

});
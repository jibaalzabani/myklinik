<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    // 1. DASHBOARD STAFF
    public function dashboard()
    {
        // Statistik
        $totalPasien = Patient::count();
        $totalDokter = Doctor::count();
        $totalReservasi = Reservation::count();
        $reservasiPending = Reservation::where('status', 'menunggu')->count();

        // Daftar Reservasi Terbaru (Yang perlu diverifikasi)
        $pendingReservations = Reservation::with(['patient', 'schedule.doctor'])
                                          ->where('status', 'menunggu')
                                          ->orderBy('created_at', 'asc')
                                          ->get();

        return view('staff.dashboard-staff', compact(
            'totalPasien', 'totalDokter', 'totalReservasi', 'reservasiPending', 'pendingReservations'
        ));
    }

    // 2. VERIFIKASI RESERVASI (Terima/Tolak)
    public function verifikasiReservasi(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);

        $reservasi = Reservation::findOrFail($id);
        $reservasi->status = $request->status;
        $reservasi->save();

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    // 3. KELOLA DOKTER (TAMPILKAN LIST)
    public function kelolaDokter()
    {
        $doctors = Doctor::with('user')->get();
        return view('staff.kelola-dokter', compact('doctors'));
    }

    // 4. TAMBAH DOKTER BARU
    public function storeDokter(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'specialization' => 'required|string',
            'phone' => 'required|string'
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Akun User Login
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'dokter'
            ]);

            // 2. Buat Profil Dokter
            Doctor::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'specialization' => $request->specialization,
                'phone' => $request->phone
            ]);
        });

        return back()->with('success', 'Dokter baru berhasil ditambahkan.');
    }

    // 5. HAPUS DOKTER
    public function destroyDokter($id)
    {
        $doctor = Doctor::findOrFail($id);
        // Hapus User-nya juga (Otomatis profile dokter terhapus karena Cascade delete di migration)
        $doctor->user->delete(); 
        
        return back()->with('success', 'Data dokter berhasil dihapus.');
    }

    // 6. KELOLA PASIEN (LIHAT DATA)
    public function kelolaPasien()
    {
        $patients = Patient::with('user')->orderBy('name', 'asc')->get();
        return view('staff.kelola-pasien', compact('patients'));
    }
}
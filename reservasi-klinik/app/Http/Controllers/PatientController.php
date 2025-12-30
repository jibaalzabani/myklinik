<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\Doctor;

class PatientController extends Controller
{
    // 1. DASHBOARD PASIEN
    public function dashboard()
    {
        // Ambil ID Pasien yang sedang login
        $patientId = Auth::user()->patient->id;

        // Hitung Data dari Database
        $menunggu = Reservation::where('patient_id', $patientId)
                                ->where('status', 'menunggu')
                                ->count();

        $selesai = Reservation::where('patient_id', $patientId)
                                ->where('status', 'selesai')
                                ->count();

        // Kirim data ke View (dashboard-pasien)
        return view('pasien.dashboard-pasien', compact('menunggu', 'selesai'));
    }

    // 2. HALAMAN RESERVASI
    public function showReservationForm()
    {
        // Ambil SEMUA data jadwal + relasi dokternya.
        $schedules = Schedule::with('doctor')
                             ->orderBy('date', 'asc')
                             ->get();

        // Debugging: Cek apakah data schedule kosong
        if ($schedules->isEmpty()) {
        }

        return view('pasien.reservasi-kunjungan', compact('schedules'));
    }

    // 3. PROSES SIMPAN RESERVASI
    public function storeReservation(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'symptoms' => 'required|string',
        ]);

        $patient = Auth::user()->patient;

        // Simpan ke database
        Reservation::create([
            'patient_id' => $patient->id,
            'schedule_id' => $request->schedule_id,
            'symptoms' => $request->symptoms,
            'status' => 'menunggu'
        ]);

        return redirect('/pasien/riwayat')->with('success', 'Reservasi berhasil dikirim! Menunggu konfirmasi.');
    }

    // 4. HALAMAN RIWAYAT
    public function riwayat(Request $request)
    {
        // Pastikan user sudah login & punya data pasien
        $patientId = Auth::user()->patient->id;
        
        // Ambil kata kunci pencarian
        $search = $request->input('search');

        $reservations = Reservation::with('schedule.doctor')
                            ->where('patient_id', $patientId)
                            // LOGIKA PENCARIAN
                            ->when($search, function($query, $search) {
                                return $query->where(function($q) use ($search) {
                                    // 1. Cari berdasarkan Nama Dokter
                                    $q->whereHas('schedule.doctor', function($subQ) use ($search) {
                                        $subQ->where('name', 'like', "%{$search}%");
                                    })
                                    // 2. Atau Cari berdasarkan Diagnosis
                                    ->orWhere('diagnosis', 'like', "%{$search}%")
                                    // 3. Atau Cari berdasarkan Keluhan
                                    ->orWhere('symptoms', 'like', "%{$search}%");
                                });
                            })
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('pasien.riwayat-reservasi', compact('reservations'));
    }

    // 5. PROFIL
    public function profil()
    {
        return view('pasien.profil');
    }
}
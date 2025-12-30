<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // 1. MEMBUAT RESERVASI
    public function createReservation(Request $request)
    {
        try {
            $user = Auth::user();
            
            // A. Cek Profil Pasien
            $patient = Patient::where('user_id', $user->id)->first();
            if (!$patient) {
                return response()->json(['msg' => 'Data profil pasien tidak ditemukan.'], 404);
            }

            // B. Cek Jadwal
            $schedule = Schedule::find($request->schedule_id);
            if (!$schedule) {
                return response()->json(['msg' => 'Jadwal tidak ditemukan'], 404);
            }

            // MULAI LOGIKA NOMOR ANTRIAN
            
            // 1. Cari antrian terakhir
            $lastReservation = Reservation::where('schedule_id', $request->schedule_id)
                ->orderBy('queue_number', 'desc')
                ->first();

            // 2. Tentukan nomor baru
            $newQueueNumber = $lastReservation ? ($lastReservation->queue_number + 1) : 1;

            // 3. Cek Kuota
            if ($newQueueNumber > $schedule->quota) {
                return response()->json(['msg' => 'Mohon maaf, kuota penuh.'], 400);
            }

            // C. Simpan ke Database
            $reservation = Reservation::create([
                'reservation_date' => now(), 
                'status' => 'pending',
                'symptoms' => $request->symptoms,
                'patient_id' => $patient->id,
                'schedule_id' => $request->schedule_id,
                'queue_number' => $newQueueNumber
            ]);

            return response()->json([
                'msg' => 'Reservasi Berhasil!',
                'data' => [
                    'nomor_antrian' => $newQueueNumber,
                    'status' => 'pending'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    // 2. MELIHAT DAFTAR RESERVASI
    public function getReservations()
    {
        $user = Auth::user();

        if ($user->role === 'pasien') {
            $patient = Patient::where('user_id', $user->id)->first();
            // Ambil reservasi milik pasien ini + Data Jadwal
            $reservations = Reservation::with('schedule')
                ->where('patient_id', $patient->id)
                ->get();
        } else {
            // Admin/Dokter/Staff: Lihat semua + Data Pasien & Jadwal
            $reservations = Reservation::with(['patient', 'schedule'])->get();
        }

        return response()->json($reservations, 200);
    }

    // 3. UBAH STATUS (Approve / Reject)
    public function approveReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['msg' => 'Reservasi tidak ditemukan'], 404);
        }

        $reservation->update([
            'status' => $request->status
        ]);

        return response()->json(['msg' => "Status berhasil diubah menjadi {$request->status}"], 200);
    }
}
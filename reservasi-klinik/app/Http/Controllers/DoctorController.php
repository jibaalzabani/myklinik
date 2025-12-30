<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Schedule;

class DoctorController extends Controller
{
    // 1. DASHBOARD DOKTER
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;
        
        // Ambil ID semua jadwal milik dokter ini
        $myScheduleIds = $doctor->schedules->pluck('id');

        // Statistik
        $pasienHariIni = Reservation::whereIn('schedule_id', $myScheduleIds)
                            ->whereHas('schedule', fn($q) => $q->where('date', date('Y-m-d')))
                            ->where('status', '!=', 'batal')
                            ->count();
                            
        $menungguKonfirmasi = Reservation::whereIn('schedule_id', $myScheduleIds)
                            ->where('status', 'menunggu')
                            ->count();
                            
        $jadwalAktif = $doctor->schedules()
                            ->where('date', '>=', date('Y-m-d'))
                            ->count();

        // Tabel Jadwal Hari Ini
        $todaysReservations = Reservation::with('patient', 'schedule')
                            ->whereIn('schedule_id', $myScheduleIds)
                            ->whereHas('schedule', fn($q) => $q->where('date', date('Y-m-d')))
                            ->orderBy('created_at', 'asc')
                            ->get();

        return view('dokter.dashboard-dokter', compact(
            'pasienHariIni', 'menungguKonfirmasi', 'jadwalAktif', 'todaysReservations'
        ));
    }

    // 2. KELOLA JADWAL
    public function jadwal()
    {
        $doctor = Auth::user()->doctor;
        // Ambil jadwal urut dari tanggal terbaru
        $schedules = $doctor->schedules()->orderBy('date', 'desc')->get();

        return view('dokter.jadwal-dokter', compact('schedules'));
    }

    // 3. DAFTAR PASIEN
    public function pasien()
    {
        $doctor = Auth::user()->doctor;
        $myScheduleIds = $doctor->schedules->pluck('id');

        // Ambil reservasi yang statusnya 'menunggu' atau 'disetujui'
        $reservations = Reservation::with('patient', 'schedule')
                            ->whereIn('schedule_id', $myScheduleIds)
                            ->whereIn('status', ['menunggu', 'disetujui'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('dokter.reservasi-pasien-untuk-dokter', compact('reservations'));
    }

    // 4. RIWAYAT PEMERIKSAAN
    public function riwayat(Request $request)
    {
        $doctor = Auth::user()->doctor;
        $myScheduleIds = $doctor->schedules->pluck('id');
        
        // Ambil kata kunci pencarian dari input form
        $search = $request->input('search');

        $completedReservations = Reservation::with('patient', 'schedule')
                            ->whereIn('schedule_id', $myScheduleIds)
                            ->where('status', 'selesai')
                            // LOGIKA PENCARIAN
                            ->when($search, function($query, $search) {
                                return $query->where(function($q) use ($search) {
                                    // 1. Cari berdasarkan Nama Pasien
                                    $q->whereHas('patient', function($subQ) use ($search) {
                                        $subQ->where('name', 'like', "%{$search}%");
                                    })
                                    // 2. Atau Cari berdasarkan Diagnosis
                                    ->orWhere('diagnosis', 'like', "%{$search}%")
                                    // 3. Atau Cari berdasarkan Keluhan
                                    ->orWhere('symptoms', 'like', "%{$search}%");
                                });
                            })
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return view('dokter.riwayat-pemeriksaan', compact('completedReservations'));
    }

    //  5. UPDATE STATUS RESERVASI
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->status = $request->status;
        $reservation->save();

        // Kirim pesan notifikasi
        $pesan = $request->status == 'disetujui' ? 'Reservasi diterima.' : 'Reservasi ditolak.';
        return back()->with('success', $pesan);
    }

    // 6. HALAMAN FORM DIAGNOSA
    public function formPeriksa($id)
    {
        // Ambil data reservasi
        $reservation = Reservation::with('patient', 'schedule')->findOrFail($id);

        // Pastikan hanya bisa memeriksa pasien yang statusnya 'disetujui'
        if($reservation->status != 'disetujui') {
            return back()->with('error', 'Pasien belum diverifikasi atau sudah selesai.');
        }

        return view('dokter.form-diagnosa', compact('reservation'));
    }

    // 7. SIMPAN HASIL DIAGNOSA
    public function simpanPemeriksaan(Request $request, $id)
    {
        $request->validate([
            'diagnosis' => 'required',
            'resep_obat' => 'required',
            'admin_note' => 'nullable'
        ]);

        $reservation = Reservation::findOrFail($id);
        
        // Update data
        $reservation->diagnosis = $request->diagnosis;
        $reservation->resep_obat = $request->resep_obat;
        $reservation->admin_note = $request->admin_note;
        $reservation->status = 'selesai'; 
        $reservation->save();

        return redirect('/dokter/dashboard')->with('success', 'Pemeriksaan selesai. Data tersimpan.');
    }

    // A. SIMPAN JADWAL BARU
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $doctor = Auth::user()->doctor;

        // Cek apakah tanggal & jam bentrok dengan jadwal lain
        Schedule::create([
            'doctor_id' => $doctor->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => true
        ]);

        return back()->with('success', 'Jadwal praktek berhasil ditambahkan.');
    }

    // B. UPDATE JADWAL
    public function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $schedule = Schedule::findOrFail($id);
        
        // Pastikan dokter hanya bisa edit jadwal miliknya sendiri
        if($schedule->doctor_id != Auth::user()->doctor->id) {
            return back()->with('error', 'Akses ditolak.');
        }

        $schedule->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => $request->has('is_available') ? true : false
        ]);

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    // C. HAPUS JADWAL
    public function deleteSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        
        // Cek kepemilikan
        if($schedule->doctor_id != Auth::user()->doctor->id) {
            return back()->with('error', 'Akses ditolak.');
        }

        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
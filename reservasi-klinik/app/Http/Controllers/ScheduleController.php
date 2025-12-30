<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Doctor;

class ScheduleController extends Controller
{
    // 1. Buat Jadwal
    public function createSchedule(Request $request)
    {
        try {
            // Cek Dokter
            $doctor = Doctor::find($request->doctor_id);
            if (!$doctor) {
                return response()->json(['msg' => 'Dokter tidak ditemukan'], 404);
            }

            $schedule = Schedule::create([
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'quota' => $request->quota,
                'doctor_id' => $request->doctor_id 
            ]);

            return response()->json(['msg' => 'Jadwal Berhasil Ditambahkan', 'data' => $schedule], 201);

        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    // 2. Lihat Jadwal
    public function getSchedules()
    {
        $schedules = Schedule::with('doctor')->get();
        return response()->json($schedules, 200);
    }
}
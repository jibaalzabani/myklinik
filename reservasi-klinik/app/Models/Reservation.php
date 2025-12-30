<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = [];

    // Relasi ke Pasien (Siapa yang daftar)
    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    // Relasi ke Jadwal (Dokter siapa & kapan)
    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }
}
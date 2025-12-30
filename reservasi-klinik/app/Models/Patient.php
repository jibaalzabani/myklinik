<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    // Satu Pasien bisa punya banyak Reservasi
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
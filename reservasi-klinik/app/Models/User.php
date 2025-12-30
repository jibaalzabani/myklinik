<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $fillable = [
    'name',      
    'email', 
    'password', 
    'role'
    ];

    // Relasi Polimorfik Sederhana
    public function patient() {
        return $this->hasOne(Patient::class);
    }
    public function doctor() {
        return $this->hasOne(Doctor::class);
    }
    
    // Helper untuk cek role
    public function hasRole($role) {
        return $this->role === $role;
    }
}
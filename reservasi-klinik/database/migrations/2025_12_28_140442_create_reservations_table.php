<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        // Relasi ke Pasien & Jadwal
        $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
        $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
        
        // Atribut Reservasi
        $table->text('symptoms');
        $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai', 'batal'])->default('menunggu');
        $table->text('admin_note')->nullable();
        $table->timestamps();
    });
}
};

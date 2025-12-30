<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        // Sesuai SRS Bab II: User Classes (Pasien, Dokter, Admin/Staff)
        $table->enum('role', ['pasien', 'dokter', 'staff', 'admin'])->default('pasien');
        $table->timestamps();
    });
}
};

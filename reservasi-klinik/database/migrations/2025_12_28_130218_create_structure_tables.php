<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Pasien
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id('id_pasien');
            $table->string('nama_lengkap');
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->text('alamat');
            $table->string('password');
            $table->timestamps();
        });

        // 2. Tabel Dokter
        Schema::create('dokters', function (Blueprint $table) {
            $table->id('id_dokter');
            $table->string('nama_dokter');
            $table->string('spesialis');
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // 3. Tabel Staff
        Schema::create('stafs', function (Blueprint $table) {
            $table->id('id_staf');
            $table->string('nama_staf');
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pasiens');
        Schema::dropIfExists('dokters');
        Schema::dropIfExists('stafs');
    }
};
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Data Dokter
        DB::table('dokters')->insert([
            [
                'nama_dokter' => 'Jibaal Zabani',
                'spesialis' => 'Umum',
                'username' => 'jibaal',
                'password' => Hash::make('jibaal123'), // Password dienkripsi otomatis
            ],
            [
                'nama_dokter' => 'Alwan Naufal',
                'spesialis' => 'Gigi',
                'username' => 'alwan',
                'password' => Hash::make('alwan123'),
            ]
        ]);

        // Data Staff
        DB::table('stafs')->insert([
            [
                'nama_staf' => 'Admin Utama',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        ]);
    }
}
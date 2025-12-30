<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('name');
        $table->string('nik')->unique(); 
        $table->string('phone');
        $table->text('address');
        $table->date('birth_date');
        $table->enum('gender', ['L', 'P']);
        $table->timestamps();
    });
}
};

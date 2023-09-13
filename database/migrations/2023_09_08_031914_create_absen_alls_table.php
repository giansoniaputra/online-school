<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absen_alls', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->string('student_unique');
            $table->string('student_kelas');
            $table->string('tahun_ajaran_unique');
            $table->date('tanggal_absen');
            $table->string('kehadiran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_alls');
    }
};

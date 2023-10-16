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
        Schema::create('tagihan_siswas', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->string('unique_student');
            $table->string('unique_kelas');
            $table->string('unique_tahun_ajaran');
            $table->string('unique_jenis_pembayaran');
            $table->string('unique_generate');
            $table->string('periode_tagihan');
            $table->date('tanggal_bayar');
            $table->integer('nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_siswas');
    }
};

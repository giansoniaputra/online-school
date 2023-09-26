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
        Schema::create('generate_tagihans', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->string('unique_tahun_ajaran');
            $table->string('unique_tagihan');
            $table->string('unique_siswa');
            $table->number('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generate_tagihans');
    }
};

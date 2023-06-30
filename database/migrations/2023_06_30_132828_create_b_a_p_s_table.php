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
        Schema::create('b_a_p_s', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->string('matpel_unique')->nullable();
            $table->string('guru_unique')->nullable();
            $table->string('pertemuan');
            $table->date('tanggal_bap');
            $table->string('bap');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b_a_p_s');
    }
};

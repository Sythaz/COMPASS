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
        Schema::create('t_rekomendasi_lomba', function (Blueprint $table) {
            $table->id('rekomendasi_lomba_id');
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->unsignedBigInteger('dosen_id')->index()->nullable();
            $table->unsignedBigInteger('lomba_id')->index();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('t_mahasiswa');
            $table->foreign('dosen_id')->references('dosen_id')->on('t_dosen');
            $table->foreign('lomba_id')->references('lomba_id')->on('t_lomba');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_rekomendasi_lomba');
    }
};

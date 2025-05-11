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
        Schema::create('t_laporan_prestasi', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->unsignedBigInteger('prestasi_id')->index();
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->timestamp('tanggal_laporan');
            $table->timestamps();

            $table->foreign('prestasi_id')->references('prestasi_id')->on('t_prestasi');
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('t_mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_laporan_prestasi');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_prestasi', function (Blueprint $table) {
            $table->id('prestasi_id');
            $table->unsignedBigInteger('lomba_id')->nullable()->index();
            $table->string('lomba_lainnya')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable()->index();
            $table->unsignedBigInteger('tingkat_lomba_id')->nullable()->index();
            $table->unsignedBigInteger('periode_id')->nullable()->index();
            $table->date('tanggal_prestasi')->nullable();
            $table->string('juara_prestasi', 255)->nullable();
            $table->enum('jenis_prestasi', ['Individu', 'Tim'])->nullable()->index();
            $table->string('img_kegiatan')->nullable();
            $table->string('bukti_prestasi')->nullable();
            $table->string('surat_tugas_prestasi')->nullable();
            $table->enum('status_prestasi', ['Aktif', 'Tidak Aktif'])->nullable()->index()->default('Aktif');
            $table->enum('status_verifikasi', ['Ditolak', 'Menunggu', 'Valid', 'Terverifikasi'])->nullable()->index()->default('Menunggu');
            $table->unsignedBigInteger('kategori_id')->nullable()->index();
            $table->timestamps();

            // Relasi
            $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori')->nullOnDelete();
            $table->foreign('lomba_id')->references('lomba_id')->on('t_lomba')->nullOnDelete();
            $table->foreign('dosen_id')->references('dosen_id')->on('t_dosen')->nullOnDelete();
            $table->foreign('periode_id')->references('periode_id')->on('t_periode')->nullOnDelete();
            $table->foreign('tingkat_lomba_id')->references('tingkat_lomba_id')->on('t_tingkat_lomba')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_prestasi');
    }
};

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
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->unsignedBigInteger('lomba_id')->nullable()->index(); // Bisa kosong jika user pilih lainnya
            $table->string('lomba_lainnya')->nullable();  // Kolom baru untuk input nama lomba yang tidak ada di tabel t_lomba
            $table->unsignedBigInteger('dosen_id')->nullable()->index(); // Set Null karena tidak semua punya Dosbing
            $table->unsignedBigInteger('tingkat_lomba_id')->nullable()->index();  // nullable karena untuk lomba_id yang ada bisa diambil dari t_lomba
            $table->unsignedBigInteger('kategori_id')->index();
            $table->unsignedBigInteger('periode_id')->index();
            $table->date('tanggal_prestasi');
            $table->string('juara_prestasi', 255);
            $table->enum('jenis_prestasi', ['Individu', 'Tim'])->default('Individu');
            $table->string('img_kegiatan')->nullable();
            $table->string('bukti_prestasi')->nullable();
            $table->string('surat_tugas_prestasi')->nullable();
            $table->enum('status_prestasi', ['Aktif', 'Tidak Aktif']);
            $table->enum('status_verifikasi', ['Ditolak', 'Menunggu', 'Valid', 'Terverifikasi']);
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('t_mahasiswa');
            $table->foreign('lomba_id')->references('lomba_id')->on('t_lomba');
            $table->foreign('dosen_id')->references('dosen_id')->on('t_dosen');
            $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori');
            $table->foreign('periode_id')->references('periode_id')->on('t_periode');
            $table->foreign('tingkat_lomba_id')->references('tingkat_lomba_id')->on('t_tingkat_lomba');
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

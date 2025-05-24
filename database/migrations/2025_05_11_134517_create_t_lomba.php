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
        Schema::create('t_lomba', function (Blueprint $table) {
            $table->id('lomba_id');
            // $table->unsignedBigInteger('kategori_id')->index();
            $table->string('nama_lomba',255);
            $table->unsignedBigInteger('tingkat_lomba_id')->index();
            $table->longtext('deskripsi_lomba');
            $table->string('penyelenggara_lomba',255);
            $table->date('awal_registrasi_lomba');
            $table->date('akhir_registrasi_lomba');
            $table->string('link_pendaftaran_lomba',255);
            $table->string('img_lomba')->nullable();
            $table->enum('status_verifikasi',['Ditolak','Menunggu','Terverifikasi']);
            $table->enum('status_lomba',['Aktif','Nonaktif']);
            $table->timestamps();

            // $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori');
            $table->foreign('tingkat_lomba_id')->references('tingkat_lomba_id')->on('t_tingkat_lomba');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_lomba');
    }
};

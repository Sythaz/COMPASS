<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('t_prestasi_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestasi_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->enum('peran', ['Ketua', 'Anggota'])->nullable(); // menentukan peran dalam tim
            $table->timestamps();

            // Foreign key yang mengacu pada kolom 'prestasi_id' di tabel 't_prestasi'
            $table->foreign('prestasi_id')->references('prestasi_id')->on('t_prestasi')->onDelete('cascade');
            // Foreign key yang mengacu pada kolom 'mahasiswa_id' di tabel 't_mahasiswa'
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('t_mahasiswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_prestasi_mahasiswa');
    }
};

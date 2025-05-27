<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('t_kategori_mahasiswa', function (Blueprint $table) {
            $table->id('kategori_mahasiswa_id');
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->unsignedBigInteger('kategori_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('t_mahasiswa')->onDelete('cascade');
            $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_kategori_mahasiswa');
    }
};

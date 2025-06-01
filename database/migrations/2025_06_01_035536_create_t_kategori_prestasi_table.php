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
        Schema::create('t_kategori_prestasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestasi_id');
            $table->unsignedBigInteger('kategori_id');
            $table->timestamps();

            $table->foreign('prestasi_id')->references('prestasi_id')->on('t_prestasi')->onDelete('cascade');
            $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kategori_prestasi');
    }
};

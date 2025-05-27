<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('t_kategori_dosen', function (Blueprint $table) {
            $table->id('kategori_dosen_id');
            $table->unsignedBigInteger('dosen_id')->index();
            $table->unsignedBigInteger('kategori_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('dosen_id')->references('dosen_id')->on('t_dosen')->onDelete('cascade');
            $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_kategori_dosen');
    }
};


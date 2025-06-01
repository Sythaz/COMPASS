<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('t_pendaftaran_mahasiswa', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('pendaftaran_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('pendaftaran_id')
                ->references('pendaftaran_id')->on('t_pendaftaran_lomba')
                ->onDelete('cascade');

            $table->foreign('mahasiswa_id')
                ->references('mahasiswa_id')->on('t_mahasiswa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_pendaftaran_mahasiswa');
    }
};

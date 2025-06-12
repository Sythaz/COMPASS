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
        Schema::create('t_preferensi_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('dosen_id');
            $table->enum('kriteria', ['bidang', 'lainnya']);
            $table->string('nama');
            $table->integer('prioritas');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('t_users')->onDelete('cascade');
            $table->foreign('dosen_id')->references('dosen_id')->on('t_dosen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_preferensi_dosen');
    }
};

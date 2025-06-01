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
        Schema::create('t_notifikasi', function (Blueprint $table) {
            $table->id('notifikasi_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('pengirim_id')->index()->nullable();
            $table->enum('pengirim_role', ['Admin', 'Dosen', 'Sistem']);
            $table->enum('jenis_notifikasi', ['Rekomendasi', 'Verifikasi Lomba', 'Verifikasi Prestasi']);
            $table->text('pesan_notifikasi');
            $table->unsignedBigInteger('lomba_id')->index()->nullable();
            $table->unsignedBigInteger('prestasi_id')->index()->nullable();
            $table->enum('status_notifikasi', ['Belum Dibaca', 'Sudah Dibaca'])->default('Belum Dibaca');
            $table->timestamps();

            // Adding foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('t_users')->onDelete('cascade');
            $table->foreign('pengirim_id')->references('user_id')->on('t_users')->onDelete('set null');
            $table->foreign('lomba_id')->references('lomba_id')->on('t_lomba')->onDelete('set null');
            $table->foreign('prestasi_id')->references('prestasi_id')->on('t_prestasi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_notifikasi', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['pengirim_id']);
        });
        Schema::dropIfExists('t_notifikasi');
    }
};

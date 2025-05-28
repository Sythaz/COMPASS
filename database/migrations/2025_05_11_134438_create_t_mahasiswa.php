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
        Schema::create('t_mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('prodi_id')->index();
            $table->unsignedBigInteger('periode_id')->index();
            $table->bigInteger('nim_mahasiswa')->unique();
            $table->string('nama_mahasiswa', 255);
            $table->string('angkatan')->index();
            $table->string('img_mahasiswa')->nullable();
            $table->string('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('kelamin')->nullable();
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('t_users');
            $table->foreign('prodi_id')->references('prodi_id')->on('t_prodi');
            $table->foreign('periode_id')->references('periode_id')->on('t_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mahasiswa');
    }
};

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
        Schema::create('t_minat_bakat', function (Blueprint $table) {
            $table->id('minbak_id');
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->unsignedBigInteger('kategori_id')->index();
            $table->enum('level_minbak',['Beginner','Advanced','Intermediate','Master']);
            $table->timestamps(); 

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('t_mahasiswa');
            $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_minat_bakat');
    }
};

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
        Schema::create('t_kategori_lomba', function (Blueprint $table) {
            $table->id('kategori_lomba_id');
            $table->unsignedBigInteger('lomba_id')->index();
            $table->unsignedBigInteger('kategori_id')->index();
            $table->timestamps();

            $table->foreign('lomba_id')->references('lomba_id')->on('t_lomba')->onDelete('cascade');
            $table->foreign('kategori_id')->references('kategori_id')->on('t_kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kategori_lomba');
    }
};

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
        Schema::create('t_periode', function (Blueprint $table) {
            $table->id('periode_id');
            $table->string('semester_periode',255);
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_periode');
    }
};

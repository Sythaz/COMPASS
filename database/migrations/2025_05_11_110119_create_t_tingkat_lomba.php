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
        Schema::create('t_tingkat_lomba', function (Blueprint $table) {
            $table->id('tingkat_lomba_id');
            $table->string('nama_tingkat',255);
            $table->enum('status_tingkat_lomba',['Aktif','Nonaktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_tingkat_lomba');
    }
};

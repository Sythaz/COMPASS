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
        Schema::create('t_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->bigInteger('username');
            $table->string('password',255);
            $table->string('phrase');
            $table->enum('role',['Admin','Mahasiswa','Dosen']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_users');
    }
};

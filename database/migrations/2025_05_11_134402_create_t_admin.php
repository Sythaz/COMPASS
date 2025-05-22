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
        Schema::create('t_admin', function (Blueprint $table) {
            $table->id('admin_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->bigInteger('nip_admin')->unique();
            $table->string('nama_admin', 255);
            $table->string('img_admin')->nullable();
            $table->string('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('kelamin')->nullable();
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('t_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_admin');
    }
};

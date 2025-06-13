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
        Schema::create('t_kriteria_promethee', function (Blueprint $table) {
            $table->id('kriteria_promethee_id');
            $table->string('kode_kriteria');
            $table->string('nama_kriteria');
            $table->enum('type', ['benefit', 'cost']);
            $table->decimal('bobot', 8, 4);
            $table->string('dynamic_group')->nullable();
            $table->string('preference_function')->default('usual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kriteria_promethee');
    }
};

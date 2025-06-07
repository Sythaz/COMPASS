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
            Schema::create('t_preferensi_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('mahasiswa_id');
                $table->enum('kriteria', ['bidang', 'tingkat', 'penyelenggara', 'deadline', 'lokasi', 'biaya']);
                $table->string('nilai');
                $table->integer('prioritas');
                $table->timestamps();

                $table->foreign('user_id')->references('user_id')->on('t_users')->onDelete('cascade');
                $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('t_mahasiswa')->onDelete('cascade');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('t_preferensi_user');
        }
    };

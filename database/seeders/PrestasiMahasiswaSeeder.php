<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_prestasi_mahasiswa')->insert([
            // ================= PRESTASI INDIVIDU =================
            // Prestasi 1 (Juara 1 - Individu)
            [
                'prestasi_id' => 1,
                'mahasiswa_id' => 1,
                'peran' => 'Ketua',
            ],

            // Prestasi 2 (Juara 2 - Individu)
            [
                'prestasi_id' => 2,
                'mahasiswa_id' => 2,
                'peran' => 'Ketua',
            ],

            // Prestasi 3 (Juara 3 - Individu)
            [
                'prestasi_id' => 3,
                'mahasiswa_id' => 2,
                'peran' => 'Ketua',
            ],

            // ================= PRESTASI TIM =================
            [
                'prestasi_id' => 6,
                'mahasiswa_id' => 1,
                'peran' => 'Ketua',
            ],
            [
                'prestasi_id' => 6,
                'mahasiswa_id' => 101,
                'peran' => 'Anggota',
            ],
            [
                'prestasi_id' => 6,
                'mahasiswa_id' => 102,
                'peran' => 'Anggota',
            ],
            [
                'prestasi_id' => 6,
                'mahasiswa_id' => 103,
                'peran' => 'Anggota',
            ],

            [
                'prestasi_id' => 7,
                'mahasiswa_id' => 3,
                'peran' => 'Ketua',
            ],
            [
                'prestasi_id' => 7,
                'mahasiswa_id' => 65,
                'peran' => 'Anggota',
            ],
            [
                'prestasi_id' => 7,
                'mahasiswa_id' => 45,
                'peran' => 'Anggota',
            ],
            [
                'prestasi_id' => 7,
                'mahasiswa_id' => 6,
                'peran' => 'Anggota',
            ],

            [
                'prestasi_id' => 5,
                'mahasiswa_id' => 1,
                'peran' => 'Ketua',
            ],
            [
                'prestasi_id' => 5,
                'mahasiswa_id' => 2,
                'peran' => 'Anggota',
            ],
            [
                'prestasi_id' => 5,
                'mahasiswa_id' => 105,
                'peran' => 'Anggota',
            ],
            [
                'prestasi_id' => 4,
                'mahasiswa_id' => 1,
                'peran' => 'Ketua',
            ],
            [
                'prestasi_id' => 4,
                'mahasiswa_id' => 110,
                'peran' => 'Anggota',
            ],
            [
                'prestasi_id' => 4,
                'mahasiswa_id' => 57,
                'peran' => 'Anggota',
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RekomPesertaLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'rekom_peserta_id' => 1,
                'mahasiswa_id' => 1,
                'lomba_id' => 1,
                'dosen_id' => 1
            ],
            [
                'rekom_peserta_id' => 2,
                'mahasiswa_id' => 2,
                'lomba_id' => 2,
                'dosen_id' => 2
            ],
            [
                'rekom_peserta_id' => 3,
                'mahasiswa_id' => 3,
                'lomba_id' => 3,
                'dosen_id' => 3
            ],
            [
                'rekom_peserta_id' => 4,
                'mahasiswa_id' => 4,
                'lomba_id' => 2,
                'dosen_id' => 2
            ],
            [
                'rekom_peserta_id' => 5,
                'mahasiswa_id' => 5,
                'lomba_id' => 1,
                'dosen_id' => 1
            ]
        ];
        DB::table('t_rekom_peserta_lomba')->insert($data);
    }
}

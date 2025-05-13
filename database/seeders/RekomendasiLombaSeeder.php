<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RekomendasiLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'rekomendasi_lomba_id' => 1,
                'mahasiswa_id' => 1,
                'lomba_id' => 1
            ],
            [
                'rekomendasi_lomba_id' => 2,
                'mahasiswa_id' => 2,
                'lomba_id' => 2
            ],
            [
                'rekomendasi_lomba_id' => 3,
                'mahasiswa_id' => 3,
                'lomba_id' => 3
            ],
            [
                'rekomendasi_lomba_id' => 4,
                'mahasiswa_id' => 4,
                'lomba_id' => 2
            ],
            [
                'rekomendasi_lomba_id' => 5,
                'mahasiswa_id' => 5,
                'lomba_id' => 1
            ]
        ];
        DB::table('t_rekomendasi_lomba')->insert($data);
    }
}

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
            ]
        ];
        DB::table('t_rekomendasi_lomba')->insert($data);
    }
}

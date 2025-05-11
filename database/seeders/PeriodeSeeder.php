<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'periode_id'       => 1,
                'tahun_periode'    => "2024/2025",
                'semester_periode' => "Ganjil",
            ],
            [
                'periode_id'       => 2,
                'tahun_periode'    => "2024/2025",
                'semester_periode' => "Genap",
            ],
            [
                'periode_id'       => 3,
                'tahun_periode'    => "2025/2026",
                'semester_periode' => "Ganjil",
            ],
        ];
        DB::table('t_periode')->insert($data);
    }
}

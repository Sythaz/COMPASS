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
                'semester_periode' => "2024/2025 Ganjil",
                'tanggal_mulai'    => "2024-08-26",
                'tanggal_akhir'    => "2024-12-20",
            ],
            [
                'periode_id'       => 2,
                'semester_periode' => "2024/2025 Genap",
                'tanggal_mulai'    => "2025-02-10",
                'tanggal_akhir'    => "2025-06-20",
            ],
            [
                'periode_id'       => 3,
                'semester_periode' => "2025/2026 Ganjil",
                'tanggal_mulai'    => "2025-08-26",
                'tanggal_akhir'    => "2025-12-20",
            ],
            [
                'periode_id'       => 4,
                'semester_periode' => "2025/2026 Genap",
                'tanggal_mulai'    => "2026-02-10",
                'tanggal_akhir'    => "2026-06-20",
            ],
        ];
        DB::table('t_periode')->insert($data);
    }
}

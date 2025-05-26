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
                'semester_periode'    => "2024/2025 Ganjil",
            ],
            [
                'periode_id'       => 2,
                'semester_periode'    => "2024/2025 Genap",
            ],
            [
                'periode_id'       => 3,
                'semester_periode'    => "2025/2026 Ganjil",
            ],
            
            [
                'periode_id'       => 4,
                'semester_periode'    => "2025/2026 Genap",
            ],
        ];
        DB::table('t_periode')->insert($data);
    }
}

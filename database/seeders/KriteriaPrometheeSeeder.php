<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaPrometheeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Kesesuaian Bidang Kompetisi',
                'type' => 'benefit',
                'bobot' => 0.25,
                'dynamic_group' => 'bidang',
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Tingkat lomba',
                'type' => 'benefit',
                'bobot' => 0.15,
                'dynamic_group' => null,
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Reputasi penyelenggara',
                'type' => 'benefit',
                'bobot' => 0.10,
                'dynamic_group' => null,
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Deadline pendaftaran',
                'type' => 'benefit',
                'bobot' => 0.10,
                'dynamic_group' => null,
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C5',
                'nama_kriteria' => 'Lokasi',
                'type' => 'cost',
                'bobot' => 0.30,
                'dynamic_group' => 'lokasi',
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C6',
                'nama_kriteria' => 'Biaya',
                'type' => 'benefit',
                'bobot' => 0.10,
                'dynamic_group' => 'biaya',
                'preference_function' => 'usual'
            ]
        ];

        DB::table('t_kriteria_promethee')->insert($criteria);
    }
}


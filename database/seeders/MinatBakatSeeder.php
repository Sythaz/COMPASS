<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MinatBakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'minbak_id' => 1,
                'mahasiswa_id' => 1,
                'kategori_id' => 1,
                'level_minbak' => 'Master',
            ]
        ];
        DB::table('t_minat_bakat')->insert($data);
    }
}

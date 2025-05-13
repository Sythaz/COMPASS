<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LevelMinatBakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'level_minbak_id' => 1,
                'level_minbak' => 'Beginner',
            ],
            [
                'level_minbak_id' => 2,
                'level_minbak' => 'Intermediate',
            ],
            [
                'level_minbak_id' => 3,
                'level_minbak' => 'Advanced',
            ],
            [
                'level_minbak_id' => 4,
                'level_minbak' => 'Master',
            ]
        ];
        DB::table('t_level_minat_bakat')->insert($data);
    }
}

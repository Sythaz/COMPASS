<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TingkatLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tingkat_lomba_id' => 1,
                'nama_tingkat' => 'Universitas',
                'status_tingkat_lomba' => 'Aktif',
            ],
            [
                'tingkat_lomba_id' => 2,
                'nama_tingkat' => 'Kota',
                'status_tingkat_lomba' => 'Aktif',
            ],
            [
                'tingkat_lomba_id' => 3,
                'nama_tingkat' => 'Kabupaten',
                'status_tingkat_lomba' => 'Aktif',
            ],
            [
                'tingkat_lomba_id' => 4,
                'nama_tingkat' => 'Provinsi',
                'status_tingkat_lomba' => 'Aktif',
            ],
            [
                'tingkat_lomba_id' => 5,
                'nama_tingkat' => 'Nasional',
                'status_tingkat_lomba' => 'Aktif',
            ],
            [
                'tingkat_lomba_id' => 6,
                'nama_tingkat' => 'Internasional',
                'status_tingkat_lomba' => 'Aktif',
            ]
        ];
        DB::table('t_tingkat_lomba')->insert($data);
    }
}

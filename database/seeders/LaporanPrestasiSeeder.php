<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LaporanPrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'laporan_id' => 1,
                'prestasi_id' => 1,
                'mahasiswa_id' => 1,
                'tanggal_laporan' => '2025-02-15'
            ],
            [
                'laporan_id' => 2,
                'prestasi_id' => 3,
                'mahasiswa_id' => 2,
                'tanggal_laporan' => '2025-03-03'
            ]
            ];
        DB::table('t_laporan_prestasi')->insert($data);    
    }
}

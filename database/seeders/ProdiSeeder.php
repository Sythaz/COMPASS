<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'prodi_id'      => 1,
                'nama_prodi'    => "D-IV Teknik Informatika",
                'status_prodi'  => 'Aktif'
            ],
            [
                'prodi_id'      => 2,
                'nama_prodi'    => "D-IV Sistem Informasi Bisnis",
                'status_prodi'  => 'Aktif'
            ]
        ];
        DB::table('t_prodi')->insert($data);
    }
}

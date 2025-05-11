<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'mahasiswa_id'  => 1,
                'user_id'       => 2,
                'prodi_id'      => 1,
                'periode_id'    => 2,
                'nim_mahasiswa' => 2341720146,
                'nama_mahasiswa'=> "Keisya Nisrina Aulia",
                'img_mahasiswa' => "Foto Keisya.JPEG"
            ],
        ];
        DB::table('t_mahasiswa')->insert($data);
    }
}

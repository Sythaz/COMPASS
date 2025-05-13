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
                'mahasiswa_id'      => 1,
                'user_id'           => 2,
                'prodi_id'          => 1,
                'periode_id'        => 1,
                'level_minbak_id'   => 3,
                'nim_mahasiswa'     => 2341720146,
                'nama_mahasiswa'    => 'Keisya Nisrina Aulia',
                'img_mahasiswa'     => 'Foto Keisya.JPEG'
            ],
            [
                'mahasiswa_id'      => 2,
                'user_id'           => 5,
                'prodi_id'          => 1,
                'periode_id'        => 1,
                'level_minbak_id'   => 3,
                'nim_mahasiswa'     => 2341720046,
                'nama_mahasiswa'    => 'Adinda Mirza Devani',
                'img_mahasiswa'     => 'Foto adinda.JPEG'
            ],
            [
                'mahasiswa_id'      => 3,
                'user_id'           => 6,
                'prodi_id'          => 1,
                'periode_id'        => 1,
                'level_minbak_id'   => 4,
                'nim_mahasiswa'     => 2341720102,
                'nama_mahasiswa'    => 'Muhammad Syafiq Aldiansyah',
                'img_mahasiswa'     => 'Foto Syafiq.JPEG'
            ],
            [
                'mahasiswa_id'      => 4,
                'user_id'           => 7,
                'prodi_id'          => 1,
                'periode_id'        => 1,
                'level_minbak_id'   => 3,
                'nim_mahasiswa'     => 2341720219,
                'nama_mahasiswa'    => 'Satrio Wisnu Adi Pratama',
                'img_mahasiswa'     => 'Foto Satrio.JPEG'
            ],
            [
                'mahasiswa_id'      => 5,
                'user_id'           => 8,
                'prodi_id'          => 1,
                'periode_id'        => 1,
                'level_minbak_id'   => 3,
                'nim_mahasiswa'     => 2341720107,
                'nama_mahasiswa'    => 'Adnan Arju Maulana Pasha',
                'img_mahasiswa'     => 'Foto Pasha.JPEG'
            ]
        ];
        DB::table('t_mahasiswa')->insert($data);
    }
}

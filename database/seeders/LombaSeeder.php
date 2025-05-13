<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'lomba_id'               => 1,
                'kategori_id'            => 5, 
                'nama_lomba'             => 'Lomba Web Development Nasional',
                'tingkat_kompetisi_lomba'=> 'Nasional',
                'deskripsi_lomba'        => 'Kompetisi pengembangan website tingkat nasional untuk mahasiswa.',
                'penyelenggara_lomba'    => 'Kemenkominfo',
                'awal_registrasi_lomba'  => Carbon::parse('2025-06-01'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-06-30'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',
                'img_lomba'              => 'webdev2025.png'
            ],
            [
                'lomba_id'               => 2,
                'kategori_id'            => 15, 
                'nama_lomba'             => 'IO CHALLENGE',
                'tingkat_kompetisi_lomba'=> 'Nasional',
                'deskripsi_lomba'        => 'Smart Solution IoT Competition',
                'penyelenggara_lomba'    => 'Universitas Negeri Malang',
                'awal_registrasi_lomba'  => Carbon::parse('2025-02-21'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-06-30'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',
                'img_lomba'              => 'IoTChallenge2025.png'
            ],
            [
                'lomba_id'               => 3,
                'kategori_id'            => 11, 
                'nama_lomba'             => 'WS ASEAN 2025',
                'tingkat_kompetisi_lomba'=> 'Nasional',
                'deskripsi_lomba'        => 'WS ASEAN 2025 di bidang Cyber Security untuk Pelajar dan Mahasiswa.',
                'penyelenggara_lomba'    => 'Indonesia Cyber Skills (ICS)',
                'awal_registrasi_lomba'  => Carbon::parse('2025-02-21'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-06-30'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',
                'img_lomba'              => 'WSASEAN2025.png',
            ]
        ];
        DB::table('t_lomba')->insert($data);
    }
}

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
                'lomba_id' => 1,
                'pengusul_id' => 1,
                'nama_lomba' => 'Lomba Web Development Nasional',
                'tingkat_lomba_id' => 5,
                'deskripsi_lomba' => 'Kompetisi pengembangan website tingkat nasional untuk mahasiswa.',
                'penyelenggara_lomba' => 'Kemenkominfo',
                'awal_registrasi_lomba' => Carbon::parse('2025-06-01'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-06-30'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',
                'img_lomba' => 'webdev2025.png',
                'status_verifikasi' => 'Terverifikasi',
                'status_lomba' => 'Aktif'
            ],
            [
                'lomba_id' => 2,
                'pengusul_id' => 1,
                'nama_lomba' => 'IOT CHALLENGE',
                'tingkat_lomba_id' => 5,
                'deskripsi_lomba' => 'Smart Solution IoT Competition',
                'penyelenggara_lomba' => 'Politeknik Elektronika Negeri Surabaya',
                'awal_registrasi_lomba' => Carbon::parse('2025-02-21'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-06-30'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',
                'img_lomba' => 'IoTChallenge2025.png',
                'status_verifikasi' => 'Menunggu',
                'status_lomba' => 'Aktif'
            ],
            [
                'lomba_id' => 3,
                'pengusul_id' => 22,
                'nama_lomba' => 'WS ASEAN 2025',
                'tingkat_lomba_id' => 5,
                'deskripsi_lomba' => 'WS ASEAN 2025 di bidang Cyber Security untuk Pelajar dan Mahasiswa.',
                'penyelenggara_lomba' => 'Indonesia Cyber Skills (ICS)',
                'awal_registrasi_lomba' => Carbon::parse('2025-02-21'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-06-30'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',
                'img_lomba' => 'WSASEAN2025.png',
                'status_verifikasi' => 'Terverifikasi',
                'status_lomba' => 'Aktif'
            ],
            [
                'lomba_id' => 4,
                'pengusul_id' => 22,
                'nama_lomba' => 'Lomba Mobile App Development Jawa Timur #21',
                'tingkat_lomba_id' => 4,
                'deskripsi_lomba' => 'Kompetisi pengembangan aplikasi mobile tingkat provinsi untuk mahasiswa seluruh Jawa Timur 2025.',
                'penyelenggara_lomba' => 'Kemenkominfo Jawa Timur',
                'awal_registrasi_lomba' => Carbon::parse('2025-02-21'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-06-30'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',

                'img_lomba' => 'MobileApp2025.png',
                'status_verifikasi' => 'Ditolak',
                'status_lomba' => 'Aktif'
            ],
            [
                'lomba_id' => 5,
                'pengusul_id' => 6,
                'nama_lomba' => 'Lomba Game Development Jawa Timur #21',
                'tingkat_lomba_id' => 4,
                'deskripsi_lomba' => 'Kompetisi pengembangan game tingkat provinsi untuk mahasiswa seluruh Jawa Timur 2025.',
                'penyelenggara_lomba' => 'Politeknik Negeri Malang',
                'awal_registrasi_lomba' => Carbon::parse('2025-03-01'),
                'akhir_registrasi_lomba' => Carbon::parse('2025-07-15'),
                'link_pendaftaran_lomba' => 'https://contoh-lomba.com/daftar',
                'img_lomba' => 'GameDev2025.png',
                'status_verifikasi' => 'Ditolak',
                'status_lomba' => 'Aktif'
            ]
        ];
        DB::table('t_lomba')->insert($data);
    }
}

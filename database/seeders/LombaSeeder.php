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
            'img_lomba'              => 'webdev2025.png',
            ],
        ];
        DB::table('t_lomba')->insert($data);
    }
}

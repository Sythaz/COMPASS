<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendaftaranLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $data = [
            [
                'pendaftaran_id' => 1,
                'mahasiswa_id' => 192,
                'lomba_id' => 2,
                'status_pendaftaran' => 'Menunggu',
                'alasan_tolak' => null,
                'bukti_pendaftaran' => 'BP_Keisya.jpeg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'pendaftaran_id' => 2,
                'mahasiswa_id' => 179,
                'lomba_id' => 2,
                'status_pendaftaran' => 'Terverifikasi',
                'alasan_tolak' => null,
                'bukti_pendaftaran' => 'BP_Adinda.jpeg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'pendaftaran_id' => 3,
                'mahasiswa_id' => 197,
                'lomba_id' => 2,
                'status_pendaftaran' => 'Ditolak',
                'alasan_tolak' => 'Bukti pendaftaran tidak sesuai',
                'bukti_pendaftaran' => 'BP_Syafiq.jpeg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'pendaftaran_id' => 4,
                'mahasiswa_id' => 202,
                'lomba_id' => 2,
                'status_pendaftaran' => 'Ditolak',
                'alasan_tolak' => 'Bukti pendaftaran tidak sesuai',
                'bukti_pendaftaran' => 'BP_Satrio.jpeg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'pendaftaran_id' => 5,
                'mahasiswa_id' => 180,
                'lomba_id' => 2,
                'status_pendaftaran' => 'Ditolak',
                'alasan_tolak' => 'Bukti pendaftaran tidak sesuai',
                'bukti_pendaftaran' => 'BP_Pasha.jpeg',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        DB::table('t_pendaftaran_lomba')->insert($data);
    }
}

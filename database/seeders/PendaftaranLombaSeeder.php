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
        $data = [
            [
                'pendaftaran_id' => 1,
                'mahasiswa_id' => 192,
                'lomba_id' => 1,
                'bukti_pendaftaran' => 'BP_Keisya.jpeg'
            ],
            [
                'pendaftaran_id' => 2,
                'mahasiswa_id' => 179,
                'lomba_id' => 2,
                'bukti_pendaftaran' => 'BP_Adinda.jpeg'
            ],
            [
                'pendaftaran_id' => 3,
                'mahasiswa_id' => 197,
                'lomba_id' => 3,
                'bukti_pendaftaran' => 'BP_Syafiq.jpeg'
            ],
            [
                'pendaftaran_id' => 4,
                'mahasiswa_id' => 202,
                'lomba_id' => 2,
                'bukti_pendaftaran' => 'BP_Satrio.jpeg'
            ],
            [
                'pendaftaran_id' => 5,
                'mahasiswa_id' => 180,
                'lomba_id' => 1,
                'bukti_pendaftaran' => 'BP_Pasha.jpeg'
            ]
        ];
        DB::table('t_pendaftaran_lomba')->insert($data);
    }
}

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
                'mahasiswa_id' => 1,
                'lomba_id' => 1
            ]
        ];
        DB::table('t_pendaftaran_lomba')->insert($data);
    }
}

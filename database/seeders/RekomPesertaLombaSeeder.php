<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RekomPesertaLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'rekom_peserta_id' => 1,
                'mahasiswa_id' => 1,
                'lomba_id' => 1,
                'dosen_id' => 1
            ]
        ];
        DB::table('t_rekom_peserta_lomba')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'prestasi_id' => 1,
                'mahasiswa_id' => 1,
                'lomba_id' => 1,
                'dosen_id' => 1,
                'kategori_id' => 1,
                'tanggal_prestasi' => '2025-02-15',
                'juara_prestasi' => 'Juara 1',
                'jenis_prestasi' => 'Individu',
                'img_kegiatan' => 'Kegiatan Keisya.jpg',
                'bukti_prestasi' => 'Sertifikat Keisya.pdf',
                'surat_tugas_prestasi' => 'ST Keisya.pdf'
            ]
        ];
        DB::table('t_prestasi')->insert($data);
    }
}

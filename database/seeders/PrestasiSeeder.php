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
                'kategori_id' => 1,
                'lomba_id' => 14,
                'tingkat_lomba_id' => 4,
                'dosen_id' => 1,
                'periode_id' => 20,
                'tanggal_prestasi' => '2025-02-15',
                'juara_prestasi' => 'Juara 1',
                'jenis_prestasi' => 'Individu',
                'img_kegiatan' => 'img-kegiatan.jpg',
                'bukti_prestasi' => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi' => 'surat-tugas-prestasi.pdf',
                'status_prestasi' => 'Aktif',
                'status_verifikasi' => 'Terverifikasi'
            ],
            [
                'prestasi_id' => 2,
                'kategori_id' => 2,
                'lomba_id' => 14,
                'tingkat_lomba_id' => 4,
                'dosen_id' => 1,
                'periode_id' => 20,
                'tanggal_prestasi' => '2025-02-15',
                'juara_prestasi' => 'Juara 2',
                'jenis_prestasi' => 'Individu',
                'img_kegiatan' => 'img-kegiatan.jpg',
                'bukti_prestasi' => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi' => 'surat-tugas-prestasi.pdf',
                'status_prestasi' => 'Aktif',
                'status_verifikasi' => 'Terverifikasi'
            ],
            [
                'prestasi_id' => 3,
                'kategori_id' => 1,
                'lomba_id' => 15,
                'tingkat_lomba_id' => 5,
                'dosen_id' => 3,
                'periode_id' => 20,
                'tanggal_prestasi' => '2025-03-03',
                'juara_prestasi' => 'Juara 3',
                'jenis_prestasi' => 'Individu',
                'img_kegiatan' => 'img-kegiatan.jpg',
                'bukti_prestasi' => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi' => 'surat-tugas-prestasi.pdf',
                'status_prestasi' => 'Aktif',
                'status_verifikasi' => 'Terverifikasi'
            ],
            [
                'prestasi_id' => 4,
                'kategori_id' => 2,
                'lomba_id' => 16,
                'tingkat_lomba_id' => 2,
                'dosen_id' => 1,
                'periode_id' => 19,
                'tanggal_prestasi' => '2024-12-25',
                'juara_prestasi' => 'Juara 4',
                'jenis_prestasi' => 'Tim',
                'img_kegiatan' => 'img-kegiatan.jpg',
                'bukti_prestasi' => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi' => 'surat-tugas-prestasi.pdf',
                'status_prestasi' => 'Aktif',
                'status_verifikasi' => 'Menunggu'
            ],
            [
                'prestasi_id' => 5,
                'kategori_id' => 2,
                'lomba_id' => 17,
                'tingkat_lomba_id' => 5,
                'dosen_id' => 2,
                'periode_id' => 20,
                'tanggal_prestasi' => '2025-03-03',
                'juara_prestasi' => 'Juara 2',
                'jenis_prestasi' => 'Tim',
                'img_kegiatan' => 'img-kegiatan.jpg',
                'bukti_prestasi' => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi' => 'surat-tugas-prestasi.pdf',
                'status_prestasi' => 'Aktif',
                'status_verifikasi' => 'Menunggu'
            ],
            [
                'prestasi_id' => 6,
                'kategori_id' => 2,
                'lomba_id' => 16,
                'tingkat_lomba_id' => 2,
                'dosen_id' => 1,
                'periode_id' => 19,
                'tanggal_prestasi' => '2024-12-25',
                'juara_prestasi' => 'Juara 2',
                'jenis_prestasi' => 'Tim',
                'img_kegiatan' => 'img-kegiatan.jpg',
                'bukti_prestasi' => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi' => 'surat-tugas-prestasi.pdf',
                'status_prestasi' => 'Aktif',
                'status_verifikasi' => 'Terverifikasi'
            ],
            [
                'prestasi_id' => 7,
                'kategori_id' => 2,
                'lomba_id' => 17,
                'tingkat_lomba_id' => 5,
                'dosen_id' => 1,
                'periode_id' => 20,
                'tanggal_prestasi' => '2025-06-12',
                'juara_prestasi' => 'Juara 3',
                'jenis_prestasi' => 'Tim',
                'img_kegiatan' => 'img-kegiatan.jpg',
                'bukti_prestasi' => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi' => 'surat-tugas-prestasi.pdf',
                'status_prestasi' => 'Aktif',
                'status_verifikasi' => 'Terverifikasi'
            ]
        ];

        DB::table('t_prestasi')->insert($data);
    }
}

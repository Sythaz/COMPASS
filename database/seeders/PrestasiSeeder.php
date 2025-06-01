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
                'prestasi_id'           => 1,
                'mahasiswa_id'          => 1,
                'lomba_id'              => 1,
                'dosen_id'              => 1,
                'kategori_id'           => 3,
                'periode_id'            => 1,
                'tanggal_prestasi'      => '2025-02-15',
                'juara_prestasi'        => 'Juara 1',
                'jenis_prestasi'        => 'Individu',
                'img_kegiatan'          => 'img-kegiatan.jpg',
                'bukti_prestasi'        => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi'  => 'surat-tugas-prestasi.pdf',
                'status_prestasi'       => 'Aktif',
                'status_verifikasi'     => 'Terverifikasi'
            ],
            [
                'prestasi_id'           => 2,
                'mahasiswa_id'          => 1,
                'lomba_id'              => 1,
                'dosen_id'              => 2,
                'kategori_id'           => 5,
                'periode_id'            => 1,
                'tanggal_prestasi'      => '2025-02-15',
                'juara_prestasi'        => 'Juara 2',
                'jenis_prestasi'        => 'Individu',
                'img_kegiatan'          => 'img-kegiatan.jpg',
                'bukti_prestasi'        => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi'  => 'surat-tugas-prestasi.pdf',
                'status_prestasi'       => 'Aktif',
                'status_verifikasi'     => 'Terverifikasi'
            ],
            [
                'prestasi_id'           => 3,
                'mahasiswa_id'          => 2,
                'lomba_id'              => 2,
                'dosen_id'              => 3,
                'kategori_id'           => 12, // Diubah dari 15 ke 12 (sesuai range 1-14)
                'periode_id'            => 2,
                'tanggal_prestasi'      => '2025-03-03',
                'juara_prestasi'        => 'Juara 3',
                'jenis_prestasi'        => 'Individu',
                'img_kegiatan'          => 'img-kegiatan.jpg',
                'bukti_prestasi'        => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi'  => 'surat-tugas-prestasi.pdf',
                'status_prestasi'       => 'Aktif',
                'status_verifikasi'     => 'Terverifikasi'
            ],
            [
                'prestasi_id'           => 4,
                'mahasiswa_id'          => 3,
                'lomba_id'              => 3,
                'dosen_id'              => 1,
                'kategori_id'           => 6,
                'periode_id'            => 2,
                'tanggal_prestasi'      => '2025-02-15',
                'juara_prestasi'        => 'Juara 4',
                'jenis_prestasi'        => 'Individu',
                'img_kegiatan'          => 'img-kegiatan.jpg',
                'bukti_prestasi'        => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi'  => 'surat-tugas-prestasi.pdf',
                'status_prestasi'       => 'Aktif',
                'status_verifikasi'     => 'Menunggu'
            ],
            [
                'prestasi_id'           => 5,
                'mahasiswa_id'          => 4,
                'lomba_id'              => 1,
                'dosen_id'              => 2,
                'kategori_id'           => 5,
                'periode_id'            => 2,
                'tanggal_prestasi'      => '2025-03-03',
                'juara_prestasi'        => 'Juara 2',
                'jenis_prestasi'        => 'Individu',
                'img_kegiatan'          => 'img-kegiatan.jpg',
                'bukti_prestasi'        => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi'  => 'surat-tugas-prestasi.pdf',
                'status_prestasi'       => 'Aktif',
                'status_verifikasi'     => 'Menunggu'
            ],
            [
                'prestasi_id'           => 6,
                'mahasiswa_id'          => 5,
                'lomba_id'              => 3,
                'dosen_id'              => 3,
                'kategori_id'           => 5,
                'periode_id'            => 1,
                'tanggal_prestasi'      => '2025-02-15',
                'juara_prestasi'        => 'Juara 5',
                'jenis_prestasi'        => 'Tim',
                'img_kegiatan'          => 'img-kegiatan.jpg',
                'bukti_prestasi'        => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi'  => 'surat-tugas-prestasi.pdf',
                'status_prestasi'       => 'Aktif',
                'status_verifikasi'     => 'Ditolak'
            ],
            [
                'prestasi_id'           => 7,
                'mahasiswa_id'          => 6,
                'lomba_id'              => 3,
                'dosen_id'              => 3,
                'kategori_id'           => 5,
                'periode_id'            => 1,
                'tanggal_prestasi'      => '2025-02-15',
                'juara_prestasi'        => 'Juara 5',
                'jenis_prestasi'        => 'Tim',
                'img_kegiatan'          => 'img-kegiatan.jpg',
                'bukti_prestasi'        => 'bukti-sertifikat.pdf',
                'surat_tugas_prestasi'  => 'surat-tugas-prestasi.pdf',
                'status_prestasi'       => 'Aktif',
                'status_verifikasi'     => 'Valid'
            ]
        ];

        DB::table('t_prestasi')->insert($data);
    }
}

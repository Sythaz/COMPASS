<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_notifikasi')->insert([
            [
                'notifikasi_id' => 1,
                'user_id' => 88,
                'pengirim_id' => 1,
                'pengirim_role' => 'Admin',
                'jenis_notifikasi' => 'Rekomendasi',
                'pesan_notifikasi' => sprintf(
                    "Anda direkomendasikan oleh Admin '%s' untuk mengikuti lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
                    'Sri Whariyanti, S.Pd',
                    'Lomba 1'
                ),
                'lomba_id' => 1,
                'prestasi_id' => null,
                'status_notifikasi' => 'Belum Dibaca',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'notifikasi_id' => 2,
                'user_id' => 88,
                'pengirim_id' => 1,
                'pengirim_role' => 'Admin',
                'jenis_notifikasi' => 'Rekomendasi',
                'pesan_notifikasi' => sprintf(
                    "Anda direkomendasikan oleh Admin '%s' untuk mengikuti lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
                    'Sri Whariyanti, S.Pd',
                    'Lomba 2'
                ),
                'lomba_id' => 2,
                'prestasi_id' => null,
                'status_notifikasi' => 'Belum Dibaca',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'notifikasi_id' => 3,
                'user_id' => 88,
                'pengirim_id' => 6,
                'pengirim_role' => 'Dosen',
                'jenis_notifikasi' => 'Rekomendasi',
                'pesan_notifikasi' => sprintf(
                    "Anda direkomendasikan oleh Dosen '%s' untuk mengikuti lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
                    'Prof. Dr. Eng. Rosa Andrie Asmara, S.T., M.T.',
                    'Lomba 3'
                ),
                'lomba_id' => 2,
                'prestasi_id' => null,
                'status_notifikasi' => 'Belum Dibaca',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'notifikasi_id' => 4,
                'user_id' => 88,
                'pengirim_id' => null,
                'pengirim_role' => 'Sistem',
                'jenis_notifikasi' => 'Rekomendasi',
                'pesan_notifikasi' => sprintf(
                    "Anda direkomendasikan oleh Sistem untuk mengikuti lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
                    'Lomba 4'
                ),
                'lomba_id' => 1,
                'prestasi_id' => null,
                'status_notifikasi' => 'Sudah Dibaca',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'notifikasi_id' => 5,
                'user_id' => 88,
                'pengirim_id' => 1,
                'pengirim_role' => 'Admin',
                'jenis_notifikasi' => 'Verifikasi Lomba',
                'pesan_notifikasi' => sprintf(
                    "Verifikasi pengajuan lomba '%s' telah selesai oleh Admin. Silakan periksa informasi lomba lebih lanjut.",
                    'Lomba 2'
                ),
                'lomba_id' => 2,
                'prestasi_id' => null,
                'status_notifikasi' => 'Sudah Dibaca',
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            [
                'notifikasi_id' => 6,
                'user_id' => 88,
                'pengirim_id' => 1,
                'pengirim_role' => 'Admin',
                'jenis_notifikasi' => 'Verifikasi Prestasi',
                'pesan_notifikasi' => sprintf(
                    "Verifikasi pengajuan prestasi '%s' telah selesai oleh Admin. Terimakasih sudah mengajukan prestasi ini.",
                    'Prestasi 1'
                ),
                'lomba_id' => null,
                'prestasi_id' => 1,
                'status_notifikasi' => 'Belum Dibaca',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'notifikasi_id' => 7,
                'user_id' => 88,
                'pengirim_id' => 6,
                'pengirim_role' => 'Dosen',
                'jenis_notifikasi' => 'Verifikasi Prestasi',
                'pesan_notifikasi' => sprintf(
                    "Verifikasi pengajuan prestasi '%s' telah selesai oleh Dosen. Menunggu verifikasi oleh Admin.",
                    'Prestasi 1'
                ),
                'lomba_id' => null,
                'prestasi_id' => 1,
                'status_notifikasi' => 'Belum Dibaca',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
        ]);
    }
}

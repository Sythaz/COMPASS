<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreferensiUserModel;
use Illuminate\Support\Facades\DB;

class PreferensiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada terlebih dahulu
        DB::table('t_preferensi_user')->truncate();

        // Contoh preferensi untuk user_id = 1
        $userId = 1;

        // Preferensi bidang
        $this->createPreferensi($userId, 'bidang', 'Data Science', 1);
        $this->createPreferensi($userId, 'bidang', 'UI/UX', 2);
        $this->createPreferensi($userId, 'bidang', 'Software Development', 3);

        // Preferensi tingkat
        $this->createPreferensi($userId, 'tingkat', 'Nasional', 1);
        $this->createPreferensi($userId, 'tingkat', 'Internasional', 2);
        $this->createPreferensi($userId, 'tingkat', 'Regional', 3);

        // Preferensi penyelenggara
        $this->createPreferensi($userId, 'penyelenggara', 'Institusi', 1);
        $this->createPreferensi($userId, 'penyelenggara', 'Kampus', 2);
        $this->createPreferensi($userId, 'penyelenggara', 'Komunitas', 3);

        // Preferensi deadline
        $this->createPreferensi($userId, 'deadline', '≥30 hari', 1);
        $this->createPreferensi($userId, 'deadline', '29 - 23 hari', 2);
        $this->createPreferensi($userId, 'deadline', '22 - 16 hari', 3);

        // Preferensi lokasi
        $this->createPreferensi($userId, 'lokasi', 'Online', 1);
        $this->createPreferensi($userId, 'lokasi', 'Hybrid', 2);
        $this->createPreferensi($userId, 'lokasi', 'Offline dalam kota', 3);

        // Preferensi biaya
        $this->createPreferensi($userId, 'biaya', 'Tanpa biaya', 1);
        $this->createPreferensi($userId, 'biaya', 'Dengan Biaya', 2);

        // Contoh preferensi untuk user_id = 2 (dengan prioritas berbeda)
        $userId = 2;

        // Preferensi bidang
        $this->createPreferensi($userId, 'bidang', 'UI/UX', 1);
        $this->createPreferensi($userId, 'bidang', 'Game Dev', 2);
        $this->createPreferensi($userId, 'bidang', 'Software Development', 3);

        // Preferensi tingkat
        $this->createPreferensi($userId, 'tingkat', 'Internasional', 1);
        $this->createPreferensi($userId, 'tingkat', 'Nasional', 2);
        $this->createPreferensi($userId, 'tingkat', 'Regional', 3);

        // Preferensi penyelenggara
        $this->createPreferensi($userId, 'penyelenggara', 'Kampus', 1);
        $this->createPreferensi($userId, 'penyelenggara', 'Institusi', 2);
        $this->createPreferensi($userId, 'penyelenggara', 'Komunitas', 3);

        // Preferensi deadline
        $this->createPreferensi($userId, 'deadline', '29 - 23 hari', 1);
        $this->createPreferensi($userId, 'deadline', '≥30 hari', 2);
        $this->createPreferensi($userId, 'deadline', '22 - 16 hari', 3);

        // Preferensi lokasi
        $this->createPreferensi($userId, 'lokasi', 'Offline dalam kota', 1);
        $this->createPreferensi($userId, 'lokasi', 'Online', 2);
        $this->createPreferensi($userId, 'lokasi', 'Hybrid', 3);

        // Preferensi biaya
        $this->createPreferensi($userId, 'biaya', 'Tanpa biaya', 1);
        $this->createPreferensi($userId, 'biaya', 'Dengan Biaya', 2);
    }

    /**
     * Membuat preferensi user
     */
    private function createPreferensi($userId, $kriteria, $nilai, $prioritas)
    {
        PreferensiUserModel::create([
            'user_id' => $userId,
            'kriteria' => $kriteria,
            'nilai' => $nilai,
            'prioritas' => $prioritas
        ]);
    }
}

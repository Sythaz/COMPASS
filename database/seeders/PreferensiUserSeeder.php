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
        $mahasiswaId = 1;
        $userId = 88;

        // Preferensi bidang (Maksimal 5 preferensi bidang)
        $this->createPreferensi($userId, $mahasiswaId, 'bidang', 'Web Development', 1);
        $this->createPreferensi($userId, $mahasiswaId, 'bidang', 'Mobile App Development', 2);
        $this->createPreferensi($userId, $mahasiswaId, 'bidang', 'Cybersecurity', 3);
        $this->createPreferensi($userId, $mahasiswaId, 'bidang', 'Data Science', 4);
        $this->createPreferensi($userId, $mahasiswaId, 'bidang', 'Internet of Things', 5);

        // Preferensi tingkat
        $this->createPreferensi($userId, $mahasiswaId, 'tingkat', 'Internasional', 1);
        $this->createPreferensi($userId, $mahasiswaId, 'tingkat', 'Nasional', 2);
        $this->createPreferensi($userId, $mahasiswaId, 'tingkat', 'Provinsi', 3);
        $this->createPreferensi($userId, $mahasiswaId, 'tingkat', 'Kabupaten', 4);
        $this->createPreferensi($userId, $mahasiswaId, 'tingkat', 'Kota', 5);
        $this->createPreferensi($userId, $mahasiswaId, 'tingkat', 'Universitas', 6);

        // Preferensi penyelenggara
        $this->createPreferensi($userId, $mahasiswaId, 'penyelenggara', 'Institusi', 1);
        $this->createPreferensi($userId, $mahasiswaId, 'penyelenggara', 'Kampus', 2);
        $this->createPreferensi($userId, $mahasiswaId, 'penyelenggara', 'Komunitas', 3);

        // Preferensi deadline (Tidak usah karena deadline adalah preferensi tetap)
        // $this->createPreferensi($userId, $mahasiswaId, 'deadline', '≥30 hari', 1);
        // $this->createPreferensi($userId, $mahasiswaId, 'deadline', '29-23 hari', 2);
        // $this->createPreferensi($userId, $mahasiswaId, 'deadline', '22-16 hari', 3);
        // $this->createPreferensi($userId, $mahasiswaId, 'deadline', '15-9 hari', 4);
        // $this->createPreferensi($userId, $mahasiswaId, 'deadline', '≤8 hari', 5);

        // Preferensi lokasi
        $this->createPreferensi($userId, $mahasiswaId, 'lokasi', 'Offline Dalam Kota', 1);
        $this->createPreferensi($userId, $mahasiswaId, 'lokasi', 'Online', 2);
        $this->createPreferensi($userId, $mahasiswaId, 'lokasi', 'Hybrid', 3);
        $this->createPreferensi($userId, $mahasiswaId, 'lokasi', 'Offline Luar Kota', 4);

        // Preferensi biaya
        $this->createPreferensi($userId, $mahasiswaId, 'biaya', 'Tanpa Biaya', 1);
        $this->createPreferensi($userId, $mahasiswaId, 'biaya', 'Dengan Biaya', 2);
    }

    /**
     * Membuat preferensi user
     */
    private function createPreferensi($userId, $mahasiswaId, $kriteria, $nilai, $prioritas)
    {
        PreferensiUserModel::create([
            'user_id' => $userId,
            'mahasiswa_id' => $mahasiswaId,
            'kriteria' => $kriteria,
            'nilai' => $nilai,
            'prioritas' => $prioritas
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PreferensiDosenModel;
use Illuminate\Support\Facades\DB;

class PreferensiDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // hapus data yang ada terlebih dahulu
        DB::table('t_preferensi_dosen')->truncate();

        // contoh preferensi untuk user_id = 1 
        $dosenId = 2;
        $userId  = 7;

        // preferensi bidang (Maksimal 5 preferensi)
        $this->createPreferensi($userId, $dosenId, 'Bidang', 'Algoritma & Logika', 1);
        $this->createPreferensi($userId, $dosenId, 'Bidang', 'Web Development', 2);
        $this->createPreferensi($userId, $dosenId, 'Bidang', 'Mobile Development', 3);
        $this->createPreferensi($userId, $dosenId, 'Bidang', 'Software Development', 4);
        $this->createPreferensi($userId, $dosenId, 'Bidang', 'Business ', 5); 
        

        $this->createPreferensi($userId, $dosenId, 'lainnya', 'lainnya', 1);
    }

    /**
     * membuat preferensi user 
     */

    private function createPreferensi($userId, $dosenId, $kriteria, $namaKategori, $prioritas)
    {
        PreferensiDosenModel::create([
            'user_id'       => $userId,
            'dosen_id'      => $dosenId,
            'kriteria'      => $kriteria,
            'nama'          => $namaKategori,
            'prioritas'     => $prioritas
        ]);
    }
}

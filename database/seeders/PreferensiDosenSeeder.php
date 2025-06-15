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

        // preferensi bidang (Maksimal 5 preferensi)
        $this->createPreferensi($dosenId, 7, 1);
        $this->createPreferensi($dosenId, 1, 2);
        $this->createPreferensi($dosenId, 12, 3);
        $this->createPreferensi($dosenId, 5, 4);
        $this->createPreferensi($dosenId, 11, 5); 
        
    }

    /**
     * membuat preferensi user 
     */

    private function createPreferensi($dosenId, $namaKategori, $prioritas)
    {
        PreferensiDosenModel::create([
            'dosen_id'      => $dosenId,
            'kategori_id'   => $namaKategori,
            'prioritas'     => $prioritas
        ]);
    }
}

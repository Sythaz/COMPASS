<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Lomba 1: Lomba Web Development Nasional
            ['lomba_id' => 1, 'kategori_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 1, 'kategori_id' => 9, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 2: IOT CHALLENGE
            ['lomba_id' => 2, 'kategori_id' => 15, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 3: WS ASEAN 2025
            ['lomba_id' => 3, 'kategori_id' => 11, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 4: Mobile App Development Jawa Timur #21
            ['lomba_id' => 4, 'kategori_id' => 6, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 5: Game Development Jawa Timur #21
            ['lomba_id' => 5, 'kategori_id' => 7, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 6: Hackathon AI Internasional
            ['lomba_id' => 6, 'kategori_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 6, 'kategori_id' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 6, 'kategori_id' => 7, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 7: Cybersecurity Challenge Provinsi Jawa Barat
            ['lomba_id' => 7, 'kategori_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 7, 'kategori_id' => 12, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 8: Kontes Mobile App Development Universitas Indonesia
            ['lomba_id' => 8, 'kategori_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 8, 'kategori_id' => 9, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 9: Lomba Esai Bahasa Inggris Tingkat Kabupaten
            ['lomba_id' => 9, 'kategori_id' => 16, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 9, 'kategori_id' => 3, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 10: Kontes Game Indie Nasional
            ['lomba_id' => 10, 'kategori_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 10, 'kategori_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 11: Olimpiade Algoritma Tingkat Kota
            ['lomba_id' => 11, 'kategori_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 12: Kompetisi UI/UX Mahasiswa Se-Jawa
            ['lomba_id' => 12, 'kategori_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 12, 'kategori_id' => 6, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 13: Lomba Bisnis Startup Tingkat Nasional
            ['lomba_id' => 13, 'kategori_id' => 17, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 13, 'kategori_id' => 13, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 14: Desain Poster Kreatif
            ['lomba_id' => 14, 'kategori_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 14, 'kategori_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 15: Kompetisi Fotografi Alam Nasional
            ['lomba_id' => 15, 'kategori_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 16: Turnamen Catur Cepat Pelajar
            ['lomba_id' => 16, 'kategori_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Lomba 17: Festival Tari Tradisional Jawa
            ['lomba_id' => 17, 'kategori_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('t_kategori_lomba')->insert($data);
    }
}

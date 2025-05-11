<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id'   => 1,
                'nama_kategori' => "Seni",
            ],
            [
                'kategori_id'   => 2,
                'nama_kategori' => "Olahraga",
            ],
            [
                'kategori_id'   => 3,
                'nama_kategori' => "Bahasa",
            ],
            [
                'kategori_id'   => 4,
                'nama_kategori' => "Algoritma & Logika",
            ],
            [
                'kategori_id'   => 5,
                'nama_kategori' => "Web Development",
            ],
            [
                'kategori_id'   => 6,
                'nama_kategori' => "Mobile App Development",
            ],
            [
                'kategori_id'   => 7,
                'nama_kategori' => "Game Development",
            ],
            [
                'kategori_id'   => 8,
                'nama_kategori' => "Software Development",
            ],
            [
                'kategori_id'   => 9,
                'nama_kategori' => "UI/UX Design",
            ],
            [
                'kategori_id'   => 10,
                'nama_kategori' => "AI & Mechine Learning",
            ],
            [
                'kategori_id'   => 11,
                'nama_kategori' => "Cybersecurity",
            ],
            [
                'kategori_id'   => 12,
                'nama_kategori' => "Networking & Cloud",
            ],
            [
                'kategori_id'   => 13,
                'nama_kategori' => "Data Analytics",
            ],
            [
                'kategori_id'   => 14,
                'nama_kategori' => "Data Science",
            ],
            [
                'kategori_id'   => 15,
                'nama_kategori' => "Internet of Things",
            ],
            [
                'kategori_id'   => 16,
                'nama_kategori' => "Essay",
            ],
            [
                'kategori_id'   => 17,
                'nama_kategori' => "Business",
            ],
            [
                'kategori_id'   => 18,
                'nama_kategori' => "Lainnya",
            ],
        ];
        DB::table('t_kategori')->insert($data);
    }
}

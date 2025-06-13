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
                'kategori_id'       => 1,
                'nama_kategori'     => 'Seni',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'       => 2,
                'nama_kategori'     => 'Olahraga',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'       => 3,
                'nama_kategori'     => 'Bahasa',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 4,
                'nama_kategori' => 'Algoritma & Logika',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 5,
                'nama_kategori' => 'Web Development',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 6,
                'nama_kategori' => 'Mobile App Development',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 7,
                'nama_kategori' => 'Game Development',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 8,
                'nama_kategori' => 'Software Development',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 9,
                'nama_kategori' => 'UI/UX Design',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 10,
                'nama_kategori' => 'AI & Machine Learning',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 11,
                'nama_kategori' => 'Cybersecurity',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 12,
                'nama_kategori' => 'Networking & Cloud',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 13,
                'nama_kategori' => 'Data Analytics',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 14,
                'nama_kategori' => 'Data Science',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 15,
                'nama_kategori' => 'Internet of Things',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 16,
                'nama_kategori' => 'Essay',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 17,
                'nama_kategori' => 'Business',
                'status_kategori'   => 'Aktif'
            ],
            [
                'kategori_id'   => 18,
                'nama_kategori' => 'Lainnya',
                'status_kategori'   => 'Aktif'
            ],
        ];
        DB::table('t_kategori')->insert($data);
    }
}

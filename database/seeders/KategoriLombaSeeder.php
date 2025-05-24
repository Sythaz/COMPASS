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
        DB::table('t_kategori_lomba')->insert([
            ['lomba_id' => 1, 'kategori_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 1, 'kategori_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 2, 'kategori_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 2, 'kategori_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => 2, 'kategori_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

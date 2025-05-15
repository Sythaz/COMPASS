<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,              // 1
            KategoriSeeder::class,          // 2
            ProdiSeeder::class,             // 3
            PeriodeSeeder::class,           // 4
            LevelMinatBakatSeeder::class,   // 5
            AdminSeeder::class,             // 6
            MahasiswaSeeder::class,         // 7
            DosenSeeder::class,             // 8
            LombaSeeder::class,             // 9
            PrestasiSeeder::class,          // 10
            RekomendasiLombaSeeder::class,  // 11
            PendaftaranLombaSeeder::class,  // 12
            RekomPesertaLombaSeeder::class, // 13
            LaporanPrestasiSeeder::class    // 14
        ]);
    }
}

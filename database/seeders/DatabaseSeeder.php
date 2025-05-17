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
            TingkatLombaSeeder::class,      // 9 
            LombaSeeder::class,             // 10
            PrestasiSeeder::class,          // 11
            RekomendasiLombaSeeder::class,  // 12
            PendaftaranLombaSeeder::class,  // 13
            RekomPesertaLombaSeeder::class, // 14
            LaporanPrestasiSeeder::class    // 15
        ]);
    }
}

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
            AdminSeeder::class,             // 5
            MahasiswaSeeder::class,         // 6
            DosenSeeder::class,             // 7
            TingkatLombaSeeder::class,      // 8
            LombaSeeder::class,             // 9
            KategoriLombaSeeder::class,     // 10
            PrestasiSeeder::class,          // 11
            PrestasiMahasiswaSeeder::class, // 12
            RekomendasiLombaSeeder::class,  // 13
            PendaftaranLombaSeeder::class,  // 14
            RekomPesertaLombaSeeder::class, // 15
            LaporanPrestasiSeeder::class,   // 16
            NotifikasiSeeder::class,        // 17
            PreferensiUserSeeder::class,    // 18
        ]);
    }
}

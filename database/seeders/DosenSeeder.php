<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'dosen_id'    => 1,
                'user_id'     => 3,
                'nip_dosen'   => 198805042015041001,
                'nama_dosen'  => "Pramana Yoga Saputra, S.Kom., M.MT.",
                'bidang_dosen'=> "Mobile App Development",
                'img_dosen'   => "Profile_Pramana Yoga.JPEG"
            ],
        ];
        DB::table('t_dosen')->insert($data);
    }
}

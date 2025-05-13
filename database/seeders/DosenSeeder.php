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
                'nama_dosen'  => 'Pramana Yoga Saputra, S.Kom., M.MT.',
                'bidang_dosen'=> 'Mobile App Development',
                'img_dosen'   => 'Profile_Pramana Yoga.JPEG'
            ],
            [
                'dosen_id'      => 2,
                'user_id'       => 9,
                'nip_dosen'     => 198211302014041001,
                'nama_dosen'    => 'Luqman Affandi, S.Kom., M.MSI.',
                'bidang_dosen'  => 'Internet of Things',
                'img_dosen'     => 'Profile_Luqman Affandi.JPEG'
            ],
            [
                'dosen_id'      => 3,
                'user_id'       => 10,
                'nip_dosen'     => 197704242008121001,
                'nama_dosen'    => 'Gunawan Budiprasetyo, S.T., M.MT., Ph.D.',
                'bidang_dosen'  => 'Web Development',
                'img_dosen'     => 'Profile_Gunawan Budiprasetyo.JPEG'
            ]
        ];
        DB::table('t_dosen')->insert($data);
    }
}

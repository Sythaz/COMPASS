<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'admin_id'   => 1,
                'user_id'    => 1,
                'nip_admin'  => 1234567891011121314,
                'nama_admin' => 'Sri Whariyanti, S.Pd',
                'img_admin'  => 'Foto_profil.png'
            ],
        ];
        DB::table('t_admin')->insert($data);
    }
}

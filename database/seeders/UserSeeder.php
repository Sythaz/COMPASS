<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id'  => 1,
                'username' => 1234567891011121314, 
                'password' => "Admin1", 
                'role'     => "Admin",
            ],
            [
                'user_id'  => 2,
                'username' => 2341720146, 
                'password' => "Mahasiswa1", 
                'role'     => "Mahasiswa",
            ],
            [
                'user_id'  => 3,
                'username' => 198805042015041001, 
                'password' => "Dosen1", 
                'role'     => "Dosen",
            ]
        ];
        DB::table('t_users')->insert($data);
    }
}

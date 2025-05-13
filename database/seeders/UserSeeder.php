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
                'password' => 'Admin1', 
                'role'     => 'Admin',
            ],
            [
                'user_id'  => 2,
                'username' => 2341720146, 
                'password' => 'Mahasiswa1', 
                'role'     => 'Mahasiswa',
            ],
            [
                'user_id'  => 3,
                'username' => 198805042015041001, 
                'password' => 'Dosen1', 
                'role'     => 'Dosen',
            ],
            [
                'user_id'   => 4,
                'username'  => 121314151617181910,
                'password'  => 'Admin2',
                'role'      => 'Admin'
            ],
            [
                'user_id'   => 5,
                'username'  => 2341720046,
                'password'  => 'Mahasiswa2',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 6,
                'username'  => 2341720102,
                'password'  => 'Mahasiswa3',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 7, 
                'username'  => 2341720219,
                'password'  => 'Mahasiswa4',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 8, 
                'username'  => 2341720107,
                'password'  => 'Mahasiswa5',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 9,
                'username'  => 198211302014041001,
                'password'  => 'Dosen2',
                'role'      => 'Dosen'
            ],
            [
                'user_id'   => 10,
                'username'  => 197704242008121001,
                'password'  => 'Dosen3',
                'role'      => 'Dosen'
            ]
        ];
        DB::table('t_users')->insert($data);
    }
}

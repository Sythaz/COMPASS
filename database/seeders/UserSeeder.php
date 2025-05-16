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
                'phrase'   => '1234567891011121314',
                'role'     => 'Admin',
            ],
            [
                'user_id'  => 2,
                'username' => 2341720146, 
                'password' => 'Mahasiswa1', 
                'phrase'   => '2341720146',
                'role'     => 'Mahasiswa',
            ],
            [
                'user_id'  => 3,
                'username' => 198805042015041001, 
                'password' => 'Dosen1', 
                'phrase'   => '198805042015041001',
                'role'     => 'Dosen',
            ],
            [
                'user_id'   => 4,
                'username'  => 121314151617181910,
                'password'  => 'Admin2',
                'phrase'    => '121314151617181910',
                'role'      => 'Admin'
            ],
            [
                'user_id'   => 5,
                'username'  => 2341720046,
                'password'  => 'Mahasiswa2',
                'phrase'    => '2341720046',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 6,
                'username'  => 2341720102,
                'password'  => 'Mahasiswa3',
                'phrase'    => '2341720102',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 7, 
                'username'  => 2341720219,
                'password'  => 'Mahasiswa4',
                'phrase'    => '2341720219',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 8, 
                'username'  => 2341720107,
                'password'  => 'Mahasiswa5',
                'phrase'    => '2341720107',
                'role'      => 'Mahasiswa'
            ],
            [
                'user_id'   => 9,
                'username'  => 198211302014041001,
                'password'  => 'Dosen2',
                'phrase'    => '198211302014041001',
                'role'      => 'Dosen'
            ],
            [
                'user_id'   => 10,
                'username'  => 197704242008121001,
                'password'  => 'Dosen3',
                'phrase'    => '197704242008121001',
                'role'      => 'Dosen'
            ]
        ];
        DB::table('t_users')->insert($data);
    }
}

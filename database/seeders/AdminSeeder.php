<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user dengan user_id 1 dan 2
        $user1 = DB::table('t_users')->where('user_id', 1)->first();
        $user2 = DB::table('t_users')->where('user_id', 2)->first();

        $data = [];

        if ($user1) {
            $data[] = [
                'admin_id' => 1,
                'user_id' => $user1->user_id,
                'nip_admin' => $user1->username, // ambil dari kolom username
                'nama_admin' => 'Sri Whariyanti, S.Pd',
                'img_admin' => 'profil-default.png'
            ];
        }

        if ($user2) {
            $data[] = [
                'admin_id' => 2,
                'user_id' => $user2->user_id,
                'nip_admin' => $user2->username, // ambil dari kolom username
                'nama_admin' => 'Lailatul Qodriyah, S.Sos',
                'img_admin' => 'profil-default.png'
            ];
        }

        // Insert data ke tabel t_admin
        if (!empty($data)) {
            DB::table('t_admin')->insert($data);
        }
    }
}

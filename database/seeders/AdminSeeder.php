<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user dengan user_id 1,2 dan 344-346
        $usersPart1 = DB::table('t_users')
            ->whereIn('user_id', [1, 2])
            ->orderBy('user_id')
            ->get();

        $usersPart2 = DB::table('t_users')
            ->whereBetween('user_id', [344, 346])
            ->orderBy('user_id')
            ->get();

        // Gabungkan semua user
        $users = $usersPart1->concat($usersPart2);

        // Ambil admin yang sudah ada untuk user_id yg sama
        $existingAdmins = DB::table('t_admin')
            ->whereIn('user_id', $users->pluck('user_id'))
            ->get()
            ->keyBy('user_id');

        // Map admin_id untuk 5 data (1 sampai 5)
        $adminIds = [1, 2, 3, 4, 5];

        $nama_admin_map = [
            1 => 'Sri Whariyanti, S.Pd',
            2 => 'Lailatul Qodriyah, S.Sos',
            3 => 'Milea Adnan Hussain, S.Pd',
            4 => 'Billie Joe Armstrong, S.T',
            5 => 'Chester Bennington, S.Th',
        ];

        $data = [];

        foreach ($users->values() as $index => $user) {
            $userId = $user->user_id;
            $adminId = $adminIds[$index];  // Admin_id urut 1..5

            $nip = $user->username;
            // Safety check kalau mau trim nip, misal seperti yang sebelumnya
            if (in_array($userId, [344, 345, 346]) && strlen($nip) > 344) {
                $nip = substr($nip, 344);
            }

            $adminData = $existingAdmins->get($userId);

            $data[] = [
                'admin_id' => $adminId,
                'user_id' => $userId,
                'nip_admin' => $nip,
                'nama_admin' => $nama_admin_map[$adminId] ?? 'Admin ' . $adminId,
                'img_admin' => 'profil-default.png',
                'email' => $adminData->email ?? 'admin' . $adminId . '@example.com',
                'no_hp' => $adminData->no_hp ?? '0812345678' . $adminId,
                'alamat' => $adminData->alamat ?? 'Jl. Buah Batu, No. 123',
            ];
        }

        if (!empty($data)) {
            DB::table('t_admin')->upsert(
                $data,
                ['admin_id'], // key unik di t_admin untuk update/insert
                ['nip_admin', 'nama_admin', 'img_admin', 'email', 'no_hp', 'alamat']
            );
        }
    }
}

<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 5 user pertama berdasarkan urutan user_id
        $users = DB::table('t_users')
            ->orderBy('user_id')
            ->limit(5)
            ->get()
            ->values(); // Reset index agar mulai dari 0

        // Mapping admin_id 1–5
        $adminIds = [1, 2, 3, 4, 5];

        // Nama admin sesuai ID
        $nama_admin_map = [
            1 => 'Sri Whariyanti, S.Pd',
            2 => 'Lailatul Qodriyah, S.Sos',
            3 => 'Milea Adnan Hussain, S.Pd',
            4 => 'Billie Joe Armstrong, S.T',
            5 => 'Chester Bennington, S.Th',
        ];

        // Jenis kelamin sesuai urutan admin
        $kelamin = ['P', 'P', 'P', 'L', 'L'];

        // Ambil data admin lama untuk user_id yang sama (jika ada)
        $existingAdmins = DB::table('t_admin')
            ->whereIn('user_id', $users->pluck('user_id'))
            ->get()
            ->keyBy('user_id');

        $data = [];
        $jalan = ['Mawar', 'Melati', 'Kenanga', 'Flamboyan', 'Cendana', 'Delima'];

        foreach ($users as $index => $user) {
            $userId = $user->user_id;
            $adminId = $adminIds[$index];
            $nip = $user->username;

            $adminData = $existingAdmins->get($userId);

            $data[] = [
                'admin_id' => $adminId,
                'user_id' => $userId,
                'nip_admin' => $nip,
                'nama_admin' => $nama_admin_map[$adminId] ?? 'Admin ' . $adminId,
                'img_admin' => 'profil-default.png',
                'email' => $adminData->email ?? 'admin' . $adminId . '@example.com',
                'no_hp' => $adminData->no_hp ?? '0812345678' . $adminId,
                'alamat' => 'Jl. ' . $jalan[array_rand($jalan)] . ' No. ' . rand(1, 10),
                'kelamin' => $kelamin[$index],
            ];
        }

        // Simpan ke tabel t_admin dengan upsert
        if (!empty($data)) {
            DB::table('t_admin')->upsert(
                $data,
                ['admin_id'], // Key unik untuk upsert
                ['nip_admin', 'nama_admin', 'img_admin', 'email', 'no_hp', 'alamat', 'kelamin']
            );
        }
    }
}
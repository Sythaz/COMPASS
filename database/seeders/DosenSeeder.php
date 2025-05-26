<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            "Prof. Dr. Eng. Rosa Andrie Asmara, S.T., M.T.",
            "Pramana Yoga Saputra, S.Kom., M.MT.",
            "Luqman Affandi, S.Kom., M.MSI.",
            "Gunawan Budiprasetyo, S.T., M.MT., Ph.D.",
            "Hendra Pradibta, S.E., M.Sc.",
            "Mungki Astiningrum, S.T., M.Kom.",
            "Ade Ismail, S.Kom., M.TI.",
            "Adevian Fairuz Pratama, S.S.T, M.Eng.",
            "Agung Nugroho Pramudhita, S.T., M.T.",
            "Ahmad Baha'uddin Almu'faro, S.Pd.I., M.Pd.I.",
            "Ahmadi Yuli Ananta, S.T., M.M.",
            "Annisa Puspa Kirana, S.Kom, M.Kom.",
            "Annisa Taufika Firdausi, S.T., M.T.",
            "Anugrah Nur Rahmanto, S.Sn, M.Ds.",
            "Ariadi Retno Tri Hayati Ririd, S.Kom., M.Kom.",
            "Arie Rachmad Syulistyo, S.Kom., M.Kom.",
            "Arief Prasetyo, S.Kom., M.Kom.",
            "Dr. Ir. Arwin Datumaya Wahyudi Sumari, S.T., M.T.IPM.ASEANEng.",
            "Astrifidha Rahma Amalia, S.Pd., M.Pd.",
            "Atiqah Nurul Asri, S.Pd., M.Pd.",
            "Bagas Satya Dian Nugraha, S.T, M.T.",
            "Dr. Eng. Banni Satria Andoko, S.Kom., M.MSI.",
            "Budi Harijanto, S.T., M.MKom.",
            "Prof. Cahya Rahmad, S.T., M.Kom., Dr. Eng.",
            "Candra Bella Vista, S.Kom, M.T.",
            "Ir. Deddy Kusbianto Purwoko Aji, M.MKom.",
            "Dhebys Suryani Hormansyah, S.Kom., M.T.",
            "Dian Hanifudin Subhi, S.Kom., M.Kom.",
            "Dika Rizky Yunianto, S.Kom., M.Kom.",
            "Dimas Wahyu Wibowo, S.T., M.T.",
            "Eka Larasati Amalia, S.ST., M.T.",
            "Ekojono, S.T., M.Kom.",
            "Elok Nur Hamdana, S.T., M.T.",
            "Dr. Ely Setyo Astuti, S.T., M.T.",
            "Endah Septa Sintiya, S.Pd., M.Kom.",
            "Erfan Rohadi, S.T., M.Eng., Ph.D.",
            "Faiz Ushbah Mubarok, S.Pd, M.Pd.",
            "Farid Angga Pribadi, S.Kom., M.Kom.",
            "Farida Ulfa, S.Pd., M.Pd.",
            "Habibie Ed Dien, S.Kom., M.T.",
            "Dra. Henny Purwaningsih, M.Pd.",
            "Ika Kusumaning Putri, S.Kom., M.T.",
            "Imam Fahrur Rozi, S.T., M.T.",
            "Dr. Indra Dharma Wijaya, S.T., M.MT.",
            "Irsyad Arif Mashudi, S.Kom., M.Kom.",
            "Kadek Suarjuna Batubulan, S.Kom., M.T.",
            "M. Hasyim Ratsanjani, S.Kom., M.Kom.",
            "Mamluatul Hani'ah, S.Kom., M.Kom.",
            "Meyti Eka Apriyani, S.T., M.T.",
            "Milyun Nima Shoumi, S.Kom., M.Kom.",
            "Moch. Zawaruddin Abdullah, S.ST., M.Kom.",
            "Muhammad Afif Hendrawan, S.Kom., M.T.",
            "Muhammad Shulhan Khairy, S.Kom., M.Kom.",
            "Muhammad Unggul Pamenang, S.ST., M.T.",
            "Mustika Mentari, S.Kom., M.Kom.",
            "Noprianto, S.Kom., M.Eng.",
            "Putra Prima Arhandi, S.T., M.Kom.",
            "Dr. Rakhmat Arianto, S.ST., M.Kom.",
            "Drs. Rawansyah, M.Pd",
            "Retno Damayanti, S.Pd., M.T.",
            "Ridwan Rismanto, S.ST., M.Kom.",
            "Rizki Putri Ramadhani, S.S., M.Pd.",
            "Robby Anggriawan, S.E, M.E.",
            "Rokhimatul Wakhidah, S.Pd., M.T.",
            "Rudy Ariyanto, S.T., M.Cs.",
            "Satrio Binusa Suryadi, S.S., M.Pd.",
            "Septian Enggar Sukmana, S.Pd., M.T.",
            "Dr. Shohib Muslim, S.H., M.Hum.",
            "Sofyan Noor Arief, S.ST., M.Kom.",
            "Triana Fatmawati, S.T., M.T.",
            "Dr. Ulla Delfana Rosiani, S.T., M.T.",
            "Usman Nurhasan, S.Kom., M.T.",
            "Vipkas Al Hadid Firdaus, S.T, M.T.",
            "Vit Zuraida, S.Kom., M.Kom.",
            "Vivi Nur Wijayaningrum, S.Kom., M.Kom.",
            "Vivin Ayu Lestari, S.Pd., M.Kom.",
            "Dr. Widaningsih condrowardhani, S.Psi., S.H., M.H.",
            "Wilda Imama Sabilla, S.Kom., M.Kom.",
            "Yan Watequlis Syaifudin, S.T., M.MT., Ph.D.",
            "Yoppy Yunhasnawa, S.ST., M.Sc.",
            "Yuri Ariyanto, S.Kom., M.Kom.",
            "Zulmy Faqihuddin Putera, S.Pd., M.Pd."
        ];

        $kelamin = [
            'L',
            'L',
            'L',
            'L',
            'L',
            'P',
            'L',
            'P',
            'L',
            'L',
            'L',
            'P',
            'P',
            'L',
            'P',
            'L',
            'L',
            'L',
            'P',
            'P',
            'L',
            'L',
            'L',
            'L',
            'P',
            'L',
            'L',
            'L',
            'L',
            'L',
            'P',
            'L',
            'P',
            'P',
            'P',
            'L',
            'L',
            'L',
            'P',
            'L',
            'P',
            'P',
            'L',
            'L',
            'L',
            'L',
            'L',
            'P',
            'P',
            'P',
            'L',
            'L',
            'L',
            'L',
            'P',
            'L',
            'L',
            'L',
            'L',
            'P',
            'L',
            'P',
            'L',
            'P',
            'L',
            'L',
            'L',
            'L',
            'L',
            'P',
            'P',
            'L',
            'L',
            'P',
            'P',
            'P',
            'P',
            'P',
            'L',
            'L',
            'L',
            'L'
        ];

        $jalan = ['Mawar', 'Melati', 'Kenanga', 'Flamboyan', 'Cendana', 'Delima', 'Kamboja', 'Bougenville', 'Teratai', 'Anggrek'];

        // Ambil user dari index ke-5 ke atas (mulai user ke-6)
        $users = DB::table('t_users')
            ->orderBy('user_id')
            ->skip(5) // Lewati 5 user pertama (index 0–4)
            ->take(count($names)) // Pastikan jumlah sesuai dengan array nama
            ->get()
            ->values(); // Reset index

        $data = [];

        foreach ($users as $index => $user) {
            $data[] = [
                'user_id' => $user->user_id,
                'kategori_id' => rand(1, 18),
                'nip_dosen' => $user->username, // Ganti nips
                'nama_dosen' => $names[$index] ?? 'Nama Tidak Diketahui',
                'img_dosen' => 'profil-default.png',
                'email' => null,
                'no_hp' => null,
                'alamat' => null,
                'kelamin' => $kelamin[$index] ?? 'Kelamin Tidak Diketahui',
            ];
        }

        DB::table('t_dosen')->insert($data);

        // Langkah 2: Update email, no_hp, alamat
        $dosen = DB::table('t_dosen')->get();
        foreach ($dosen as $d) {
            DB::table('t_dosen')
                ->where('dosen_id', $d->dosen_id)
                ->update([
                    'email' => 'dosen' . $d->dosen_id . '@example.com',
                    'no_hp' => '08' . str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT),
                    'alamat' => 'Jl. ' . $jalan[array_rand($jalan)] . ' No. ' . rand(1, 100),
                ]);
        }
    }
}



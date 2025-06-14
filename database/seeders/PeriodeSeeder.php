<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'periode_id'       => 1,
                'semester_periode' => "2015/2016 Ganjil",
                'tanggal_mulai'    => "2015-08-26",
                'tanggal_akhir'    => "2015-12-20",
            ],
            [
                'periode_id'       => 2,
                'semester_periode' => "2015/2016 Genap",
                'tanggal_mulai'    => "2016-02-10",
                'tanggal_akhir'    => "2016-06-20",
            ],
            [
                'periode_id'       => 3,
                'semester_periode' => "2016/2017 Ganjil",
                'tanggal_mulai'    => "2016-08-26",
                'tanggal_akhir'    => "2016-12-20",
            ],
            [
                'periode_id'       => 4,
                'semester_periode' => "2016/2017 Genap",
                'tanggal_mulai'    => "2017-02-10",
                'tanggal_akhir'    => "2017-06-20",
            ],
            [
                'periode_id'       => 5,
                'semester_periode' => "2017/2018 Ganjil",
                'tanggal_mulai'    => "2017-08-26",
                'tanggal_akhir'    => "2017-12-20",
            ],
            [
                'periode_id'       => 6,
                'semester_periode' => "2017/2018 Genap",
                'tanggal_mulai'    => "2018-02-10",
                'tanggal_akhir'    => "2018-06-20",
            ],
            [
                'periode_id'       => 7,
                'semester_periode' => "2018/2019 Ganjil",
                'tanggal_mulai'    => "2018-08-26",
                'tanggal_akhir'    => "2018-12-20",
            ],
            [
                'periode_id'       => 8,
                'semester_periode' => "2018/2019 Genap",
                'tanggal_mulai'    => "2019-02-10",
                'tanggal_akhir'    => "2019-06-20",
            ],
            [
                'periode_id'       => 9,
                'semester_periode' => "2019/2020 Ganjil",
                'tanggal_mulai'    => "2019-08-26",
                'tanggal_akhir'    => "2019-12-20",
            ],
            [
                'periode_id'       => 10,
                'semester_periode' => "2019/2020 Genap",
                'tanggal_mulai'    => "2020-02-10",
                'tanggal_akhir'    => "2020-06-20",
            ],
            [
                'periode_id'       => 11,
                'semester_periode' => "2020/2021 Ganjil",
                'tanggal_mulai'    => "2020-08-26",
                'tanggal_akhir'    => "2020-12-20",
            ],
            [
                'periode_id'       => 12,
                'semester_periode' => "2020/2021 Genap",
                'tanggal_mulai'    => "2021-02-10",
                'tanggal_akhir'    => "2021-06-20",
            ],
            [
                'periode_id'       => 13,
                'semester_periode' => "2021/2022 Ganjil",
                'tanggal_mulai'    => "2021-08-26",
                'tanggal_akhir'    => "2021-12-20",
            ],
            [
                'periode_id'       => 14,
                'semester_periode' => "2021/2022 Genap",
                'tanggal_mulai'    => "2022-02-10",
                'tanggal_akhir'    => "2022-06-20",
            ],
            [
                'periode_id'       => 15,
                'semester_periode' => "2022/2023 Ganjil",
                'tanggal_mulai'    => "2022-08-26",
                'tanggal_akhir'    => "2022-12-20",
            ],
            [
                'periode_id'       => 16,
                'semester_periode' => "2022/2023 Genap",
                'tanggal_mulai'    => "2023-02-10",
                'tanggal_akhir'    => "2023-06-20",
            ],
            [
                'periode_id'       => 17,
                'semester_periode' => "2023/2024 Ganjil",
                'tanggal_mulai'    => "2023-08-26",
                'tanggal_akhir'    => "2023-12-20",
            ],
            [
                'periode_id'       => 18,
                'semester_periode' => "2023/2024 Genap",
                'tanggal_mulai'    => "2024-02-10",
                'tanggal_akhir'    => "2024-06-20",
            ],
            [
                'periode_id'       => 19,
                'semester_periode' => "2024/2025 Ganjil",
                'tanggal_mulai'    => "2024-08-26",
                'tanggal_akhir'    => "2024-12-20",
            ],
            [
                'periode_id'       => 20,
                'semester_periode' => "2024/2025 Genap",
                'tanggal_mulai'    => "2025-02-10",
                'tanggal_akhir'    => "2025-06-20",
            ],
        ];
        DB::table('t_periode')->insert($data);
    }
}

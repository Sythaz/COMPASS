<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrometheeRekomendasiController extends Controller
{
    /**
     * Data lomba (alternatif) dengan nilai preferensi untuk setiap kriteria
     */
    private array $alternatif = [
        [
            'id' => 'A1',
            'name' => 'Gemastik Data Mining',
            'values' => [4, 4, 5, 4, 5, 5]
        ],
        [
            'id' => 'A2',
            'name' => 'UI/UX Challenge',
            'values' => [4, 4, 5, 3, 4, 3]
        ],
        [
            'id' => 'A3',
            'name' => 'Hackathon Merdeka',
            'values' => [3, 4, 3, 5, 3, 3]
        ],
        [
            'id' => 'A4',
            'name' => 'Data Science Competition',
            'values' => [2, 2, 1, 2, 4, 5]
        ],
        [
            'id' => 'A5',
            'name' => 'Game Development Challenge',
            'values' => [5, 1, 3, 1, 5, 5]
        ],
        [
            'id' => 'A6',
            'name' => 'Regional Data Analysis',
            'values' => [4, 3, 3, 4, 4, 3]
        ],
        [
            'id' => 'A7',
            'name' => 'International App Challenge',
            'values' => [3, 2, 1, 2, 2, 5]
        ],
        [
            'id' => 'A8',
            'name' => 'Local UI Design Contest',
            'values' => [2, 1, 5, 5, 2, 3]
        ],
        [
            'id' => 'A9',
            'name' => 'Mobile Game Competition',
            'values' => [5, 2, 3, 1, 3, 5]
        ],
        [
            'id' => 'A10',
            'name' => 'Web Development Contest',
            'values' => [2, 1, 5, 1, 5, 3]
        ],
    ];

    /**
     * Informasi kriteria dengan bobot tetap sesuai nilai default
     */
    private array $criteria = [
        [
            'id' => 'C1',
            'name' => 'Kesesuaian Bidang Kompetisi',
            'type' => 'benefit',
            'weight' => 0.10,
            'dynamic_group' => 'bidang',
            'preference_function' => 'usual'
        ],
        [
            'id' => 'C2',
            'name' => 'Tingkat lomba',
            'type' => 'benefit',
            'weight' => 0.15,
            'dynamic_group' => null,
            'preference_function' => 'usual'
        ],
        [
            'id' => 'C3',
            'name' => 'Reputasi penyelenggara',
            'type' => 'benefit',
            'weight' => 0.10,
            'dynamic_group' => null,
            'preference_function' => 'usual'
        ],
        [
            'id' => 'C4',
            'name' => 'Deadline pendaftaran',
            'type' => 'benefit',
            'weight' => 0.10,
            'dynamic_group' => null,
            'preference_function' => 'usual'
        ],
        [
            'id' => 'C5',
            'name' => 'Lokasi',
            'type' => 'cost',
            'weight' => 0.35,
            'dynamic_group' => 'lokasi',
            'preference_function' => 'usual'
        ],
        [
            'id' => 'C6',
            'name' => 'Biaya',
            'type' => 'benefit',
            'weight' => 0.20,
            'dynamic_group' => 'biaya',
            'preference_function' => 'usual'
        ]
    ];

    /**
     * Menghitung rekomendasi lomba menggunakan metode PROMETHEE
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request): JsonResponse
    {
        // Ambil preferensi pengguna dari request
        $userPreferences = $request->all();

        // Jika tidak ada preferensi, gunakan default
        if (empty($userPreferences)) {
            $userPreferences = [
                'bidang_priority' => [
                    'Data Science' => 1,
                    'Data Analys' => 2,
                    'UI/UX' => 3,
                    'Software Development' => 4,
                    'Game Dev' => 5
                ],
                'lokasi_priority' => [
                    'Online' => 1,
                    'Offline dalam kota' => 2,
                    'Hybrid' => 3,
                    'Offline luar kota' => 4
                ],
                'biaya_priority' => [
                    'Tanpa biaya' => 1,
                    'Dengan Biaya' => 2
                ]
            ];
        }

        // Hitung indeks preferensi untuk setiap pasangan alternatif
        $preferenceIndices = $this->calculatePreferenceIndices();

        // Hitung outranking flows (leaving dan entering)
        [$leavingFlow, $enteringFlow] = $this->calculateOutrankingFlows($preferenceIndices);

        // Hitung net flow dan ranking
        $results = $this->calculateNetFlowAndRank($leavingFlow, $enteringFlow);

        // Siapkan data untuk response
        $response = [
            'message' => 'Rekomendasi lomba berhasil dihitung',
            'weights' => $this->getWeights(),
            'preference_indices' => $preferenceIndices,
            'leaving_flow' => $leavingFlow,
            'entering_flow' => $enteringFlow,
            'results' => $results
        ];

        return response()->json($response);
    }

    /**
     * Mendapatkan bobot kriteria
     *
     * @return array
     */
    private function getWeights(): array
    {
        $weights = [];
        foreach ($this->criteria as $criterion) {
            $weights[$criterion['id']] = $criterion['weight'];
        }
        return $weights;
    }

    /**
     * Menghitung indeks preferensi untuk setiap pasangan alternatif
     *
     * @return array
     */
    private function calculatePreferenceIndices(): array
    {
        $preferenceIndices = [];
        $n = count($this->alternatif);

        for ($i = 0; $i < $n; $i++) {
            $a = $this->alternatif[$i];
            $preferenceIndices[$a['id']] = [];

            for ($j = 0; $j < $n; $j++) {
                $b = $this->alternatif[$j];

                if ($i === $j) {
                    $preferenceIndices[$a['id']][$b['id']] = 0;
                    continue;
                }

                $sum = 0;
                for ($k = 0; $k < count($this->criteria); $k++) {
                    $criterion = $this->criteria[$k];
                    $weight = $criterion['weight'];

                    $valA = $a['values'][$k];
                    $valB = $b['values'][$k];

                    // Hitung perbedaan nilai
                    $difference = $valA - $valB;

                    // Sesuaikan perbedaan untuk kriteria cost
                    if ($criterion['type'] === 'cost') {
                        $difference = -$difference; // Nilai lebih tinggi lebih buruk untuk cost
                    }

                    // Hitung preferensi berdasarkan tipe fungsi preferensi
                    $preference = $this->calculateSinglePreference($difference, $criterion['preference_function']);

                    // Tambahkan ke jumlah tertimbang
                    $sum += $weight * $preference;
                }

                $preferenceIndices[$a['id']][$b['id']] = $sum;
            }
        }

        return $preferenceIndices;
    }

    /**
     * Menghitung preferensi berdasarkan perbedaan nilai dan tipe fungsi preferensi
     *
     * @param float $d Perbedaan nilai
     * @param string $type Tipe fungsi preferensi
     * @return float Nilai preferensi
     */
    private function calculateSinglePreference(float $d, string $type): float
    {
        // Untuk kesederhanaan, kita hanya implementasikan tipe "usual"
        // Tipe lain bisa ditambahkan sesuai kebutuhan
        switch ($type) {
            case 'usual':
                return ($d > 0) ? 1.0 : 0.0;
            default:
                return ($d > 0) ? 1.0 : 0.0;
        }
    }

    /**
     * Menghitung outranking flows (leaving dan entering)
     *
     * @param array $preferenceIndices Indeks preferensi
     * @return array Array berisi leaving flow dan entering flow
     */
    private function calculateOutrankingFlows(array $preferenceIndices): array
    {
        $leavingFlow = [];
        $enteringFlow = [];
        $alternativeIds = array_column($this->alternatif, 'id');
        $n = count($alternativeIds);

        if ($n <= 1) {
            foreach ($alternativeIds as $id) {
                $leavingFlow[$id] = 0;
                $enteringFlow[$id] = 0;
            }
            return [$leavingFlow, $enteringFlow];
        }

        $divisor = $n - 1;

        foreach ($alternativeIds as $aId) {
            $leavingSum = 0;
            $enteringSum = 0;

            foreach ($alternativeIds as $bId) {
                if ($aId !== $bId) {
                    $leavingSum += $preferenceIndices[$aId][$bId];
                    $enteringSum += $preferenceIndices[$bId][$aId];
                }
            }

            $leavingFlow[$aId] = $leavingSum / $divisor;
            $enteringFlow[$aId] = $enteringSum / $divisor;
        }

        return [$leavingFlow, $enteringFlow];
    }

    /**
     * Menghitung net flow dan ranking
     *
     * @param array $leavingFlow Leaving flow untuk setiap alternatif
     * @param array $enteringFlow Entering flow untuk setiap alternatif
     * @return array Hasil perhitungan dengan ranking
     */
    private function calculateNetFlowAndRank(array $leavingFlow, array $enteringFlow): array
    {
        $results = [];
        $netFlows = [];

        // Hitung net flow untuk setiap alternatif
        foreach ($this->alternatif as $alternative) {
            $id = $alternative['id'];
            $netFlow = $leavingFlow[$id] - $enteringFlow[$id];

            $results[$id] = [
                'id' => $id,
                'name' => $alternative['name'],
                'leaving_flow' => $leavingFlow[$id],
                'entering_flow' => $enteringFlow[$id],
                'net_flow' => $netFlow
            ];

            $netFlows[$id] = $netFlow;
        }

        // Urutkan alternatif berdasarkan net flow (descending)
        arsort($netFlows);

        // Tetapkan ranking
        $rank = 1;
        $lastNetFlow = null;
        $sameRankCount = 0;

        foreach ($netFlows as $id => $netFlow) {
            if ($lastNetFlow !== null && abs($netFlow - $lastNetFlow) < 0.001) {
                // Net flow sama dengan sebelumnya, ranking sama
                $results[$id]['rank'] = $rank - $sameRankCount;
                $sameRankCount++;
            } else {
                // Net flow berbeda, ranking baru
                $results[$id]['rank'] = $rank;
                $sameRankCount = 0;
            }

            $lastNetFlow = $netFlow;
            $rank++;
        }

        // Urutkan hasil berdasarkan ranking
        uasort($results, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });

        return array_values($results); // Konversi ke array numerik untuk JSON
    }

    /**
     * Contoh penggunaan tanpa request HTTP (untuk testing)
     *
     * @return array
     */
    public function calculateTest(): array
    {
        // Hitung indeks preferensi untuk setiap pasangan alternatif
        $preferenceIndices = $this->calculatePreferenceIndices();

        // Hitung outranking flows (leaving dan entering)
        [$leavingFlow, $enteringFlow] = $this->calculateOutrankingFlows($preferenceIndices);

        // Hitung net flow dan ranking
        $results = $this->calculateNetFlowAndRank($leavingFlow, $enteringFlow);

        // Siapkan data untuk response
        return [
            'message' => 'Rekomendasi lomba berhasil dihitung',
            'weights' => $this->getWeights(),
            'preference_indices' => $preferenceIndices,
            'leaving_flow' => $leavingFlow,
            'entering_flow' => $enteringFlow,
            'results' => $results
        ];
    }

    /**
     * Fungsi untuk menampilkan detail perhitungan
     * 
     * @return array
     */
    public function showDetailedCalculation(): array
    {
        // Hitung indeks preferensi untuk setiap pasangan alternatif
        $preferenceIndices = $this->calculatePreferenceIndices();

        // Hitung outranking flows (leaving dan entering)
        [$leavingFlow, $enteringFlow] = $this->calculateOutrankingFlows($preferenceIndices);

        // Tampilkan detail perhitungan
        $details = [];

        // Detail bobot kriteria
        $details['weights'] = $this->getWeights();

        // Detail nilai alternatif
        $details['alternative_values'] = [];
        foreach ($this->alternatif as $alternative) {
            $details['alternative_values'][$alternative['id']] = [
                'name' => $alternative['name'],
                'values' => []
            ];

            for ($i = 0; $i < count($alternative['values']); $i++) {
                $details['alternative_values'][$alternative['id']]['values'][$this->criteria[$i]['id']] = $alternative['values'][$i];
            }
        }

        // Detail indeks preferensi
        $details['preference_indices'] = $preferenceIndices;

        // Detail leaving dan entering flow
        $details['flows'] = [];
        foreach ($this->alternatif as $alternative) {
            $id = $alternative['id'];
            $netFlow = ($leavingFlow[$id] - $enteringFlow[$id]);

            $details['flows'][$id] = [
                'name' => $alternative['name'],
                'leaving_flow' => $leavingFlow[$id],
                'entering_flow' => $enteringFlow[$id],
                'net_flow' => $netFlow
            ];
        }

        return $details;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\PreferensiUserModel;
use App\Models\TingkatLombaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrometheeRekomendasiController extends Controller
{
    /**
     * Data lomba (alternatif) dengan nilai preferensi untuk setiap kriteria
     */
    private $alternatif = [];

    public function __construct()
    {
        // // Ambil semua lomba yang aktif dan terverifikasi, beserta relasi kategorinya
        // $lombas = LombaModel::with('kategori')
        //     ->where('status_lomba', 'Aktif')
        //     ->where('status_verifikasi', 'Terverifikasi')
        //     ->get();

        // // Ambil semua preferensi user yang sedang login
        // $preferensiAll = PreferensiUserModel::where('user_id', auth()->user())
        //     ->orderBy('prioritas')
        //     ->get();


        // $preferensiBidang = $preferensiAll
        //     ->where('kriteria', 'bidang')
        //     ->pluck('nilai', 'prioritas');
        // $totalCountBidangLomba = $preferensiAll->where('kriteria', 'bidang')->count();

        // $preferensiTingkat = $preferensiAll
        //     ->where('kriteria', 'tingkat')
        //     ->pluck('nilai', 'prioritas');
        // $totalCountTingkatLomba = TingkatLombaModel::count();

        // $preferensiReputasiPenyelenggara = $preferensiAll
        //     ->where('kriteria', 'penyelenggara')
        //     ->pluck('nilai', 'prioritas');
        // $totalCountReputasiPenyelenggara = $preferensiAll->where('kriteria', 'penyelenggara')->count();

        // $preferensiLokasi = $preferensiAll
        //     ->where('kriteria', 'lokasi')
        //     ->pluck('nilai', 'prioritas');
        // $totalCountLokasi = $preferensiAll->where('kriteria', 'lokasi')->count();

        // $preferensiBiaya = $preferensiAll
        //     ->where('kriteria', 'biaya')
        //     ->pluck('nilai', 'prioritas');
        // $totalCountBiaya = $preferensiAll->where('kriteria', 'biaya')->count();

        // foreach ($lombas as $lomba) {
        //     $this->alternatif[] = [
        //         'id' => $lomba->lomba_id,
        //         'name' => $lomba->nama_lomba,
        //         'values' => [
        //             $this->getBidangScore($lomba, $preferensiBidang, $totalCountBidangLomba),
        //             $this->getTingkatScore($lomba, $preferensiTingkat, $totalCountTingkatLomba),
        //             $this->getReputasiPenyelenggaraScore($lomba, $preferensiReputasiPenyelenggara, $totalCountReputasiPenyelenggara),
        //             $this->getDeadlineScore($lomba),
        //             $this->getLokasiScore($lomba, $preferensiLokasi, $totalCountLokasi),
        //             $this->getBiayaScore($lomba, $preferensiBiaya, $totalCountBiaya),
        //         ],
        //     ];
        // }
    }

    public function loadPreferensi()
    {
        // Ambil semua lomba yang aktif dan terverifikasi, beserta relasi kategorinya
        $lombas = LombaModel::with('kategori')
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->get();

        // Ambil semua preferensi user yang sedang login
        $user = auth()->user();
        $preferensiAll = PreferensiUserModel::where('user_id', $user->user_id)
            ->orderBy('prioritas')
            ->get();


        $preferensiBidang = $preferensiAll
            ->where('kriteria', 'bidang')
            ->pluck('nilai', 'prioritas');
        $totalCountBidangLomba = $preferensiAll->where('kriteria', 'bidang')->count();

        $preferensiTingkat = $preferensiAll
            ->where('kriteria', 'tingkat')
            ->pluck('nilai', 'prioritas');
        $totalCountTingkatLomba = TingkatLombaModel::count();

        $preferensiReputasiPenyelenggara = $preferensiAll
            ->where('kriteria', 'penyelenggara')
            ->pluck('nilai', 'prioritas');
        $totalCountReputasiPenyelenggara = $preferensiAll->where('kriteria', 'penyelenggara')->count();

        $preferensiLokasi = $preferensiAll
            ->where('kriteria', 'lokasi')
            ->pluck('nilai', 'prioritas');
        $totalCountLokasi = $preferensiAll->where('kriteria', 'lokasi')->count();

        $preferensiBiaya = $preferensiAll
            ->where('kriteria', 'biaya')
            ->pluck('nilai', 'prioritas');
        $totalCountBiaya = $preferensiAll->where('kriteria', 'biaya')->count();

        foreach ($lombas as $lomba) {
            $this->alternatif[] = [
                'id' => $lomba->lomba_id,
                'name' => $lomba->nama_lomba,
                'values' => [
                    $this->getBidangScore($lomba, $preferensiBidang, $totalCountBidangLomba),
                    $this->getTingkatScore($lomba, $preferensiTingkat, $totalCountTingkatLomba),
                    $this->getReputasiPenyelenggaraScore($lomba, $preferensiReputasiPenyelenggara, $totalCountReputasiPenyelenggara),
                    $this->getDeadlineScore($lomba),
                    $this->getLokasiScore($lomba, $preferensiLokasi, $totalCountLokasi),
                    $this->getBiayaScore($lomba, $preferensiBiaya, $totalCountBiaya),
                ],
            ];
        }
    }

    public function laporan()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Laporan Promethee']
        ];

        // Validasi, jika user belum memiliki preferensi, kirim ke dashboard
        if (PreferensiUserModel::where('user_id', auth()->id())->doesntExist()) {
            return redirect()->route('mahasiswa.profile.index');
        }

        $this->loadPreferensi();

        try {
            // Hitung semua data yang diperlukan
            $preferenceIndices = $this->calculatePreferenceIndices();
            [$leavingFlow, $enteringFlow] = $this->calculateOutrankingFlows($preferenceIndices);
            $results = $this->calculateNetFlowAndRank($leavingFlow, $enteringFlow);

            // Siapkan data kriteria dengan informasi lengkap
            $criteriaData = [];
            foreach ($this->criteria as $index => $criterion) {
                $criteriaData[] = [
                    'id' => $criterion['id'],
                    'name' => $criterion['name'],
                    'type' => $criterion['type'],
                    'weight' => $criterion['weight']
                ];
            }

            // Siapkan data nilai keputusan (matriks keputusan)
            $decisionMatrix = [];
            foreach ($this->alternatif as $alternative) {
                $row = [
                    'id' => $alternative['id'],
                    'name' => $alternative['name'],
                    'values' => []
                ];

                for ($i = 0; $i < count($alternative['values']); $i++) {
                    $row['values'][$this->criteria[$i]['id']] = $alternative['values'][$i];
                }

                $decisionMatrix[] = $row;
            }

            // Siapkan data perhitungan preferensi antar alternatif (untuk setiap kriteria)
            $criteriaPreferences = [];
            $n = count($this->alternatif);

            foreach ($this->criteria as $criteriaIndex => $criterion) {
                $criteriaPreferences[$criterion['id']] = [];

                for ($i = 0; $i < $n; $i++) {
                    $a = $this->alternatif[$i];

                    for ($j = 0; $j < $n; $j++) {
                        $b = $this->alternatif[$j];

                        if ($i === $j) {
                            $preference = 0;
                        } else {
                            $valA = $a['values'][$criteriaIndex];
                            $valB = $b['values'][$criteriaIndex];

                            $difference = $valA - $valB;

                            if ($criterion['type'] === 'cost') {
                                $difference = -$difference;
                            }

                            $preference = $this->calculateSinglePreference($difference, $criterion['preference_function']);
                        }

                        $criteriaPreferences[$criterion['id']][$a['id']][$b['id']] = $preference;
                    }
                }
            }

            // Siapkan data indeks preferensi multikriteria (Q matrix - weighted preferences)
            $weightedPreferences = [];
            foreach ($this->criteria as $criteriaIndex => $criterion) {
                $weightedPreferences[$criterion['id']] = [];

                foreach ($criteriaPreferences[$criterion['id']] as $aId => $preferences) {
                    foreach ($preferences as $bId => $preference) {
                        $weightedPreferences[$criterion['id']][$aId][$bId] = $preference * $criterion['weight'];
                    }
                }
            }

            // Siapkan data untuk view
            $data = [
                'criteria' => $criteriaData,
                'alternatif' => $this->alternatif,
                'decision_matrix' => $decisionMatrix,
                'criteria_preferences' => $criteriaPreferences,
                'weighted_preferences' => $weightedPreferences,
                'preference_indices' => $preferenceIndices,
                'leaving_flow' => $leavingFlow,
                'entering_flow' => $enteringFlow,
                'results' => $results,
                'weights' => $this->getWeights()
            ];

            return view('mahasiswa.laporan', compact('breadcrumb', 'data'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghitung laporan: ' . $e->getMessage());
        }
    }

    // Fungsi untuk menghitung skor bidang lomba
    private function getBidangScore($lomba, $preferensiBidang, $totalCountBidangLomba): float
    {
        if ($totalCountBidangLomba === 0) return 0;

        $maxScore = 5.0;
        $step = $maxScore / $totalCountBidangLomba;

        foreach ($lomba->kategori as $kategori) {
            foreach ($preferensiBidang as $prioritas => $nilaiPreferensi) {
                if (strcasecmp($kategori->nama_kategori, $nilaiPreferensi) === 0) {
                    return $maxScore - $step * ($prioritas - 1);
                }
            }
        }

        return 0;
    }


    // Fungsi untuk menghitung skor tingkat lomba
    private function getTingkatScore($lomba, $preferensiTingkat, $totalCountTingkatLomba): float
    {
        if ($totalCountTingkatLomba === 0) return 0;

        foreach ($preferensiTingkat as $prioritas => $nilaiPreferensi) {
            if (strcasecmp($lomba->tingkat_lomba->nama_tingkat, $nilaiPreferensi) == 0) {
                $maxScore = 5.0;
                $step = $maxScore / $totalCountTingkatLomba;
                return $maxScore - ($step * ($prioritas - 1));
            }
        }

        return 0;
    }

    // Fungsi untuk menghitung skor reputasi penyelenggara
    private function getReputasiPenyelenggaraScore($lomba, $preferensiReputasiPenyelenggara, $totalCountReputasiPenyelenggara): float
    {
        if ($totalCountReputasiPenyelenggara === 0) return 0;

        $maxScore = 5.0;
        $step = $maxScore / $totalCountReputasiPenyelenggara;

        foreach ($preferensiReputasiPenyelenggara as $prioritas => $nilaiPreferensi) {
            if (strcasecmp($lomba->jenis_penyelenggara_lomba, $nilaiPreferensi) === 0) {
                return $maxScore - $step * ($prioritas - 1);
            }
        }

        return 0;
    }

    // Fungsi untuk menghitung skor deadline
    private function getDeadlineScore($lomba): float
    {
        $hariTersisa = Carbon::today()->diffInDays(Carbon::parse($lomba->akhir_registrasi_lomba)->startOfDay(), false);

        if ($hariTersisa <= 0) return 0.0;
        elseif ($hariTersisa <= 8) return 1.0;
        elseif ($hariTersisa <= 15) return 2.0;
        elseif ($hariTersisa <= 22) return 3.0;
        elseif ($hariTersisa <= 29) return 4.0;
        else return 5.0;
    }

    // Fungsi untuk menghitung skor lokasi
    private function getLokasiScore($lomba, $preferensiLokasi, $totalCountLokasi): float
    {
        if ($totalCountLokasi === 0) return 0;
        $maxScore = 5.0;
        $step = $maxScore / $totalCountLokasi;

        foreach ($preferensiLokasi as $prioritas => $nilaiPreferensi) {
            if (strcasecmp($lomba->lokasi_lomba, $nilaiPreferensi) === 0) {
                return $maxScore - $step * ($prioritas - 1);
            }
        }

        return 0;
    }

    // Fungsi untuk menghitung skor biaya
    private function getBiayaScore($lomba, $preferensiBiaya, $totalCountBiaya): float
    {
        if ($totalCountBiaya === 0) return 0;
        $maxScore = 5.0;
        $step = $maxScore / $totalCountBiaya;

        foreach ($preferensiBiaya as $prioritas => $nilaiPreferensi) {
            if (strcasecmp($lomba->biaya_lomba, $nilaiPreferensi) === 0) {
                return $maxScore - $step * ($prioritas - 1);
            }
        }

        return 0;
    }

    /**
     * Informasi kriteria dengan bobot tetap sesuai nilai default
     */
    private array $criteria = [
        [
            'id' => 'C1',
            'name' => 'Kesesuaian Bidang Kompetisi',
            'type' => 'benefit',
            'weight' => 0.25,
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
            'weight' => 0.30,
            'dynamic_group' => 'lokasi',
            'preference_function' => 'usual'
        ],
        [
            'id' => 'C6',
            'name' => 'Biaya',
            'type' => 'benefit',
            'weight' => 0.10,
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
        // Untuk json ubah di loadpreferensi menjadi angka bukan auth->user_id, karena tidak bisa dibaca auth jika didalam PostMan
        $this->loadPreferensi();

        // Hitung indeks preferensi untuk setiap pasangan alternatif
        $preferenceIndices = $this->calculatePreferenceIndices();

        // Hitung outranking flows (leaving dan entering)
        [$leavingFlow, $enteringFlow] = $this->calculateOutrankingFlows($preferenceIndices);

        // Hitung net flow dan ranking
        $results = $this->calculateNetFlowAndRank($leavingFlow, $enteringFlow);

        // Siapkan data untuk response
        $response = [
            'message' => 'Rekomendasi lomba berhasil dihitung',
            'alternatif' => $this->alternatif,
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

    // Untuk rekomendasi lomba tiap mahasiswa
    /**
     * Load preferensi dari user tertentu dan bangun data alternatif hanya dengan satu lomba baru
     *
     * @param int $user_id
     * @param LombaModel $lombaBaru
     */
    public function loadPreferensiByUser(int $user_id, LombaModel $lombaBaru): void
    {
        // Reset alternatif sebelumnya
        $this->alternatif = [];

        // Ambil semua preferensi user berdasarkan user_id
        $preferensiAll = PreferensiUserModel::where('user_id', $user_id)
            ->orderBy('prioritas')
            ->get();

        if ($preferensiAll->isEmpty()) {
            return; // User tidak punya preferensi
        }

        // Pisahkan preferensi per kriteria
        $preferensiBidang = $preferensiAll->where('kriteria', 'bidang')->pluck('nilai', 'prioritas');
        $totalCountBidangLomba = $preferensiBidang->count();

        $preferensiTingkat = $preferensiAll->where('kriteria', 'tingkat')->pluck('nilai', 'prioritas');
        $totalCountTingkatLomba = TingkatLombaModel::count();

        $preferensiReputasiPenyelenggara = $preferensiAll->where('kriteria', 'penyelenggara')->pluck('nilai', 'prioritas');
        $totalCountReputasiPenyelenggara = $preferensiReputasiPenyelenggara->count();

        $preferensiLokasi = $preferensiAll->where('kriteria', 'lokasi')->pluck('nilai', 'prioritas');
        $totalCountLokasi = $preferensiLokasi->count();

        $preferensiBiaya = $preferensiAll->where('kriteria', 'biaya')->pluck('nilai', 'prioritas');
        $totalCountBiaya = $preferensiBiaya->count();

        // Hanya tambahkan lomba baru sebagai satu-satunya alternatif
        $this->alternatif[] = [
            'id' => $lombaBaru->lomba_id,
            'name' => $lombaBaru->nama_lomba,
            'values' => [
                $this->getBidangScore($lombaBaru, $preferensiBidang, $totalCountBidangLomba),
                $this->getTingkatScore($lombaBaru, $preferensiTingkat, $totalCountTingkatLomba),
                $this->getReputasiPenyelenggaraScore($lombaBaru, $preferensiReputasiPenyelenggara, $totalCountReputasiPenyelenggara),
                $this->getDeadlineScore($lombaBaru),
                $this->getLokasiScore($lombaBaru, $preferensiLokasi, $totalCountLokasi),
                $this->getBiayaScore($lombaBaru, $preferensiBiaya, $totalCountBiaya),
            ],
        ];
    }

    /**
     * Hitung net flow untuk satu lomba baru berdasarkan preferensi user tertentu
     *
     * @param LombaModel $lombaBaru
     * @param int $user_id
     * @return array
     */
    public function calculateNetFlowForSingleLomba(LombaModel $lombaBaru, int $user_id): array
    {
        try {
            // Muat preferensi user dan bangun data alternatif (hanya satu lomba)
            $this->loadPreferensiByUser($user_id, $lombaBaru);

            if (empty($this->alternatif)) {
                return [
                    'user_id' => $user_id,
                    'meets_threshold' => false,
                    'reason' => 'User tidak memiliki preferensi atau lomba tidak sesuai'
                ];
            }

            // Karena hanya ada satu alternatif, tidak ada outranking ke alternatif lain
            // Maka net_flow = leaving_flow - entering_flow = 0 - 0 = 0
            // Namun kita bisa buat logika khusus jika hanya ada satu lomba

            $alternative = $this->alternatif[0];
            $netFlow = 0.0;

            // Contoh logika custom: jika skor keseluruhan > threshold
            $totalScore = array_sum($alternative['values']);
            $maxPossibleScore = count($this->criteria) * 5;
            $scoreRatio = $totalScore / $maxPossibleScore;

            // Threshold: jika skor rata-rata > 3 → net flow asumsi = scoreRatio - 0.5
            $netFlow = $scoreRatio - 0.5;

            $meetsThreshold = $netFlow >= 0.2;

            return [
                'user_id' => $user_id,
                'net_flow' => round($netFlow, 4),
                'meets_threshold' => $meetsThreshold
            ];
        } catch (\Exception $e) {
            return [
                'user_id' => $user_id,
                'meets_threshold' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

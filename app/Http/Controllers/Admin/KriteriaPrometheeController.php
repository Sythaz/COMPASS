<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KriteriaPrometheeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Master Data',
            'list' => ['Master Data', 'Bobot Rekomendasi']
        ];

        try {
            // Ambil data kriteria dari database
            // Jika tidak ada kriteria, buat kriteria default
            $criteria = DB::table('t_kriteria_promethee')
                ->select('kode_kriteria', 'nama_kriteria', 'type', 'bobot')
                ->orderBy('kode_kriteria')
                ->get();

            $criteriaArray = $criteria->mapWithKeys(function ($criterion) {
                return [
                    $criterion->kode_kriteria => [
                        'nama' => $criterion->nama_kriteria,
                        'type' => $criterion->type,
                        'bobot' => $criterion->bobot * 100
                    ]
                ];
            })->toArray();

            return view('admin.master-data.bobot-rekomendasi.index', compact('breadcrumb', 'criteriaArray'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'weights' => 'required|array',
                'weights.*' => 'required|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            $weights = $request->input('weights');

            // Periksa apakah total bobot sama dengan 100
            $totalWeight = array_sum($weights);
            if ($totalWeight != 100) {
                return response()->json([
                    'success' => false,
                    'message' => "Total bobot harus 100%. Saat ini: {$totalWeight}%"
                ], 422);
            }

            // Update bobot di database
            DB::beginTransaction();

            foreach ($weights as $kode => $bobot) {
                DB::table('t_kriteria_promethee')
                    ->where('kode_kriteria', $kode)
                    ->update(['bobot' => $bobot / 100]); // Konversi persentase ke desimal
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bobot kriteria berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data'
            ], 500);
        }
    }

    // Reset bobot ke nilai default
    public function reset()
    {
        try {
            DB::beginTransaction();

            // Fetch default weights from the database
            $defaultWeights = DB::table('t_kriteria_promethee')
                ->select('kode_kriteria', 'bobot')
                ->get()
                ->pluck('bobot', 'kode_kriteria')
                ->toArray();

            // Update bobot di database
            foreach ($defaultWeights as $kode => $bobot) {
                DB::table('t_kriteria_promethee')
                    ->where('kode_kriteria', $kode)
                    ->update(['bobot' => $bobot]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bobot kriteria berhasil direset ke nilai default!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mereset bobot kriteria'
            ], 500);
        }
    }

    // Dapatkan bobot saat ini (untuk permintaan AJAX)
    public function ambilBobot()
    {
        try {
            $criteria = DB::table('t_kriteria_promethee')
                ->select('kode_kriteria', 'nama_kriteria', 'bobot')
                ->orderBy('kode_kriteria')
                ->get();

            $weights = [];
            foreach ($criteria as $criterion) {
                $weights[$criterion->kode_kriteria] = [
                    'nama' => $criterion->nama_kriteria,
                    'bobot' => $criterion->bobot * 100
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $weights
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    // Buat kriteria default jika belum ada
    private function createDefaultCriteria()
    {
        $criteria = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Kesesuaian Bidang Kompetisi',
                'type' => 'benefit',
                'bobot' => 0.25,
                'dynamic_group' => 'bidang',
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Tingkat Lomba',
                'type' => 'benefit',
                'bobot' => 0.15,
                'dynamic_group' => null,
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Reputasi Penyelenggara',
                'type' => 'benefit',
                'bobot' => 0.10,
                'dynamic_group' => null,
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Deadline Pendaftaran',
                'type' => 'benefit',
                'bobot' => 0.10,
                'dynamic_group' => null,
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C5',
                'nama_kriteria' => 'Lokasi',
                'type' => 'cost',
                'bobot' => 0.30,
                'dynamic_group' => 'lokasi',
                'preference_function' => 'usual'
            ],
            [
                'kode_kriteria' => 'C6',
                'nama_kriteria' => 'Biaya',
                'type' => 'benefit',
                'bobot' => 0.10,
                'dynamic_group' => 'biaya',
                'preference_function' => 'usual'
            ]
        ];

        DB::table('t_kriteria_promethee')->upsert($criteria, ['kode_kriteria'], ['nama_kriteria', 'type', 'bobot', 'dynamic_group', 'preference_function']);
    }
}

<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\PrometheeRekomendasiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\LombaModel;
use App\Models\KategoriModel;
use App\Models\TingkatLombaModel;
use App\Models\KategoriLombaModel;
use App\Models\PreferensiUserModel;

class DataLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Riwayat Pengajuan Lomba']
        ];

        return view('dosen.data-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $dosenId = auth()->user()->user_id;

        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->where('pengusul_id', $dosenId)
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            ->addColumn('nama_lomba', fn ($row) => $row->nama_lomba ?? '-')
            ->addColumn('kategori', fn ($row) => $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui')
            ->addColumn('tingkat_lomba', fn ($row) => $row->tingkat_lomba->nama_tingkat ?? '-')
            ->addColumn('awal_registrasi_lomba', fn ($row) => date('d M Y', strtotime($row->awal_registrasi_lomba)))
            ->addColumn('akhir_registrasi_lomba', fn ($row) => date('d M Y', strtotime($row->akhir_registrasi_lomba)))
            ->addColumn('status_verifikasi', function ($row) {
                $statusLomba = $row->status_verifikasi;
                switch ($statusLomba) {
                    case 'Terverifikasi':
                        $badge = '<span class="label label-success">Terverifikasi</span>';
                        break;
                    case 'Valid':
                        $badge = '<span class="label label-info">Valid</span>';
                        break;
                    case 'Menunggu':
                        $badge = '<span class="label label-warning">Menunggu</span>';
                        break;
                    case 'Ditolak':
                        $badge = '<span class="label label-danger">Ditolak</span>';
                        break;
                    default:
                        $badge = '<span class="label label-secondary">Tidak Diketahui</span>';
                        break;
                }
                return $badge;
            })
            ->addColumn('aksi', function ($row) {
                return '<div class="text-center">
                    <button style="white-space:nowrap" onclick="modalAction(\'' . route('info-lomba.show', $row->lomba_id) . '\')" class="btn btn-info btn-sm">Detail</button>
                </div>';
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    public function create()
    {
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();
        $dataLomba = LombaModel::where('status_lomba', 'Aktif')->get();

        return view('dosen.data-lomba.create', compact('dataLomba', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirimkan
        $validator = Validator::make($request->all(), [
            'nama_lomba' => 'required',
            'deskripsi_lomba' => 'required',
            'kategori_id' => 'required|array|exists:t_kategori,kategori_id',
            'tingkat_lomba_id' => 'required|exists:t_tingkat_lomba,tingkat_lomba_id',
            'penyelenggara_lomba' => 'required',
            'awal_registrasi_lomba' => 'required|date',
            'akhir_registrasi_lomba' => 'required|date|after_or_equal:awal_registrasi_lomba',
            'link_pendaftaran_lomba' => 'required|url',
            'img_lomba' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_verifikasi' => 'required|in:Terverifikasi,Menunggu,Ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('img_lomba', 'kategori_id');

            // Tambahkan pengusul_id dengan pengusul yang sedang login
            $data['pengusul_id'] = auth()->user()->user_id;

            // Simpan data ke database terlebih dahulu
            $lomba = LombaModel::create($data);

            // Buat kategori lomba baru
            foreach ($request->kategori_id as $kategoriId) {
                KategoriLombaModel::create([
                    'lomba_id' => $lomba->lomba_id,
                    'kategori_id' => $kategoriId,
                ]);
            }

            // Jika ada gambar, upload ke storage
            if ($request->hasFile('img_lomba')) {
                $file = $request->file('img_lomba');
                $filename = $lomba->lomba_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/img/lomba', $filename); // Simpan ke storage

                // Update kolom img_lomba di database
                $lomba->update(['img_lomba' => $filename]);
            }

            $results = [];
            if ($request->input('status_verifikasi') === 'Terverifikasi') {
                // Ambil semua user_id yang sudah mengisi preferensi
                $userIds = PreferensiUserModel::distinct('user_id')->pluck('user_id');

                $results = [];
                foreach ($userIds as $userId) {
                    $prometheeController = new PrometheeRekomendasiController();
                    $result = $prometheeController->calculateNetFlowForSingleLomba($lomba, $userId);
                    $results[] = $result;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'threshold_results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. ' . $e->getMessage()
            ]);
        }
    }

}

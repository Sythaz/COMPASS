<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PrometheeRekomendasiController;
use Illuminate\Http\Request;
use App\Models\LombaModel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\PendaftaranLombaModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\KategoriModel;
use App\Models\TingkatLombaModel;
use App\Models\KategoriLombaModel;
use App\Models\PreferensiUserModel;
use Illuminate\Support\Facades\Log;

class LombaMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Informasi Lomba',
            'list' => ['Informasi Lomba', 'Detail Lomba']
        ];

        $dataLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->select([
                'lomba_id',
                'nama_lomba',
                'penyelenggara_lomba',
                'tingkat_lomba_id',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'lokasi_lomba',
                'tipe_lomba',
            ])
            ->where('status_lomba', 'Aktif')
            ->whereIn('status_verifikasi', ['Terverifikasi'])
            ->where('akhir_registrasi_lomba', '>=', now())
            ->get();

        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        // Cek apakah user punya preferensi
        $cekUserPreferensi = PreferensiUserModel::where('user_id', auth()->id())->exists();

        $rekomendasiLomba = collect(); // Default kosong

        if ($cekUserPreferensi) {
            try {
                $promethee = new PrometheeRekomendasiController();
                $response = $promethee->calculate(request()); // Mengembalikan JsonResponse
                $responseData = $response->getData(); // Ambil data PHP object

                if (!empty($responseData->results)) {
                    // Ambil lomba_id yang sudah diurutkan
                    $lombaIds = collect($responseData->results)->pluck('id')->toArray();

                    // Ambil data lomba dari database sesuai urutan rekomendasi
                    $rekomendasiLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
                        ->whereIn('lomba_id', $lombaIds)
                        ->orderByRaw("FIELD(lomba_id, " . implode(',', $lombaIds) . ")")
                        ->get();
                }
            } catch (\Exception $e) {
                Log::error('Gagal memuat rekomendasi lomba: ' . $e->getMessage());
            }
        }

        // Data tambahan untuk filter
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('mahasiswa.informasi-lomba.index', compact(
            'breadcrumb',
            'dataLomba',
            'daftarKategori',
            'daftarTingkatLomba',
            'rekomendasiLomba',
            'cekUserPreferensi'
        ));
    }
    // Tambahkan method private untuk membuat badge status
    private function getStatusBadge($status_verifikasi)
    {
        $status = strtolower($status_verifikasi ?? '');

        switch ($status) {
            case 'terverifikasi':
                return '<span class="label label-success">' . e(ucwords($status)) . '</span>';
            case 'valid':
                return '<span class="label label-info">' . e(ucwords($status)) . '</span>';
            case 'menunggu':
                return '<span class="label label-warning">' . e(ucwords($status)) . '</span>';
            case 'ditolak':
                return '<span class="label label-danger">' . e(ucwords($status)) . '</span>';
            default:
                return '<span class="label label-secondary">Tidak Diketahui</span>';
        }
    }

    public function showAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        $pengusul = $lomba->pengusul_id;
        $rolePengusul = UsersModel::where('user_id', $pengusul)->first()->role;
        $tipeLomba = $lomba->tipe_lomba;

        $namaPengusul = '';
        switch ($rolePengusul) {
            case 'Admin':
                $namaPengusul = AdminModel::where('user_id', $pengusul)->first()->nama_admin;
                break;
            case 'Dosen':
                $namaPengusul = DosenModel::where('user_id', $pengusul)->first()->nama_dosen;
                break;
            case 'Mahasiswa':
                $namaPengusul = MahasiswaModel::where('user_id', $pengusul)->first()->nama_mahasiswa;
                break;
            default:
                $namaPengusul = 'Pengusul tidak diketahui';
                break;
        }

        $badgeStatus = $this->getStatusBadge($lomba->status_verifikasi);

        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Detail Lomba']
        ];

        return view('mahasiswa.informasi-lomba.show', compact('lomba', 'namaPengusul', 'breadcrumb', 'badgeStatus', 'tipeLomba'));
    }

    public function form_daftar($id)
    {
        $lomba = LombaModel::with(['kategori', 'tingkat_lomba'])->findOrFail($id);

        $daftarMahasiswa = MahasiswaModel::all();

        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Daftar Lomba']
        ];

        return view('mahasiswa.informasi-lomba.daftar', compact('lomba', 'daftarMahasiswa', 'breadcrumb'));
    }

    public function store_pendaftaran(Request $request)
    {
        $request->validate([
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'mahasiswa_id' => 'required|array|min:1',
            'mahasiswa_id.*' => 'required|exists:t_mahasiswa,mahasiswa_id',
            'bukti_pendaftaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $anggotaTim = collect($request->mahasiswa_id)->unique()->values()->toArray();

        // Cek apakah user login ada dalam list anggota (ketua atau anggota)
        $ketuaId = Auth::user()->mahasiswa->mahasiswa_id;

        if (!in_array($ketuaId, $anggotaTim)) {
            return response()->json([
                'message' => 'Anda harus menjadi salah satu anggota atau ketua tim.'
            ], 422);
        }

        $adaYangSudahDaftar = PendaftaranLombaModel::where('lomba_id', $request->lomba_id)
            ->where(function ($query) use ($anggotaTim) {
                $query->whereIn('t_pendaftaran_lomba.mahasiswa_id', $anggotaTim)
                    ->orWhereHas('anggota', function ($q) use ($anggotaTim) {
                        $q->whereIn('t_pendaftaran_mahasiswa.mahasiswa_id', $anggotaTim);
                    });
            })
            ->exists();

        if ($adaYangSudahDaftar) {
            return response()->json([
                'message' => 'Anda sudah pernah mendaftar lomba ini.'
            ], 422);
        }

        $ketuaId = Auth::user()->mahasiswa->mahasiswa_id;

        $pendaftaran = new PendaftaranLombaModel();
        $pendaftaran->mahasiswa_id = $ketuaId;
        $pendaftaran->lomba_id = $request->lomba_id;

        if ($request->hasFile('bukti_pendaftaran')) {
            $file = $request->file('bukti_pendaftaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $pendaftaran->bukti_pendaftaran = $filename;
        } else {
            $pendaftaran->bukti_pendaftaran = 'default-file.jpg';
        }

        $pendaftaran->save();

        $pendaftaran->anggota()->sync($anggotaTim);

        return response()->json([
            'message' => 'Pendaftaran lomba berhasil disimpan!'
        ], 200);
    }

    public function create()
    {
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();
        $dataLomba = LombaModel::where('status_lomba', 'Aktif')->get();

        return view('mahasiswa.informasi-lomba.create', compact('dataLomba', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function store_lomba(Request $request)
    {
        // Normalisasi case tipe_lomba ke kapital awal
        $request->merge([
            'tipe_lomba' => ucfirst(strtolower($request->tipe_lomba)),
        ]);

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

    public function history()
    {
        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Riwayat Pengajuan Lomba']
        ];

        return view('mahasiswa.informasi-lomba.history', compact('breadcrumb'));
    }

    public function list_history(Request $request)
    {
        $mahasiswaId = auth()->user()->user_id;

        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->where('pengusul_id', $mahasiswaId)
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            ->addColumn('nama_lomba', fn ($row) => $row->nama_lomba ?? '-')
            ->addColumn('kategori', fn ($row) => $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui')
            ->addColumn('tingkat_lomba', fn ($row) => $row->tingkat_lomba->nama_tingkat ?? '-')
            ->addColumn('awal_registrasi_lomba', fn ($row) => date('d M Y', strtotime($row->awal_registrasi_lomba)))
            ->addColumn('akhir_registrasi_lomba', fn ($row) => date('d M Y', strtotime($row->akhir_registrasi_lomba)))
            ->addColumn('status_verifikasi', function ($row) {
                return $this->getStatusBadge($row->status_verifikasi);
            })

            ->addColumn('aksi', function ($row) {
                return '<div class="text-center">
                    <button style="white-space:nowrap" onclick="modalAction(\'' . route('informasi-lomba.show', $row->lomba_id) . '\')" class="btn btn-info btn-sm">Detail</button>
                </div>';
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    private function getStatusPendaftaran(?string $status_pendaftaran)
    {
        $status = strtolower(trim($status_pendaftaran ?? ''));

        switch ($status) {
            case 'terverifikasi':
                return '<span class="label label-success">' . e(ucwords($status)) . '</span>';
            case 'menunggu':
                return '<span class="label label-warning">' . e(ucwords($status)) . '</span>';
            case 'ditolak':
                return '<span class="label label-danger">' . e(ucwords($status)) . '</span>';
            default:
                return '<span class="label label-secondary">Tidak Diketahui</span>';
        }
    }

    public function riwayat_pendaftaran()
    {
        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Riwayat Pendaftaran']
        ];

        return view('mahasiswa.informasi-lomba.pendaftaran', compact('breadcrumb'));
    }

    public function list_pendaftaran(Request $request)
    {
        $mahasiswaId = Auth::user()->mahasiswa->mahasiswa_id;

        $pendaftaran = PendaftaranLombaModel::with(['lomba.kategori', 'lomba.tingkat_lomba'])
            ->where('mahasiswa_id', $mahasiswaId);

        return DataTables::of($pendaftaran)
            ->addIndexColumn()
            ->addColumn('nama_lomba', fn ($row) => $row->lomba->nama_lomba ?? '-')
            ->addColumn('tingkat_lomba', fn ($row) => $row->lomba->tingkat_lomba->nama_tingkat ?? '-')
            ->addColumn('kategori', function ($row) {
                // kalau relasi kategori many-to-many, pluck dan implode, kalau one-to-many bisa langsung akses
                return $row->lomba->kategori ? ($row->lomba->kategori->pluck('nama_kategori')->implode(', ') ?: '-') : '-';
            })

            ->addColumn('tipe_lomba', fn ($row) => $row->lomba->tipe_lomba ?? '-')

            ->addColumn('status', function ($row) {
                return $this->getStatusPendaftaran($row->status_pendaftaran);
            })

            ->addColumn('tanggal', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d M Y') : '-';
            })

            ->addColumn('aksi', function ($row) {
                // gunakan pendaftaran_id untuk detail
                return '<button style="white-space:nowrap" onclick="modalAction(\'' . route('mahasiswa.informasi-lomba.detail-pendaftaran', $row->pendaftaran_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
            })

            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    public function detail_pendaftaran($id)
    {
        $pendaftaran = PendaftaranLombaModel::with(['lomba', 'mahasiswa', 'anggota'])
            ->findOrFail($id);

        return view('mahasiswa.informasi-lomba.riwayat-pendaftaran', compact('pendaftaran'));
    }
}

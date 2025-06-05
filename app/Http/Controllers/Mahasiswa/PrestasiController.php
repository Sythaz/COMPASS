<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TingkatLombaModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\KategoriModel;
use App\Models\DosenModel;
use App\Models\PeriodeModel;
use App\Models\PrestasiModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;



class PrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Prestasi Mahasiswa', 'Prestasi']
        ];

        return view('mahasiswa.prestasi.index', compact('breadcrumb'));
    }

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
                return '<span class="label label-default">Tidak Diketahui</span>';
        }
    }

    public function list(Request $request)
    {
        $mahasiswaId = Auth::user()->mahasiswa->mahasiswa_id;

        // Ambil semua prestasi yang berkaitan dengan mahasiswa (baik sebagai ketua maupun anggota)
        $data = PrestasiModel::with(['lomba', 'dosen'])
            ->whereHas('mahasiswa', function ($query) use ($mahasiswaId) {
                $query->where('t_prestasi_mahasiswa.mahasiswa_id', $mahasiswaId);
            })
            ->select('t_prestasi.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', function ($row) {
                return $row->lomba->nama_lomba ?? $row->lomba_lainnya ?? '-';
            })
            ->addColumn('dosen_pembimbing', function ($row) {
                return $row->dosen->nama_dosen ?? '<span class="text-muted">Belum ada</span>';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status);
            })
            ->addColumn('aksi', function ($row) {
                $url = route('mhs.prestasi.showAjax', $row->prestasi_id);
                return '<button data-url="' . $url . '" class="btn btn-info btn-sm btn-detail-prestasi">Detail</button>';
            })

            ->rawColumns(['dosen_pembimbing', 'status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $prestasi = PrestasiModel::with(['mahasiswa', 'dosen', 'kategori', 'tingkat_lomba', 'periode'])->findOrFail($id);

        $statusBadge = $this->getStatusBadge($prestasi->status_verifikasi);

        return view('mahasiswa.prestasi.show', compact('prestasi', 'statusBadge'))->render();
    }

    public function create_prestasi()
    {
        $daftarLomba = LombaModel::with('tingkat_lomba')
            ->where('status_lomba', 'Aktif')
            ->get();
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarDosen = DosenModel::where('status', 'Aktif')->get();
        $daftarPeriode = PeriodeModel::all();
        $daftarTingkatLomba = TingkatLombaModel::all(); // Ambil data tingkat lomba dari DB

        return view('mahasiswa.prestasi.create', compact(
            'daftarMahasiswa',
            'daftarLomba',
            'daftarKategori',
            'daftarDosen',
            'daftarPeriode',
            'daftarTingkatLomba'
        ));
    }

    public function store(Request $request)
    {
        // Manipulasi data sebelum validasi
        if ($request->input('lomba_id') === 'lainnya') {
            $request->merge(['lomba_id' => null]);
        }
        // Validasi input utama dengan aturan required_without agar salah satu wajib diisi
        $validated = $request->validate([
            'lomba_id' => 'nullable|required_without:lomba_lainnya|exists:t_lomba,lomba_id',
            'lomba_lainnya' => 'nullable|required_without:lomba_id|string|max:255',
            'dosen_id' => 'nullable|exists:t_dosen,dosen_id',
            'kategori_id' => 'required|exists:t_kategori,kategori_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'tanggal_prestasi' => 'required|date',
            'juara_prestasi' => 'required|string|max:255',
            'jenis_prestasi' => 'nullable|string|max:255',
            'tingkat_lomba_id' => 'nullable|exists:t_tingkat_lomba,tingkat_lomba_id',
        ]);

        // Jika user pilih lomba dari daftar (lomba_id), isi otomatis tingkat_lomba_id dan jenis_prestasi
        if (!empty($validated['lomba_id'])) {
            $lomba = LombaModel::with('tingkat_lomba')->find($validated['lomba_id']);
            if ($lomba) {
                // Isi tingkat lomba jika kosong
                if (empty($validated['tingkat_lomba_id']) && $lomba->tingkat_lomba) {
                    $validated['tingkat_lomba_id'] = $lomba->tingkat_lomba->tingkat_lomba_id;
                }
                // Isi jenis prestasi jika kosong
                if (empty($validated['jenis_prestasi'])) {
                    $validated['jenis_prestasi'] = $lomba->tipe_lomba;
                }
            }

            // Kosongkan lomba_lainnya supaya tidak duplikasi data
            $validated['lomba_lainnya'] = null;
        } else {
            // Jika input lomba_lainnya manual, pastikan lomba_id di-set null
            $validated['lomba_id'] = null;
        }

        // Simpan data ke tabel prestasi
        $prestasi = PrestasiModel::create($validated);

        // Jika ada input mahasiswa_id (array), simpan relasi ke pivot dengan peran Ketua dan Anggota
        if ($request->filled('mahasiswa_id')) {
            $mahasiswaIds = $request->input('mahasiswa_id');
            $pivotData = [];
            foreach ($mahasiswaIds as $index => $id) {
                $pivotData[$id] = [
                    'peran' => $index === 0 ? 'Ketua' : 'Anggota'
                ];
            }
            $prestasi->mahasiswa()->sync($pivotData);
        }

        return redirect()->back()->with('success', 'Data prestasi berhasil disimpan.');
    }

    public function cekLombaDuplicate(Request $request)
    {
        $mahasiswaId = auth()->user()->mahasiswa->mahasiswa_id;
        $lombaId = $request->input('lomba_id');
        $lombaLainnya = $request->input('lomba_lainnya');

        if (!$lombaId && !$lombaLainnya) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lomba tidak boleh kosong.'
            ], 422);
        }

        $query = PrestasiModel::whereHas('mahasiswa', function ($query) use ($mahasiswaId) {
            $query->where('t_prestasi_mahasiswa.mahasiswa_id', $mahasiswaId);
        });

        if ($lombaId) {
            $query->where('lomba_id', $lombaId);
        } elseif ($lombaLainnya) {
            // Bisa kamu trim supaya tidak ada spasi ekstra, dan case insensitive
            $query->whereRaw('LOWER(lomba_lainnya) = ?', [strtolower(trim($lombaLainnya))]);
        }

        $exists = $query->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah pernah submit lomba ini sebelumnya.'
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lomba belum pernah di-submit sebelumnya.'
        ], 200);
    }

}

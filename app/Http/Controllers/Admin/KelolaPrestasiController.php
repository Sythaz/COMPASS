<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\KategoriModel;
use App\Models\LaporanPrestasiModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;
use App\Models\PrestasiModel;
use App\Models\TingkatLombaModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KelolaPrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Prestasi',
            'list' => ['Manajemen Prestasi', 'Kelola Prestasi']
        ];
        return view('admin.manajemen-prestasi.kelola-prestasi.index', compact('breadcrumb'));
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
        $data = PrestasiModel::with(['lomba', 'dosen', 'mahasiswa'])
            ->select('t_prestasi.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', function ($row) {
                return $row->lomba->nama_lomba ?? $row->lomba_lainnya ?? '-';
            })
            ->addColumn('dosen_pembimbing', function ($row) {
                return $row->dosen->nama_dosen ?? '<span class="text-muted">Belum ada</span>';
            })
            ->addColumn('ketua_mahasiswa', function ($row) {
                $ketua = $row->mahasiswa->first(function ($m) {
                    return strtolower($m->pivot->peran) === 'ketua';
                });
                return $ketua->nama_mahasiswa ?? '<span class="text-muted">Belum ada</span>';
            })
            ->addColumn('jenis_prestasi', function ($row) {
                return $row->jenis_prestasi ?? '-';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status);
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center" style="gap: 0.5rem;">';
                $btn .= '<button onclick="modalAction(\'' . route('kelola-prestasi.showAjax', $row->prestasi_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('kelola-prestasi.editAjax', $row->prestasi_id) . '\')" class="btn btn-warning btn-sm">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . route('kelola-prestasi.deleteAjax', $row->prestasi_id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['dosen_pembimbing', 'status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $prestasi = PrestasiModel::with(['mahasiswa', 'dosen', 'kategori', 'tingkat_lomba', 'periode'])->findOrFail($id);

        $statusBadge = $this->getStatusBadge($prestasi->status_verifikasi);

        return view('admin.manajemen-prestasi.kelola-prestasi.show', compact('prestasi', 'statusBadge'))->render();
    }

    public function editAjax($id)
    {
        $prestasi = PrestasiModel::with('mahasiswa')->findOrFail($id);

        // Ambil daftar data pendukung, misal dosen, kategori, periode, lomba, dll
        $daftarDosen = DosenModel::all();
        $daftarKategori = KategoriModel::all();
        $daftarPeriode = PeriodeModel::all();
        $daftarLomba = LombaModel::all();
        $daftarTingkatLomba = TingkatLombaModel::all();
        $daftarMahasiswa = MahasiswaModel::all();

        // Data mahasiswa yang terkait (untuk form anggota tim)
        $anggotaTim = $prestasi->mahasiswa->map(function ($mhs) {
            return [
                'mahasiswa_id' => $mhs->pivot->mahasiswa_id,
                'nama' => $mhs->nama_mahasiswa,
                'peran' => $mhs->pivot->peran,
            ];
        });

        return view('admin.manajemen-prestasi.kelola-prestasi.edit', compact(
            'prestasi',
            'daftarDosen',
            'daftarKategori',
            'daftarPeriode',
            'daftarLomba',
            'daftarTingkatLomba',
            'anggotaTim',
            'daftarMahasiswa'
        ));
    }
    public function update(Request $request, $id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        if ($request->input('lomba_id') === 'lainnya') {
            $request->merge(['lomba_id' => null]);
        }

        // Validasi dengan enum dari DB (hardcode nilai enum di validasi)
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

            // Enum status_verifikasi, hardcode sesuai enum di DB
            'status_verifikasi' => 'required|string|in:Ditolak,Valid,Menunggu,Terverifikasi',

            // alasan_tolak boleh kosong
            'alasan_tolak' => 'nullable|string|max:1000',
        ]);

        if (!empty($validated['lomba_id'])) {
            $lomba = LombaModel::with('tingkat_lomba')->find($validated['lomba_id']);
            if ($lomba) {
                if (empty($validated['tingkat_lomba_id']) && $lomba->tingkat_lomba) {
                    $validated['tingkat_lomba_id'] = $lomba->tingkat_lomba->tingkat_lomba_id;
                }
                if (empty($validated['jenis_prestasi'])) {
                    $validated['jenis_prestasi'] = $lomba->tipe_lomba;
                }
            }
            $validated['lomba_lainnya'] = null;
        } else {
            $validated['lomba_id'] = null;
        }

        $prestasi->update($validated);

        if ($request->filled('mahasiswa_id')) {
            $mahasiswaIds = $request->input('mahasiswa_id');
            $pivotData = [];
            foreach ($mahasiswaIds as $index => $id) {
                $pivotData[$id] = [
                    'peran' => $index === 0 ? 'Ketua' : 'Anggota'
                ];
            }
            $prestasi->mahasiswa()->sync($pivotData);
        } else {
            $prestasi->mahasiswa()->detach();
        }
    }

    public function create()
    {
        $daftarLomba = LombaModel::with('tingkat_lomba')
            ->where('status_lomba', 'Aktif')
            ->get();
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarDosen = DosenModel::where('status', 'Aktif')->get();
        $daftarPeriode = PeriodeModel::all();
        $daftarTingkatLomba = TingkatLombaModel::all(); // Ambil data tingkat lomba dari DB

        return view('admin.manajemen-prestasi.kelola-prestasi.create', compact(
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

    public function deleteAjax($id)
    {
        $prestasi = PrestasiModel::with(['lomba', 'kategori', 'dosen', 'periode', 'tingkat_lomba', 'mahasiswa'])->findOrFail($id);

        return view('admin.manajemen-prestasi.kelola-prestasi.delete', compact('prestasi'));
    }

    public function destroy($id)
    {
        try {
            $laporanPrestasi = LaporanPrestasiModel::where('prestasi_id', $id)->first();
            if ($laporanPrestasi) {
                $laporanPrestasi->delete();
            }

            $prestasi = PrestasiModel::findOrFail($id);
            $prestasi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data prestasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

}

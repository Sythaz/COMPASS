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
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button onclick="modalAction(\'' . route('kelola-prestasi.showAjax', $row->prestasi_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
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
        $kelolaPrestasi = PrestasiModel::findOrFail($id);
        // Load data tambahan untuk dropdown di form edit
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarDosen = DosenModel::where('status', 'Aktif')->get();
        $daftarLomba = LombaModel::where('status_lomba', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarPeriode = PeriodeModel::all();

        return view('admin.manajemen-prestasi.kelola-prestasi.edit', compact('kelolaPrestasi', 'daftarMahasiswa', 'daftarLomba', 'daftarKategori', 'daftarDosen', 'daftarPeriode'));
    }

    public function deleteAjax($id)
    {
        $kelolaPrestasi = PrestasiModel::findOrFail($id);
        // Load data tambahan untuk dropdown di form edit
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarDosen = DosenModel::where('status', 'Aktif')->get();
        $daftarLomba = LombaModel::where('status_lomba', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarPeriode = PeriodeModel::all();

        return view('admin.manajemen-prestasi.kelola-prestasi.delete', compact('kelolaPrestasi', 'daftarMahasiswa', 'daftarDosen', 'daftarLomba', 'daftarKategori', 'daftarPeriode'));
    }

    public function create()
    {
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarLomba = LombaModel::where('status_lomba', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarDosen = DosenModel::where('status', 'Aktif')->get();
        $daftarPeriode = PeriodeModel::all();

        return view('admin.manajemen-prestasi.kelola-prestasi.create', compact(
            'daftarMahasiswa',
            'daftarLomba',
            'daftarKategori',
            'daftarDosen',
            'daftarPeriode'
        ));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:t_mahasiswa,mahasiswa_id',
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'dosen_id' => 'nullable|exists:t_dosen,dosen_id',
            'kategori_id' => 'required|exists:t_kategori,kategori_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'tanggal_prestasi' => 'required|date',
            'juara_prestasi' => 'required|string|max:50',
            'jenis_prestasi' => 'required|string',
            'img_kegiatan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_prestasi' => 'nullable|mimes:pdf|max:2048',
            'surat_tugas_prestasi' => 'nullable|mimes:pdf|max:2048',
            'status_verifikasi' => 'required|in:Terverifikasi,Valid,Menunggu,Ditolak',
        ]);

        try {
            // Simpan prestasi
            $prestasi = PrestasiModel::create($validated);

            // Upload file jika ada
            if ($request->hasFile('img_kegiatan')) {
                $originalFilename = $request->file('img_kegiatan')->getClientOriginalName();
                $filename = 'img-prestasi/' . $prestasi->prestasi_id . '-' . time() . '-' . $originalFilename;
                $request->file('img_kegiatan')->storeAs('public/prestasi/img-prestasi', $filename);
                $prestasi->update(['img_kegiatan' => $filename]);
            }

            if ($request->hasFile('bukti_prestasi')) {
                $filename = 'bukti_' . $prestasi->prestasi_id . '.pdf';
                $request->file('bukti_prestasi')->storeAs('public/prestasi/bukti', $filename);
                $prestasi->update(['bukti_prestasi' => $filename]);
            }

            if ($request->hasFile('surat_tugas_prestasi')) {
                $filename = 'surat_' . $prestasi->prestasi_id . '.pdf';
                $request->file('surat_tugas_prestasi')->storeAs('public/prestasi/surat', $filename);
                $prestasi->update(['surat_tugas_prestasi' => $filename]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data prestasi berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:t_mahasiswa,mahasiswa_id',
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'kategori_id' => 'required|exists:t_kategori,kategori_id',
            'jenis_prestasi' => 'required|string',
            'dosen_id' => 'required|exists:t_dosen,dosen_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'tanggal_prestasi' => 'required|date',
            'juara_prestasi' => 'required|string|max:50',
            'img_kegiatan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_prestasi' => 'nullable|mimes:pdf|max:2048',
            'surat_tugas_prestasi' => 'nullable|mimes:pdf|max:2048',
            'status_verifikasi' => 'required|in:Terverifikasi,Valid,Menunggu,Ditolak',
        ]);

        try {
            // Update data utama
            $prestasi->update($validated);

            // Upload file baru jika ada
            if ($request->hasFile('img_kegiatan')) {
                $filename = 'img_' . $prestasi->prestasi_id . '.' . $request->file('img_kegiatan')->getClientOriginalExtension();
                $request->file('img_kegiatan')->storeAs('public/prestasi/img', $filename);
                $prestasi->update(['img_kegiatan' => $filename]);
            }

            if ($request->hasFile('bukti_prestasi')) {
                $originalFilename = $request->file('bukti_prestasi')->getClientOriginalName();
                $filename = 'bukti_' . $prestasi->prestasi_id . '_' . time() . '_' . $originalFilename;
                $request->file('bukti_prestasi')->storeAs('public/prestasi/bukti', $filename);
                $prestasi->update(['bukti_prestasi' => $filename]);
            }

            if ($request->hasFile('surat_tugas_prestasi')) {
                $originalFilename = $request->file('surat_tugas_prestasi')->getClientOriginalName();
                $filename = 'surat_' . $prestasi->prestasi_id . '_' . time() . '_' . $originalFilename;
                $request->file('surat_tugas_prestasi')->storeAs('public/prestasi/surat', $filename);
                $prestasi->update(['surat_tugas_prestasi' => $filename]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data prestasi berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $laporanPrestasi = LaporanPrestasiModel::where('prestasi_id', $id)->first();
            $laporanPrestasi->delete();

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

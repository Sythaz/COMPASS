<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PrestasiModel;
use Illuminate\Support\Facades\Auth;

class KelolaBimbinganController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Bimbingan', 'Kelola Bimbingan']

        ];

        // Data untuk dropdown kategori dan tahun prestasi
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTahun = PrestasiModel::selectRaw('YEAR(tanggal_prestasi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('dosen.kelola-bimbingan.index', compact('breadcrumb', 'daftarKategori', 'daftarTahun'));
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
        // Ambil ID dosen dari user yang login
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            abort(403, 'Akses ditolak. Anda bukan dosen.');
        }

        $dosenId = $dosen->dosen_id;

        // Ambil semua prestasi yang dibimbing oleh dosen yang login
        $data = PrestasiModel::with(['lomba', 'dosen'])
            ->where('dosen_id', $dosenId)
            ->whereIn('status_verifikasi', ['Terverifikasi', 'Valid'])
            ->select('t_prestasi.*');

        if ($request->kategori) {
            $data->whereHas('kategori', function ($q) use ($request) {
                $q->where('t_kategori.kategori_id', $request->kategori);
            });
        }

        if ($request->tahun) {
            $data->whereBetween('tanggal_prestasi', [
                "{$request->tahun}-01-01",
                "{$request->tahun}-12-31"
            ]);
        }

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', function ($row) {
                return $row->lomba->nama_lomba ?? $row->lomba_lainnya ?? '-';
            })
            ->addColumn('nama_peserta', function ($row) {
                $output = '';
                foreach ($row->mahasiswa as $mhs) {
                    $output .= '<li>' . e($mhs->nama_mahasiswa) . ' (' . e($mhs->pivot->peran) . ')</li>';
                }
                return $output ?: '<span class="text-muted">Belum ada anggota</span>';
            })
            ->editColumn('tanggal_prestasi', function ($row) {
                return date('d M Y', strtotime($row->tanggal_prestasi));
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status); // Tetap gunakan method privat di controller
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button onclick="modalAction(\'' . route('dosen.kelola-bimbingan.showAjax', $row->prestasi_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['nama_peserta', 'status_verifikasi', 'aksi'])
            ->make(true);
    }


    public function showAjax($id)
    {
        $prestasi = PrestasiModel::with([
           'mahasiswa',
           'dosen',
           'kategori',
           'tingkat_lomba',
           'periode'
        ])->findOrFail($id);

        $statusBadge = $this->getStatusBadge($prestasi->status_verifikasi);

        return view('dosen.kelola-bimbingan.show', compact('prestasi', 'statusBadge'))->render();
    }
}

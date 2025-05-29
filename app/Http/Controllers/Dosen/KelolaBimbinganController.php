<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PrestasiModel;


class KelolaBimbinganController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Mahasiswa Bimbingan']
        ];

        return view('dosen.kelola-bimbingan.index', compact('breadcrumb'));
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            // Ambil dosen yang sedang login
            $dosen = auth()->user()->dosen;

            // Cek apakah dosen ditemukan
            if (!$dosen) {
                return response()->json(['message' => 'Dosen tidak ditemukan'], 403);
            }

            // Ambil data prestasi hanya milik mahasiswa yang dibimbing oleh dosen ini
            $data = PrestasiModel::with([
                'mahasiswa:mahasiswa_id,nim_mahasiswa,nama_mahasiswa',
                'lomba:lomba_id,nama_lomba',
                'kategori:kategori_id,nama_kategori',
                'periode:periode_id,semester_periode'
            ])
                ->select('prestasi_id', 'mahasiswa_id', 'lomba_id', 'kategori_id', 'dosen_id', 'periode_id', 'jenis_prestasi', 'tanggal_prestasi', 'juara_prestasi', 'status_verifikasi')
                ->where('dosen_id', $dosen->dosen_id)
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nim_mahasiswa', fn($row) => $row->mahasiswa?->nim_mahasiswa ?? '-')
                ->addColumn('nama_mahasiswa', fn($row) => $row->mahasiswa?->nama_mahasiswa ?? '-')
                ->addColumn('nama_lomba', fn($row) => $row->lomba?->nama_lomba ?? '-')
                ->addColumn('nama_kategori', fn($row) => $row->kategori?->nama_kategori ?? '-')
                ->addColumn('semester_periode', fn($row) => $row->periode?->semester_periode ?? '-')
                ->addColumn('status_verifikasi', function ($row) {
                    return match ($row->status_verifikasi) {
                        'Terverifikasi' => '<span class="badge badge-success">Terverifikasi</span>',
                        'Valid' => '<span class="badge badge-info">Valid</span>',
                        'Menunggu' => '<span class="badge badge-warning">Menunggu</span>',
                        'Ditolak' => '<span class="badge badge-danger">Ditolak</span>',
                        default => '<span class="badge badge-secondary">Tidak Diketahui</span>',
                    };
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<button onclick="modalAction(\'' . route('dosen.kelola-bimbingan.showAjax', $row->prestasi_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_verifikasi', 'aksi'])
                ->make(true);
        }
    }

    public function showAjax($id)
    {
        $kelolaPrestasi = PrestasiModel::with([
            'mahasiswa:mahasiswa_id,nim_mahasiswa,nama_mahasiswa',
            'lomba:lomba_id,nama_lomba',
            'kategori:kategori_id,nama_kategori',
            'dosen:dosen_id,nama_dosen',
            'periode:periode_id,semester_periode'
        ])->findOrFail($id);

        $statusBadge = match ($kelolaPrestasi->status_verifikasi) {
            'Terverifikasi' => '<span class="badge badge-success">Terverifikasi</span>',
            'Valid' => '<span class="badge badge-info">Valid</span>',
            'Menunggu' => '<span class="badge badge-warning">Menunggu</span>',
            'Ditolak' => '<span class="badge badge-danger">Ditolak</span>',
            default => '<span class="badge badge-secondary">Tidak Diketahui</span>',
        };

        return view('dosen.kelola-bimbingan.show', compact('kelolaPrestasi', 'statusBadge'));
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\KategoriModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;
use App\Models\PrestasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Prestasi',
            'list'  => ['Manajemen Prestasi', 'Verifikasi Prestasi']
        ];
        return view('admin.manajemen-prestasi.verifikasi-prestasi.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = PrestasiModel::with([
            'mahasiswa:mahasiswa_id,nim_mahasiswa,nama_mahasiswa',
            'lomba:lomba_id,nama_lomba',
            'kategori:kategori_id,nama_kategori',
            'dosen:dosen_id,nama_dosen',
            'periode:periode_id,semester_periode'
        ])
            ->select('prestasi_id', 'mahasiswa_id', 'lomba_id', 'kategori_id', 'dosen_id', 'periode_id', 'jenis_prestasi', 'tanggal_prestasi', 'juara_prestasi', 'status_verifikasi')
            ->whereIn('status_verifikasi', ['Menunggu', 'Valid'])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nim_mahasiswa', function ($row) {
                return $row->mahasiswa?->nim_mahasiswa ?? '-';
            })
            ->addColumn('nama_mahasiswa', function ($row) {
                return $row->mahasiswa?->nama_mahasiswa ?? '-';
            })
            ->addColumn('nama_lomba', function ($row) {
                return $row->lomba?->nama_lomba ?? '-';
            })
            ->addColumn('nama_kategori', function ($row) {
                return $row->kategori?->nama_kategori ?? '-';
            })
            ->addColumn('nama_dosen', function ($row) {
                return $row->dosen?->nama_dosen ?? '-';
            })
            ->addColumn('semester_periode', function ($row) {
                return $row->periode?->semester_periode ?? '-';
            })
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
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-prestasi/verifikasi-prestasi/' . $row->prestasi_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-prestasi/verifikasi-prestasi/' . $row->prestasi_id . '/terima_prestasi_ajax') . '\')" class="btn text-white btn-success btn-sm mx-2">Terima</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-prestasi/verifikasi-prestasi/' . $row->prestasi_id . '/tolak_prestasi_ajax') . '\')" class="btn btn-danger btn-sm">Tolak</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $prestasi = PrestasiModel::with([
            'mahasiswa:mahasiswa_id,nim_mahasiswa,nama_mahasiswa',
            'lomba:lomba_id,nama_lomba',
            'kategori:kategori_id,nama_kategori',
            'dosen:dosen_id,nama_dosen',
            'periode:periode_id,semester_periode'
        ])->findOrFail($id);

        return view('admin.manajemen-prestasi.verifikasi-prestasi.show', compact('prestasi'));
    }

    public function terimaPrestasiAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        return view('admin.manajemen-prestasi.verifikasi-prestasi.terima', compact('prestasi'));
    }

    public function tolakPrestasiAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        return view('admin.manajemen-prestasi.verifikasi-prestasi.tolak', compact('prestasi'));
    }

    public function terimaPrestasi($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        try {
            // Update status_verifikasi menjadi Terverifikasi
            $prestasi->update(['status_verifikasi' => 'Terverifikasi']);

            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil diterima dan diverifikasi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tolakPrestasi($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        try {
            // Update status_verifikasi menjadi Ditolak
            $prestasi->update(['status_verifikasi' => 'Ditolak']);

            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}

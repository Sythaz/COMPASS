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
            'list' => ['Manajemen Prestasi', 'Verifikasi Prestasi']
        ];
        return view('admin.manajemen-prestasi.verifikasi-prestasi.index', compact('breadcrumb'));
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
                $btn .= '<button onclick="modalAction(\'' . route('verifikasi-prestasi.showAjax', $row->prestasi_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
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

        return view('admin.manajemen-prestasi.verifikasi-prestasi.show', compact('prestasi', 'statusBadge'))->render();
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

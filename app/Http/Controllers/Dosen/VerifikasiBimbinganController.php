<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PrestasiModel;
use Illuminate\Support\Facades\Auth;

class VerifikasiBimbinganController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Mahasiswa Bimbingan']
        ];

        return view('dosen.verifikasi-bimbingan.index', compact('breadcrumb'));
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

        // Ambil semua prestasi yang dibimbing oleh dosen yang login dengan status_verifikasi = 'Menunggu'
        $data = PrestasiModel::with(['lomba', 'dosen'])
            ->where('dosen_id', $dosenId)
            ->where('status_verifikasi', 'Menunggu')   // <-- Filter hanya yang status menunggu
            ->select('t_prestasi.*');

        return DataTables::of($data)
            ->addColumn('nama_lomba', function ($row) {
                return $row->lomba->nama_lomba ?? $row->lomba_lainnya ?? '-';
            })
            ->addColumn('dosen_pembimbing', function ($row) {
                return $row->dosen->nama_dosen ?? '<span class="text-muted">Belum ada</span>';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status); // Tetap gunakan method privat di controller
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button onclick="modalAction(\'' . route('dosen.kelola-bimbingan.showAjax', $row->prestasi_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('dosen.terimaPrestasiAjax', $row->prestasi_id) . '\')" class="btn btn-success btn-sm mx-2">Terima</button>';
                $btn .= '<button onclick="modalAction(\'' . route('dosen.tolakPrestasiAjax', $row->prestasi_id) . '\')" class="btn btn-danger btn-sm">Tolak</button>';
                return $btn;
            })
            ->rawColumns(['dosen_pembimbing', 'status_verifikasi', 'aksi'])
            ->make(true);
    }


    public function terimaPrestasiAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        return view('dosen.verifikasi-bimbingan.terima', compact('prestasi'));
    }

    public function tolakPrestasiAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        return view('dosen.verifikasi-bimbingan.tolak', compact('prestasi'));
    }

    public function terimaPrestasi($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        try {
            // Update status_verifikasi menjadi Terverifikasi
            $prestasi->update(['status_verifikasi' => 'Valid']);

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

    public function tolakPrestasi(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:255'
        ]);

        $prestasi = PrestasiModel::findOrFail($id);

        try {
            $prestasi->update([
                'status_verifikasi' => 'Ditolak',
                'alasan_tolak' => $request->alasan_tolak
            ]);

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

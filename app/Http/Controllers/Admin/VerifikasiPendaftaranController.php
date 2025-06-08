<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PendaftaranLombaModel;

class VerifikasiPendaftaranController extends Controller
{
    protected function getStatusBadge($status)
    {
        switch ($status) {
            case 'valid':
                return '<span class="label label-success">Terverifikasi</span>';
            case 'menunggu':
                return '<span class="label label-warning">Menunggu</span>';
            case 'ditolak':
                return '<span class="label label-danger">Ditolak</span>';
            default:
                return '<span class="label label-default">Tidak Diketahui</span>';
        }
    }

    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Verifikasi Pendaftaran']
        ];

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = PendaftaranLombaModel::with(['mahasiswa', 'lomba'])
               ->select('t_pendaftaran_lomba.*')
               ->whereRaw('LOWER(status_pendaftaran) = ?', ['menunggu']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_mahasiswa', function ($row) {
                return optional($row->mahasiswa)->nama_mahasiswa ?? '-';
            })
            ->addColumn('nama_lomba', function ($row) {
                return optional($row->lomba)->nama_lomba ?? '-';
            })
            ->addColumn('tipe_lomba', function ($row) {
                return optional($row->lomba)->tipe_lomba ?? '-';
            })
            ->addColumn('tanggal_daftar', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y') : '-';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status);
            })

            ->addColumn('aksi', function ($row) {
                return '<button style="white-space:nowrap" onclick="modalAction(\'' . route('verifikasi-pendaftaran.show', $row->pendaftaran_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
            })


            ->rawColumns(['status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function riwayat_index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Verifikasi Pendaftaran']
        ];

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.riwayat', compact('breadcrumb'));
    }

    public function riwayat_list(Request $request)
    {
        $data = PendaftaranLombaModel::with(['mahasiswa', 'lomba'])
            ->select('t_pendaftaran_lomba.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_mahasiswa', function ($row) {
                return optional($row->mahasiswa)->nama_mahasiswa ?? '-';
            })
            ->addColumn('nama_lomba', function ($row) {
                return optional($row->lomba)->nama_lomba ?? '-';
            })
            ->addColumn('tipe_lomba', function ($row) {
                return optional($row->lomba)->tipe_lomba ?? '-';
            })
            ->addColumn('tanggal_daftar', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y') : '-';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status);
            })

            ->addColumn('aksi', function ($row) {
                return '<button style="white-space:nowrap" onclick="modalAction(\'' . route('verifikasi-pendaftaran.show', $row->pendaftaran_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
            })


            ->rawColumns(['status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function detail_pendaftaran($id)
    {
        $pendaftaran = PendaftaranLombaModel::with(['lomba', 'mahasiswa', 'anggota'])
            ->findOrFail($id);

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.show', compact('pendaftaran'));
    }
}

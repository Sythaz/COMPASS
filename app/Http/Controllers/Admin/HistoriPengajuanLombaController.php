<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HistoriPengajuanLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Histori Pengajuan Lomba']
        ];

        return view('admin.manajemen-lomba.histori-pengajuan-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = LombaModel::with('kategori', 'tingkat_lomba')
            ->select([
                'lomba_id',
                'nama_lomba',
                'tingkat_lomba_id',
                'penyelenggara_lomba',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'status_verifikasi'
            ])
            ->where('pengusul_id', auth()->id())
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_tingkat', function ($row) {
                return $row->tingkat_lomba?->nama_tingkat ?? '-';
            })
            ->addColumn('kategori', function ($row) {
                return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            })
            ->addColumn('status_verifikasi', function ($row) {
                $statusLomba = $row->status_verifikasi;
                switch ($statusLomba) {
                    case 'Terverifikasi':
                        $label = '<span class="label label-success">Terverifikasi</span>';
                        break;
                    case 'Valid':
                        $label = '<span class="label label-info">Valid</span>';
                        break;
                    case 'Menunggu':
                        $label = '<span class="label label-warning">Menunggu</span>';
                        break;
                    case 'Ditolak':
                        $label = '<span class="label label-danger">Ditolak</span>';
                        break;
                    default:
                        $label = '<span class="label label-secondary">Tidak Diketahui</span>';
                        break;
                }
                return $label;
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/histori-pengajuan-lomba/' . $row->lomba_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        $pengusul = $lomba->pengusul_id;
        $rolePengusul = UsersModel::where('user_id', $pengusul)->first()->role;

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
        return view('admin.manajemen-lomba.histori-pengajuan-lomba.show', compact('lomba', 'namaPengusul'));
    }
}

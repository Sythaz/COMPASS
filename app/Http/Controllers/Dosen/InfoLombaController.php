<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LombaModel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;

class InfoLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Info Lomba']
        ];

        return view('dosen.info-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        // Ambil data lomba yang status_lomba = 'Aktif' dan status_verifikasi = 'Terverifikasi' saja
        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi') // hanya Terverifikasi
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            ->addColumn('kategori', function ($row) {
                return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            })
            ->addColumn('tingkat_lomba', function ($row) {
                return $row->tingkat_lomba->nama_tingkat ?? '-';
            })
            ->addColumn('status_verifikasi', function ($row) {
                // Tampilkan status_lomba (yang pasti "Aktif" sesuai kondisi)
                $status = $row->status_lomba;
                if ($status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                }
                // Jika ingin fallback, tapi seharusnya gak perlu karena filter di query
                return '<span class="label label-secondary">Tidak Diketahui</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="text-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . route('info-lomba.show', $row->lomba_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
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

        $breadcrumb = (object) [
            'list' => ['Info Lomba', 'Detail Lomba']
        ];

        return view('dosen.info-lomba.show', compact('lomba', 'namaPengusul', 'breadcrumb'));
    }

}

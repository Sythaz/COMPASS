<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LombaModel;
use Yajra\DataTables\Facades\DataTables;

class InfoLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Info Lomba']
        ];

        return view('dosen.info-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->select([
                'lomba_id',
                'nama_lomba',
                'tingkat_lomba_id',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'status_lomba'
            ])
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            ->addColumn('tingkat_lomba', function ($row) {
                return $row->tingkat_lomba->nama_tingkat ?? '-';
            })
            ->addColumn('kategori', function ($row) {
                return $row->kategori->pluck('nama_kategori')->implode(', ');
            })
            ->addColumn('status_lomba', function ($row) {
                return '<span class="badge badge-success">Aktif</span>';
            })
            ->rawColumns(['status_lomba'])
            ->make(true);
    }

}

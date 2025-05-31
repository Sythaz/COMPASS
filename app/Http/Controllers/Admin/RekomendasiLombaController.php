<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use App\Models\LombaModel;
use App\Models\TingkatLombaModel;
use Illuminate\Http\Request;

class RekomendasiLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Lomba',
            'list'  => ['Manajemen Lomba', 'Rekomendasi Lomba']
        ];

        $dataLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->select([
                'lomba_id',
                'nama_lomba',
                'penyelenggara_lomba',
                'tingkat_lomba_id',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
            ])
            ->where('status_lomba', 'Aktif')
            ->whereIn('status_verifikasi', ['Terverifikasi'])
            ->get();

        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('admin.manajemen-lomba.rekomendasi-lomba.index', compact('breadcrumb', 'dataLomba', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function showAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);

        return view('admin.manajemen-lomba.rekomendasi-lomba.show', compact('lomba'));
    }
}

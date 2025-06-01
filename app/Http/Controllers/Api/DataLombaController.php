<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LombaModel;
use Illuminate\Http\Request;

class DataLombaController extends Controller
{
    public function dataLomba($id)
    {
        $lomba = LombaModel::with(['kategori', 'tingkat_lomba'])->find($id);

        if (!$lomba) {
            return response()->json([
                'success' => false,
                'message' => 'Lomba tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'deskripsi_lomba' => $lomba->deskripsi_lomba,
                'kategori' => $lomba->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui',
                'tingkat_lomba_id' => $lomba->tingkat_lomba->nama_tingkat,
                'penyelenggara_lomba' => $lomba->penyelenggara_lomba,
                'periode_registrasi' => \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') . ' - ' .
                    \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y'),
                'link_pendaftaran_lomba' => $lomba->link_pendaftaran_lomba
            ]
        ]);
    }
}

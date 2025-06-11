<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\NotifikasiModel;
use App\Models\TingkatLombaModel;
use App\Models\UsersModel;
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
            ->where('akhir_registrasi_lomba', '>=', now())
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

    public function tambahRekomendasiAjax()
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->get();
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('admin.manajemen-lomba.rekomendasi-lomba.tambah-rekomendasi', compact('lomba', 'daftarMahasiswa', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function rekomendasiAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();

        return view('admin.manajemen-lomba.rekomendasi-lomba.rekomendasi', compact('lomba', 'daftarMahasiswa', 'daftarKategori'));
    }

    public function notifikasiRekomendasi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:t_users,user_id',
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'pesan_notifikasi' => 'nullable|string'
        ]);

        $user = UsersModel::findOrFail($request->user_id);
        $lomba = LombaModel::findOrFail($request->lomba_id);
        $pesanNotifikasi = $request->pesan_notifikasi ?? sprintf(
            "Anda direkomendasikan oleh %s '%s' untuk mengikuti lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
            ucfirst(strtolower(auth()->user()->getRole())) == 'dosen' ? 'Dosen' : 'Admin',
            auth()->user()->getName(),
            $lomba->nama_lomba
        );

        // Cek apakah notifikasi sudah pernah terkirim untuk lomba ini kepada user ini
        $cekNotif = NotifikasiModel::where('user_id', $user->user_id)
            ->where('lomba_id', $lomba->lomba_id)
            ->where('jenis_notifikasi', 'Rekomendasi')
            ->exists();

        if ($cekNotif) {
            return response()->json(['success' => false, 'message' => 'Notifikasi sudah pernah terkirim untuk lomba ini kepada user ini.']);
        }

        $notifikasi = NotifikasiModel::create([
            'user_id' => $user->user_id,
            'pengirim_id' => auth()->id(),
            'pengirim_role' => auth()->user()->getRole(),
            'lomba_id' => $lomba->lomba_id,
            'jenis_notifikasi' => 'Rekomendasi',
            'pesan_notifikasi' => $pesanNotifikasi
        ]);

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil dikirim kepada mahasiswa.']);
    }
}

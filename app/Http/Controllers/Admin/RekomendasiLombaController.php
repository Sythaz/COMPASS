<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekomendasiLombaModel;
use App\Models\KategoriModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\NotifikasiModel;
use App\Models\TingkatLombaModel;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('admin.manajemen-lomba.rekomendasi-lomba.index', compact(
            'breadcrumb', 
            'dataLomba', 
            'daftarKategori', 
            'daftarTingkatLomba'
        ));
    }

    public function showAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);

        return view('admin.manajemen-lomba.rekomendasi-lomba.show', compact(
            'lomba'
        ));
    }

    public function tambahRekomendasiAjax()
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->get();
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        $kategoriAktifIds = $daftarKategori->pluck('kategori_id');

        $dosenIds = DB::table('t_preferensi_dosen')
            ->whereIn('kategori_id', $kategoriAktifIds)
            ->pluck('dosen_id')
            ->unique();

        $daftarDosen = DosenModel::whereHas('preferensiDosen.kategori', function ($query) {
            $query->where('status_kategori', 'Aktif');
        })->get();


        return view('admin.manajemen-lomba.rekomendasi-lomba.tambah-rekomendasi', compact(
            'lomba', 
            'daftarMahasiswa', 
            'daftarKategori', 
            'daftarTingkatLomba',
            'daftarDosen'
        ));
    }

    public function rekomendasiAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();

        $kategoriIds = DB::table('t_kategori_lomba')
        ->where('lomba_id', $id)
        ->pluck('kategori_id');

        // Ambil dosen yang punya preferensi (nama) yang cocok dengan kategori lomba
        $dosenIds = DB::table('t_preferensi_dosen')
            ->whereIn('kategori_id', $kategoriIds)
            ->pluck('dosen_id')
            ->unique();

        $daftarDosen = DosenModel::with('users')
            ->whereIn('dosen_id', $dosenIds)
            ->where('status', 'Aktif')
            ->get();

        return view('admin.manajemen-lomba.rekomendasi-lomba.rekomendasi', compact(
            'lomba', 
            'daftarMahasiswa', 
            'daftarKategori',
            'daftarDosen'
        ));
    }

    public function notifikasiRekomendasi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:t_users,user_id',
            'dosen_id' => 'required|exists:t_dosen,dosen_id',
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'pesan_notifikasi' => 'nullable|string'
        ]);

        $user = UsersModel::findOrFail($request->user_id);
        $dosen = DosenModel::with('users')->findOrFail($request->dosen_id);
        $lomba = LombaModel::findOrFail($request->lomba_id);

        $adminName = auth()->user()->getName();

        // === Notifikasi untuk Mahasiswa === //
        $pesanNotifikasiMahasiswa = $request->pesan_notifikasi ?? sprintf(
            "Anda direkomendasikan oleh Admin '%s' untuk mengikuti lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
            $adminName,
            $lomba->nama_lomba
        );

        // Cek apakah notifikasi sudah pernah terkirim untuk lomba ini kepada user ini
        $cekNotifMahasiswa = NotifikasiModel::where([
            ['user_id', '=', $user->user_id],
            ['lomba_id', '=', $lomba->lomba_id],
            ['jenis_notifikasi', '=', 'Rekomendasi']
        ])->exists();

        if (!$cekNotifMahasiswa) {
            $notifikasi = NotifikasiModel::create([
                'user_id' => $user->user_id,
                'pengirim_id' => auth()->id(),
                'pengirim_role' => auth()->user()->getRole(),
                'lomba_id' => $lomba->lomba_id,
                'jenis_notifikasi' => 'Rekomendasi',
                'pesan_notifikasi' => $pesanNotifikasiMahasiswa
            ]);
            //return response()->json(['success' => false, 'message' => 'Notifikasi sudah pernah terkirim untuk lomba ini kepada user ini.']);
        }

        // Notifikasi untuk Dosen // 
        $pesanNotifikasiDosen = $request->pesan_notifikasi ?? sprintf(
            "Anda direkomendasikan oleh Admin '%s' untuk mendampingi lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
            $adminName,
            $lomba->nama_lomba
        );

        $cekNotifDosen = NotifikasiModel::where([
            ['user_id', '=', $dosen->users->user_id],
            ['lomba_id', '=', $lomba->lomba_id],
            ['jenis_notifikasi', '=', 'Rekomendasi']
        ])->exists();

        if (!$cekNotifDosen) {
            $notifikasi = NotifikasiModel::create([
                'user_id' => $dosen->user_id,
                'pengirim_id' => auth()->id(),
                'pengirim_role' => auth()->user()->getRole(),
                'lomba_id' => $lomba->lomba_id,
                'jenis_notifikasi' => 'Rekomendasi',
                'pesan_notifikasi' => $pesanNotifikasiDosen
            ]);
            //return response()->json(['success' => false, 'message' => 'Notifikasi sudah pernah terkirim untuk lomba ini kepada user ini.']);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Notifikasi berhasil dikirim kepada mahasiswa dan dosen.'
        ]);
    }

    public function getLombaData($id)
    {
        try {
            $lomba = LombaModel::with(['kategori', 'tingkat_lomba'])->findOrFail($id);
        
            $data = [
                'deskripsi_lomba' => $lomba->deskripsi_lomba ?? '-',
                'kategori' => $lomba->kategori->nama_kategori ?? '-',
                'tingkat_lomba_id' => $lomba->tingkat_lomba->nama_tingkat ?? '-',
                'penyelenggara_lomba' => $lomba->penyelenggara_lomba ?? '-',
                'periode_registrasi' => $lomba->awal_registrasi_lomba . ' - ' . $lomba->akhir_registrasi_lomba,
                'link_pendaftaran_lomba' => $lomba->link_pendaftaran_lomba ?? '#',
            ];
        
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data lomba',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

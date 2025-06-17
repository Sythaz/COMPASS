<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LombaModel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\KategoriModel;
use App\Models\MahasiswaModel;
use App\Models\NotifikasiModel;
use App\Models\TingkatLombaModel;
use App\Models\UsersModel;
use Carbon\Carbon;

class InfoLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Informasi Lomba']
        ];

        $dataLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->select([
                'lomba_id',
                'nama_lomba',
                'penyelenggara_lomba',
                'tingkat_lomba_id',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'lokasi_lomba',
                'tipe_lomba',
            ])
            ->where('status_lomba', 'Aktif')
            ->whereIn('status_verifikasi', ['Terverifikasi'])
            ->where('akhir_registrasi_lomba', '>=', now())
            ->get();

        // Data tambahan untuk filter
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('dosen.info-lomba.index', compact('breadcrumb', 'dataLomba', 'daftarKategori', 'daftarTingkatLomba'));
    }

    // Tambahkan method private untuk membuat badge status
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
                return '<span class="label label-secondary">Tidak Diketahui</span>';
        }
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

        // Hitung sisa hari
        $deadline = Carbon::parse($lomba->akhir_registrasi_lomba);
        $today = Carbon::today();
        $sisaHari = $today->diffInDays($deadline, false);
        $badgeStatus = $this->getStatusBadge($lomba->status_verifikasi);

        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Detail Lomba']
        ];

        return view('dosen.info-lomba.show', compact('lomba', 'namaPengusul', 'breadcrumb', 'badgeStatus', 'sisaHari'));
    }

    public function tambahRekomendasiAjax()
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->get();
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('dosen.info-lomba.tambah-rekomendasi', compact('lomba', 'daftarMahasiswa', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function rekomendasiAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();

        return view('dosen.info-lomba.show-rekomendasi', compact('lomba', 'daftarMahasiswa', 'daftarKategori'));
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

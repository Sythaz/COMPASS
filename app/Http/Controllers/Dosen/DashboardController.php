<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Home', 'Dashboard']
        ];

        // Inisialisasi variabel
        $userRank = '-';
        $totalPartisipasiLomba = 0;
        $mahasiswaId = null;

        $loggedInUser = Auth::user();

        if (!$loggedInUser || strtolower($loggedInUser->role) !== 'dosen') {
            return redirect('/login/dosen');
        }

        $dosen = $loggedInUser->dosen;

        if (!$dosen) {
            return redirect('/error');
        }

        $dosenId = $dosen->dosen_id;

        // // Data untuk kartu Performa Bimbingan
        // $performaBimbingan = $this->getPerformaBimbingan($dosenId);

        // // Data untuk kartu verifikasi bimbingan
        $jumlahVerifikasiBimbingan = $this->getJumlahVerifikasiBimbingan($dosenId);

        // Data untuk kartu Prestasi Bimbingan
        $jumlahPrestasiBimbingan = $this->getJumlahPrestasiBimbingan($dosenId);

        // Ambil tanggal semester aktif
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;

        if ($currentMonth >= 8 && $currentMonth <= 12) {
            // Semester Ganjil (Agustus–Desember)
            $startSemester = Carbon::create($currentDate->year, 8, 1);
            $endSemester = Carbon::create($currentDate->year, 12, 31);
        } else {
            // Semester Genap (Februari–Juni)
            $startSemester = Carbon::create($currentDate->year, 2, 1);
            $endSemester = Carbon::create($currentDate->year, 6, 30);
        }

        // Ambil 5 mahasiswa teratas dengan ranking
        $topMahasiswa = DB::table('t_mahasiswa')
            ->join('t_prestasi_mahasiswa', 't_mahasiswa.mahasiswa_id', '=', 't_prestasi_mahasiswa.mahasiswa_id')
            ->join('t_prestasi', 't_prestasi_mahasiswa.prestasi_id', '=', 't_prestasi.prestasi_id')
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->whereBetween('t_prestasi.tanggal_prestasi', [$startSemester, $endSemester])
            ->select(
                't_mahasiswa.mahasiswa_id',
                't_mahasiswa.nama_mahasiswa',
                DB::raw('COUNT(t_prestasi.prestasi_id) as total_partisipasi'),
                DB::raw('MAX(CASE WHEN t_prestasi_mahasiswa.peran = "Ketua" THEN 1 ELSE 0 END) as ketua')
            )
            ->groupBy('t_mahasiswa.mahasiswa_id', 't_mahasiswa.nama_mahasiswa')
            ->orderByDesc('total_partisipasi')
            ->orderByDesc('ketua')
            ->limit(5)
            ->get();

        // Tambahkan ranking ke dalam data topMahasiswa
        $topMahasiswaRank = collect($topMahasiswa)->map(function ($item, $key) {
            $item = (object) $item;
            $item->rank = '#' . ($key + 1); // Tambahkan nomor ranking
            return $item;
        });

        // Mengambil lomba yang sedang aktif pendaftaran
        $lombaSedangAktif = DB::table('t_lomba')
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('akhir_registrasi_lomba', '>=', Carbon::now())
            ->orderBy('akhir_registrasi_lomba', 'ASC')
            ->get()
            ->map(function ($lomba) {
                $lomba->akhir_registrasi_lomba = Carbon::parse($lomba->akhir_registrasi_lomba)->translatedFormat('d M Y');
                return $lomba;
            });

        return view('dosen.index', compact(
            'breadcrumb',
            'jumlahPrestasiBimbingan',
            'jumlahVerifikasiBimbingan',
            'topMahasiswaRank',
            'lombaSedangAktif',
        ));
    }

    // protected function getPerformaBimbingan($dosenId)
    // {
    //     // Hitung persentase mahasiswa bimbingan yang mendapatkan prestasi
    //     $totalMahasiswaBimbingan = DB::table('t_bimbingan')
    //         ->where('dosen_id', $dosenId)
    //         ->count();

    //     $mahasiswaDenganPrestasi = DB::table('t_bimbingan')
    //         ->join('t_prestasi_mahasiswa', 't_bimbingan.mahasiswa_id', '=', 't_prestasi_mahasiswa.mahasiswa_id')
    //         ->join('t_prestasi', 't_prestasi_mahasiswa.prestasi_id', '=', 't_prestasi.prestasi_id')
    //         ->where('t_bimbingan.dosen_id', $dosenId)
    //         ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
    //         ->distinct('t_bimbingan.mahasiswa_id')
    //         ->count();

    //     $persentase = $totalMahasiswaBimbingan > 0
    //         ? round(($mahasiswaDenganPrestasi / $totalMahasiswaBimbingan) * 100)
    //         : 0;

    //     return [
    //         'persentase' => $persentase,
    //         'label' => 'Mahasiswa mendapatkan prestasi'
    //     ];
    // }

    // protected function getJumlahMahasiswaBimbingan($dosenId)
    // {
    //     $jumlah = DB::table('t_bimbingan')
    //         ->where('dosen_id', $dosenId)
    //         ->count();

    //     return [
    //         'jumlah' => $jumlah,
    //         'label' => 'Mahasiswa telah dibimbing'
    //     ];
    // }

    protected function getJumlahVerifikasiBimbingan($dosenId)
    {
        $jumlah = DB::table('t_prestasi')
            ->where('dosen_id', $dosenId)
            ->where('status_verifikasi', 'Menunggu')
            ->count();

        return $jumlah;
    }

    protected function getJumlahPrestasiBimbingan($dosenId)
    {
        $jumlah = DB::table('t_prestasi')
            ->where('dosen_id', $dosenId)
            ->where('status_verifikasi', 'Terverifikasi')
            ->count();

        return $jumlah;
    }
}

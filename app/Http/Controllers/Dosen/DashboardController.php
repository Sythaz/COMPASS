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

        // Hitung jumlah partisipasi lomba pengguna
        $partisipasiLomba = DB::table('t_prestasi_mahasiswa')
            ->join('t_prestasi', 't_prestasi_mahasiswa.prestasi_id', '=', 't_prestasi.prestasi_id')
            ->where('t_prestasi_mahasiswa.mahasiswa_id', $mahasiswaId)
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->count();

        $totalPartisipasiLomba = $partisipasiLomba > 0 ? $partisipasiLomba : '-';

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

        // Ambil data 5 mahasiswa teratas dengan ranking.
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

        // Jumlah lomba tersedia
        $jumlahLombaTersedia = DB::table('t_lomba')
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('akhir_registrasi_lomba', '>=', Carbon::today())
            ->count();

        // Hitung ranking dalam konteks semester aktif
        $rankingData = DB::table('t_prestasi_mahasiswa')
            ->select(
                't_prestasi_mahasiswa.mahasiswa_id',
                DB::raw('COUNT(*) as total_partisipasi')
            )
            ->join('t_prestasi', 't_prestasi_mahasiswa.prestasi_id', '=', 't_prestasi.prestasi_id')
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->whereBetween('t_prestasi.tanggal_prestasi', [$startSemester, $endSemester])
            ->groupBy('t_prestasi_mahasiswa.mahasiswa_id')
            ->orderByDesc('total_partisipasi')
            ->get();

        // Temukan rank pengguna dalam konteks semester aktif
        foreach ($rankingData as $index => $data) {
            if ((int)$data->mahasiswa_id === (int)$mahasiswaId) {
                $userRank = '#' . ($index + 1);
                break;
            }
        }

        // Lomba sedang aktif
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

        return view('mahasiswa.index', compact(
            'breadcrumb',
            'topMahasiswaRank',
            'jumlahLombaTersedia',
            'userRank',
            'lombaSedangAktif',
            'totalPartisipasiLomba'
        ));
    }
}

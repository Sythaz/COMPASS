<?php

namespace App\Http\Controllers\Mahasiswa;

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

        // Ranking Mahasiswa 
        $loggedInUser = Auth::user();

        if (!$loggedInUser || $loggedInUser->role !== 'Mahasiswa') {
            return redirect('/login/mahasiswa');
        } else {
            $mahasiswa = $loggedInUser->mahasiswa;

            if (!$mahasiswa){
                return redirect('/error');
            } else {
                $mahasiswaId = $mahasiswa->mahasiswa_id;

                // hitung ranking 
                $rankingData = DB::table('t_pendaftaran_lomba')
                ->select('mahasiswa_id', DB::raw('COUNT(*) as total_lomba'))
                ->where('status_pendaftaran', 'Terverifikasi')
                ->groupBy('mahasiswa_id')
                ->orderByDesc('total_lomba')
                ->get();

                $ranking = 1;
                $userRank ='-';
                foreach ($rankingData as $data) {
                    if ((int)$data->mahasiswa_id == (int)$mahasiswaId) {
                        $userRank = '#' . $ranking;
                        break;
                    }
                    $ranking++;
                }
            }
        }

        // Mendapatkan data mahasiswa teraktif mengikuti lomba dalam 1 semester yang sedang berlangsung
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

        // Kode ini mengambil data 5 mahasiswa teratas yang paling aktif mengikuti lomba dalam satu semester.
        // Proses dimulai dengan menggabungkan tabel mahasiswa, pendaftaran lomba, dan lomba untuk memperoleh data yang saling terkait.
        // Kemudian, hanya lomba yang waktu pendaftarannya berada dalam rentang semester aktif yang dihitung.
        // Dari data tersebut, sistem menghitung jumlah total pendaftaran (termasuk duplikat lomba) dan jumlah lomba unik yang diikuti oleh setiap mahasiswa.
        // Data mahasiswa kemudian dikelompokkan berdasarkan ID, diurutkan berdasarkan jumlah partisipasi terbanyak, dan dibatasi hanya menampilkan lima teratas.
        $topMahasiswa = DB::table('t_mahasiswa')
            ->join('t_pendaftaran_lomba', 't_mahasiswa.mahasiswa_id', '=', 't_pendaftaran_lomba.mahasiswa_id')
            ->join('t_lomba', 't_pendaftaran_lomba.lomba_id', '=', 't_lomba.lomba_id')
            // ->where('t_lomba.awal_registrasi_lomba', '<=', $endSemester)
            // ->where('t_lomba.akhir_registrasi_lomba', '>=', $startSemester)
            ->where('t_pendaftaran_lomba.status_pendaftaran', 'Terverifikasi') // Tambahkan ini
            // ->where('t_lomba.status_verifikasi', 'Terverifikasi')              // Tambahkan ini
            ->select('t_mahasiswa.nama_mahasiswa', DB::raw('COUNT(t_pendaftaran_lomba.lomba_id) as total_partisipasi'))
            ->groupBy('t_mahasiswa.mahasiswa_id')
            ->orderBy('total_partisipasi', 'desc')
            ->limit(5)
            ->get();

        // Partisipasi Lomba Mahasiswa 
        $partisipasiLomba = DB::table('t_pendaftaran_lomba')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status_pendaftaran', 'Terverifikasi')
            ->count();    
        if($partisipasiLomba > 0) {
            $totalPartisipasiLomba = $partisipasiLomba;
        } else {
            $totalPartisipasiLomba = '-';
        }
        
        $jumlahLombaTersedia = DB::table('t_lomba')
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->count();

        $lombaSedangAktif = DB::table('t_lomba')
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->orderByRaw('akhir_registrasi_lomba ASC')
            ->get()
            ->map(function ($lomba) {
                $lomba->akhir_registrasi_lomba = Carbon::parse($lomba->akhir_registrasi_lomba)
                    ->translatedFormat('d F Y');
                return $lomba;
            });    

        return view('mahasiswa.index', compact(
            'breadcrumb', 
            'topMahasiswa', 
            'jumlahLombaTersedia', 
            'userRank',
            'lombaSedangAktif',
            'totalPartisipasiLomba'
        ));
    }
}

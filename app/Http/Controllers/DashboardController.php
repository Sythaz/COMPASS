<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\PendaftaranLombaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Dashboard', 'Home']
        ];

        // Menghitung persentase mahasiswa ikut lomba
        $totalMhs = MahasiswaModel::count();
        $totalMhsIkutLomba = PendaftaranLombaModel::distinct('mahasiswa_id')->count();

        $persentaseMhsIkutLomba = 0;
        if ($totalMhs > 0) {
            $persentaseMhsIkutLomba = $totalMhsIkutLomba / $totalMhs * 100;
        }

        // Menghitung lomba yang sedang aktif pendaftaran
        $hariIni = Carbon::today();
        $lombaAktif = LombaModel::where('awal_registrasi_lomba', '<=', $hariIni)
            ->where('akhir_registrasi_lomba', '>=', $hariIni);

        $totalLombaAktif = $lombaAktif->count();

        // Mengambil data lomba yang sedang aktif dan format tanggal (Tanggal Bulan Tahun)
        $lombaSedangAktif = $lombaAktif->orderByRaw('akhir_registrasi_lomba ASC')
            ->get()->map(function ($lomba) {
                $lomba->akhir_registrasi_lomba = Carbon::parse($lomba->akhir_registrasi_lomba)
                    ->translatedFormat('d F Y');
                return $lomba;
            });


        // Menghitung jumlah mahasiswa berpartisipasi dalam lomba seminggu terakhir
        $semingguTerakhir = Carbon::now()->subWeek();
        $jmlPartisipasiSemingguTerakhir = PendaftaranLombaModel::where('created_at', '>=', $semingguTerakhir)
            ->count();

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
            ->where('t_lomba.awal_registrasi_lomba', '<=', $endSemester)
            ->where('t_lomba.akhir_registrasi_lomba', '>=', $startSemester)
            ->select('t_mahasiswa.nama_mahasiswa', DB::raw('COUNT(t_pendaftaran_lomba.lomba_id) as total_partisipasi'))
            ->groupBy('t_mahasiswa.mahasiswa_id')
            ->orderBy('total_partisipasi', 'desc')
            ->limit(5)
            ->get();

        // Menampilkan Dominasi Bidang Prestasi Mahasiswa        
        $dataPieChart = DB::table('t_kategori')     // Ambil semua kategori + hitung jumlah prestasi per kategori
            ->leftJoin('t_prestasi', 't_kategori.kategori_id', '=', 't_prestasi.kategori_id')
            ->select(
                't_kategori.nama_kategori as label',
                DB::raw('COUNT(t_prestasi.prestasi_id) as total')
            )
            ->groupBy('t_kategori.nama_kategori')
            ->get();

        // Format menjadi array untuk Flot Pie Chart
        $chartDataColors = ['#ff5e5e', '#e62739', '#4CAF50', '#9097c4', '#e1e8f0', '#7571F9', '#FFC107', '#9C27B0', '#03A9F4', '#FF9800', '#E91E63', '#00BCD4', '#CDDC39', '#FFEB3B', '#3F51B5', '#009688', '#FF5722', '#607D8B'];
        $chartPieData = [];

        foreach ($dataPieChart as $index => $item) {
            $chartPieData[] = [
                'label' => $item->label,
                'data' => [[1, $item->total]], // Format Flot: [[urutan, nilai]]
                'color' => $chartDataColors[$index % count($chartDataColors)] // Loop warna jika lebih banyak kategori
            ];
        }

        return view('admin.index', compact('breadcrumb', 'persentaseMhsIkutLomba', 'totalLombaAktif', 'jmlPartisipasiSemingguTerakhir', 'topMahasiswa', 'lombaSedangAktif', 'chartPieData'));
    }
}

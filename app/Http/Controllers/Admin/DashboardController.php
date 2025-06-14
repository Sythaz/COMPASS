<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Dashboard', 'Home']
        ];

        // Menghitung persentase mahasiswa pernah berprestasi setahun ini
        $totalMhs = MahasiswaModel::count();
        $totalPrestasiMahasiswa = DB::table('t_prestasi')
            ->select('mahasiswa_id')
            ->where('status_verifikasi', 'Terverifikasi')
            ->whereBetween('tanggal_prestasi', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->distinct()
            ->count();

        $persentasePrestasiMahasiswa = 0;
        if ($totalMhs > 0) {
            $persentasePrestasiMahasiswa = floor($totalPrestasiMahasiswa / $totalMhs * 100);
        }

        // Menghitung lomba yang sedang aktif pendaftaran
        $jumlahLombaAktif = DB::table('t_lomba')
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('akhir_registrasi_lomba', '>=', Carbon::today())
            ->count();

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

        // Menghitung jumlah mahasiswa berprestasi sebulan ini
        $sebulanTerakhir = Carbon::now()->subMonth();
        $jmlPrestasiSebulanTerakhir = DB::table('t_prestasi')
            ->select('mahasiswa_id')
            ->where('status_verifikasi', 'Terverifikasi')
            ->whereBetween('tanggal_prestasi', [$sebulanTerakhir, Carbon::now()])
            ->distinct()
            ->count();

        // Ambil tanggal semester aktif
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;

        if ($currentMonth >= 8 && $currentMonth <= 12) {
            // Semester Ganjil: Agustus - Desember
            $startSemester = Carbon::create($currentDate->year, 8, 1);
            $endSemester = Carbon::create($currentDate->year, 12, 31);
        } else {
            // Semester Genap: Januari - Juli
            $startSemester = Carbon::create($currentDate->year, 1, 1);
            $endSemester = Carbon::create($currentDate->year, 7, 31);
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

        // Memanggil fungsi untuk mendapatkan data pie chart (Dominasi Bidang Prestasi Mahasiswa)
        $chartPieData = $this->getPieChartData();

        // Mendapatkan data untuk Flot Chart (Perkembangan Prestasi Mahasiswa per Semester)
        list($labels, $values) = $this->getFlotChartData();

        return view('admin.index', compact('breadcrumb', 'persentasePrestasiMahasiswa', 'jumlahLombaAktif', 'jmlPrestasiSebulanTerakhir', 'topMahasiswa', 'lombaSedangAktif', 'chartPieData', 'labels', 'values'));
    }

    // Warna untuk chart pie
    const CHART_COLORS = [
        '#9C27B0',
        '#7571F9',
        '#03A9F4',
        '#9097c4',
        '#e62739',
        '#4CAF50',
        '#e1e8f0',
        '#ff5e5e',
        '#FFC107',
        '#FF9800'
    ];

    protected function getPieChartData()
    {
        $dataPieChart = DB::table('t_kategori')
            ->leftJoin('t_prestasi', 't_kategori.kategori_id', '=', 't_prestasi.kategori_id')
            ->select(
                't_kategori.nama_kategori as label',
                DB::raw('COUNT(t_prestasi.prestasi_id) as total')
            )
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->groupBy('t_kategori.nama_kategori')
            ->get();

        // Format menjadi array untuk Flot Pie Chart
        $chartPieData = [];

        foreach ($dataPieChart as $index => $item) {
            $chartPieData[] = [
                'label' => $item->label,
                'data' => [[1, $item->total]], // Format Flot: [[urutan, nilai]]
                'color' => self::CHART_COLORS[$index % count(self::CHART_COLORS)] // Loop warna jika lebih banyak kategori
            ];
        }

        return $chartPieData;
    }

    protected function getFlotChartData()
    {
        $currentDate = Carbon::now();

        // Ambil semua periode dan tambahkan status apakah sedang aktif atau tidak
        $allPeriode = DB::table('t_periode')
            ->select('periode_id', 'semester_periode', 'tanggal_mulai', 'tanggal_akhir')
            ->orderBy('tanggal_mulai', 'desc')
            ->get()
            ->map(function ($p) use ($currentDate) {
                $mulai = Carbon::parse($p->tanggal_mulai);
                $akhir = Carbon::parse($p->tanggal_akhir);
                $p->is_current = $currentDate->between($mulai, $akhir); // apakah periode ini sedang berlangsung?
                return $p;
            });

        // Cari periode yang sedang aktif
        $activePeriode = $allPeriode->firstWhere('is_current', true);

        // Jika tidak ditemukan periode aktif, ambil periode terbaru
        if (!$activePeriode) {
            $activePeriode = $allPeriode->first();
        }

        // Dapatkan index periode aktif
        $allPeriodeArray = $allPeriode->toArray();
        $currentIndex = array_search($activePeriode, $allPeriodeArray);

        // Ambil 6 periode terakhir (aktif + 5 sebelumnya)
        $selectedPeriode = array_slice($allPeriodeArray, max(0, $currentIndex - 5), 6);

        // Urutkan dari awal ke akhir (kiri ke kanan di grafik)
        $selectedPeriode = array_reverse($selectedPeriode);

        // Ambil ID periode yang dipilih
        $selectedPeriodeIds = collect($selectedPeriode)->pluck('periode_id')->toArray();

        // Ambil jumlah prestasi per periode dari database
        $prestasiPerPeriode = DB::table('t_periode')
            ->whereIn('t_periode.periode_id', $selectedPeriodeIds)
            ->leftJoin('t_prestasi', function ($join) {
                $join->on('t_periode.periode_id', '=', 't_prestasi.periode_id')
                    ->where('t_prestasi.status_verifikasi', 'Terverifikasi');
            })
            ->select(
                't_periode.periode_id',
                't_periode.semester_periode',
                DB::raw('COUNT(t_prestasi.prestasi_id) as jumlah')
            )
            ->groupBy('t_periode.periode_id', 't_periode.semester_periode')
            ->orderBy('t_periode.tanggal_mulai')
            ->get();

        // Format untuk chart
        $labels = $prestasiPerPeriode->pluck('semester_periode')->toArray();
        $values = $prestasiPerPeriode->pluck('jumlah')->toArray();

        return [$labels, $values];
    }
}

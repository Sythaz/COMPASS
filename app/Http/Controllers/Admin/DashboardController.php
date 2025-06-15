<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\PendaftaranLombaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

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

        return view('admin.index', compact(
            'breadcrumb',
            'persentasePrestasiMahasiswa',
            'jumlahLombaAktif',
            'jmlPrestasiSebulanTerakhir',
            'topMahasiswa',
            'lombaSedangAktif',
            'chartPieData',
            'labels',
            'values'
        ));
    }

    // Warna untuk chart pie
    public const CHART_COLORS = [
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

    public function export()
    {
        $current = Carbon::now();
        $startYear = $current->copy()->startOfYear();
        $endYear = $current->copy()->endOfYear();
        $sebulanTerakhir = $current->copy()->subMonth();

        $totalMhs = DB::table('t_mahasiswa')->count();
        $totalPrestasi = DB::table('t_prestasi')
            ->join('t_prestasi_mahasiswa', 't_prestasi.prestasi_id', '=', 't_prestasi_mahasiswa.prestasi_id')
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->whereBetween('t_prestasi.tanggal_prestasi', [$startYear, $endYear])
            ->distinct('t_prestasi_mahasiswa.mahasiswa_id')
            ->count('t_prestasi_mahasiswa.mahasiswa_id');

        $persentase = $totalMhs > 0 ? floor($totalPrestasi / $totalMhs * 100) : 0;

        $jumlahLombaAktif = DB::table('t_lomba')
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('akhir_registrasi_lomba', '>=', Carbon::today())
            ->count();

        $prestasiSebulan = DB::table('t_prestasi')
            ->join('t_prestasi_mahasiswa', 't_prestasi.prestasi_id', '=', 't_prestasi_mahasiswa.prestasi_id')
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->whereBetween('t_prestasi.tanggal_prestasi', [$sebulanTerakhir, $current])
            ->distinct('t_prestasi_mahasiswa.mahasiswa_id')
            ->count('t_prestasi_mahasiswa.mahasiswa_id');

        [$startSemester, $endSemester] = $this->getSemesterDate();
        $topMahasiswa = DB::table('t_mahasiswa')
            ->join('t_prestasi_mahasiswa', 't_mahasiswa.mahasiswa_id', '=', 't_prestasi_mahasiswa.mahasiswa_id')
            ->join('t_prestasi', 't_prestasi_mahasiswa.prestasi_id', '=', 't_prestasi.prestasi_id')
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->whereBetween('t_prestasi.tanggal_prestasi', [$startSemester, $endSemester])
            ->select('t_mahasiswa.nama_mahasiswa', DB::raw('COUNT(t_prestasi.prestasi_id) as total'))
            ->groupBy('t_mahasiswa.nama_mahasiswa')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $kategori = DB::table('t_kategori')
            ->leftJoin('t_prestasi', 't_kategori.kategori_id', '=', 't_prestasi.kategori_id')
            ->where('t_prestasi.status_verifikasi', 'Terverifikasi')
            ->select('t_kategori.nama_kategori', DB::raw('COUNT(t_prestasi.prestasi_id) as jumlah'))
            ->groupBy('t_kategori.nama_kategori')
            ->get();

        $periode = DB::table('t_periode')
            ->orderBy('tanggal_mulai', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        $perkembangan = [];
        foreach ($periode as $p) {
            $jumlah = DB::table('t_prestasi')
                ->where('periode_id', $p->periode_id)
                ->where('status_verifikasi', 'Terverifikasi')
                ->count();
            $perkembangan[] = [$p->semester_periode, $jumlah];
        }

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Ringkasan');

        $bold = ['font' => ['bold' => true]];
        $borderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ];
        $sheet->getStyle('B1:B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E1:E100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // HEADER
        $bold = ['font' => ['bold' => true]];
        $sheet->setCellValue('A1', '--- RINGKASAN ---');
        $sheet->setCellValue('A2', 'Persentase Mahasiswa Berprestasi');
        $sheet->setCellValue('B2', $persentase . '%');
        $sheet->setCellValue('A3', 'Jumlah Lomba Aktif');
        $sheet->setCellValue('B3', $jumlahLombaAktif);
        $sheet->setCellValue('A4', 'Mahasiswa Berprestasi Sebulan Terakhir');
        $sheet->setCellValue('B4', $prestasiSebulan);

        $sheet->getStyle('A1:B1')->applyFromArray($bold);
        $sheet->getStyle('A2:A4')->applyFromArray($bold);
        $sheet->getStyle('A2:B4')->applyFromArray($borderAll);

        // MAHASISWA TERAKTIF
        $sheet->setCellValue('A6', '--- MAHASISWA TERAKTIF ---');
        $sheet->setCellValue('A7', 'Nama Mahasiswa');
        $sheet->setCellValue('B7', 'Jumlah Prestasi');
        $sheet->getStyle('A6:B7')->applyFromArray($bold);

        // Isi data
        $row = 8;
        foreach ($topMahasiswa as $mhs) {
            $sheet->setCellValue("A$row", $mhs->nama_mahasiswa);
            $sheet->setCellValue("B$row", $mhs->total);
            $row++;
        }
        // Tambahkan border ke seluruh area tabel A7 sampai B (baris terakhir)
        $lastDataRow = $row - 1;
        $sheet->getStyle("A7:B$lastDataRow")->applyFromArray($borderAll);

        // DOMINASI BIDANG PRESTASI (PIE)
        $sheet->setCellValue("D1", '--- DOMINASI BIDANG PRESTASI ---');
        $sheet->setCellValue("D2", 'Bidang');
        $sheet->setCellValue("E2", 'Jumlah');
        $sheet->getStyle('D1:E2')->applyFromArray($bold);

        $kategoriStartRow = 3;
        foreach ($kategori as $kat) {
            $sheet->setCellValue("D$kategoriStartRow", $kat->nama_kategori);
            $sheet->setCellValue("E$kategoriStartRow", $kat->jumlah);
            $kategoriStartRow++;
        }
        $kategoriEndRow = $kategoriStartRow - 1;
        $sheet->getStyle("D2:E$kategoriEndRow")->applyFromArray($borderAll);

        // PIE CHART
        $dataSeriesLabels = [
            new DataSeriesValues('String', 'Ringkasan!$E$2', null, 1),
        ];
        $xAxisTickValues = [
            new DataSeriesValues('String', 'Ringkasan!$D$3:$D$' . ($kategoriStartRow - 1), null, 4),
        ];
        $dataSeriesValues = [
            new DataSeriesValues('Number', 'Ringkasan!$E$3:$E$' . ($kategoriStartRow - 1), null, 4),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $chart = new Chart(
            'Dominasi Prestasi',
            new Title('Dominasi Bidang Prestasi'),
            $legend,
            $plotArea
        );
        $chart->setTopLeftPosition('G2');
        $chart->setBottomRightPosition('L15');
        $sheet->addChart($chart);

        // PERKEMBANGAN PRESTASI (BAR)
        $row = 16;
        $sheet->setCellValue("A$row", '--- PERKEMBANGAN PRESTASI (6 Periode Terakhir) ---');
        $sheet->setCellValue("A" . ($row + 1), 'Semester');
        $sheet->setCellValue("B" . ($row + 1), 'Jumlah Prestasi');
        $sheet->getStyle("A$row:B" . ($row + 1))->applyFromArray($bold);

        $row += 2; // baris pertama data
        $startDataRow = $row;

        foreach ($perkembangan as $p) {
            $sheet->setCellValue("A$row", $p[0]);
            $sheet->setCellValue("B$row", $p[1]);
            $row++;
        }

        $endDataRow = $row - 1;
        $startFullTable = $startDataRow - 1; // termasuk judul kolom

        // Terapkan border dari judul kolom sampai akhir data
        $sheet->getStyle("A$startFullTable:B$endDataRow")->applyFromArray($borderAll);

        // Bar Chart
        $barRowStart = $row - count($perkembangan);
        $barRowEnd = $row - 1;
        $barChart = new Chart(
            'Perkembangan',
            new Title('Perkembangan Prestasi'),
            new Legend(Legend::POSITION_RIGHT, null, false),
            new PlotArea(null, [
                new DataSeries(
                    DataSeries::TYPE_BARCHART,
                    DataSeries::GROUPING_CLUSTERED,
                    range(0, 1),
                    [],
                    [new DataSeriesValues('String', "Ringkasan!A$barRowStart:A$barRowEnd", null, 6)],
                    [new DataSeriesValues('Number', "Ringkasan!B$barRowStart:B$barRowEnd", null, 6)]
                )
            ])
        );
        $barChart->setTopLeftPosition('G17');
        $barChart->setBottomRightPosition('L30');
        $sheet->addChart($barChart);

        // Autofit semua kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output XLSX
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);

        $filename = 'Laporan_Statistik_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }


    private function getSemesterDate(): array
    {
        $now = Carbon::now();
        if ($now->month >= 8 && $now->month <= 12) {
            return [Carbon::create($now->year, 8, 1), Carbon::create($now->year, 12, 31)];
        } else {
            return [Carbon::create($now->year, 1, 1), Carbon::create($now->year, 7, 31)];
        }
    }
}

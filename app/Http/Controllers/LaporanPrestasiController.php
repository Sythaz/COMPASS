<?php

namespace App\Http\Controllers;

use App\Models\PrestasiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanPrestasiController extends Controller
{
    // Export Excel (Admin)
    public function export_excel(Request $request)
    {
        $status = $request->input('status');
        $periode = $request->input('periode_id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $headers = [
            'No', 'Nama Mahasiswa', 'NIM', 'Kategori', 'Nama Lomba', 'Jenis Prestasi', 'Peran',
            'Juara', 'Tanggal', 'Dosen Pembimbing', 'Tingkat Lomba', 'Periode', 'Status Verifikasi'
        ];

        foreach ($headers as $index => $title) {
            $column = chr(65 + $index); // 'A', 'B', 'C', dst
            $sheet->setCellValue($column . '1', $title);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
        }

        $row = 2;
        $no = 1;

        // Query dengan filter
        $query = PrestasiModel::with(['mahasiswa', 'kategori', 'lomba', 'dosen', 'tingkat_lomba', 'periode']);

        if ($status) {
            $query->where('status_verifikasi', $status);
        }

        if ($periode) {
            $query->where('periode_id', $periode);
        }

        $prestasiList = $query->get();

        foreach ($prestasiList as $prestasi) {
            foreach ($prestasi->mahasiswa as $mahasiswa) {
                $sheet->setCellValueExplicit('A' . $row, $no++, DataType::TYPE_NUMERIC);
                $sheet->setCellValue('B' . $row, $mahasiswa->nama_mahasiswa ?? '-');
                $sheet->setCellValueExplicit('C' . $row, $mahasiswa->nim_mahasiswa ?? '-', DataType::TYPE_STRING);
                $sheet->setCellValue('D' . $row, $prestasi->kategori->nama_kategori ?? '-');
                $sheet->setCellValue('E' . $row, $prestasi->lomba->nama_lomba ?? $prestasi->lomba_lainnya ?? '-');
                $sheet->setCellValue('F' . $row, $prestasi->jenis_prestasi ?? '-');
                $sheet->setCellValue('G' . $row, $mahasiswa->pivot->peran ?? '-');
                $sheet->setCellValue('H' . $row, $prestasi->juara_prestasi ?? '-');
                $sheet->setCellValue('I' . $row, $prestasi->tanggal_prestasi ?? '-');
                $sheet->setCellValue('J' . $row, $prestasi->dosen->nama_dosen ?? 'Tidak ada');
                $sheet->setCellValue('K' . $row, $prestasi->tingkat_lomba->nama_tingkat ?? '-');
                $sheet->setCellValue('L' . $row, $prestasi->periode->semester_periode ?? '-');
                $sheet->setCellValue('M' . $row, $prestasi->status_verifikasi ?? '-');

                if ($row % 2 == 0) {
                    $sheet->getStyle("A$row:M$row")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F2F2F2');
                }

                $row++;
            }
        }

        $lastRow = $row - 1;
        $sheet->getStyle("A1:M$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_prestasi_' . now()->format('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }


    // Export PDF
    public function export_pdf(Request $request)
    {
        $status = $request->input('status');
        $periode = $request->input('periode_id');

        $query = PrestasiModel::with(['mahasiswa', 'kategori', 'lomba', 'dosen', 'tingkat_lomba', 'periode'])
            ->orderBy('tanggal_prestasi', 'desc');

        if ($status) {
            $query->where('status_verifikasi', $status);
        }

        if ($periode) {
            $query->where('periode_id', $periode);
        }

        $prestasiList = $query->get();

        $html = view('admin.manajemen-prestasi.kelola-prestasi.export_pdf', [
            'prestasiList' => $prestasiList
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('Laporan Prestasi Mahasiswa ' . date('Y-m-d H:i:s') . '.pdf');
    }

}

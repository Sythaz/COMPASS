<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranLombaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPendaftaranController extends Controller
{
    public function export_excel(Request $request)
    {
        $status = $request->input('status_verifikasi');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'No', 'Nama Mahasiswa', 'NIM', 'Nama Lomba', 'Tipe Lomba', 'Bukti Pendaftaran',
            'Status Pendaftaran', 'Alasan Penolakan'
        ];

        foreach ($headers as $index => $title) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . '1', $title);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
        }

        $row = 2;
        $no = 1;

        $query = PendaftaranLombaModel::with(['mahasiswa', 'lomba', 'anggota']);

        if ($status) {
            $query->where('status_pendaftaran', $status);
        }

        $pendaftaranList = $query->get();

        foreach ($pendaftaranList as $pendaftaran) {
            $tipeLomba = $pendaftaran->lomba->tipe_lomba ?? '-';
            $namaLomba = $pendaftaran->lomba->nama_lomba ?? '-';

            // Ketua tim
            $sheet->setCellValueExplicit('A' . $row, $no++, DataType::TYPE_NUMERIC);
            $sheet->setCellValue('B' . $row, $pendaftaran->mahasiswa->nama_mahasiswa ?? '-');
            $sheet->setCellValueExplicit('C' . $row, $pendaftaran->mahasiswa->nim_mahasiswa ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $namaLomba);
            $sheet->setCellValue('E' . $row, $tipeLomba);
            $sheet->setCellValue('F' . $row, $pendaftaran->bukti_pendaftaran ?? '-');
            $sheet->setCellValue('G' . $row, $pendaftaran->status_pendaftaran ?? '-');
            $sheet->setCellValue('H' . $row, $pendaftaran->alasan_tolak ?? '-');

            if ($row % 2 == 0) {
                $sheet->getStyle("A$row:H$row")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }

            $row++;

            // Anggota tim
            foreach ($pendaftaran->anggota as $anggota) {
                $sheet->setCellValueExplicit('A' . $row, $no++, DataType::TYPE_NUMERIC);
                $sheet->setCellValue('B' . $row, $anggota->nama_mahasiswa ?? '-');
                $sheet->setCellValueExplicit('C' . $row, $anggota->nim_mahasiswa ?? '-', DataType::TYPE_STRING);
                $sheet->setCellValue('D' . $row, $namaLomba);
                $sheet->setCellValue('E' . $row, $tipeLomba);
                $sheet->setCellValue('F' . $row, $pendaftaran->bukti_pendaftaran ?? '-');
                $sheet->setCellValue('G' . $row, $pendaftaran->status_pendaftaran ?? '-');
                $sheet->setCellValue('H' . $row, $pendaftaran->alasan_tolak ?? '-');

                if ($row % 2 == 0) {
                    $sheet->getStyle("A$row:H$row")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F2F2F2');
                }

                $row++;
            }
        }

        $lastRow = $row - 1;
        $sheet->getStyle("A1:H$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'pendaftaran_lomba_' . now()->format('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

    public function export_pdf(Request $request)
    {
        $status = $request->input('status_verifikasi');

        $query = PendaftaranLombaModel::with(['mahasiswa', 'anggota', 'lomba'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status_pendaftaran', $status);
        }

        $pendaftaranList = $query->get();

        $html = view('admin.manajemen-lomba.verifikasi-pendaftaran.export_pdf', [
            'pendaftaranList' => $pendaftaranList
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('Laporan_Pendaftaran_Lomba_' . date('Y-m-d_His') . '.pdf');
    }

}

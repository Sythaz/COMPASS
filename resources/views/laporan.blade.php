<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cetak Laporan Prestasi</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }

        .signature-block {
            padding-top: 20px;
            padding-bottom: 30px;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('polinema-bw.png') }}" width="100px">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN
                    TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341)
                    404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <br>
    <h3 class="text-center">SURAT KETERANGAN PRESTASI MAHASISWA</h3>

    <p class="font-12" style="text-align: justify;">
        Dengan hormat,<br><br>
        Yang bertanda tangan di bawah ini menerangkan bahwa mahasiswa berikut telah meraih prestasi dalam kegiatan
        sebagai berikut:
    </p>

    @if ($prestasi)
        <table class="font-12" style="text-align: justify; border-spacing: 0 5px;">
            <tr>
                <td><strong>Nama Lomba</strong></td>
                <td>:</td>
                <td>{{ $prestasi->lomba->nama_lomba ?? $prestasi->lomba_lainnya }}</td>
            </tr>
            <tr>
                <td><strong>Tingkat</strong></td>
                <td>:</td>
                <td>{{ $prestasi->tingkat_lomba->nama_tingkat }}</td>
            </tr>
            <tr>
                <td><strong>Kategori</strong></td>
                <td>:</td>
                <td>{{ $prestasi->kategori->nama_kategori }}</td>
            </tr>
            <tr>
                <td><strong>Juara</strong></td>
                <td>:</td>
                <td>{{ $prestasi->juara_prestasi }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Dosen Pembimbing</strong></td>
                <td>:</td>
                <td>{{ optional($prestasi->dosen)->nama_dosen ?? 'Tidak Ada' }}</td>
            </tr>
            <tr>
                <td><strong>Jenis Prestasi</strong></td>
                <td>:</td>
                <td>{{ $prestasi->jenis_prestasi }}</td>
            </tr>
            <tr>
                <td valign="top"><strong>Anggota</strong></td>
                <td valign="top">:</td>
                <td>
                    @foreach ($prestasi->mahasiswa as $mhs)
                        {{ $mhs->nama_mahasiswa ?? '-' }} - {{ $mhs->nim_mahasiswa ?? '-' }}<br>
                    @endforeach
                </td>
            </tr>
        </table>
    @endif

    <p class="font-12" style="text-align: justify;">
        Prestasi tersebut merupakan bentuk kontribusi mahasiswa dalam bidang akademik maupun non-akademik yang patut
        diapresiasi.
        Surat keterangan ini dibuat sebagai bukti dokumentasi dan dapat digunakan sebagaimana mestinya.
    </p>

    {{-- Tanda tangan --}}
    <br>
    <table>
        <tr>
            <td width="60%"></td>
            <td class="text-center signature-block">
                Malang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Mengetahui,<br>
                <strong>Pejabat Berwenang</strong><br><br><br><br>
                <u>______________________________</u><br>
            </td>
        </tr>
    </table>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cetak Laporan Statistik Mahasiswa</title>
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

        .no-border,
        .no-border td {
            border: none !important;
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

    <h2 style="text-align: center;">LAPORAN STATISTIK MAHASISWA</h2>
    <p>
        Laporan ini disusun sebagai bentuk evaluasi dan dokumentasi atas pencapaian mahasiswa dalam bidang prestasi
        selama periode akademik yang berlangsung. Data yang ditampilkan mencakup berbagai indikator penting seperti
        partisipasi mahasiswa dalam lomba, distribusi prestasi berdasarkan bidang, serta tren perkembangan capaian
        prestasi antar semester.
    </p>
    <p>
        Diharapkan laporan ini dapat menjadi bahan pertimbangan dalam pengambilan kebijakan dan strategi pengembangan
        potensi mahasiswa ke depan, serta menjadi motivasi bagi seluruh civitas akademika untuk terus mendorong
        terciptanya budaya prestasi di lingkungan kampus.
    </p>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        th:last-child,
        td:last-child {
            text-align: center;
        }

        p {
            text-align: justify;
        }

        .section {
            page-break-inside: avoid;
            margin-bottom: 30px;
        }
    </style>

    <div class="section">
        <h3>Ringkasan</h3>
        <p>
            Bagian ini memberikan gambaran umum mengenai kondisi prestasi mahasiswa secara keseluruhan. Dengan melihat
            indikator seperti persentase mahasiswa yang aktif dalam kegiatan lomba dan prestasi, jumlah lomba yang masih
            berlangsung saat ini, serta jumlah mahasiswa yang berhasil meraih prestasi dalam sebulan terakhir, pihak
            institusi dapat mengevaluasi seberapa efektif program pembinaan dan pendampingan yang telah berjalan.
        </p>
        <table>
            <tr>
                <th>Persentase Mahasiswa Berprestasi</th>
                <td>{{ $persentase }}%</td>
            </tr>
            <tr>
                <th>Jumlah Lomba Aktif</th>
                <td>{{ $jumlahLombaAktif }}</td>
            </tr>
            <tr>
                <th>Mahasiswa Berprestasi Sebulan Terakhir</th>
                <td>{{ $prestasiSebulan }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Mahasiswa Teraktif (Semester Ini)</h3>
        <p>
            Tabel berikut menampilkan daftar mahasiswa yang paling aktif dan konsisten dalam mengikuti serta menorehkan
            prestasi pada semester ini. Nama-nama yang tercantum di sini menunjukkan semangat dan komitmen yang tinggi
            dalam pengembangan diri melalui kompetisi. Hal ini dapat menjadi inspirasi bagi mahasiswa lain dan bahan
            evaluasi bagi pembina kemahasiswaan untuk terus memberikan dukungan.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Jumlah Prestasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topMahasiswa as $mhs)
                    <tr>
                        <td>{{ $mhs->nama_mahasiswa }}</td>
                        <td>{{ $mhs->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Dominasi Bidang Prestasi</h3>
        <p>
            Data pada bagian ini menyajikan distribusi prestasi mahasiswa berdasarkan bidang atau kategori lomba. Hal
            ini berguna untuk melihat kecenderungan dan minat mahasiswa terhadap bidang-bidang tertentu, apakah lebih
            dominan di bidang akademik, seni, olahraga, teknologi, atau bidang lainnya. Informasi ini juga penting
            sebagai dasar strategi pengembangan potensi dan penyusunan program pembinaan di masa mendatang.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Bidang</th>
                    <th>Jumlah Prestasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategori as $kat)
                    <tr>
                        <td>{{ $kat->nama_kategori }}</td>
                        <td>{{ $kat->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Perkembangan Prestasi (6 Periode Terakhir)</h3>
        <p>
            Bagian ini memperlihatkan tren pertumbuhan prestasi mahasiswa dalam enam periode akademik terakhir.
            Tujuannya adalah untuk menilai efektivitas program kerja dan pembinaan yang telah dilaksanakan. Jika
            terlihat peningkatan yang konsisten, maka hal tersebut mencerminkan keberhasilan sistem pembinaan.
            Sebaliknya, jika terjadi penurunan, perlu dilakukan evaluasi menyeluruh agar kualitas pembinaan dapat terus
            meningkat.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>Jumlah Prestasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perkembangan as $p)
                    <tr>
                        <td>{{ $p['semester'] }}</td>
                        <td>{{ $p['jumlah'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tanda tangan --}}
    <br>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 60%; border: none;"></td>
            <td style="text-align: center; border: none;">
                Malang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Mengetahui,<br>
                <strong>Pejabat Berwenang</strong><br><br><br><br>
                <u>______________________________</u><br>
            </td>
        </tr>
    </table>
</body>

</html>

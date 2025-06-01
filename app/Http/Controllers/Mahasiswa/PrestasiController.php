<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TingkatLombaModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\KategoriModel;
use App\Models\DosenModel;
use App\Models\PeriodeModel;
use App\Models\PrestasiModel;


class PrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Prestasi Mahasiswa', 'Prestasi']
        ];

        return view('mahasiswa.prestasi.index', compact('breadcrumb'));
    }

    public function create_prestasi()
    {

        $daftarLomba = LombaModel::with('tingkat_lomba')
            ->where('status_lomba', 'Aktif')
            ->get();
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarDosen = DosenModel::where('status', 'Aktif')->get();
        $daftarPeriode = PeriodeModel::all();
        $daftarTingkatLomba = TingkatLombaModel::all(); // Ambil data tingkat lomba dari DB

        return view('mahasiswa.prestasi.create', compact(
            'daftarMahasiswa',
            'daftarLomba',
            'daftarKategori',
            'daftarDosen',
            'daftarPeriode',
            'daftarTingkatLomba'
        ));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:t_mahasiswa,mahasiswa_id',
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'dosen_id' => 'nullable|exists:t_dosen,dosen_id',
            'kategori_id' => 'required|exists:t_kategori,kategori_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'tanggal_prestasi' => 'required|date',
            'juara_prestasi' => 'required|string|max:50',
            'jenis_prestasi' => 'required|string',
            'img_kegiatan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_prestasi' => 'nullable|mimes:pdf|max:2048',
            'surat_tugas_prestasi' => 'nullable|mimes:pdf|max:2048',
            'status_verifikasi' => 'required|in:Terverifikasi,Valid,Menunggu,Ditolak',
        ]);

        try {
            // Simpan prestasi
            $prestasi = PrestasiModel::create($validated);

            // Upload file jika ada
            if ($request->hasFile('img_kegiatan')) {
                $originalFilename = $request->file('img_kegiatan')->getClientOriginalName();
                $filename = 'img-prestasi/' . $prestasi->prestasi_id . '-' . time() . '-' . $originalFilename;
                $request->file('img_kegiatan')->storeAs('public/prestasi/img-prestasi', $filename);
                $prestasi->update(['img_kegiatan' => $filename]);
            }

            if ($request->hasFile('bukti_prestasi')) {
                $filename = 'bukti_' . $prestasi->prestasi_id . '.pdf';
                $request->file('bukti_prestasi')->storeAs('public/prestasi/bukti', $filename);
                $prestasi->update(['bukti_prestasi' => $filename]);
            }

            if ($request->hasFile('surat_tugas_prestasi')) {
                $filename = 'surat_' . $prestasi->prestasi_id . '.pdf';
                $request->file('surat_tugas_prestasi')->storeAs('public/prestasi/surat', $filename);
                $prestasi->update(['surat_tugas_prestasi' => $filename]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data prestasi berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}

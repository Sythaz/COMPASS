<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\TingkatLombaModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\KategoriModel;
use App\Models\DosenModel;
use App\Models\PeriodeModel;
use App\Models\PrestasiModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;



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
        // Validasi user login harus jadi anggota tim
        // $mahasiswaLoginId = auth()->user()->mahasiswa->mahasiswa_id ?? null;

        // if (!$mahasiswaLoginId || !in_array($mahasiswaLoginId, $request->input('mahasiswa_id', []))) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->withErrors(['mahasiswa_id' => 'Mahasiswa yang login harus menjadi salah satu anggota tim (Ketua atau Anggota).']);
        // }

        // Jika 'lomba_id' adalah 'lainnya', ubah 'jenis_prestasi' ke lowercase
        if ($request->lomba_id === 'lainnya' && $request->has('jenis_prestasi')) {
            $request->merge([
                'jenis_prestasi' => strtolower($request->jenis_prestasi),
            ]);
        }

        // Jika bukan 'lainnya', hapus field yang tidak relevan agar tidak ikut proses validasi dan simpan
        if ($request->lomba_id !== 'lainnya') {
            $request->request->remove('lomba_lainnya');
            $request->request->remove('kategori_id_manual');
            $request->request->remove('jenis_prestasi');
            $request->request->remove('tingkat_lomba_id');
        }

        // Validasi input
        $request->validate([
            'mahasiswa_id' => 'required|array|min:1',
            'tanggal_prestasi' => 'required|date',
            'juara_prestasi' => 'required|string',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'status_prestasi' => 'nullable|in:Aktif,Tidak Aktif',
            'status_verifikasi' => 'nullable|in:Ditolak,Menunggu,Valid,Terverifikasi',

            'lomba_id' => 'required|string',
            'lomba_lainnya' => 'required_if:lomba_id,lainnya|string|max:255',

            'kategori_id_manual' => 'required_if:lomba_id,lainnya|array|min:1',
            'jenis_prestasi' => [
                'required_if:lomba_id,lainnya',
                Rule::in(['individu', 'tim']),
            ],
            'tingkat_lomba_id' => 'required_if:lomba_id,lainnya|exists:t_tingkat_lomba,tingkat_lomba_id',

            'dosen_id' => 'nullable|exists:t_dosen,dosen_id',
        ]);

        // Validasi 'lomba_id' ada di tabel jika bukan 'lainnya'
        if ($request->lomba_id !== 'lainnya') {
            $request->validate([
                'lomba_id' => 'exists:t_lomba,lomba_id',
            ]);
        }

        DB::beginTransaction();
        try {
            $lombaId = $request->lomba_id !== 'lainnya' ? $request->lomba_id : null;
            $lombaLainnya = $request->lomba_id === 'lainnya' ? $request->lomba_lainnya : null;

            if ($lombaId) {
                $lomba = LombaModel::findOrFail($lombaId);
                $jenisPrestasi = $lomba->tipe_lomba;
                $tingkatLombaId = $lomba->tingkat_lomba_id;
            } else {
                $jenisPrestasi = ucfirst(strtolower($request->jenis_prestasi));
                $tingkatLombaId = $request->tingkat_lomba_id;
            }

            $statusPrestasi = $request->status_prestasi ?? 'Aktif';
            $statusVerifikasi = $request->status_verifikasi ?? 'Menunggu';

            $prestasi = PrestasiModel::create([
                'lomba_id' => $lombaId,
                'lomba_lainnya' => $lombaLainnya,
                'dosen_id' => $request->dosen_id,
                'tingkat_lomba_id' => $tingkatLombaId,
                'periode_id' => $request->periode_id,
                'tanggal_prestasi' => $request->tanggal_prestasi,
                'juara_prestasi' => $request->juara_prestasi,
                'jenis_prestasi' => $jenisPrestasi,
                'status_prestasi' => $statusPrestasi,
                'status_verifikasi' => $statusVerifikasi,
            ]);

            if ($request->lomba_id === 'lainnya') {
                $prestasi->kategori()->attach($request->kategori_id_manual);
            }

            foreach ($request->mahasiswa_id as $index => $id) {
                $prestasi->mahasiswa()->attach($id, [
                    'peran' => $index === 0 ? 'Ketua' : 'Anggota',
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Prestasi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal menyimpan prestasi: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan prestasi: ' . $e->getMessage());
        }
    }

    public function cekLombaDuplicate(Request $request)
    {
        $mahasiswaId = auth()->user()->mahasiswa->mahasiswa_id;
        $lombaId = $request->input('lomba_id');

        if (!$lombaId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lomba tidak boleh kosong.'
            ], 422);
        }

        // Cek prestasi dengan join tabel pivot secara manual supaya gak ambigu
        $exists = PrestasiModel::where('lomba_id', $lombaId)
            ->whereHas('mahasiswa', function ($query) use ($mahasiswaId) {
                $query->where('t_prestasi_mahasiswa.mahasiswa_id', $mahasiswaId);
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah pernah submit lomba ini sebelumnya.'
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lomba belum pernah di-submit sebelumnya.'
        ], 200);
    }



}

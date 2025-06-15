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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PrestasiController extends Controller
{
    private function getStatusBadge($status_verifikasi)
    {
        $status = strtolower($status_verifikasi ?? '');

        switch ($status) {
            case 'terverifikasi':
                return '<span class="label label-success">' . e(ucwords($status)) . '</span>';
            case 'valid':
                return '<span class="label label-info">' . e(ucwords($status)) . '</span>';
            case 'menunggu':
                return '<span class="label label-warning">' . e(ucwords($status)) . '</span>';
            case 'ditolak':
                return '<span class="label label-danger">' . e(ucwords($status)) . '</span>';
            default:
                return '<span class="label label-default">Tidak Diketahui</span>';
        }
    }

    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Prestasi Mahasiswa', 'Prestasi']
        ];

        return view('mahasiswa.prestasi.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $mahasiswaId = Auth::user()->mahasiswa->mahasiswa_id;

        // Ambil semua prestasi yang berkaitan dengan mahasiswa (baik sebagai ketua maupun anggota)
        $data = PrestasiModel::with(['lomba', 'dosen'])
            ->whereHas('mahasiswa', function ($query) use ($mahasiswaId) {
                $query->where('t_prestasi_mahasiswa.mahasiswa_id', $mahasiswaId);
            })
            ->select('t_prestasi.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', function ($row) {
                return $row->lomba->nama_lomba ?? $row->lomba_lainnya ?? '-';
            })
            ->addColumn('juara_prestasi', function ($row) {
                return $row->juara_prestasi ?? '-';
            })
            ->addColumn('dosen_pembimbing', function ($row) {
                return $row->dosen->nama_dosen ?? '<span class="text-muted">Tidak ada</span>';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status);
            })
            ->addColumn('aksi', function ($row) {
                $urlDetail = route('mhs.prestasi.showAjax', $row->prestasi_id);
                $btnDetail = '<button data-url="' . $urlDetail . '" class="btn btn-info btn-sm btn-detail-prestasi">Detail</button>';

                $btnCetak = '';
                if ($row->status_verifikasi === 'Terverifikasi') {
                    $urlCetak = route('laporan.prestasi.pdf', ['id' => $row->prestasi_id]);
                    $btnCetak = '<a href="' . $urlCetak . '" target="_blank" class="btn btn-success btn-sm ml-1">Cetak</a>';
                }

                $btnEdit = '';
                if ($row->status_verifikasi === 'Ditolak') {
                    $urlEdit = route('mhs.prestasi.editAjax', $row->prestasi_id);
                    $btnEdit = '<a href="#" onclick="modalAction(\'' . $urlEdit . '\')" class="btn btn-warning btn-sm ml-1">Edit</a>';
                }

                return $btnDetail . ' ' . $btnEdit . ' ' . $btnCetak;
            })

            ->rawColumns(['dosen_pembimbing', 'status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $prestasi = PrestasiModel::with(['mahasiswa', 'dosen', 'kategori', 'tingkat_lomba', 'periode'])->findOrFail($id);

        $statusBadge = $this->getStatusBadge($prestasi->status_verifikasi);

        return view('mahasiswa.prestasi.show', compact('prestasi', 'statusBadge'))->render();
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
        // Manipulasi data sebelum validasi
        if ($request->input('lomba_id') === 'lainnya') {
            $request->merge(['lomba_id' => null]);
        }

        // Validasi input utama
        $validated = $request->validate(
            [
                'lomba_id' => 'nullable|required_without:lomba_lainnya|exists:t_lomba,lomba_id',
                'lomba_lainnya' => 'nullable|required_without:lomba_id|string|max:255',
                'dosen_id' => 'nullable|exists:t_dosen,dosen_id',
                'kategori_id' => 'required|exists:t_kategori,kategori_id',
                'periode_id' => 'required|exists:t_periode,periode_id',
                'tanggal_prestasi' => 'required|date',
                'jenis_prestasi' => 'nullable|string|max:255',
                'juara_prestasi' => 'required|string|max:255',
                'tingkat_lomba_id' => 'nullable|exists:t_tingkat_lomba,tingkat_lomba_id',
                'img_kegiatan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'bukti_prestasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                'surat_tugas_prestasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            ],
            ['tanggal_prestasi.before_or_equal' => 'Tanggal prestasi tidak boleh lebih dari hari ini.',]
        );

        // Lengkapi data dari relasi lomba jika tersedia
        if (!empty($validated['lomba_id'])) {
            $lomba = LombaModel::with('tingkat_lomba')->find($validated['lomba_id']);
            if ($lomba) {
                if (empty($validated['tingkat_lomba_id']) && $lomba->tingkat_lomba) {
                    $validated['tingkat_lomba_id'] = $lomba->tingkat_lomba->tingkat_lomba_id;
                }
                if (empty($validated['jenis_prestasi'])) {
                    $validated['jenis_prestasi'] = $lomba->tipe_lomba;
                }
            }
            $validated['lomba_lainnya'] = null;
        } else {
            $validated['lomba_id'] = null;
        }

        // Simpan data prestasi
        $prestasi = PrestasiModel::create($validated);

        // Upload file baru jika ada
        if ($request->hasFile('img_kegiatan')) {
            $filename = 'img_' . $prestasi->prestasi_id . '.' . $request->file('img_kegiatan')->getClientOriginalExtension();
            $request->file('img_kegiatan')->storeAs('public/prestasi/img', $filename);
            $prestasi->update(['img_kegiatan' => $filename]);
        }

        if ($request->hasFile('bukti_prestasi')) {
            $originalFilename = $request->file('bukti_prestasi')->getClientOriginalName();
            $filename = 'bukti_' . $prestasi->prestasi_id . '_' . time() . '_' . $originalFilename;
            $request->file('bukti_prestasi')->storeAs('public/prestasi/bukti', $filename);
            $prestasi->update(['bukti_prestasi' => $filename]);
        }

        if ($request->hasFile('surat_tugas_prestasi')) {
            $originalFilename = $request->file('surat_tugas_prestasi')->getClientOriginalName();
            $filename = 'surat_' . $prestasi->prestasi_id . '_' . time() . '_' . $originalFilename;
            $request->file('surat_tugas_prestasi')->storeAs('public/prestasi/surat', $filename);
            $prestasi->update(['surat_tugas_prestasi' => $filename]);
        }

        // Simpan relasi mahasiswa jika ada
        if ($request->filled('mahasiswa_id')) {
            $mahasiswaIds = $request->input('mahasiswa_id');
            $pivotData = [];
            foreach ($mahasiswaIds as $index => $id) {
                $pivotData[$id] = [
                    'peran' => $index === 0 ? 'Ketua' : 'Anggota'
                ];
            }
            $prestasi->mahasiswa()->sync($pivotData);
        }

        return redirect()->back()->with('success', 'Data prestasi berhasil disimpan.');
    }

    public function editAjax($id)
    {
        $prestasi = PrestasiModel::with(['mahasiswa', 'dosen', 'kategori', 'tingkat_lomba', 'periode'])->findOrFail($id);
        $daftarLomba = LombaModel::with('tingkat_lomba')
            ->where('status_lomba', 'Aktif')
            ->get();
        $daftarMahasiswa = MahasiswaModel::where('status', 'Aktif')->get();
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarDosen = DosenModel::where('status', 'Aktif')->get();
        $daftarPeriode = PeriodeModel::all();
        $daftarTingkatLomba = TingkatLombaModel::all(); // Ambil data tingkat lomba dari DB

        $anggotaTim = $prestasi->mahasiswa->map(function ($m) {
            return [
                'mahasiswa_id' => $m->mahasiswa_id,
                'nama' => $m->nama_mahasiswa,
                'nim' => $m->nim_mahasiswa,
            ];
        })->toArray();


        return view('mahasiswa.prestasi.edit', compact(
            'prestasi',
            'daftarMahasiswa',
            'daftarLomba',
            'daftarKategori',
            'daftarDosen',
            'daftarPeriode',
            'daftarTingkatLomba',
            'anggotaTim'
        ));
    }

    public function update(Request $request, $id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        // Manipulasi data sebelum validasi
        if ($request->input('lomba_id') === 'lainnya') {
            $request->merge(['lomba_id' => null]);
        }

        // Validasi input utama
        $validated = $request->validate(
            [
                'lomba_id' => 'nullable|required_without:lomba_lainnya|exists:t_lomba,lomba_id',
                'lomba_lainnya' => 'nullable|required_without:lomba_id|string|max:255',
                'dosen_id' => 'nullable|exists:t_dosen,dosen_id',
                'kategori_id' => 'required|exists:t_kategori,kategori_id',
                'periode_id' => 'required|exists:t_periode,periode_id',
                'tanggal_prestasi' => 'required|date',
                'jenis_prestasi' => 'nullable|string|max:255',
                'juara_prestasi' => 'required|string|max:255',
                'tingkat_lomba_id' => 'nullable|exists:t_tingkat_lomba,tingkat_lomba_id',
                'img_kegiatan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'bukti_prestasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                'surat_tugas_prestasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            ],
            ['tanggal_prestasi.before_or_equal' => 'Tanggal prestasi tidak boleh lebih dari hari ini.',]
        );

        // Lengkapi data dari relasi lomba jika tersedia
        if (!empty($validated['lomba_id'])) {
            $lomba = LombaModel::with('tingkat_lomba')->find($validated['lomba_id']);
            if ($lomba) {
                if (empty($validated['tingkat_lomba_id']) && $lomba->tingkat_lomba) {
                    $validated['tingkat_lomba_id'] = $lomba->tingkat_lomba->tingkat_lomba_id;
                }
                if (empty($validated['jenis_prestasi'])) {
                    $validated['jenis_prestasi'] = $lomba->tipe_lomba;
                }
            }
            $validated['lomba_lainnya'] = null;
        } else {
            $validated['lomba_id'] = null;
        }

        // Update data prestasi
        $prestasi->update($validated);

        // Upload file baru jika ada
        if ($request->hasFile('img_kegiatan')) {
            $filename = 'img_' . $prestasi->prestasi_id . '.' . $request->file('img_kegiatan')->getClientOriginalExtension();
            $request->file('img_kegiatan')->storeAs('public/prestasi/img', $filename);
            $prestasi->update(['img_kegiatan' => $filename]);
        }
        if ($request->hasFile('img_kegiatan') && $prestasi->img_kegiatan) {
            Storage::delete('public/prestasi/img/' . $prestasi->img_kegiatan);
        }


        if ($request->hasFile('bukti_prestasi')) {
            $originalFilename = $request->file('bukti_prestasi')->getClientOriginalName();
            $filename = 'bukti_' . $prestasi->prestasi_id . '_' . time() . '_' . $originalFilename;
            $request->file('bukti_prestasi')->storeAs('public/prestasi/bukti', $filename);
            $prestasi->update(['bukti_prestasi' => $filename]);
        }
        if ($request->hasFile('bukti_prestasi') && $prestasi->bukti_prestasi) {
            Storage::delete('public/prestasi/bukti/' . $prestasi->bukti_prestasi);
        }

        if ($request->hasFile('surat_tugas_prestasi')) {
            $originalFilename = $request->file('surat_tugas_prestasi')->getClientOriginalName();
            $filename = 'surat_' . $prestasi->prestasi_id . '_' . time() . '_' . $originalFilename;
            $request->file('surat_tugas_prestasi')->storeAs('public/prestasi/surat', $filename);
            $prestasi->update(['surat_tugas_prestasi' => $filename]);
        }
        if ($request->hasFile('surat_tugas_prestasi') && $prestasi->surat_tugas_prestasi) {
            Storage::delete('public/prestasi/surat/' . $prestasi->surat_tugas_prestasi);
        }

        // Simpan relasi mahasiswa jika ada
        if ($request->filled('mahasiswa_id')) {
            $mahasiswaIds = $request->input('mahasiswa_id');
            $pivotData = [];
            foreach ($mahasiswaIds as $index => $id) {
                $pivotData[$id] = [
                    'peran' => $index === 0 ? 'Ketua' : 'Anggota'
                ];
            }
            // Sync mahasiswa dengan prestasi
            $prestasi->mahasiswa()->sync($pivotData);
        }

        $prestasi->status_verifikasi = 'Menunggu';
        $prestasi->save();

        return redirect()->back()->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function cekLombaDuplicate(Request $request)
    {
        $mahasiswaId = auth()->user()->mahasiswa->mahasiswa_id;
        $lombaId = $request->input('lomba_id');
        $lombaLainnya = $request->input('lomba_lainnya');

        if (!$lombaId && !$lombaLainnya) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lomba tidak boleh kosong.'
            ], 422);
        }

        $query = PrestasiModel::whereHas('mahasiswa', function ($query) use ($mahasiswaId) {
            $query->where('t_prestasi_mahasiswa.mahasiswa_id', $mahasiswaId);
        });

        if ($lombaId) {
            $query->where('lomba_id', $lombaId);
        } elseif ($lombaLainnya) {
            // Bisa kamu trim supaya tidak ada spasi ekstra, dan case insensitive
            $query->whereRaw('LOWER(lomba_lainnya) = ?', [strtolower(trim($lombaLainnya))]);
        }

        $exists = $query->exists();

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

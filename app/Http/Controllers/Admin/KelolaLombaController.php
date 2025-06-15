<?php

namespace App\Http\Controllers\Admin;

use App\Models\LombaModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PrometheeRekomendasiController;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\KategoriLombaModel;
use App\Models\KategoriModel;
use App\Models\MahasiswaModel;
use App\Models\PreferensiUserModel;
use App\Models\TingkatLombaModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KelolaLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Kelola Lomba']
        ];

        // Data untuk dropdown kategori dan tingkat lomba
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('admin.manajemen-lomba.kelola-lomba.index', compact('breadcrumb', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function list(Request $request)
    {
        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->selectRaw('t_lomba.*, null as DT_RowIndex')
            ->where('status_lomba', 'Aktif')
            ->whereIn('status_verifikasi', ['Terverifikasi']);

        if ($request->kategori) {
            $dataKelolaLomba->whereHas('kategori', function ($q) use ($request) {
                $q->where('t_kategori.kategori_id', $request->kategori);
            });
        }

        if ($request->tingkat) {
            $dataKelolaLomba->where('tingkat_lomba_id', $request->tingkat);
        }

        return DataTables::eloquent($dataKelolaLomba)
            ->addIndexColumn()
            ->addColumn('kategori', function ($row) {
                return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            })
            ->addColumn('tingkat_lomba', function ($row) {
                return $row->tingkat_lomba->nama_tingkat ?? '-';
            })
            ->addColumn('awal_registrasi_lomba', function ($row) {
                return $row->awal_registrasi_lomba ? \Carbon\Carbon::parse($row->awal_registrasi_lomba)->translatedFormat('d M Y') : '-';
            })
            ->addColumn('akhir_registrasi_lomba', function ($row) {
                return $row->akhir_registrasi_lomba ? \Carbon\Carbon::parse($row->akhir_registrasi_lomba)->translatedFormat('d M Y') : '-';
            })
            ->addColumn('status_verifikasi', function ($row) {
                switch ($row->status_verifikasi) {
                    case 'Terverifikasi':
                        return '<span class="label label-success">Terverifikasi</span>';
                    case 'Menunggu':
                        return '<span class="label label-warning">Menunggu</span>';
                    case 'Ditolak':
                        return '<span class="label label-danger">Ditolak</span>';
                    default:
                        return '<span class="label label-secondary">Tidak Diketahui</span>';
                }
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/manajemen-lomba/kelola-lomba/' . $row->lomba_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/manajemen-lomba/kelola-lomba/' . $row->lomba_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mx-2">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/manajemen-lomba/kelola-lomba/' . $row->lomba_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        $pengusul = $lomba->pengusul_id;
        $rolePengusul = UsersModel::where('user_id', $pengusul)->first()->role;
        $tipeLomba = $lomba->tipe_lomba;

        $namaPengusul = '';
        switch ($rolePengusul) {
            case 'Admin':
                $namaPengusul = AdminModel::where('user_id', $pengusul)->first()->nama_admin;
                break;
            case 'Dosen':
                $namaPengusul = DosenModel::where('user_id', $pengusul)->first()->nama_dosen;
                break;
            case 'Mahasiswa':
                $namaPengusul = MahasiswaModel::where('user_id', $pengusul)->first()->nama_mahasiswa;
                break;
            default:
                $namaPengusul = 'Pengusul tidak diketahui';
                break;
        }

        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Detail Lomba']
        ];

        return view('admin.manajemen-lomba.kelola-lomba.show', compact('lomba', 'namaPengusul', 'tipeLomba', 'breadcrumb'));
    }

    public function editAjax($id)
    {
        $kelolaLomba = LombaModel::findOrFail($id);
        $pengusul = $kelolaLomba->pengusul_id;
        $rolePengusul = UsersModel::find($pengusul)->getRoleName();

        $namaPengusul = '';
        switch ($rolePengusul) {
            case 'Admin':
                $namaPengusul = AdminModel::where('user_id', $pengusul)->first()->nama_admin;
                break;
            case 'Dosen':
                $namaPengusul = DosenModel::where('user_id', $pengusul)->first()->nama_dosen;
                break;
            case 'Mahasiswa':
                $namaPengusul = MahasiswaModel::where('user_id', $pengusul)->first()->nama_mahasiswa;
                break;

            default:
                $namaPengusul = 'Pengusul tidak diketahui';
                break;
        }

        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('admin.manajemen-lomba.kelola-lomba.edit', compact('kelolaLomba', 'daftarKategori', 'daftarTingkatLomba', 'namaPengusul'));
    }

    public function deleteAjax($id)
    {
        $kelolaLomba = LombaModel::findOrFail($id);
        $pengusul = $kelolaLomba->pengusul_id;
        $rolePengusul = UsersModel::where('user_id', $pengusul)->first()->role;

        $namaPengusul = '';
        switch ($rolePengusul) {
            case 'Admin':
                $namaPengusul = AdminModel::where('user_id', $pengusul)->first()->nama_admin;
                break;
            case 'Dosen':
                $namaPengusul = DosenModel::where('user_id', $pengusul)->first()->nama_dosen;
                break;
            case 'Mahasiswa':
                $namaPengusul = MahasiswaModel::where('user_id', $pengusul)->first()->nama_mahasiswa;
                break;

            default:
                $namaPengusul = 'Pengusul tidak diketahui';
                break;
        }
        return view('admin.manajemen-lomba.kelola-lomba.delete', compact('kelolaLomba', 'namaPengusul'));
    }

    public function create()
    {
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();
        $dataLomba = LombaModel::where('status_lomba', 'Aktif')->get();

        return view('admin.manajemen-lomba.kelola-lomba.create', compact('dataLomba', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirimkan
        $validator = Validator::make($request->all(), [
            'nama_lomba' => 'required',
            'deskripsi_lomba' => 'required',
            'kategori_id' => 'required|array|exists:t_kategori,kategori_id',
            'tingkat_lomba_id' => 'required|exists:t_tingkat_lomba,tingkat_lomba_id',
            'penyelenggara_lomba' => 'required',
            'awal_registrasi_lomba' => 'required|date',
            'akhir_registrasi_lomba' => 'required|date|after_or_equal:awal_registrasi_lomba',
            'link_pendaftaran_lomba' => 'required|url',
            'img_lomba' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_verifikasi' => 'required|in:Terverifikasi,Menunggu,Ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('img_lomba', 'kategori_id');

            // Tambahkan pengusul_id dengan pengusul yang sedang login
            $data['pengusul_id'] = auth()->user()->user_id;

            // Simpan data ke database terlebih dahulu
            $lomba = LombaModel::create($data);

            // Buat kategori lomba baru
            foreach ($request->kategori_id as $kategoriId) {
                KategoriLombaModel::create([
                    'lomba_id' => $lomba->lomba_id,
                    'kategori_id' => $kategoriId,
                ]);
            }

            // Jika ada gambar, upload ke storage
            if ($request->hasFile('img_lomba')) {
                $file = $request->file('img_lomba');
                $filename = $lomba->lomba_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/img/lomba', $filename); // Simpan ke storage

                // Update kolom img_lomba di database
                $lomba->update(['img_lomba' => $filename]);
            }

            $results = [];
            if ($request->input('status_verifikasi') === 'Terverifikasi') {
                // Ambil semua user_id yang sudah mengisi preferensi
                $userIds = PreferensiUserModel::distinct('user_id')->pluck('user_id');

                $results = [];
                foreach ($userIds as $userId) {
                    $prometheeController = new PrometheeRekomendasiController();
                    $result = $prometheeController->calculateNetFlowForSingleLomba($lomba, $userId);
                    $results[] = $result;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'threshold_results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirimkan
        $request->validate([
            'nama_lomba' => 'required',
            'deskripsi_lomba' => 'required',
            'kategori_id' => 'required|array|exists:t_kategori,kategori_id',
            'tingkat_lomba_id' => 'required|exists:t_tingkat_lomba,tingkat_lomba_id',
            'penyelenggara_lomba' => 'required',
            'awal_registrasi_lomba' => 'required|date',
            'akhir_registrasi_lomba' => 'required|date|after_or_equal:awal_registrasi_lomba',
            'link_pendaftaran_lomba' => 'required|url',
            'img_lomba' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_verifikasi' => 'required|in:Terverifikasi,Menunggu,Ditolak',
        ]);

        try {
            $data = $request->except('img_lomba', 'kategori_id');

            // Temukan lomba berdasarkan ID dan perbarui data
            $lomba = LombaModel::findOrFail($id);
            $lomba->update($data);

            // Hapus kategori lomba lama
            KategoriLombaModel::where('lomba_id', $id)->delete();

            // Buat kategori lomba baru
            foreach ($request->kategori_id as $kategoriId) {
                KategoriLombaModel::create([
                    'lomba_id' => $id,
                    'kategori_id' => $kategoriId,
                ]);
            }

            // Jika ada gambar, upload ke storage
            if ($request->hasFile('img_lomba')) {
                $file = $request->file('img_lomba');
                // Simpan gambar ke storage dengan nama unik
                $filename = $lomba->lomba_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Simpan gambar ke storage (perlu "php artisan storage:link" ya)
                $path = $file->storeAs('public/img/lomba', $filename);

                // Update kolom img_lomba di database
                $lomba->update(['img_lomba' => $filename]);
            }

            $results = null; // inisialisasi dulu supaya tidak undefined

            if ($request->input('status_verifikasi') === 'Terverifikasi') {
                // Ambil semua user_id yang sudah mengisi preferensi
                $userIds = PreferensiUserModel::distinct('user_id')->pluck('user_id');

                $results = [];
                foreach ($userIds as $userId) {
                    $prometheeController = new PrometheeRekomendasiController();
                    $result = $prometheeController->calculateNetFlowForSingleLomba($lomba, $userId);
                    $results[] = $result;
                }
            }

            // Buat respons dinamis
            $response = [
                'success' => true,
                'message' => 'Data berhasil diperbarui.'
            ];

            // Hanya tambahkan threshold_results jika statusnya Terverifikasi
            if ($results !== null) {
                $response['threshold_results'] = $results;
            }
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kelolaLomba = LombaModel::findOrFail($id);
            if ($kelolaLomba->status_lomba === 'Nonaktif') {
                return response()->json([
                    'success' => true,
                    'message' => 'Data sudah tidak aktif sebelumnya.',
                ]);
            }

            $kelolaLomba->update([
                'status_lomba' => $kelolaLomba->status_lomba = 'Nonaktif',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}

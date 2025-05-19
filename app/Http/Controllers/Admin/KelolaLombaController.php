<?php

namespace App\Http\Controllers\Admin;

use App\Models\LombaModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use App\Models\TingkatLombaModel;
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

        return view('admin.manajemen-lomba.kelola-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        // Mengambil data dari database
        // $dataKelolaLomba = LombaModel::select(['lomba_id', 'nama_lomba', 't_kategori.nama_kategori as kategori', 't_tingkat_lomba.nama_tingkat as tingkat_lomba', 'awal_registrasi_lomba', 'akhir_registrasi_lomba', 'status_verifikasi'])->join('t_kategori', 't_lomba.kategori_id', '=', 't_kategori.kategori_id')->join('t_tingkat_lomba', 't_lomba.tingkat_lomba_id', '=', 't_tingkat_lomba.tingkat_lomba_id')->get();
        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->select([
                'lomba_id',
                'nama_lomba',
                'kategori_id',
                'tingkat_lomba_id',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'status_verifikasi'
            ])
            ->where('status_lomba', 'Aktif')
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            ->addColumn('kategori', function ($row) {
                return $row->kategori->nama_kategori ?? '-';
            })
            ->addColumn('tingkat_lomba', function ($row) {
                return $row->tingkat_lomba->nama_tingkat ?? '-';
            })
            ->addColumn('status_verifikasi', function ($row) {
                $statusLomba = $row->status_verifikasi;
                switch ($statusLomba) {
                    case 'Terverifikasi':
                        $badge = '<span class="label label-success">Terverifikasi</span>';
                        break;
                    case 'Menunggu':
                        $badge = '<span class="label label-warning">Menunggu</span>';
                        break;
                    case 'Ditolak':
                        $badge = '<span class="label label-danger">Ditolak</span>';
                        break;
                    default:
                        $badge = '<span class="label label-secondary">Tidak Diketahui</span>';
                        break;
                }
                return $badge;
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/kelola-lomba/' . $row->lomba_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/kelola-lomba/' . $row->lomba_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mx-2">Edit</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/kelola-lomba/' . $row->lomba_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $kelolaLomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        return view('admin.manajemen-lomba.kelola-lomba.show', compact('kelolaLomba'));
    }

    public function editAjax($id)
    {
        $kelolaLomba = LombaModel::findOrFail($id);
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('admin.manajemen-lomba.kelola-lomba.edit', compact('kelolaLomba', 'daftarKategori', 'daftarTingkatLomba'));
    }

    public function deleteAjax($id)
    {
        $kelolaLomba = LombaModel::findOrFail($id);
        return view('admin.manajemen-lomba.kelola-lomba.delete', compact('kelolaLomba'));
    }

    public function create()
    {
        $daftarKategori = KategoriModel::where('status_kategori', 'Aktif')->get();
        $daftarTingkatLomba = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();
        return view('admin.manajemen-lomba.kelola-lomba.create', compact('daftarKategori', 'daftarTingkatLomba'));
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirimkan
        try {
            $validator = Validator::make($request->all(), [
                'nama_lomba' => 'required',
                'deskripsi_lomba' => 'required',
                'kategori_id' => 'required|exists:t_kategori,kategori_id',
                'tingkat_lomba_id' => 'required|exists:t_tingkat_lomba,tingkat_lomba_id',
                'penyelenggara_lomba' => 'required',
                'awal_registrasi_lomba' => 'required|date',
                'akhir_registrasi_lomba' => 'required|date|after_or_equal:awal_registrasi_lomba',
                'link_pendaftaran_lomba' => 'required|url',
                'img_lomba' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status_verifikasi' => 'required|in:Terverifikasi,Menunggu,Ditolak',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Ambil data yang dikirimkan
            $data = $request->only([
                'kategori_id',
                'tingkat_lomba_id',
                'nama_lomba',
                'deskripsi_lomba',
                'penyelenggara_lomba',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'link_pendaftaran_lomba',
                'status_verifikasi',
            ]);


            // Simpan data ke database
            LombaModel::create($data);

            // Jika ada gambar yang dikirimkan, simpan gambar ke folder img/lomba
            if ($request->hasFile('img_lomba')) {
                $imgName = $request->file('img_lomba')->getClientOriginalName();
                $request->file('img_lomba')->move(public_path('img/lomba'), $imgName);
                $data['img_lomba'] = $imgName;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
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
            'kategori_id' => 'required|exists:t_kategori,kategori_id',
            'tingkat_lomba_id' => 'required|exists:t_tingkat_lomba,tingkat_lomba_id',
            'penyelenggara_lomba' => 'required',
            'awal_registrasi_lomba' => 'required|date',
            'akhir_registrasi_lomba' => 'required|date|after_or_equal:awal_registrasi_lomba',
            'link_pendaftaran_lomba' => 'required|url',
            'img_lomba' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_verifikasi' => 'required|in:Terverifikasi,Menunggu,Ditolak',
        ]);

        try {
            // Temukan data LombaModel berdasarkan ID
            $kelolaLomba = LombaModel::findOrFail($id);

            // Periksa apakah ada gambar yang diunggah
            if ($request->hasFile('img_lomba')) {
                $file = $request->file('img_lomba');
                // Buat nama gambar yang unik
                $filename = $kelolaLomba->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/img/lomba', $filename);

                // Hapus gambar lama jika ada
                if ($kelolaLomba->img_lomba && Storage::exists('public/img/lomba/' . $kelolaLomba->img_lomba)) {
                    Storage::delete('public/img/lomba/' . $kelolaLomba->img_lomba);
                }

                // Simpan nama gambar yang baru ke database
                $kelolaLomba->img_lomba = $filename;
            }

            // Perbarui data LombaModel dengan data yang baru
            $kelolaLomba->update([
                'kategori_id' => $request->kategori_id,
                'tingkat_lomba_id' => $request->tingkat_lomba_id,
                'nama_lomba' => $request->nama_lomba,
                'deskripsi_lomba' => $request->deskripsi_lomba,
                'penyelenggara_lomba' => $request->penyelenggara_lomba,
                'awal_registrasi_lomba' => $request->awal_registrasi_lomba,
                'akhir_registrasi_lomba' => $request->akhir_registrasi_lomba,
                'link_pendaftaran_lomba' => $request->link_pendaftaran_lomba,
                'status_verifikasi' => $request->status_verifikasi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
            ]);
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

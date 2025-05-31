<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\KategoriLombaModel;
use App\Models\KategoriModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\TingkatLombaModel;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HistoriPengajuanLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Histori Pengajuan Lomba']
        ];

        return view('admin.manajemen-lomba.histori-pengajuan-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = LombaModel::with('kategori', 'tingkat_lomba')
            ->select([
                'lomba_id',
                'nama_lomba',
                'tingkat_lomba_id',
                'penyelenggara_lomba',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'status_verifikasi'
            ])
            ->where('pengusul_id', auth()->id())
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_tingkat', function ($row) {
                return $row->tingkat_lomba?->nama_tingkat ?? '-';
            })
            ->addColumn('kategori', function ($row) {
                return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            })
            ->addColumn('status_verifikasi', function ($row) {
                $statusLomba = $row->status_verifikasi;
                switch ($statusLomba) {
                    case 'Terverifikasi':
                        $label = '<span class="label label-success">Terverifikasi</span>';
                        break;
                    case 'Valid':
                        $label = '<span class="label label-info">Valid</span>';
                        break;
                    case 'Menunggu':
                        $label = '<span class="label label-warning">Menunggu</span>';
                        break;
                    case 'Ditolak':
                        $label = '<span class="label label-danger">Ditolak</span>';
                        break;
                    default:
                        $label = '<span class="label label-secondary">Tidak Diketahui</span>';
                        break;
                }
                return $label;
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/histori-pengajuan-lomba/' . $row->lomba_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                if ($row->status_verifikasi == 'Ditolak') {
                    $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/histori-pengajuan-lomba/' . $row->lomba_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mx-2">Edit</button>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $lomba = LombaModel::with('kategori', 'tingkat_lomba')->findOrFail($id);
        $pengusul = $lomba->pengusul_id;
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
        return view('admin.manajemen-lomba.histori-pengajuan-lomba.show', compact('lomba', 'namaPengusul'));
    }

    public function editAjax($id)
    {
        $lomba = LombaModel::findOrFail($id);
        $pengusul = $lomba->pengusul_id;
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

        return view('admin.manajemen-lomba.histori-pengajuan-lomba.edit', compact('lomba', 'namaPengusul', 'daftarKategori', 'daftarTingkatLomba'));
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
        ]);

        try {
            $data = $request->except('img_lomba', 'kategori_id');

            // Temukan lomba berdasarkan ID dan perbarui data
            $lomba = LombaModel::findOrFail($id);
            $lomba->update($data);

            // Set status_verifikasi menjadi 'Menunggu' karena data telah diperbarui
            $lomba->update(['status_verifikasi' => 'Menunggu']);

            // Ambil kategori lomba saat ini
            // Bandingkan dengan kategori baru
            // Hapus kategori yang tidak ada di daftar baru
            $kategoriLama = KategoriLombaModel::where('lomba_id', $id)->pluck('kategori_id')->toArray();
            $kategoriBaru = $request->kategori_id;
            KategoriLombaModel::where('lomba_id', $id)->whereNotIn('kategori_id', $kategoriBaru)->delete();

            // Tambahkan kategori yang baru ditambahkan
            foreach (array_diff($kategoriBaru, $kategoriLama) as $kategoriId) {
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
}

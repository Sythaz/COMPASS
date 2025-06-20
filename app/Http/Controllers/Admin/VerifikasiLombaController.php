<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PrometheeRekomendasiController;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\NotifikasiModel;
use App\Models\PreferensiUserModel;
use App\Models\TingkatLombaModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Lomba',
            'list' => ['Manajemen Lomba', 'Verifikasi Lomba']
        ];
        return view('admin.manajemen-lomba.verifikasi-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = LombaModel::with('tingkat_lomba', 'kategori')
            ->select([
                'lomba_id',
                'nama_lomba',
                'tingkat_lomba_id',
                'penyelenggara_lomba',
                'awal_registrasi_lomba',
                'akhir_registrasi_lomba',
                'status_verifikasi'
            ])
            ->where('status_lomba', 'Aktif')
            ->whereIn('status_verifikasi', ['Menunggu'])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_tingkat', function ($row) {
                return $row->tingkat_lomba?->nama_tingkat ?? '-';
            })
            ->addColumn('kategori', function ($row) {
                return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            })
            ->addColumn('awal_registrasi_lomba', function ($row) {
                return $row->awal_registrasi_lomba ? date('d M Y', strtotime($row->awal_registrasi_lomba)) : '-';
            })
            ->addColumn('akhir_registrasi_lomba', function ($row) {
                return $row->akhir_registrasi_lomba ? date('d M Y', strtotime($row->akhir_registrasi_lomba)) : '-';
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
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/verifikasi-lomba/' . $row->lomba_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/verifikasi-lomba/' . $row->lomba_id . '/terima_lomba_ajax') . '\')" class="btn text-white btn-success btn-sm mx-2">Terima</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/manajemen-lomba/verifikasi-lomba/' . $row->lomba_id . '/tolak_lomba_ajax') . '\')" class="btn btn-danger btn-sm">Tolak</button>';
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
        return view('admin.manajemen-lomba.verifikasi-lomba.show', compact('lomba', 'namaPengusul'));
    }

    public function terimaLombaAjax($id)
    {
        $lomba = LombaModel::findOrFail($id);
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
        return view('admin.manajemen-lomba.verifikasi-lomba.terima', compact('lomba', 'namaPengusul'));
    }

    public function tolakLombaAjax($id)
    {
        $lomba = LombaModel::findOrFail($id);
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
        return view('admin.manajemen-lomba.verifikasi-lomba.tolak', compact('lomba', 'namaPengusul'));
    }

    public function terimaLomba($id)
    {
        $lomba = LombaModel::findOrFail($id);

        // Tambahkan eager loading setelah ambil model
        $lomba = LombaModel::with(['kategori', 'tingkat_lomba'])->find($id);

        try {
            // Update status_verifikasi menjadi Terverifikasi
            $lomba->update(['status_verifikasi' => 'Terverifikasi']);

            $userIds = PreferensiUserModel::distinct('user_id')->pluck('user_id');

            $results = [];
            foreach ($userIds as $userId) {
                $prometheeController = new PrometheeRekomendasiController();
                $result = $prometheeController->calculateNetFlowForSingleLomba($lomba, $userId);

                $pesanNotifikasi = sprintf(
                    "Anda direkomendasikan oleh Sistem untuk mengikuti lomba '%s'. Silakan periksa informasi lomba lebih lanjut jika berminat.",
                    $lomba->nama_lomba
                );

                if ($result['meets_threshold']) {
                    NotifikasiModel::create([
                        'user_id' => $userId,
                        'pengirim_role' => 'Sistem',
                        'lomba_id' => $lomba->lomba_id,
                        'jenis_notifikasi' => 'Rekomendasi',
                        'pesan_notifikasi' => $pesanNotifikasi
                    ]);
                }

                $results[] = $result;
            }

            return response()->json([
                'success' => true,
                'message' => 'Lomba berhasil diterima dan diverifikasi.',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tolakLomba(Request $request, $id)
    {
        $lomba = LombaModel::findOrFail($id);
        $alasan_tolak = $request->input('alasan_tolak');

        try {
            // Update status_verifikasi menjadi Ditolak
            $lomba->update(['status_verifikasi' => 'Ditolak', 'alasan_tolak' => $alasan_tolak]);

            return response()->json([
                'success' => true,
                'message' => 'Lomba berhasil ditolak.' . ($alasan_tolak ? ' Alasan: ' . $alasan_tolak : '')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}

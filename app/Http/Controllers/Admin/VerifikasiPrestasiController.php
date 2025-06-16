<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrestasiModel;
use App\Models\PrestasiMahasiswaModel;
use App\Models\MahasiswaModel;
use App\Models\NotifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;


class VerifikasiPrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Prestasi',
            'list' => ['Manajemen Prestasi', 'Verifikasi Prestasi']
        ];
        return view('admin.manajemen-prestasi.verifikasi-prestasi.index', compact('breadcrumb'));
    }

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

    public function list(Request $request)
    {
        $data = PrestasiModel::with(['lomba', 'dosen', 'mahasiswa'])
            ->select('t_prestasi.*')
            ->where(function ($query) {
                $query->where('status_verifikasi', 'Valid') // tampilkan yang valid
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status_verifikasi', 'menunggu')
                            ->whereNull('dosen_id'); // tampilkan menunggu jika belum punya dosen
                    });
            });

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', function ($row) {
                return optional($row->lomba)->nama_lomba ?? $row->lomba_lainnya ?? '-';
            })
            ->addColumn('dosen_pembimbing', function ($row) {
                return optional($row->dosen)->nama_dosen ?? '<span class="text-muted">Tidak ada</span>';
            })
            ->addColumn('ketua_mahasiswa', function ($row) {
                $ketua = $row->mahasiswa->first(function ($m) {
                    return strtolower($m->pivot->peran) === 'ketua';
                });
                return optional($ketua)->nama_mahasiswa ?? '<span class="text-muted">Belum ada</span>';
            })
            ->addColumn('jenis_prestasi', function ($row) {
                return $row->jenis_prestasi ?? '-';
            })
            ->editColumn('tanggal_prestasi', function ($row) {
                return $row->tanggal_prestasi ? \Carbon\Carbon::parse($row->tanggal_prestasi)->format('d M Y') : '-';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                // Tetap gunakan status asli (karena query sudah disaring sebelumnya)
                $status = $prestasi->status_verifikasi ?? 'menunggu';
                return $this->getStatusBadge($status);
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button onclick="modalAction(\'' . route('verifikasi-prestasi.showAjax', $row->prestasi_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('verifikasi-prestasi.terimaPrestasiAjax', $row->prestasi_id) . '\')" class="btn btn-success btn-sm mx-2">Terima</button>';
                $btn .= '<button onclick="modalAction(\'' . route('verifikasi-prestasi.tolakPrestasiAjax', $row->prestasi_id) . '\')" class="btn btn-danger btn-sm">Tolak</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['dosen_pembimbing', 'status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $prestasi = PrestasiModel::with(['mahasiswa', 'dosen', 'kategori', 'tingkat_lomba', 'periode'])->findOrFail($id);

        $statusBadge = $this->getStatusBadge($prestasi->status_verifikasi);

        return view('admin.manajemen-prestasi.verifikasi-prestasi.show', compact('prestasi', 'statusBadge'))->render();
    }

    public function terimaPrestasiAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        return view('admin.manajemen-prestasi.verifikasi-prestasi.terima', compact('prestasi'));
    }

    public function tolakPrestasiAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        return view('admin.manajemen-prestasi.verifikasi-prestasi.tolak', compact('prestasi'));
    }

    public function terimaPrestasi($id)
    {
        $prestasi = PrestasiModel::with(['mahasiswa.users', 'dosen.users'])->findOrFail($id);

        try {
            $prestasi->update(['status_verifikasi' => 'Terverifikasi']);
            $ketua = $prestasi->mahasiswa->first(function ($mhs) {
                return strtolower($mhs->pivot->peran) === 'ketua';
            });

            // Notifikasi ke mahasiswa
            if ($ketua && $ketua->users) {
                NotifikasiModel::create([
                    'user_id' => $ketua->users->user_id,
                    'pengirim_id' => auth()->id(), // Admin yg login
                    'pengirim_role' => 'Admin',
                    'jenis_notifikasi' => 'Verifikasi Prestasi',
                    'pesan_notifikasi' => 'Prestasi yang Anda ajukan telah diterima dan diverifikasi oleh admin.',
                    'lomba_id' => $prestasi->lomba_id,
                    'prestasi_id' => $prestasi->prestasi_id,
                    'pendaftaran_id'=> null,
                    'status_notifikasi' => 'Belum Dibaca',
                ]);
            } 
            // notif ke dosen
            if ($prestasi->dosen && $prestasi->dosen->users) {
                NotifikasiModel::create([
                    'user_id' => $prestasi->dosen->users->user_id, // diasumsikan fieldnya dosen_id
                    'pengirim_id' => auth()->id(),
                    'pengirim_role' => 'Admin',
                    'jenis_notifikasi' => 'Verifikasi Prestasi',
                    'pesan_notifikasi' => 'Prestasi dari mahasiswa bimbingan Anda telah diterima oleh admin.',
                    'lomba_id' => $prestasi->lomba_id,
                    'prestasi_id' => $prestasi->prestasi_id,
                    'pendaftaran_id'=> null,
                    'status_notifikasi' => 'Belum Dibaca',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil diterima dan notifikasi dikirim.'
            ]);
        } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status: ' . $e->getMessage()
                ], 500);
        }
    }

    public function tolakPrestasi(Request $request, $id) {
        $request->validate([
            'alasan_tolak' => 'required|string|max:255'
        ]);

        $prestasi = PrestasiModel::with(['mahasiswa.users', 'dosen.users'])->findOrFail($id);
        $ketua = $prestasi->mahasiswa->first(function ($mhs) {
            return strtolower($mhs->pivot->peran) === 'ketua';
        });

        try {
            $prestasi->update([
                'status_verifikasi' => 'Ditolak',
                'alasan_tolak' => $request->alasan_tolak
            ]);

            // Notifikasi ke mahasiswa
            if ($ketua && $ketua->users) {
                NotifikasiModel::create([
                    'user_id' => $ketua->users->user_id,
                    'pengirim_id' => auth()->id(),
                    'pengirim_role' => 'Admin',
                    'jenis_notifikasi' => 'Verifikasi Prestasi',
                    'pesan_notifikasi' => 'Prestasi Anda ditolak oleh admin. Alasan: ' . $request->alasan_tolak,
                    'lomba_id' => $prestasi->lomba_id,
                    'prestasi_id' => $prestasi->prestasi_id,
                    'pendaftaran_id'=> null,
                    'status_notifikasi' => 'Belum Dibaca',
                ]);
            }
            // notif ke dosen 
            if ($prestasi->dosen && $prestasi->dosen->users) {
                NotifikasiModel::create([
                    'user_id' => $prestasi->dosen->users->user_id,
                    'pengirim_id' => auth()->id(),
                    'pengirim_role' => 'Admin',
                    'jenis_notifikasi' => 'Verifikasi Prestasi',
                    'pesan_notifikasi' => 'Prestasi dari mahasiswa bimbingan Anda ditolak oleh admin. Alasan: ' . $request->alasan_tolak,
                    'lomba_id' => $prestasi->lomba_id,
                    'prestasi_id' => $prestasi->prestasi_id,
                    'pendaftaran_id'=> null,
                    'status_notifikasi' => 'Belum Dibaca',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil ditolak dan notifikasi dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }

    }
}

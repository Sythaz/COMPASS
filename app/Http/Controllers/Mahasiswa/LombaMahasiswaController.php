<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LombaModel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\PendaftaranLombaModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LombaMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Lomba']
        ];

        return view('mahasiswa.informasi-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        // Ambil data lomba yang status_lomba = 'Aktif' dan status_verifikasi = 'Terverifikasi' saja
        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi') // hanya Terverifikasi
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            // ->addColumn('kategori', function ($row) {
            //     return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            // })
            ->addColumn('tingkat_lomba', function ($row) {
                return $row->tingkat_lomba->nama_tingkat ?? '-';
            })
            ->addColumn('tipe_lomba', function ($row) {
                return ucfirst($row->tipe_lomba) ?? '-';
            })
            ->addColumn('status_verifikasi', function ($row) {
                // Tampilkan status_lomba (yang pasti "Aktif" sesuai kondisi)
                $status = $row->status_lomba;
                if ($status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                }
                // Jika ingin fallback, tapi seharusnya gak perlu karena filter di query
                return '<span class="label label-secondary">Tidak Diketahui</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="text-center">';
                $btn .= '<button style="white-space:nowrap; margin-right: 5px;" onclick="modalAction(\'' . route('informasi-lomba.show', $row->lomba_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap;" onclick="modalAction(\'' . route('informasi-lomba.daftar', $row->lomba_id) . '\')" class="btn btn-success btn-sm">Daftar</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

    // Tambahkan method private untuk membuat badge status
    private function getStatusBadge($status_verifikasi)
    {
        $status = strtolower($status_verifikasi ?? '');

        switch ($status) {
            case 'terverifikasi':
                return '<span class="badge bg-success text-white">' . e($status_verifikasi) . '</span>';
            case 'valid':
                return '<span class="badge bg-info text-white">' . e($status_verifikasi) . '</span>';
            case 'menunggu':
                return '<span class="badge bg-warning text-dark">' . e($status_verifikasi) . '</span>';
            case 'ditolak':
                return '<span class="badge bg-danger text-white">' . e($status_verifikasi) . '</span>';
            default:
                return '<span class="badge bg-secondary text-white">Tidak diketahui</span>';
        }
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

        $badgeStatus = $this->getStatusBadge($lomba->status_verifikasi);

        $breadcrumb = (object) [
            'list' => ['Info Lomba', 'Detail Lomba']
        ];

        return view('mahasiswa.informasi-lomba.show', compact('lomba', 'namaPengusul', 'breadcrumb', 'badgeStatus'));
    }

    public function form_daftar($id)
    {
        $lomba = LombaModel::with(['kategori', 'tingkat_lomba'])->findOrFail($id);

        $daftarMahasiswa = MahasiswaModel::all();

        $breadcrumb = (object) [
            'list' => ['Info Lomba', 'Daftar Lomba']
        ];

        return view('mahasiswa.informasi-lomba.daftar', compact('lomba', 'daftarMahasiswa', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'mahasiswa_id' => 'required|array|min:1',
            'mahasiswa_id.*' => 'required|exists:t_mahasiswa,mahasiswa_id',
            'bukti_pendaftaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $anggotaTim = collect($request->mahasiswa_id)->unique()->values()->toArray();

        // Cek apakah user login ada dalam list anggota (ketua atau anggota)
        $ketuaId = Auth::user()->mahasiswa->mahasiswa_id;

        if (!in_array($ketuaId, $anggotaTim)) {
            return response()->json([
                'message' => 'Anda harus menjadi salah satu anggota atau ketua tim.'
            ], 422);
        }

        $adaYangSudahDaftar = PendaftaranLombaModel::where('lomba_id', $request->lomba_id)
            ->where(function ($query) use ($anggotaTim) {
                $query->whereIn('t_pendaftaran_lomba.mahasiswa_id', $anggotaTim)
                    ->orWhereHas('anggota', function ($q) use ($anggotaTim) {
                        $q->whereIn('t_pendaftaran_mahasiswa.mahasiswa_id', $anggotaTim);
                    });
            })
            ->exists();

        if ($adaYangSudahDaftar) {
            return response()->json([
                'message' => 'Anda sudah pernah mendaftar lomba ini.'
            ], 422);
        }

        $ketuaId = Auth::user()->mahasiswa->mahasiswa_id;

        $pendaftaran = new PendaftaranLombaModel();
        $pendaftaran->mahasiswa_id = $ketuaId;
        $pendaftaran->lomba_id = $request->lomba_id;

        if ($request->hasFile('bukti_pendaftaran')) {
            $file = $request->file('bukti_pendaftaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $pendaftaran->bukti_pendaftaran = $filename;
        } else {
            $pendaftaran->bukti_pendaftaran = 'default-file.jpg';
        }

        $pendaftaran->save();

        $pendaftaran->anggota()->sync($anggotaTim);

        return response()->json([
            'message' => 'Pendaftaran lomba berhasil disimpan!'
        ], 200);
    }

    public function history()
    {
        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Riwayat Pengajuan Lomba']
        ];

        return view('mahasiswa.informasi-lomba.history', compact('breadcrumb'));
    }

    public function list_history(Request $request)
    {
        // Ambil data lomba yang status_lomba = 'Aktif' dan status_verifikasi = 'Terverifikasi' saja
        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi') // hanya Terverifikasi
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            // ->addColumn('kategori', function ($row) {
            //     return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            // })
            ->addColumn('tingkat_lomba', function ($row) {
                return $row->tingkat_lomba->nama_tingkat ?? '-';
            })
            ->addColumn('tipe_lomba', function ($row) {
                return ucfirst($row->tipe_lomba) ?? '-';
            })
            ->addColumn('status_verifikasi', function ($row) {
                // Tampilkan status_lomba (yang pasti "Aktif" sesuai kondisi)
                $status = $row->status_lomba;
                if ($status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                }
                // Jika ingin fallback, tapi seharusnya gak perlu karena filter di query
                return '<span class="label label-secondary">Tidak Diketahui</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="text-center">';
                $btn .= '<button style="white-space:nowrap; margin-right: 5px;" onclick="modalAction(\'' . route('informasi-lomba.show', $row->lomba_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap;" onclick="modalAction(\'' . route('informasi-lomba.daftar', $row->lomba_id) . '\')" class="btn btn-success btn-sm">Daftar</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }


    public function riwayat_pendaftaran()
    {
        $breadcrumb = (object) [
            'list' => ['Informasi Lomba', 'Riwayat Pendaftaran']
        ];

        return view('mahasiswa.informasi-lomba.pendaftaran', compact('breadcrumb'));
    }

    public function list_pendaftaran(Request $request)
    {
        // Ambil data lomba yang status_lomba = 'Aktif' dan status_verifikasi = 'Terverifikasi' saja
        $dataKelolaLomba = LombaModel::with(['kategori', 'tingkat_lomba'])
            ->where('status_lomba', 'Aktif')
            ->where('status_verifikasi', 'Terverifikasi') // hanya Terverifikasi
            ->get();

        return DataTables::of($dataKelolaLomba)
            ->addIndexColumn()
            // ->addColumn('kategori', function ($row) {
            //     return $row->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui';
            // })
            ->addColumn('tingkat_lomba', function ($row) {
                return $row->tingkat_lomba->nama_tingkat ?? '-';
            })
            ->addColumn('tipe_lomba', function ($row) {
                return ucfirst($row->tipe_lomba) ?? '-';
            })
            ->addColumn('status_verifikasi', function ($row) {
                // Tampilkan status_lomba (yang pasti "Aktif" sesuai kondisi)
                $status = $row->status_lomba;
                if ($status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                }
                // Jika ingin fallback, tapi seharusnya gak perlu karena filter di query
                return '<span class="label label-secondary">Tidak Diketahui</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="text-center">';
                $btn .= '<button style="white-space:nowrap; margin-right: 5px;" onclick="modalAction(\'' . route('informasi-lomba.show', $row->lomba_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap;" onclick="modalAction(\'' . route('informasi-lomba.daftar', $row->lomba_id) . '\')" class="btn btn-success btn-sm">Daftar</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_verifikasi'])
            ->make(true);
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PendaftaranLombaModel;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\NotifikasiModel;

class VerifikasiPendaftaranController extends Controller
{
    protected function getStatusBadge($status)
    {
        switch ($status) {
            case 'Terverifikasi':
                return '<span class="label label-success">Terverifikasi</span>';
            case 'Menunggu':
                return '<span class="label label-warning">Menunggu</span>';
            case 'Ditolak':
                return '<span class="label label-danger">Ditolak</span>';
            default:
                return '<span class="label label-default">Tidak Diketahui</span>';
        }
    }

    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Verifikasi Pendaftaran Lomba']
        ];

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = PendaftaranLombaModel::with(['mahasiswa', 'lomba'])
               ->select('t_pendaftaran_lomba.*')
               ->whereRaw('LOWER(status_pendaftaran) = ?', ['menunggu']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_mahasiswa', function ($row) {
                return optional($row->mahasiswa)->nama_mahasiswa ?? '-';
            })
            ->addColumn('nama_lomba', function ($row) {
                return optional($row->lomba)->nama_lomba ?? '-';
            })
            ->addColumn('tipe_lomba', function ($row) {
                return optional($row->lomba)->tipe_lomba ?? '-';
            })
            ->addColumn('tanggal_daftar', function ($row) {
                return $row->created_at ? $row->created_at->format('d F Y') : '-';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_pendaftaran ?? '';
                return $this->getStatusBadge($status);
            })

            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . route('verifikasi-pendaftaran.show', $row->pendaftaran_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . route('verifikasi-pendaftaran.terima_view', $row->pendaftaran_id) . '\')" class="btn text-white btn-success btn-sm mx-2">Terima</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . route('verifikasi-pendaftaran.tolak_view', $row->pendaftaran_id) . '\')" class="btn btn-danger btn-sm">Tolak</button>';
                $btn .= '</div>';
                return $btn;
            })

            ->rawColumns(['status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function riwayat_index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Verifikasi Pendaftaran']
        ];

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.riwayat', compact('breadcrumb'));
    }

    public function riwayat_list(Request $request)
    {
        $data = PendaftaranLombaModel::with(['mahasiswa', 'lomba'])
            ->select('t_pendaftaran_lomba.*');

        if ($request->filled('status_verifikasi')) {
            $data->where('status_pendaftaran', $request->status_verifikasi);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_mahasiswa', function ($row) {
                return optional($row->mahasiswa)->nama_mahasiswa ?? '-';
            })
            ->addColumn('nama_lomba', function ($row) {
                return optional($row->lomba)->nama_lomba ?? '-';
            })
            ->addColumn('tipe_lomba', function ($row) {
                return optional($row->lomba)->tipe_lomba ?? '-';
            })
            ->addColumn('tanggal_daftar', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y') : '-';
            })
            ->editColumn('status_verifikasi', function ($prestasi) {
                $status = $prestasi->status_pendaftaran ?? '';
                return $this->getStatusBadge($status);
            })

            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button onclick="modalAction(\'' . route('verifikasi-pendaftaran.show', $row->pendaftaran_id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('verifikasi-pendaftaran.edit', $row->pendaftaran_id) . '\')" class="btn btn-warning btn-sm mx-2">Ubah status</button>';
                $btn .= '<button onclick="modalAction(\'' . route('verifikasi-pendaftaran.hapus', $row->pendaftaran_id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            })

            ->rawColumns(['status_verifikasi', 'aksi'])
            ->make(true);
    }

    public function detail_pendaftaran($id)
    {
        $pendaftaran = PendaftaranLombaModel::with(['lomba', 'mahasiswa', 'anggota'])
            ->findOrFail($id);

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.show', compact('pendaftaran'));
    }

    public function terimaView($id)
    {
        $pendaftaran = PendaftaranLombaModel::findOrFail($id);
        return view('admin.manajemen-lomba.verifikasi-pendaftaran.terima', compact('pendaftaran'));
    }

    public function tolakView($id)
    {
        $pendaftaran = PendaftaranLombaModel::findOrFail($id);
        return view('admin.manajemen-lomba.verifikasi-pendaftaran.tolak', compact('pendaftaran'));
    }

    public function terima(Request $request, $id)
    {
        $pendaftaran = PendaftaranLombaModel::findOrFail($id);
        $pendaftaran->status_pendaftaran = 'Terverifikasi';
        $pendaftaran->save();

        NotifikasiModel::create([
            'user_id' => $pendaftaran->mahasiswa->user_id, // user yang menerima notifikasi
            'pengirim_id' => auth()->id(), // admin login
            'pengirim_role' => 'Admin',
            'jenis_notifikasi' => 'Verifikasi Pendaftaran',
            'pesan_notifikasi' => 'Selamat! Pendaftaran lomba Anda telah diterima.',
            'lomba_id' => $pendaftaran->lomba_id ?? null, // kalau ada
            'prestasi_id' => null,
            'pendaftaran_id' => $pendaftaran->pendaftaran_id,
            'status_notifikasi' => 'Belum Dibaca',
            'created_at' => now(),
            'updated_at' => now()
        ]);


        return response()->json(['message' => 'Pendaftaran berhasil diterima.']);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:255',
        ]);

        $pendaftaran = PendaftaranLombaModel::findOrFail($id);
        $pendaftaran->status_pendaftaran = 'Ditolak';
        $pendaftaran->alasan_tolak = $request->alasan_tolak;
        $pendaftaran->save();

        NotifikasiModel::create([
            'user_id' => $pendaftaran->mahasiswa->user_id,
            'pengirim_id' => auth()->id(),
            'pengirim_role' => 'Admin',
            'jenis_notifikasi' => 'Verifikasi Pendaftaran',
            'pesan_notifikasi' => 'Pendaftaran lomba Anda telah ditolak. Alasan: ' . $request->alasan_tolak . '',
            'lomba_id' => $pendaftaran->lomba_id ?? null,
            'prestasi_id' => null,
            'pendaftaran_id' => $pendaftaran->pendaftaran_id,
            'status_notifikasi' => 'Belum Dibaca',
            'created_at' => now(),
            'updated_at' => now()
        ]);


        return response()->json(['message' => 'Pendaftaran berhasil ditolak.']);
    }

    public function edit($id)
    {
        $pendaftaran = PendaftaranLombaModel::with(['lomba', 'mahasiswa', 'anggota'])->findOrFail($id);
        $daftarLomba = LombaModel::where('status_lomba', 'Aktif')->get();
        $daftarMahasiswa = MahasiswaModel::all();

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.edit', compact(
            'pendaftaran',
            'daftarLomba',
            'daftarMahasiswa'
        ));
    }

    public function update_pendaftaran(Request $request, $id)
    {
        $request->validate([
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'mahasiswa_id' => 'required|array|min:1',
            'mahasiswa_id.*' => 'required|exists:t_mahasiswa,mahasiswa_id',
            'status_pendaftaran' => 'required|in:Menunggu,Terverifikasi,Ditolak',
            'bukti_pendaftaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $anggotaTim = collect($request->mahasiswa_id)->unique()->values()->toArray();

        $pendaftaran = PendaftaranLombaModel::findOrFail($id);

        // Update data pendaftaran
        $pendaftaran->mahasiswa_id = $anggotaTim[0]; // Anggota pertama dianggap ketua
        $pendaftaran->lomba_id = $request->lomba_id;
        $pendaftaran->status_pendaftaran = $request->status_pendaftaran;

        // Cek dan simpan file baru jika ada
        if ($request->hasFile('bukti_pendaftaran')) {
            // Hapus file lama jika bukan default
            if ($pendaftaran->bukti_pendaftaran && $pendaftaran->bukti_pendaftaran !== 'default-file.jpg') {
                $oldFile = public_path('uploads/bukti/' . $pendaftaran->bukti_pendaftaran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $file = $request->file('bukti_pendaftaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $pendaftaran->bukti_pendaftaran = $filename;
        }

        $pendaftaran->save();

        // Simpan ulang anggota tim
        $pendaftaran->anggota()->sync($anggotaTim);

        return response()->json([
            'message' => 'Pendaftaran lomba berhasil diperbarui!'
        ], 200);
    }


    public function hapus($id)
    {
        $pendaftaran = PendaftaranLombaModel::with(['lomba', 'mahasiswa', 'anggota'])->findOrFail($id);
        return view('admin.manajemen-lomba.verifikasi-pendaftaran.hapus', compact('pendaftaran'));
    }

    public function destroy($id)
    {
        $pendaftaran = PendaftaranLombaModel::with('anggota')->findOrFail($id);

        // Hapus file jika ada dan bukan default
        if ($pendaftaran->bukti_pendaftaran && $pendaftaran->bukti_pendaftaran !== 'default-file.jpg') {
            $filePath = public_path('uploads/bukti/' . $pendaftaran->bukti_pendaftaran);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus relasi anggota tim (pivot table)
        $pendaftaran->anggota()->detach();

        // Hapus data utama
        $pendaftaran->delete();

        return redirect()->route('riwayat-pendaftaran.index')->with('success', 'Data pendaftaran berhasil dihapus.');
    }


}

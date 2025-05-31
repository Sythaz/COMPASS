<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LombaModel;
use App\Models\TingkatLombaModel;
use App\Models\KategoriModel; 
use Illuminate\Support\Facades\Storage;


class InputLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'list' => ['Input Data', 'Input Lomba']
        ];

        // pass active tingkat values for filters/dropdowns
        $tingkat = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();

        return view('mahasiswa.input-lomba.index', compact('breadcrumb', 'tingkat'));
    }

    public function list(Request $request)
    {
        $data = LombaModel::with('tingkat_lomba')->select('t_lomba.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', fn($row) => $row->nama_lomba)
            ->addColumn('tingkat', fn($row) => $row->tingkat_lomba->nama_tingkat ?? '-')
            ->addColumn('penyelenggara', fn($row) => $row->penyelenggara_lomba ?? '-')
            ->addColumn('periode_registrasi', function($row) {
                return $row->awal_registrasi_lomba->format('Y-m-d')
                    .' → '.$row->akhir_registrasi_lomba->format('Y-m-d');
            })
            ->addColumn('link', fn($row) => "<a href=\"{$row->link_pendaftaran_lomba}\" target=\"_blank\">Daftar</a>")
            ->addColumn('aksi', function ($row) {
                $url = url('mahasiswa/lomba/input/list');
                return
                    "<button onclick=\"modalAction('{$url}/{$row->lomba_id}/show_ajax')\" class=\"btn btn-info btn-sm\">Detail</button> ".
                    "<button onclick=\"modalAction('{$url}/{$row->lomba_id}/edit_ajax')\" class=\"btn btn-warning btn-sm\">Edit</button> ".
                    "<button onclick=\"modalAction('{$url}/{$row->lomba_id}/delete_ajax')\" class=\"btn btn-danger btn-sm\">Hapus</button>";
            })
            ->rawColumns(['link','aksi'])
            ->make(true);
    }

    public function create()
    {
        $tingkat = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();
        return view('mahasiswa.input-lomba.create', compact('tingkat'));
    }

    public function showAjax($id)
    {
        $lomba = LombaModel::with('tingkat_lomba')->findOrFail($id);
        return view('mahasiswa.input-lomba.show', compact('lomba'));
    }

    public function editAjax($id)
    {
        $lomba = LombaModel::findOrFail($id);
        $tingkat = TingkatLombaModel::where('status_tingkat_lomba', 'Aktif')->get();
        return view('mahasiswa.input-lomba.edit', compact('lomba','tingkat'));
    }

    public function deleteAjax($id)
    {
        $lomba = LombaModel::findOrFail($id);
        return view('mahasiswa.input-lomba.delete', compact('lomba'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'            => 'required|integer|exists:t_kategori,kategori_id',
            'tingkat_lomba_id'       => 'required|integer|exists:t_tingkat_lomba,tingkat_lomba_id',
            'nama_lomba'             => 'required|string|max:255',
            'deskripsi_lomba'        => 'nullable|string',
            'penyelenggara_lomba'    => 'nullable|string|max:255',
            'awal_registrasi_lomba'  => 'required|date',
            'akhir_registrasi_lomba' => 'required|date|after_or_equal:awal_registrasi_lomba',
            'link_pendaftaran_lomba' => 'nullable|url|max:255',
            'img_lomba'              => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'kategori_id','tingkat_lomba_id','nama_lomba','deskripsi_lomba',
            'penyelenggara_lomba','awal_registrasi_lomba','akhir_registrasi_lomba',
            'link_pendaftaran_lomba'
        ]);

        if($request->hasFile('img_lomba')) {
            $path = $request->file('img_lomba')->store('lomba','public');
            $data['img_lomba'] = basename($path);
        }

        // default statuses
        $data['status_verifikasi'] = 'Pending';
        $data['status_lomba']      = 'Aktif';

        LombaModel::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data lomba berhasil disimpan.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $lomba = LombaModel::findOrFail($id);

        $request->validate([
            'kategori_id'            => 'required|integer|exists:t_kategori,kategori_id',
            'tingkat_lomba_id'       => 'required|integer|exists:t_tingkat_lomba,tingkat_lomba_id',
            'nama_lomba'             => 'required|string|max:255',
            'deskripsi_lomba'        => 'nullable|string',
            'penyelenggara_lomba'    => 'nullable|string|max:255',
            'awal_registrasi_lomba'  => 'required|date',
            'akhir_registrasi_lomba' => 'required|date|after_or_equal:awal_registrasi_lomba',
            'link_pendaftaran_lomba' => 'nullable|url|max:255',
            'img_lomba'              => 'nullable|image|max:2048',
            'status_verifikasi'      => 'required|in:Pending,Terverifikasi,Ditolak',
            'status_lomba'           => 'required|in:Aktif,Nonaktif',
        ]);

        $data = $request->only([
            'kategori_id','tingkat_lomba_id','nama_lomba','deskripsi_lomba',
            'penyelenggara_lomba','awal_registrasi_lomba','akhir_registrasi_lomba',
            'link_pendaftaran_lomba','status_verifikasi','status_lomba'
        ]);

        if($request->hasFile('img_lomba')) {
            // delete old one
            if($lomba->img_lomba) {
                Storage::disk('public')->delete('lomba/'.$lomba->img_lomba);
            }
            $path = $request->file('img_lomba')->store('lomba','public');
            $data['img_lomba'] = basename($path);
        }

        $lomba->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data lomba berhasil diperbarui.'
        ]);
    }

    public function destroy($id)
    {
        $lomba = LombaModel::findOrFail($id);
        // optional: delete image file
        if($lomba->img_lomba) {
            Storage::disk('public')->delete('lomba/'.$lomba->img_lomba);
        }
        $lomba->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data lomba berhasil dihapus.'
        ]);
    }
}

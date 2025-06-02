<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PrestasiModel;
use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use Illuminate\Support\Facades\DB;

class InputPrestasiController extends Controller
{
    /**
     * Tampilkan halaman kelola prestasi mahasiswa
     */
    public function index()
    {
        $breadcrumb = (object)[
            'list' => ['Input Data', 'Input Prestasi']
        ];

        return view('mahasiswa.input-prestasi.index', compact('breadcrumb'));
    }

    /**
     * Data untuk DataTables
     */
    public function list(Request $request)
    {
        // Ambil prestasi milik mahasiswa yang sedang login
        $mahasiswa = auth()->user()->mahasiswa;
        $data = PrestasiModel::with(['lomba'])
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', fn($row) => $row->lomba->nama_lomba ?? '-')
            ->addColumn('tahun', fn($row) => $row->tahun ?? '-')
            ->addColumn('peringkat', fn($row) => $row->peringkat ?? '-')
            ->addColumn('aksi', function ($row) {
                $url = url('mahasiswa/input-prestasi/kelola-prestasi');
                $btn = '<button onclick="modalAction(\'' . $url . '/' . $row->prestasi_id . '/show_ajax\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . $url . '/' . $row->prestasi_id . '/edit_ajax\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . $url . '/' . $row->prestasi_id . '/delete_ajax\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Form tambah prestasi
     */
    public function create()
    {
        $list_lomba = LombaModel::all();
        return view('mahasiswa.input-prestasi.create', compact('list_lomba'));
    }

    /**
     * Tampilkan detail prestasi lewat AJAX
     */
    public function showAjax($id)
    {
        $prestasi = PrestasiModel::with(['lomba'])->findOrFail($id);
        return view('mahasiswa.input-prestasi.show', compact('prestasi'));
    }

    /**
     * Form edit prestasi lewat AJAX
     */
    public function editAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);
        $list_lomba = LombaModel::all();
        return view('mahasiswa.input-prestasi.edit', compact('prestasi', 'list_lomba'));
    }

    /**
     * Konfirmasi hapus lewat AJAX
     */
    public function deleteAjax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);
        return view('mahasiswa.input-prestasi.delete', compact('prestasi'));
    }

    /**
     * Simpan prestasi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'tahun' => 'required|digits:4',
            'peringkat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        PrestasiModel::create([
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'lomba_id' => $request->lomba_id,
            'tahun' => $request->tahun,
            'peringkat' => $request->peringkat,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil disimpan.'
        ]);
    }

    /**
     * Update data prestasi
     */
    public function update(Request $request, $id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        $request->validate([
            'lomba_id' => 'required|exists:t_lomba,lomba_id',
            'tahun' => 'required|digits:4',
            'peringkat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $prestasi->update([
            'lomba_id' => $request->lomba_id,
            'tahun' => $request->tahun,
            'peringkat' => $request->peringkat,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil diperbarui.'
        ]);
    }

    /**
     * Hapus prestasi
     */
    public function destroy($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);
        $prestasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prestasi berhasil dihapus.'
        ]);
    }
}

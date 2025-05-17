<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KategoriKeahlianController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Master Data', 'Kategori & Keahlian']
        ];

        return view('admin.master-data.kategori-keahlian.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        // Mengambil data kategori keahlian dari database
        $dataKategoriKeahlian = KategoriModel::select(['kategori_id', 'nama_kategori'])->get();

        return DataTables::of($dataKategoriKeahlian)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/kategori-keahlian/' . $row->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/kategori-keahlian/' . $row->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mx-2">Edit</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/kategori-keahlian/' . $row->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        return view('admin.master-data.kategori-keahlian.show', compact('kategori'));
    }

    public function editAjax($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        return view('admin.master-data.kategori-keahlian.edit', compact('kategori'));
    }

    public function deleteAjax($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        return view('admin.master-data.kategori-keahlian.delete', compact('kategori'));
    }

    public function create()
    {
        return view('admin.master-data.kategori-keahlian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:t_kategori,nama_kategori',
        ]);

        KategoriModel::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori Keahlian berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // Memeriksa apakah data sudah digunakan oleh data lain
            // kecuali data yang sedang diedit
            'nama_kategori' => 'required|unique:t_kategori,nama_kategori,' . $id . ',kategori_id',
        ]);

        try {
            // Mencari data berdasarkan ID lalu mengubahnya dengan data baru yang diterima dari request
            $kategori = KategoriModel::findOrFail($id);
            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
                'redirect' => url('admin/master-data/kategori-keahlian'),
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
        $kategori = KategoriModel::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori Keahlian berhasil dihapus'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Master Data', 'Program Studi']
        ];

        return view('admin.master-data.program-studi.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        // Mengambil data dari database
        $dataProgramStudi = ProdiModel::select(['prodi_id', 'nama_prodi', 'status_prodi'])->get();

        return DataTables::of($dataProgramStudi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/program-studi/' . $row->prodi_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/program-studi/' . $row->prodi_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mx-2">Edit</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/program-studi/' . $row->prodi_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $prodi = ProdiModel::findOrFail($id);
        return view('admin.master-data.program-studi.show', compact('prodi'));
    }

    public function editAjax($id)
    {
        $prodi = ProdiModel::findOrFail($id);
        return view('admin.master-data.program-studi.edit', compact('prodi'));
    }

    public function deleteAjax($id)
    {
        $prodi = ProdiModel::findOrFail($id);
        return view('admin.master-data.program-studi.delete', compact('prodi'));
    }

    public function create()
    {
        return view('admin.master-data.program-studi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|unique:t_prodi,nama_prodi',
        ]);

        ProdiModel::create([
            'nama_prodi' => $request->nama_prodi,
            'status_prodi' => 'Aktif',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // Memeriksa apakah data sudah digunakan oleh data lain
            // kecuali data yang sedang diedit
            'nama_prodi' => 'required|unique:t_prodi,nama_prodi,' . $id . ',prodi_id',
            'status_prodi' => 'required|in:Aktif,Nonaktif',
        ]);

        try {
            // Mencari data berdasarkan ID lalu mengubahnya dengan data baru yang diterima dari request
            $prodi = ProdiModel::findOrFail($id);
            $prodi->update([
                'nama_prodi' => $request->nama_prodi,
               'status_prodi' => $request->status_prodi,
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
            $prodi = ProdiModel::findOrFail($id);
            if ($prodi->status_prodi === 'Nonaktif') {
                return response()->json([
                    'success' => true,
                    'message' => 'Data sudah tidak aktif sebelumnya.',
                ]);
            }

            $prodi->update([
                'status_prodi' => $prodi->status_prodi = 'Nonaktif',
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


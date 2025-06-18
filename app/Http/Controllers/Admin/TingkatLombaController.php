<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TingkatLombaModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TingkatLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Master Data', 'Tingkat Lomba']
        ];

        return view('admin.master-data.tingkat-lomba.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        // Mengambil data dari database
        $dataTingkatLomba = TingkatLombaModel::select(['tingkat_lomba_id', 'nama_tingkat', 'status_tingkat_lomba'])->get();

        return DataTables::of($dataTingkatLomba)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/tingkat-lomba/' . $row->tingkat_lomba_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/tingkat-lomba/' . $row->tingkat_lomba_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mx-2">Edit</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/tingkat-lomba/' . $row->tingkat_lomba_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Nonaktif</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $tingkatLomba = TingkatLombaModel::findOrFail($id);
        return view('admin.master-data.tingkat-lomba.show', compact('tingkatLomba'));
    }

    public function editAjax($id)
    {
        $tingkatLomba = TingkatLombaModel::findOrFail($id);
        return view('admin.master-data.tingkat-lomba.edit', compact('tingkatLomba'));
    }

    public function deleteAjax($id)
    {
        $tingkatLomba = TingkatLombaModel::findOrFail($id);
        return view('admin.master-data.tingkat-lomba.delete', compact('tingkatLomba'));
    }

    public function create()
    {
        return view('admin.master-data.tingkat-lomba.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tingkat' => 'required|unique:t_tingkat_lomba,nama_tingkat',
        ]);

        TingkatLombaModel::create([
            'nama_tingkat' => $request->nama_tingkat,
            'status_tingkat_lomba' => 'Aktif',
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
            'nama_tingkat' => 'required|unique:t_tingkat_lomba,nama_tingkat,' . $id . ',tingkat_lomba_id',
            'status_tingkat_lomba' => 'required|in:Aktif,Nonaktif',
        ]);

        try {
            // Mencari data berdasarkan ID lalu mengubahnya dengan data baru yang diterima dari request
            $tingkatLomba = TingkatLombaModel::findOrFail($id);
            $tingkatLomba->update([
                'nama_tingkat' => $request->nama_tingkat,
                'status_tingkat_lomba' => $request->status_tingkat_lomba,
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
            $tingkatLomba = TingkatLombaModel::findOrFail($id);
            if ($tingkatLomba->status_tingkat_lomba === 'Nonaktif') {
                return response()->json([
                    'success' => true,
                    'message' => 'Data sudah tidak aktif sebelumnya.',
                ]);
            }

            $tingkatLomba->update([
                'status_tingkat_lomba' => $tingkatLomba->status_tingkat_lomba = 'Nonaktif',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dinonaktifkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}


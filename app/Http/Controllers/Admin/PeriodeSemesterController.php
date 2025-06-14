<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeriodeSemesterController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Master Data', 'Periode Semester']
        ];

        return view('admin.master-data.periode-semester.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        // Mengambil data dari database
        $dataPeriodeSemester = PeriodeModel::select(['periode_id', 'semester_periode', 'tanggal_mulai', 'tanggal_akhir', 'status_periode'])->get();

        return DataTables::of($dataPeriodeSemester)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center">';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/periode-semester/' . $row->periode_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/periode-semester/' . $row->periode_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm mx-2">Edit</button>';
                $btn .= '<button style="white-space:nowrap" onclick="modalAction(\'' . url('/admin/master-data/periode-semester/' . $row->periode_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function showAjax($id)
    {
        $periode = PeriodeModel::findOrFail($id);
        return view('admin.master-data.periode-semester.show', compact('periode'));
    }

    public function editAjax($id)
    {
        $periode = PeriodeModel::findOrFail($id);
        return view('admin.master-data.periode-semester.edit', compact('periode'));
    }

    public function deleteAjax($id)
    {
        $periode = PeriodeModel::findOrFail($id);
        return view('admin.master-data.periode-semester.delete', compact('periode'));
    }
    public function create()
    {
        return view('admin.master-data.periode-semester.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'semester_periode' => 'required|unique:t_periode,semester_periode',
                'tanggal_mulai' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            ]);

            PeriodeModel::create([
                'semester_periode' => $request->semester_periode,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // Memeriksa apakah data sudah digunakan oleh data lain
            // kecuali data yang sedang diedit
            'semester_periode' => 'required|unique:t_periode,semester_periode,' . $id . ',periode_id',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            // Mencari data berdasarkan ID lalu mengubahnya dengan data baru yang diterima dari request
            $periode = PeriodeModel::findOrFail($id);
            $periode->update([
                'semester_periode' => $request->semester_periode,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
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
            $periode = PeriodeModel::findOrFail($id);
            if ($periode->status_periode === 'Nonaktif') {
                return response()->json([
                    'success' => true,
                    'message' => 'Data sudah tidak aktif sebelumnya.',
                ]);
            }

            $periode->update([
                'status_periode' => $periode->status_periode = 'Nonaktif',
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\PeriodeModel;
use App\Models\ProdiModel;
use App\Models\LevelMinatBakatModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KelolaMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Mahasiswa']
        ];

        return view('admin.kelola-pengguna.kelola-mahasiswa.index', compact('breadcrumb'));
    }

    // List data Mahasiswa dengan relasi users, prodi, periode, dan level_minat_bakat
    public function list(Request $request)
    {
        $data = MahasiswaModel::with(['users', 'prodi', 'periode', 'level_minat_bakat'])
            ->select('mahasiswa_id', 'user_id', 'prodi_id', 'periode_id', 'level_minbak_id', 'nim_mahasiswa', 'nama_mahasiswa')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => $row->users->username ?? '-')
            ->addColumn('role', fn($row) => $row->users->role ?? '-')
            ->addColumn('prodi', fn($row) => $row->prodi->nama_prodi ?? '-')
            ->addColumn('periode', fn($row) => $row->periode->semester_periode ?? '-')
            ->addColumn('level_minat_bakat', fn($row) => $row->level_minat_bakat->level_minbak ?? '-')
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Form Create
    public function create()
    {
        $list_prodi = ProdiModel::all();
        $list_periode = PeriodeModel::all();
        $list_level = LevelMinatBakatModel::all();

        return view('admin.kelola-pengguna.kelola-mahasiswa.create', compact('list_prodi', 'list_periode', 'list_level'));
    }

    // Modal actions Show
    public function showAjax($id)
    {
        $mahasiswa = MahasiswaModel::with(['users', 'prodi', 'periode', 'level_minat_bakat'])->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-mahasiswa.show', compact('mahasiswa'));
    }

    // Modal actions Edit
    public function editAjax($id)
    {
        $mahasiswa = MahasiswaModel::with(['users', 'prodi', 'periode', 'level_minat_bakat'])->findOrFail($id);
        $list_prodi = ProdiModel::all();
        $list_periode = PeriodeModel::all();
        $list_level = LevelMinatBakatModel::all();

        return view('admin.kelola-pengguna.kelola-mahasiswa.edit', compact('mahasiswa', 'list_prodi', 'list_periode', 'list_level'));
    }
    // Modal actions Delete
    public function deleteAjax($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-mahasiswa.delete', compact('mahasiswa'));
    }

    // Store new Mahasiswa
    public function store(Request $request)
    {
        $request->validate([
            'nim_mahasiswa' => 'required|unique:t_mahasiswa,nim_mahasiswa',
            'nama_mahasiswa' => 'required',
            'prodi_id' => 'required|exists:t_prodi,prodi_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'level_minbak_id' => 'required|exists:t_level_minat_bakat,level_minbak_id',
            'username' => 'required|unique:t_users,username',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $user = UsersModel::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            MahasiswaModel::create([
                'user_id' => $user->user_id,
                'prodi_id' => $request->prodi_id,
                'periode_id' => $request->periode_id,
                'level_minbak_id' => $request->level_minbak_id,
                'nim_mahasiswa' => $request->nim_mahasiswa,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'img_mahasiswa' => null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil ditambahkan'
        ]);
    }

    // Update data Mahasiswa
    public function update(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        $user = $mahasiswa->users;

        $request->validate([
            'nim_mahasiswa' => 'required|unique:t_mahasiswa,nim_mahasiswa,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'nama_mahasiswa' => 'required',
            'prodi_id' => 'required|exists:t_prodi,prodi_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'level_minbak_id' => 'required|exists:t_level_minat_bakat,level_minbak_id',
            'username' => 'required|unique:t_users,username,' . $user->user_id . ',user_id',
            'role' => 'required',
            'password' => 'nullable|min:6',
        ]);

        DB::transaction(function () use ($request, $mahasiswa, $user) {
            $user->username = $request->username;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->save();

            $mahasiswa->prodi_id = $request->prodi_id;
            $mahasiswa->periode_id = $request->periode_id;
            $mahasiswa->level_minbak_id = $request->level_minbak_id;
            $mahasiswa->nim_mahasiswa = $request->nim_mahasiswa;
            $mahasiswa->nama_mahasiswa = $request->nama_mahasiswa;
            $mahasiswa->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Mahasiswa Berhasil Diperbarui'
        ]);
    }

    // Delete Mahasiswa
    public function destroy($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->delete();
            $mahasiswa->users()->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Mahasiswa Berhasil Dihapus'
        ]);
    }
}

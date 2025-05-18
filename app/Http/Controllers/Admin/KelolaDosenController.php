<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DosenModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\KategoriModel;

class KelolaDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Dosen']
        ];

        return view('admin.kelola-pengguna.kelola-dosen.index', compact('breadcrumb'));
    }

    // List data Dosen
    public function list(Request $request)
    {
        $data = DosenModel::with(['users', 'kategori'])
            ->select('dosen_id', 'user_id', 'nip_dosen', 'nama_dosen', 'kategori_id')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => $row->users->username ?? '-')
            ->addColumn('role', fn($row) => $row->users->role ?? '-')
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-') // Perbaikan nama kolom kategori
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/dosen/' . $row->dosen_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/dosen/' . $row->dosen_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/dosen/' . $row->dosen_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Form Create
    public function create()
    {
        $kategori = KategoriModel::all();
        return view('admin.kelola-pengguna.kelola-dosen.create', compact('kategori'));
    }

    // Modal actions Show
    public function showAjax($id)
    {
        $dosen = DosenModel::with(['users', 'kategori'])->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-dosen.show', compact('dosen'));
    }

    // Modal actions Edit
    public function editAjax($id)
    {
        $dosen = DosenModel::with(['users', 'kategori'])->findOrFail($id);
        $kategori = KategoriModel::all();
        return view('admin.kelola-pengguna.kelola-dosen.edit', compact('dosen', 'kategori'));
    }

    // Modal actions Delete
    public function deleteAjax($id)
    {
        $dosen = DosenModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-dosen.delete', compact('dosen'));
    }

    // Store new Dosen
    public function store(Request $request)
    {
        $request->validate([
            'nip_dosen' => 'required|unique:t_dosen,nip_dosen',
            'nama_dosen' => 'required',
            'kategori_id' => 'required|exists:t_kategori,kategori_id',
            'role' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $user = UsersModel::create([
                'username' => $request->nip_dosen,
                'password' => Hash::make($request->nip_dosen),
                'role' => $request->role,
                'phrase' => $request->nip_dosen,
            ]);

            DosenModel::create([
                'user_id' => $user->user_id,
                'nip_dosen' => $request->nip_dosen,
                'nama_dosen' => $request->nama_dosen,
                'kategori_id' => $request->kategori_id,
                'img_dosen' => null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Dosen berhasil ditambahkan dengan username & password sesuai dengan NIP',
        ]);
    }

    // Update data Dosen
    public function update(Request $request, $id)
    {
        $dosen = DosenModel::findOrFail($id);
        $user = $dosen->users;

        $request->validate([
            'nip_dosen' => 'required|unique:t_dosen,nip_dosen,' . $dosen->dosen_id . ',dosen_id',
            'nama_dosen' => 'required',
            'kategori_id' => 'required|exists:t_kategori,kategori_id', // perbaikan tabel kategori
            'username' => 'required|unique:t_users,username,' . $user->user_id . ',user_id',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'phrase' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $dosen, $user) {
            // Update user
            $user->username = $request->username;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->phrase = $request->input('phrase', $user->phrase); // update phrase
            $user->save();

            // Update dosen, termasuk kategori_id
            $dosen->nip_dosen = $request->nip_dosen;
            $dosen->nama_dosen = $request->nama_dosen;
            $dosen->kategori_id = $request->kategori_id;
            $dosen->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Dosen Berhasil Diperbarui',
        ]);
    }

    // Delete Dosen
    public function destroy($id)
    {
        $dosen = DosenModel::findOrFail($id);
        $userId = $dosen->user_id;

        DB::transaction(function () use ($id, $userId) {
            // Hapus dari tabel dosen berdasarkan dosen_id
            DosenModel::where('dosen_id', $id)->delete();
            // Hapus dari tabel user berdasarkan user_id
            UsersModel::where('user_id', $userId)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Dosen Berhasil Dihapus'
        ]);
    }

}

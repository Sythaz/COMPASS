<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdminModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KelolaAdminController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Admin']
        ];

        return view('admin.kelola-pengguna.kelola-admin.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = AdminModel::with('users')->select('admin_id', 'user_id', 'nip_admin', 'nama_admin')->get();
        // return response()->json($data); // tes coba 

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => $row->users ? ' ' . $row->users->username : '-')
            ->addColumn('role', fn($row) => $row->users->role ?? '-')
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/admin/' . $row->admin_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/admin/' . $row->admin_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/admin/' . $row->admin_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    // Show data detail for modal
    public function showAjax($id)
    {
        $admin = AdminModel::with('users')->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.show', compact('admin'));
    }

    // Edit data modal (form)
    public function editAjax($id)
    {
        $admin = AdminModel::with('users')->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.edit', compact('admin'));
    }

    // Delete confirmation modal
    public function deleteAjax($id)
    {
        $admin = AdminModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.delete', compact('admin'));
    }
    public function create()
    {
        return view('admin.kelola-pengguna.kelola-admin.create');
    }

    // Store new admin
    public function store(Request $request)
    {
        $request->validate([
            'nip_admin' => 'required|unique:t_admin,nip_admin',
            'nama_admin' => 'required',
            'role' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $user = UsersModel::create([
                'username' => $request->nip_admin,
                'password' => Hash::make($request->nip_admin),
                'role' => $request->role,
                'phrase' => $request->nip_admin,
            ]);

            AdminModel::create([
                'user_id' => $user->user_id,
                'nip_admin' => $request->nip_admin,
                'nama_admin' => $request->nama_admin,
                'img_admin' => null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil ditambahkan dengan username & password sesuai dengan NIP',
        ]);
    }

    // Update existing admin
    public function update(Request $request, $id)
    {
        $admin = AdminModel::findOrFail($id);
        $user = $admin->users;

        $request->validate([
            'nip_admin' => 'required|unique:t_admin,nip_admin,' . $admin->admin_id . ',admin_id',
            'nama_admin' => 'required',
            'username' => 'required|unique:t_users,username,' . $user->user_id . ',user_id',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'phrase' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $admin, $user) {
            $user->username = $request->username;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;

            // Set phrase, kalau dari form tidak ada isian phrase maka tetap 
            $user->phrase = $request->input('phrase', $user->phrase); // update phrase

            $user->save();

            $admin->nip_admin = $request->nip_admin;
            $admin->nama_admin = $request->nama_admin;
            $admin->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Diperbarui'
        ]);
    }

    // Delete admin
    public function destroy($id)
    {
        $admin = AdminModel::findOrFail($id);
        DB::transaction(function () use ($admin) {
            $admin->delete();        // hapus admin dulu (child)
            $admin->users()->delete(); // hapus user (parent) setelahnya
        });
        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Dihapus'
        ]);
    }
}

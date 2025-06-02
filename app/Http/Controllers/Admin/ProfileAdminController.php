<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfileAdminController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Admin',
            'list'  => ['Home', 'Profil Admin']
        ];

        $page = (object) [
            'title' => 'Profil Saya'
        ];

        $activeMenu = 'profil';

        $admin = AdminModel::with('users')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        return view('admin.profile-admin.index', compact('breadcrumb', 'page', 'activeMenu', 'admin'));
    }

    public function show($id)
    {
        $admin = AdminModel::where($id);

        return view('admin.profile-admin.show', compact('admin'));
    }

    public function edit($id)
    {
        $admin = AdminModel::with('users')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        return view('admin.profile-admin.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        try {
            $admin = AdminModel::where('user_id', Auth::user()->user_id)->firstOrFail();

            $request->validate([
                'nip_admin'         => 'required',
                'nama_admin'        => 'required',
                'alamat'            => 'required',
                'email'             => 'required',
                'no_hp'             => 'required',
                'kelamin'           => 'required',
                'img_admin'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            DB::transaction(function () use ($request, $mahasiswa) {
                // Delete old image if new image is uploaded
                if ($request->hasFile('img_admin') && $admin->img_admin) {
                    Storage::delete('public/' . $admin->img_admin);
                }
                $admin->nip_admin      = $request->nip_admin;
                $admin->nama_admin     = $request->nama_admin;
                $admin->alamat         = $request->alamat;
                $admin->email          = $request->email;
                $admin->no_hp          = $request->no_hp;
                $admin->kelamin        = $request->kelamin;
            
                if ($request->hasFile('img_admin')) {
                    $file = $request->file('img_admin');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/foto_admin', $filename);
                    $admin->img_admin = 'foto_admin/' . $filename;
                }

                $admin->save();
            });


            return response()->json([
                'success' => 'true',
                'message' => 'Profil Anda Berhasil Diperbarui'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang Anda masukkan tidak valid',
                'msgField' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

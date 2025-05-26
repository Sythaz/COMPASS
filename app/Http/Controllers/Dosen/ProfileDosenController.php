<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfileDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Dosen',
            'list'  => ['Home', 'Profil Dosen']
        ];

        $page = (object) [
            'title' => 'Profil Saya'
        ];

        $activeMenu = 'profil';

        $dosen = DosenModel::with('users', 'kategori')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $kategori = KategoriModel::all();

        return view('dosen.profile-dosen.index', compact('breadcrumb', 'page', 'activeMenu', 'dosen', 'kategori'));
    }

    public function show($id)
    {
        $dosen = DosenModel::findOrFail($id);

        return view('dosen.profile-dosen.show', compact('dosen'));
    }

    public function edit($id)
    {
        $dosen = DosenModel::with('users', 'kategori')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $kategori = KategoriModel::all();

        return view('dosen.profile-dosen.edit', compact('dosen', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        try {
            $dosen = DosenModel::findOrFail($id);            
            $user = $dosen->users;
            $kategori = $dosen->kategori;

            $request->validate([
                'kategori_id'   => 'required|exists:t_kategori,kategori_id',
                'nip_dosen'     => 'required',
                'nama_dosen'    => 'required',
                'alamat'        => 'required',
                'email'         => 'required',
                'no_hp'         => 'required',
                'img_dosen'     => 'nullable|image|nimes:jpeg|max:2048'
            ]);

            DB::transaction(function () use ($request, $dosen) {
                // Delete old image if new image is uploaded
                if ($request->hasFile('img_dosen') && $dosen->img_dosen) {
                    Storage::delete('public/' . $dosen->img_dosen);
                }

                $dosen->kategori_id = $request->kategori_id;
                $dosen->nip_dosen = $request->nip_dosen;
                $dosen->nama_dosen = $request->nama_dosen;
                $dosen->alamat = $request->alamat;
                $dosen->email = $request->email;
                $dosen->no_hp = $request->no_hp;
            
                if ($request->hasFile('img_dosen')) {
                    $file = $request->file('img_dosen');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/foto_dosen', $filename);
                    $dosen->img_dosen = 'foto_dosen/' . $filename;
                }

                $dosen->save();
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

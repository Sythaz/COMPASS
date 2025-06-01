<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use App\Models\KategoriModel;
use App\Models\KategoriDosenModel;
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

        $dosen = DosenModel::with('users')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $kategoriDosen = KategoriDosenModel::all();

        $kategori = KategoriModel::all();

        return view('dosen.profile-dosen.index', compact('breadcrumb', 'page', 'activeMenu', 'dosen', 'kategoriDosen', 'kategori'));
    }

    // public function show($id)
    // {
    //     $dosen = DosenModel::findOrFail($id);

    //     return view('dosen.profile-dosen.show', compact('dosen'));
    // }

    // public function edit($id)
    // {
    //     $dosen = DosenModel::with('users')
    //         ->where('user_id', Auth::user()->user_id)
    //         ->firstOrFail();

    //     $kategoriDosen = KategoriDosenModel::all();

    //     $kategori = KategoriModel::all();

    //     return view('dosen.profile-dosen.edit', compact('dosen', 'kategoriDosen', 'kategori'));
    // }

    public function edit($id)
{
    $dosen = DosenModel::with('kategoriDosen.kategori')->findOrFail($id);
    $kategori = KategoriModel::all();

    return view('dosen.profile-dosen.edit', compact('dosen', 'kategori'));
}


    public function update(Request $request, $id)
    {
        try {
            $dosen = DosenModel::findOrFail($id);            
            $user = $dosen->users;
            // $kategoriDosen = $dosen->kategoriDosen;
            $kategori = $dosen->kategori;
            $kategoriDosen = $kategori->kategoriDosen;

            $request->validate([
                'kategori_dosen_id' => 'required|exists:t_kategori_dosen,kategori_dosen_id',
                'nip_dosen'         => 'required',
                'nama_dosen'        => 'required',
                'alamat'            => 'required',
                'email'             => 'required',
                'no_hp'             => 'required',
                'img_dosen'         => 'nullable|image|nimes:jpeg|max:2048'
            ]);

            DB::transaction(function () use ($request, $dosen) {
                // Delete old image if new image is uploaded
                if ($request->hasFile('img_dosen') && $dosen->img_dosen) {
                    Storage::delete('public/' . $dosen->img_dosen);
                }

                $dosen->kategori_dosen_id = $request->kategori_dosen_id;
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

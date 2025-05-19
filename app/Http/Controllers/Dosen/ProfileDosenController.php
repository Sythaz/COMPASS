<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        return view('dosen.profil-dosen.profil-dosen.index', compact('breadcrumb', 'page', 'activeMenu', 'dosen'));
    }

    public function edit()
    {
        $dosen = DosenModel::with('users', 'kategori')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $kategori = KategoriModel::all();

        return view('dosen.profil-dosen.profil-dosen.edit', compact('dosen', 'kategori'));
    }

    public function update(Request $request)
    {
        try {
            $dosen = DosenModel::where('user_id', Auth::user()->user_id)->firstOrFail();
            $user = $dosen->users;
            $kategori = $dosen->kategori;

            $request->validate([
                'kategori_id'   => 'required|exists:t_kategori,kategori_id',
                'nip_dosen'     => 'required',
                'nama_dosen'    => 'required',
                'img_dosen'     => 'nullable|image|nimes:jpeg|max:2048'
            ]);

            DB::transaction(function () use ($request, $dosen) {
                $dosen->kategori_id = $request->kategori_id;
                $dosen->nip_dosen = $request->nip_dosen;
                $dosen->nama_dosen = $request->nama_dosen;
            
                if ($request->hasFile('img_dosen')) {
                    $file = $request->file('img_dosen');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/foto_dosen', $filename);
                    $dosen->img_dosen = 'foto_dosen/' . $filename;
                }

                $dosen->save();
            });

            return response()->json([
                'success' => 'true',
                'message' => 'Profil Anda Berhasil Diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'messgae' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ]);
        }
    }
}

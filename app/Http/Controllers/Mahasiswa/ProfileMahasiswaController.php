<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use App\Models\PeriodeModel;
use App\Models\PreferensiUserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfileMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Mahasiswa',
            'list'  => ['Home', 'Profil Mahasiswa']
        ];

        $page = (object) [
            'title' => 'Profil Saya'
        ];


        $preferensi = true;
        if (PreferensiUserModel::where('user_id', auth()->id())->doesntExist()) {
            $preferensi = false;
        }

        $activeMenu = 'profil';

        $mahasiswa = MahasiswaModel::with('users', 'prodi', 'periode')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $prodi   = ProdiModel::all();
        $periode = PeriodeModel::all();

        return view('mahasiswa.profile-mahasiswa.index', compact('breadcrumb', 'preferensi','page', 'activeMenu', 'mahasiswa', 'prodi', 'periode'));
    }

    public function show($id)
    {
        $mahasiswa = MahasiswaModel::where($id);

        return view('mahasiswa.profile-mahasiswa.show', compact('mahasiswa'));
    }

    public function edit($id)
    {
        $mahasiswa = MahasiswaModel::with('users', 'prodi', 'periode')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $prodi   = ProdiModel::all();
        $periode = PeriodeModel::all();

        return view('mahasiswa.profile-mahasiswa.edit', compact('mahasiswa', 'prodi', 'periode'));
    }

    public function update(Request $request)
    {
        try {
            $mahasiswa = MahasiswaModel::where('user_id', Auth::user()->user_id)->firstOrFail();

            $request->validate([
                'prodi_id'          => 'required|exists:t_prodi,prodi_id',
                'periode_id'        => 'required|exists:t_periode,periode_id',
                'nim_mahasiswa'     => 'required',
                'nama_mahasiswa'    => 'required',
                'angkatan'          => 'required',
                'alamat'            => 'required',
                'email'             => 'required',
                'no_hp'             => 'required',
                'kelamin'           => 'required',
                'img_mahasiswa'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            DB::transaction(function () use ($request, $mahasiswa) {
                // Delete old image if new image is uploaded
                if ($request->hasFile('img_mahasiswa') && $mahasiswa->img_mahasiswa) {
                    Storage::delete('public/' . $mahasiswa->img_mahasiswa);
                }

                $mahasiswa->prodi_id       = $request->prodi_id;
                $mahasiswa->periode_id     = $request->periode_id;
                $mahasiswa->nim_mahasiswa  = $request->nim_mahasiswa;
                $mahasiswa->nama_mahasiswa = $request->nama_mahasiswa;
                $mahasiswa->angkatan       = $request->angkatan;
                $mahasiswa->alamat         = $request->alamat;
                $mahasiswa->email          = $request->email;
                $mahasiswa->no_hp          = $request->no_hp;
                $mahasiswa->kelamin        = $request->kelamin;

                if ($request->hasFile('img_mahasiswa')) {
                    $file = $request->file('img_mahasiswa');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/foto_mahasiswa', $filename);
                    $mahasiswa->img_mahasiswa = 'foto_mahasiswa/' . $filename;
                }

                $mahasiswa->save();
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

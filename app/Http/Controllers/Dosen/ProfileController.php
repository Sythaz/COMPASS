<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Dosen',
            'list'  => ['Home', 'Dosen']
        ];

        $page = (object) [
            'title' => 'Profil Dosen Pembimbing'
        ];

        $activeMenu = 'dosen';
        $user = UsersModel::all();

        return view('dosen.index', compact('breadcrumb', 'page', 'dosen', 'activeMenu'));
    }

    public function show(string $id)
    {
        $dosen = DosenModel::with('user')->find($id);

        if (!$dosen) {
            return redirect('/dosen')->with('error', 'Data dosen tidak ditemukan');
        }
    }

    public function edit(string $id)
    {
        $dosen = DosenModel::find($id);
        if (!$dosen) {
            return redirect('/dosen')->with('error', 'Data user tidak ditemukan');
        }

        $user = UsersModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Edit Profil',
            'list' => ['Home', 'Dosen', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Profil'
        ];

        $activeMenu = 'dosen';

        return view('dosen.edit', compact('breadcrumb', 'page', 'dosen', 'user', 'activemenu'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id'       => "required|interger",
            'nip_dosen'     => "required|integer"
        ]);
    }
}

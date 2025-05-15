<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelolaDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Dosen']
        ];

        return view('admin.kelola-pengguna.kelola-dosen.index', compact('breadcrumb'));
    }
}

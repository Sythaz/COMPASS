<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelolaMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['?', '?']
        ];

        return view('admin.kelola-pengguna.mahasiswa.index', compact('breadcrumb'));
    }
}

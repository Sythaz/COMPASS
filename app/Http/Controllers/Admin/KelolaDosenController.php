<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelolaDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['?', '?']
        ];

        return view('admin.kelola-pengguna.dosen.index', compact('breadcrumb'));
    }
}

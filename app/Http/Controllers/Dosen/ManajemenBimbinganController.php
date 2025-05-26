<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManajemenBimbinganController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Mahasiswa Bimbingan']
        ];

        return view('dosen.manajemen-bimbingan.index', compact('breadcrumb'));
    }
}

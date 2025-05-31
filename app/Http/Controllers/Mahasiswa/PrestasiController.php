<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Prestasi Mahasiswa', 'Prestasi']
        ];

        return view('mahasiswa.prestasi.index', compact('breadcrumb'));
    }
}

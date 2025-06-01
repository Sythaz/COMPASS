<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifikasiPendaftaranController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Manajemen Lomba', 'Verifikasi Pendaftaran']
        ];

        return view('admin.manajemen-lomba.verifikasi-pendaftaran.index', compact('breadcrumb'));
    }
}

<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoLombaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Info Lomba']
        ];

        return view('dosen.info-lomba.index', compact('breadcrumb'));
    }
}

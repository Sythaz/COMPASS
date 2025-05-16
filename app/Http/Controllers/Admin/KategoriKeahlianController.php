<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KategoriKeahlianController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Master Data', 'Kategori & Keahlian']
        ];

        return view('admin.master-data.kategori-keahlian', compact('breadcrumb'));
    }
}

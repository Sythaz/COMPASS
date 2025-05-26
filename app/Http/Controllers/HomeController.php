<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\PrestasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $jumlahMahasiswa = MahasiswaModel::count();
        $jumlahPrestasi = PrestasiModel::count();
        $jumlahLomba = LombaModel::count();

        $user = auth()->user(); // Bisa null kalau tamu

        return view('home', compact('jumlahMahasiswa', 'jumlahPrestasi', 'jumlahLomba', 'user'));
    }

}

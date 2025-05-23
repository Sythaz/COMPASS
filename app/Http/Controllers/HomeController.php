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
        // Mengambil data banyak mahasiswa
        $jumlahMahasiswa = MahasiswaModel::count();

        // Mengambil data banyak prestasi
        $jumlahPrestasi = PrestasiModel::count();

        // Menampilkan data banyak lomba
        $jumlahLomba = LombaModel::count();
        
        return view('home', compact('jumlahMahasiswa', 'jumlahPrestasi', 'jumlahLomba'));
    }
}

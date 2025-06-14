@extends('layouts.template')

@section('title', 'Dashboard | COMPASS')

@section('page-title', 'Dashboard')

@section('page-description', 'Hai, selamat datang kembali di COMPASS!')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body" style="cursor: pointer;"
                    onclick="location.href='{{ route('dosen.kelola-bimbingan.index') }}'">
                    <h3 class="card-title text-white">Prestasi Bimbingan</h3>
                    <div class="d-inline-block">
                        <h1 class="text-white my-3">{{ $jumlahPrestasiBimbingan }}</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fas fa-medal"></i></span>
                    <p class="text-white mb-0">Mahasiswa mendapatkan prestasi</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body" style="cursor: pointer;"
                    onclick="location.href='{{ route('dosen.verifikasi-bimbingan.index') }}'">
                    <h3 class="card-title text-white">Verifikasi Bimbingan</h3>
                    <div class="d-inline-block">
                        <h1 class="text-white my-3">{{ $jumlahVerifikasiBimbingan }}</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fas fa-user-check"></i></span>
                    <p class="text-white mb-0">Data belum diverifikasi</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mahasiswa Teraktif Semester Ini</h4>
                    <div class="table-responsive">
                        <table class="table header-border table-hover verticle-middle">
                            <thead>
                                <tr>
                                    <th scope="col-6" class="pl-0" style="width: 60%">Nama Mahasiswa</th>
                                    <th scope="col-3" class="text-center" style="width: 30%">Total Lomba</th>
                                    <th scope="col-3" style="width: 10%">Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topMahasiswaRank as $index => $mahasiswa)
                                    <tr>
                                        <td class="pl-0">{{ $mahasiswa->nama_mahasiswa }}</td>
                                        <td class="text-center">{{ $mahasiswa->total_partisipasi }}</td>
                                        <th class="label btn-disable gradient-2 text-center">#{{ $index + 1 }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lomba dengan Batas Waktu Terdekat</h4>
                    <div class="scrollable-container" style="max-height: 400px; overflow-y: auto;">
                        <table class="table header-border table-hover verticle-middle">
                            <tbody>
                                @foreach ($lombaSedangAktif as $index => $upcomingLomba)
                                    <tr>
                                        <td class="pl-0 col-8 font-weight-bold">{{ $upcomingLomba->nama_lomba }}</td>
                                        <td class="text-center col">{{ $upcomingLomba->akhir_registrasi_lomba }}</td>
                                        <th class="text-center col" style="cursor: pointer;">
                                            <i onclick="location.href='{{ route('dosen.info-lomba.index') }}'"
                                                class="fa fa-solid fa-angle-right fa-2x"></i>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush

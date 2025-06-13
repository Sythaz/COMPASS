@extends('layouts.template')

@section('title', 'Dashboard | COMPASS')

@section('page-title', 'Dashboard')

@section('page-description', 'Halo, selamat datang kembali di COMPASS!')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Partisipasi Lomba</h3>
                    <div class="d-inline-block mt-auto">
                        <h1 class="text-white my-3">{{ $totalPartisipasiLomba }}</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fas fa-chart-line"></i></span>
                    <p class="text-white mb-0">Lomba yang telah diikuti</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Lomba Tersedia</h3>
                    <div class="d-inline-block">
                        <h1 class="text-white my-3">{{ $jumlahLombaTersedia }}</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fas fa-flag-checkered"></i></span>
                    <p class="text-white mb-0">Lomba yang dapat diikuti</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Ranking</h3>
                    <div class="d-inline-block">
                        <h1 class="text-white my-3">{{ $userRank }}</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fas fa-medal"></i></span>
                    <p class="text-white mb-0">Teraktif semester ini</p>
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
                                @foreach ($topMahasiswa as $index => $mahasiswa)
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
                    <h4 class="card-title">Jadwal Batas Lomba Yang Tersedia</h4>
                    <div class="scrollable-container">
                        <table class="table header-border table-hover verticle-middle">
                            <tbody>
                                @foreach ($lombaSedangAktif as $index => $upcomingLomba)
                                    <tr>
                                        <td class="pl-0 col-8 font-weight-bold">{{ $upcomingLomba->nama_lomba }}</td>
                                        <td class="text-center col">{{ $upcomingLomba->akhir_registrasi_lomba }}</td>
                                        <th class="text-center col"><i class="fa fa-solid fa-angle-right fa-2x"></i></th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row"> --}}
        {{-- <div class="col-lg-12"> --}}
            {{-- <div class="card"> --}}
                {{-- <div class="card-body"> --}}
                    {{-- <h4 class="card-title">Perbandingan Prestasi Mahasiswa Antar Angkatan</h4> --}}
                    {{-- <div id="morris-bar-chart"></div> --}}
                {{-- </div> --}}
            {{-- </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}
@endsection

@push('css')
@endpush

@push('js')
    <!--  Morris bar chart js -->
    <script src="{{ asset('theme/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('theme/js/plugins-init/morris-init.js') }}"></script>

    <!-- Memanggil Chart Script -->
    <script src="{{ asset('js-custom/charts/mahasiswa/bar-chart.js') }}"></script>

    <!-- Data didefinisikan di view -->
    <script>
        $(document).ready(function() {
            // Data dari server
            const BarData = [{
                    y: 'Angkatan 2024',
                    a: 10
                },
                {
                    y: 'Angkatan 2023',
                    a: 20
                },
                {
                    y: 'Angkatan 2022',
                    a: 15
                },
                {
                    y: 'Angkatan 2021',
                    a: 25
                }
            ];

            // Inisialisasi chart pertama
            if (typeof MorrisBarChart !== 'undefined') {
                MorrisBarChart.init('morris-bar-chart', BarData, {
                    ykeys: ['a'],
                    labels: ['Jumlah Prestasi'],
                    barColors: ['#7571F9'],
                });
            } else {
                console.error("Bar Chart belum termuat.");
            }
        });
    </script>
@endpush
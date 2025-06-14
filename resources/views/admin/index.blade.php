@extends('layouts.template')

@section('title', 'Dashboard | COMPASS')

@section('page-title', 'Dashboard')

@section('page-description', 'Hai, selamat datang kembali di COMPASS!')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Prestasi Mahasiswa</h3>
                    <div class="d-inline-block mt-auto">
                        <h1 class="text-white my-3">{{ $persentasePrestasiMahasiswa }}%</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                    <p class="text-white mb-0">Dalam 1 tahun ini</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Lomba Aktif</h3>
                    <div class="d-inline-block">
                        <h1 class="text-white my-3">{{ $jumlahLombaAktif }}</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fas fa-flag-checkered"></i></span>
                    <p class="text-white mb-0">Sedang berlangsung</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Prestasi Sebulan Terakhir</h3>
                    <div class="d-inline-block">
                        <h1 class="text-white my-3">{{ $jmlPrestasiSebulanTerakhir }}</h1>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-calendar-alt"></i></span>
                    <p class="text-white mb-0">Mahasiswa yang berprestasi</p>
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
                    <h4 class="card-title">Lomba dengan Batas Waktu Terdekat</h4>
                    <div class="scrollable-container" style="max-height: 400px; overflow-y: auto;">
                        <table class="table header-border table-hover verticle-middle">
                            <tbody>
                                @foreach ($lombaSedangAktif as $index => $upcomingLomba)
                                    <tr>
                                        <td class="pl-0 col-8 font-weight-bold">{{ $upcomingLomba->nama_lomba }}</td>
                                        <td class="text-center col">{{ $upcomingLomba->akhir_registrasi_lomba }}</td>
                                        <th class="text-center col" style="cursor: pointer;">
                                            <i onclick="location.href='{{ route('mahasiswa.informasi-lomba.index') }}'"
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Dominasi Bidang Prestasi Mahasiswa</h4>
                    <div id="flotPie" class="flot-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Perkembangan Prestasi per Semester</h4>
                    <div id="flotLine" class="flot-chart"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <!--  flot-chart js -->
    <script src="{{ asset('theme/plugins/flot/js/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/flot/js/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('theme/plugins/flot/js/jquery.flot.init.js') }}"></script>

    <!-- Memanggil Chart Script -->
    <script src="{{ asset('js-custom/charts/admin/pie-chart.js') }}"></script>
    <script src="{{ asset('js-custom/charts/admin/line-chart.js') }}"></script>

    <!-- Menginisialisasi Chart yang dipanggil dari script diatas -->
    <script>
        $(function() {
            // Data untuk pie chart dari server
            const pieData = {!! json_encode($chartPieData) !!};

            // Inisialisasi pie chart
            PieChart.init(pieData, 'flotPie');

            // Data untuk line chart
            const lineConfig = {
                labels: @json($labels),
                values: @json($values),
                label: "Jumlah Prestasi",
                color: "#7571F9"
            };

            // Inisialisasi line chart
            LineChart.init(lineConfig, 'flotLine');
        });
    </script>
@endpush

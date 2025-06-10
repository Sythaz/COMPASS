@extends('layouts.template')
@section('title', 'Laporan | COMPASS')
@section('page-title', 'Laporan PROMETHEE')
@section('page-description',
    'Halaman untuk melihat laporan detail perhitungan rekomendasi lomba menggunakan metode
    PROMETHEE!')

@section('content')
    <div class="row">
        <!-- Tabel 1: Kriteria -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-info">
                    <h5 class="text-white mb-0"><i class="fas fa-list-alt"></i> 1. Tabel Kriteria</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Jenis</th>
                                    <th>Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['criteria'] as $criterion)
                                    <tr>
                                        <td><strong>{{ $criterion['id'] }}</strong></td>
                                        <td>{{ $criterion['name'] }}</td>
                                        <td>
                                            <span
                                                class="text-white font-weight-bold p-2 badge badge-{{ $criterion['type'] == 'benefit' ? 'success' : 'warning' }}">
                                                {{ ucfirst($criterion['type']) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($criterion['weight'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel 2: Matriks Keputusan (Nilai Preferensi) -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-info">
                    <h5 class="text-white mb-0"><i class="fas fa-table"></i> 2. Matriks Keputusan (Nilai Preferensi)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>Alternatif</th>
                                    @foreach ($data['criteria'] as $criterion)
                                        <th class="text-center">{{ $criterion['id'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['decision_matrix'] as $alternative)
                                    <tr>
                                        <td><strong>A{{ $loop->iteration }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($alternative['name'], 20) }}</small>
                                        </td>
                                        @foreach ($data['criteria'] as $criterion)
                                            <td class="text-center">
                                                {{ number_format($alternative['values'][$criterion['id']], 2) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel 3: Perhitungan Preferensi per Kriteria -->
        @foreach ($data['criteria'] as $criterion)
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h5 class="text-white mb-0"><i class="fas fa-calculator"></i> 3.{{ $loop->iteration }}. Preferensi
                            Kriteria
                            {{ $criterion['id'] }} - {{ $criterion['name'] }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Alternatif</th>
                                        @foreach ($data['decision_matrix'] as $alt)
                                            <th class="text-center">A{{ $loop->iteration }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['decision_matrix'] as $i => $altA)
                                        <tr>
                                            <td><strong>A{{ $loop->iteration }}</strong></td>
                                            @foreach ($data['decision_matrix'] as $j => $altB)
                                                <td class="text-center">
                                                    @if ($i == $j)
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        {{ $data['criteria_preferences'][$criterion['id']][$altA['id']][$altB['id']] }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Tabel 4: Indeks Preferensi Multikriteria (Weighted Preferences) -->
        @foreach ($data['criteria'] as $criterion)
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="text-white mb-0"><i class="fas fa-weight-hanging"></i> 4.{{ $loop->iteration }}.
                            Preferensi
                            Berbobot {{ $criterion['id'] }} (Bobot: {{ $criterion['weight'] }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Alternatif</th>
                                        @foreach ($data['decision_matrix'] as $alt)
                                            <th class="text-center">A{{ $loop->iteration }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['decision_matrix'] as $i => $altA)
                                        <tr>
                                            <td><strong>A{{ $loop->iteration }}</strong></td>
                                            @foreach ($data['decision_matrix'] as $j => $altB)
                                                <td class="text-center">
                                                    @if ($i == $j)
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        {{ number_format($data['weighted_preferences'][$criterion['id']][$altA['id']][$altB['id']], 3) }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Tabel 5: Matriks Multikriteria Q (Total Preferensi) -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-dark">
                    <h5 class="text-white mb-0"><i class="fas fa-sigma"></i> 5. Matriks Multikriteria Q (Total Indeks
                        Preferensi)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Alternatif</th>
                                    @foreach ($data['decision_matrix'] as $alt)
                                        <th class="text-center">A{{ $loop->iteration }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['decision_matrix'] as $i => $altA)
                                    <tr>
                                        <td><strong>A{{ $loop->iteration }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($altA['name'], 15) }}</small>
                                        </td>
                                        @foreach ($data['decision_matrix'] as $j => $altB)
                                            <td class="text-center">
                                                @if ($i == $j)
                                                    <span class="text-muted font-weight-bold">0.000</span>
                                                @else
                                                    <strong>{{ number_format($data['preference_indices'][$altA['id']][$altB['id']], 3) }}</strong>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel 6: Hasil Akhir (Flows dan Ranking) -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="text-white mb-0"><i class="fas fa-trophy"></i> 6. Hasil Akhir PROMETHEE</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Alternatif</th>
                                    <th>Nama Lomba</th>
                                    <th>Leaving Flow (φ+)</th>
                                    <th>Entering Flow (φ-)</th>
                                    <th>Net Flow (φ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['results'] as $result)
                                    <tr class="{{ $loop->first ? 'table-success font-weight-bold' : '' }}">
                                        <td class="text-center">
                                            @if ($loop->first)
                                                <i class="fas fa-crown text-warning"></i>
                                            @endif
                                            <strong>{{ $result['rank'] }}</strong>
                                        </td>
                                        <td><strong>A{{ $result['id'] }}</strong></td>
                                        <td>
                                            {{ $result['name'] }}
                                            @if ($loop->first)
                                                <span class="font-weight-bold badge badge-primary p-2 ml-2">TERBAIK</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ number_format($result['leaving_flow'], 4) }}</td>
                                        <td class="text-center">{{ number_format($result['entering_flow'], 4) }}</td>
                                        <td class="text-center">
                                            <strong class="{{ $result['net_flow'] > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($result['net_flow'], 4) }}
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penjelasan Metode -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="text-white mb-0"><i class="fas fa-info-circle"></i> Penjelasan Metode PROMETHEE</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><strong>Langkah Perhitungan:</strong></h6>
                            <ol>
                                <li><strong>Kriteria:</strong> Menentukan kriteria dengan bobot dan jenis (benefit/cost)
                                </li>
                                <li><strong>Matriks Keputusan:</strong> Nilai preferensi setiap alternatif untuk setiap
                                    kriteria</li>
                                <li><strong>Preferensi per Kriteria:</strong> Membandingkan setiap pasang alternatif
                                    menggunakan fungsi preferensi 'usual'</li>
                                <li><strong>Preferensi Berbobot:</strong> Mengalikan preferensi dengan bobot kriteria</li>
                                <li><strong>Matriks Q:</strong> Menjumlahkan semua preferensi berbobot</li>
                                <li><strong>Flows & Ranking:</strong> Menghitung leaving flow, entering flow, net flow, dan
                                    ranking</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Keterangan:</strong></h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-arrow-up text-success"></i> <strong>Leaving Flow (φ+):</strong>
                                    Seberapa kuat alternatif mendominasi yang lain</li>
                                <li><i class="fas fa-arrow-down text-danger"></i> <strong>Entering Flow (φ-):</strong>
                                    Seberapa kuat alternatif didominasi yang lain</li>
                                <li><i class="fas fa-balance-scale text-primary"></i> <strong>Net Flow (φ):</strong> Selisih
                                    leaving dan entering flow</li>
                                <li><i class="fas fa-trophy text-warning"></i> <strong>Ranking:</strong> Berdasarkan net
                                    flow tertinggi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .card-header {
            font-weight: 600;
        }

        .card-header:first-child {
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .table th {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
            font-size: 0.85rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        .fa-crown {
            font-size: 1.2rem;
        }

        .table-responsive {
            border-radius: 0.375rem;
        }
    </style>
@endpush

@push('js')
@endpush

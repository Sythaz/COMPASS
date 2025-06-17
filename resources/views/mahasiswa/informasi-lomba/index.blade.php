@extends('layouts.template')

@section('title', 'Informasi Lomba | COMPASS')
@section('page-title', 'Informasi Lomba')
@section('page-description', 'Halaman Informasi Lomba yang Tersedia')

@section('content')
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-lg-8">
                                <h5 class="font-weight-bold mb-0">Daftar Lomba Tersedia</h5>
                                <p class="text-muted mb-0">Temukan lomba yang sesuai dengan minat dan kemampuan Anda</p>
                            </div>
                            <div class="col-lg-4 text-right">
                                <button class="btn btn-primary font-weight-bold"
                                    onclick="modalAction('{{ route('create-lomba') }}')">
                                    <i class="fa-solid fa-plus"></i> Ajukan Lomba
                                </button>
                            </div>
                        </div>

                        {{-- Enhanced Filter Section --}}
                        <div class="filter-container mb-4">
                            <div class="filter-header">
                                <div class="filter-title">
                                    <i class="fas fa-filter filter-icon"></i>
                                    <span class="filter-label">Filter & Pencarian Lomba</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="reset-filters">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                            <div class="filter-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-3 filter-item">
                                        <div class="form-group">
                                            <label class="filter-form-label">
                                                <i class="fas fa-tags category-icon"></i>
                                                Kategori Lomba
                                            </label>
                                            <select class="form-control filter-select" id="categoryFilter">
                                                <option value="" {{ empty(request()->get('kategori_id')) ? 'selected' : '' }}>
                                                    Pilih Kategori...</option>
                                                @foreach ($daftarKategori as $kategori)
                                                    <option value="{{ $kategori->kategori_id }}"
                                                        {{ in_array($kategori->kategori_id, old('kategori_id', request()->get('kategori_id', []))) ? 'selected' : '' }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 filter-item">
                                        <div class="form-group">
                                            <label class="filter-form-label">
                                                <i class="fas fa-level-up-alt level-icon"></i>
                                                Tingkat Lomba
                                            </label>
                                            <select class="form-control filter-select" id="levelFilter">
                                                <option value="">Pilih Tingkat...</option>
                                                @foreach ($daftarTingkatLomba as $tingkat_lomba)
                                                    <option value="{{ $tingkat_lomba->tingkat_lomba_id }}"
                                                        {{ old('tingkat_lomba_id', $kelolaLomba->tingkat_lomba_id ?? '') == $tingkat_lomba->tingkat_lomba_id ? 'selected' : '' }}>
                                                        {{ $tingkat_lomba->nama_tingkat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 filter-item">
                                        <div class="form-group">
                                            <label class="filter-form-label">
                                                <i class="fas fa-search search-icon"></i>
                                                Cari Nama Lomba
                                            </label>
                                            <input type="text" class="form-control filter-select" id="searchInput"
                                                placeholder="Masukkan nama lomba...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 filter-item d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="button" class="btn btn-info btn-block filter-apply-btn"
                                                id="apply-filters">
                                                <i class="fas fa-search"></i>
                                                Terapkan Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nav tabs -->
                        <div class="mt-3">
                            <span class="font-weight-bold">Tampilkan:</span>
                            <ul class="nav nav-tabs mt-1" id="semua-lombaTabs" role="tablist">
                                <li class="nav-item bg-white" style="flex: none; border-radius: 8px">
                                    <a class="nav-link active" id="semua-lomba-tab" data-toggle="tab" href="#semua-lomba"
                                        role="tab" aria-controls="semua-lomba" aria-selected="true">
                                        <i class="fas fa-trophy"></i> Semua Lomba
                                    </a>
                                </li>
                                <li class="ml-1 nav-item bg-white {{ $cekUserPreferensi ? '' : 'd-none' }}"
                                    style="flex: none; border-radius: 8px">
                                    <a class="nav-link" id="rekomendasi-lomba-tab" data-toggle="tab"
                                        href="#rekomendasi-lomba" role="tab" aria-controls="rekomendasi-lomba"
                                        aria-selected="false">
                                        <i class="fas fa-lightbulb"></i> Rekomendasi Lomba
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Semua Lomba -->
        <div class="tab-content p-0" id="profileTabsContent">
            <div class="tab-pane fade show active" id="semua-lomba" role="tabpanel" aria-labelledby="semua-lomba-tab">
                <div class="row" id="lombaCards">
                    @foreach ($dataLomba as $lomba)
                        <div class="col-lg-4 col-md-6 lomba-item"
                            data-category="{{ $lomba->kategori->pluck('kategori_id')->join(',') }}"
                            data-level="{{ $lomba->tingkat_lomba_id }}" data-name="{{ $lomba->nama_lomba }}">
                            <div class="card gradient-1 lomba-card">
                                <div class="card-body position-relative">
                                    <div class="mb-3">
                                        <img src="{{ asset('assets/images/logo/compass-putih.svg') }}" alt="Compass Logo"
                                            style="height: 40px; object-fit: cover;">
                                    </div>

                                    <div class="lomba-content text-white">
                                        <h4 class="font-weight-bold text-white mb-3">{{ $lomba->nama_lomba }}</h4>
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <span class="label label-primary">
                                                {{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?? 'Tidak Diketahui' }}
                                            </span>
                                        </div>

                                        <div class="lomba-info mb-3">
                                            <p class="text-white mb-1">
                                                <i class="fa fa-trophy mr-2"></i>
                                                <small>{{ $lomba->tingkat_lomba->nama_tingkat }}</small>
                                            </p>
                                            <p class="text-white mb-1">
                                                <i class="fa fa-building mr-2"></i>
                                                <small>{{ $lomba->penyelenggara_lomba }}</small>
                                            </p>
                                            <p class="text-white mb-1">
                                                <i class="fas fa-compass mr-2"></i>
                                                <small>{{ $lomba->tipe_lomba }}</small>
                                            </p>
                                            <p class="text-white mb-1">
                                                <i class="fa fa-calendar mr-2"></i>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y') }}
                                                </small>
                                            </p>
                                        </div>

                                        <div class="lomba-deadline">
                                            <div class="alert alert-info py-2 mb-2">
                                                <small><i class="fa fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->diffInDays(\Carbon\Carbon::now()) }}
                                                    hari lagi</small>
                                            </div>
                                        </div>

                                        <div class="lomba-actions mt-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <button class="btn btn-secondary text-white btn-sm btn-block"
                                                        onclick="modalAction('{{ route('informasi-lomba.show', ['id' => $lomba->lomba_id]) }}')">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button
                                                        onclick="modalAction('{{ route('informasi-lomba.daftar', ['id' => $lomba->lomba_id]) }}')"
                                                        class="btn btn-success text-white btn-sm btn-block">
                                                        <i class="fa fa-external-link-alt"></i> Daftar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thropy Icon -->
                                    <span class="position-absolute"
                                        style="top: 25px; right: 25px; font-size: 2rem; opacity: 0.9;">
                                        <i class="fa fa-trophy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Rekomendasi Lomba --}}
            <div class="tab-pane fade" id="rekomendasi-lomba" role="tabpanel" aria-labelledby="rekomendasi-lomba-tab">
                <div class="row" id="lombaCards">
                    @forelse ($rekomendasiLomba as $lomba)
                        <div class="col-lg-4 col-md-6 lomba-item"
                            data-category="{{ $lomba->kategori->pluck('kategori_id')->join(',') }}"
                            data-level="{{ $lomba->tingkat_lomba_id }}" data-name="{{ $lomba->nama_lomba }}">
                            <div class="card gradient-1 lomba-card">
                                <div class="card-body position-relative">
                                    <div class="mb-3">
                                        <img src="{{ asset('assets/images/logo/compass-putih.svg') }}" alt="Compass Logo"
                                            style="height: 40px; object-fit: cover;">
                                    </div>

                                    <div class="lomba-content text-white">
                                        <h4 class="font-weight-bold text-white mb-3">{{ $lomba->nama_lomba }}</h4>
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <span class="label label-primary">
                                                {{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?? 'Tidak Diketahui' }}
                                            </span>
                                        </div>

                                        <div class="lomba-info mb-3">
                                            <p class="text-white mb-1">
                                                <i class="fa fa-trophy mr-2"></i>
                                                <small>{{ $lomba->tingkat_lomba->nama_tingkat }}</small>
                                            </p>
                                            <p class="text-white mb-1">
                                                <i class="fa fa-building mr-2"></i>
                                                <small>{{ $lomba->penyelenggara_lomba }}</small>
                                            </p>
                                            <p class="text-white mb-1">
                                                <i class="fas fa-compass mr-2"></i>
                                                <small>{{ $lomba->tipe_lomba }}</small>
                                            </p>
                                            <p class="text-white mb-1">
                                                <i class="fa fa-calendar mr-2"></i>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y') }}
                                                </small>
                                            </p>
                                        </div>

                                        <div class="lomba-deadline">
                                            <div class="alert alert-info py-2 mb-2">
                                                <small><i class="fa fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->diffInDays(\Carbon\Carbon::now()) }}
                                                    hari lagi</small>
                                            </div>
                                        </div>

                                        <div class="lomba-actions mt-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <button class="btn btn-secondary text-white btn-sm btn-block"
                                                        onclick="modalAction('{{ route('informasi-lomba.show', ['id' => $lomba->lomba_id]) }}')">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button
                                                        onclick="modalAction('{{ route('informasi-lomba.daftar', ['id' => $lomba->lomba_id]) }}')"
                                                        class="btn btn-success text-white btn-sm btn-block">
                                                        <i class="fa fa-external-link-alt"></i> Daftar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thropy Icon -->
                                    <span class="position-absolute"
                                        style="top: 25px; right: 25px; font-size: 2rem; opacity: 0.9;">
                                        <span class="font-weight-bold">
                                            #{{ $loop->iteration }}
                                        </span>
                                        <i class="fa fa-trophy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">
                            <p>Tidak ada lomba yang direkomendasikan saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Saat filter atau pencarian tidak ditemukan -->
        <div class="row" id="emptyState" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fa-solid fa-search fa-5x text-primary mb-4"></i>
                        <h4>Tidak ada lomba yang ditemukan</h4>
                        <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Bootstrap untuk AJAX (Hanya 1 modal) -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="ajaxModalContent">
                    <!-- Konten modal akan dimuat via AJAX -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Custom CSS untuk daftar lomba --}}
    <link href="{{ asset('css-custom/daftar-card-lomba.css') }}" rel="stylesheet">

    {{-- Custom CSS untuk tab --}}
    <link href="{{ asset('css-custom/tab-custom.css') }}" rel="stylesheet">

    <style>
        .filter-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .filter-container:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .filter-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .filter-title {
            display: flex;
            align-items: center;
            color: white;
            font-weight: 600;
        }

        .filter-icon {
            font-size: 1.2em;
            margin-right: 10px;
            color: #fff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .filter-label {
            font-size: 1.1em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .filter-body {
            padding: 25px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .filter-item {
            margin-bottom: 15px;
        }

        .filter-form-label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        .category-icon,
        .level-icon,
        .search-icon {
            margin-right: 8px;
            color: #667eea;
        }

        .filter-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.95em;
            transition: all 0.3s ease;
            background: white;
        }

        .filter-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-1px);
        }

        .filter-select:hover {
            border-color: #667eea;
        }

        .filter-apply-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .filter-apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .filter-apply-btn:active {
            transform: translateY(0);
        }

        #reset-filters {
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: all 0.3s ease;
        }

        #reset-filters:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .filter-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .filter-body {
                padding: 20px 15px;
            }

            .filter-apply-btn {
                margin-top: 10px;
            }
        }

        /* Animation for filter items */
        .filter-item {
            opacity: 0;
            animation: slideInUp 0.6s ease forwards;
        }

        .filter-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .filter-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .filter-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .filter-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced card styling */
        .lomba-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .lomba-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@push('js')
    <script>
        function filterLomba() {
            const category = $('#categoryFilter').val()?.toLowerCase();
            const level = $('#levelFilter').val()?.toLowerCase();
            const search = $('#searchInput').val()?.toLowerCase();

            let visibleCount = 0;

            $('.lomba-item').each(function() {
                const item = $(this);
                const itemCategory = item.attr('data-category')?.toLowerCase().split(',') || [];
                const itemLevel = item.attr('data-level')?.toLowerCase();
                const itemName = item.attr('data-name')?.toLowerCase();

                const matchCategory = !category || itemCategory.includes(category);
                const matchLevel = !level || itemLevel === level;
                const matchSearch = !search || itemName.includes(search);

                if (matchCategory && matchLevel && matchSearch) {
                    item.show();
                    visibleCount++;
                } else {
                    item.hide();
                }
            });

            if (visibleCount === 0) {
                $('#lombaCards').hide();
                $('#emptyState').show();
            } else {
                $('#lombaCards').show();
                $('#emptyState').hide();
            }
        }

        // Enhanced filter functionality with visual feedback
        $('#categoryFilter, #levelFilter, #searchInput').on('input change', function() {
            filterLomba();
        });

        // Apply filters button
        $('#apply-filters').on('click', function() {
            filterLomba();

            // Visual feedback
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Memuat...');
            setTimeout(() => {
                $(this).html('<i class="fas fa-search"></i> Terapkan Filter');
            }, 1000);
        });

        // Reset filters
        $('#reset-filters').on('click', function() {
            $('#categoryFilter').val('');
            $('#levelFilter').val('');
            $('#searchInput').val('');
            filterLomba();

            // Visual feedback
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            setTimeout(() => {
                $(this).html('<i class="fas fa-undo"></i> Reset');
            }, 800);
        });

        // Initialize when page loads
        $(document).ready(function() {
            filterLomba();
        });

        function modalAction(url) {
            $.get(url)
                .done(function(res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function() {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }
    </script>
@endpush
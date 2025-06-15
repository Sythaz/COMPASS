@extends('layouts.template')

@section('title', 'Kelola Lomba | COMPASS')

@section('page-title', 'Kelola Lomba')

@section('page-description', 'Halaman untuk mengelola lomba!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="filter-container mb-4">
                        <div class="filter-header">
                            <div class="filter-title">
                                <i class="fas fa-filter filter-icon"></i>
                                <span class="filter-label">Filter Lomba</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="reset-filters">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                        <div class="filter-body">
                            <div class="row align-items-end">
                                <div class="col-md-6 col-lg-4 filter-item">
                                    <div class="form-group">
                                        <label class="filter-form-label">
                                            <i class="fas fa-tags status-icon"></i>
                                            Kategori Lomba
                                        </label>
                                        <select id="filter-kategori" class="form-control filter-select" name="kategori">
                                            <option value="">Filter Kategori</option>
                                            @foreach ($daftarKategori as $kategori)
                                                <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 filter-item">
                                    <div class="form-group">
                                        <label class="filter-form-label">
                                            <i class="fas fa-trophy periode-icon"></i>
                                            Tingkat Lomba
                                        </label>
                                        <select id="filter-tingkat" class="form-control filter-select" name="tingkat">
                                            <option value="">Filter Tingkat</option>
                                            @foreach ($daftarTingkatLomba as $tingkatLomba)
                                                <option value="{{ $tingkatLomba->tingkat_lomba_id }}">
                                                    {{ $tingkatLomba->nama_tingkat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4 filter-item">
                                    <div class="form-group">
                                        {{-- MODIFIED: HTML structure for buttons updated --}}
                                        <div class="d-flex align-items-end h-100">
                                            <div class="action-buttons-container">
                                                <button type="button" class="btn btn-info filter-apply-btn" id="apply-filters">
                                                    <i class="fas fa-search"></i> Terapkan Filter
                                                </button>
                                                <button class="btn btn-primary add-lomba-btn"
                                                    onclick="modalAction('{{ url('/admin/manajemen-lomba/kelola-lomba/create') }}')">
                                                    <i class="fa-solid fa-plus"></i> Tambah Lomba
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="tabel-kelola-lomba">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Tingkat</th>
                                    <th>Awal Reg.</th>
                                    <th>Akhir Reg.</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 1px; white-space: nowrap;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        {{-- Custom Pagination DataTables --}}
                        <div class="bootstrap-pagination"></div>
                    </div>
                </div>
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="ajaxModalContent">
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('theme/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    {{-- Custom Pagination DataTables CSS --}}
    <link href="{{ asset('css-custom/pagination-datatables.css') }}" rel="stylesheet">

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
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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

        .status-icon, .periode-icon {
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
        
        /* MODIFIED: CSS rules for buttons from the example code */
        .action-buttons-container {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Space for vertical stacking */
            width: 100%;
        }

        .filter-apply-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            font-size: 0.9em;
            width: 100%;
        }

        .filter-apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .filter-apply-btn:active {
            transform: translateY(0);
        }

        .add-lomba-btn {
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.9em;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
            width: 100%;
        }

        .add-lomba-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
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
        @media (max-width: 991px) { /* Changed from 768px to match LG breakpoint better */
            .filter-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .filter-body {
                padding: 20px 15px;
            }
        }
        
        /* MODIFIED: Responsive layout for buttons on larger screens */
        @media (min-width: 992px) {
            .action-buttons-container {
                flex-direction: row; /* Side-by-side on large screens */
                gap: 12px;
            }

            .filter-apply-btn,
            .add-lomba-btn {
                flex: 1; /* Make buttons take equal width */
            }
        }
        
        /* Animation for filter items */
        .filter-item {
            opacity: 0;
            animation: slideInUp 0.6s ease forwards;
        }

        .filter-item:nth-child(1) { animation-delay: 0.1s; }
        .filter-item:nth-child(2) { animation-delay: 0.2s; }
        .filter-item:nth-child(3) { animation-delay: 0.3s; }

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
    </style>
@endpush

@push('js')
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var idDataTables = '#tabel-kelola-lomba';
        var table;

        $(document).ready(function() {
            // Dropdown tidak bisa di buka langsung sehingga perlu dipanggil
            $('.dropdown-toggle').dropdown();

            // DataTables
            table = $(idDataTables).DataTable({
                // Styling untuk pagination (Jangan diubah)
                pagingType: "simple_numbers",
                language: {
                    lengthMenu: "Tampilkan _MENU_ entri",
                    paginate: {
                        first: "Pertama",
                        previous: "Sebelum",
                        next: "Lanjut",
                        last: "Terakhir",
                    },
                    search: "Cari:",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                },

                // Bisa di Kustomisasi dengan data
                autoWidth: true,
                ajax: {
                    url: "{{ route('kelola-lomba.list') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // Untuk filter kategori dan tingkat lomba
                    data: function(data) {
                        data.kategori = $('#filter-kategori').val();
                        data.tingkat = $('#filter-tingkat').val();
                    }

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'nama_lomba',
                        name: 'nama_lomba',
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                    },
                    {
                        data: 'tingkat_lomba',
                        name: 'tingkat_lomba',
                    },
                    {
                        data: 'awal_registrasi_lomba',
                        name: 'awal_registrasi_lomba',
                    },
                    {
                        data: 'akhir_registrasi_lomba',
                        name: 'akhir_registrasi_lomba',
                    },
                    {
                        data: 'status_verifikasi',
                        name: 'status_verifikasi',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                    }
                ],
                scrollX: true,
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: null,
                    bottomEnd: null
                },

                // Styling untuk pagination (Jangan diubah)
                drawCallback: function() {
                    // Styling untuk pagination
                    $(".dataTables_wrapper").css({
                        margin: "0",
                        padding: "0",
                    });
                    $(".dataTables_paginate .pagination").addClass("justify-content-end");
                    $(".dataTables_paginate .paginate_button")
                        .removeClass("paginate_button")
                        .addClass("page-item");
                    $(".dataTables_paginate .paginate_button a")
                        .addClass("page-link")
                        .css("border-radius", "5px");
                    $(".dataTables_paginate .paginate_button.previous a").text("Sebelum");
                    $(".dataTables_paginate .paginate_button.next a").text("Lanjut");
                    $(".dataTables_paginate .paginate_button.first a").text("Pertama");
                    $(".dataTables_paginate .paginate_button.last a").text("Terakhir");

                    // Styling untuk input dan dropdown
                    $(idDataTables + ' select').css({
                        width: "auto",
                        height: "auto",
                        "border-radius": "5px",
                        border: "1px solid #ced4da",
                    });
                    $(idDataTables + '_filter input').css({
                        height: "auto",
                        "border-radius": "5px",
                        border: "1px solid #ced4da",
                    });
                    $(idDataTables + '_wrapper .table-bordered').css({
                        "border-top-left-radius": "5px",
                        "border-top-right-radius": "5px",
                    });
                    $(idDataTables + '_wrapper .dataTables_scrollBody table').css({
                        "border-top-left-radius": "0px",
                        "border-top-right-radius": "0px",
                        "border-bottom-left-radius": "5px",
                        "border-bottom-right-radius": "5px",
                    });
                }
            });
            
            // Reload saat filter berubah
            $('#filter-kategori, #filter-tingkat').on('change', function() {
                table.ajax.reload();
            });
        });

        // Reset filters with visual feedback
        function resetFilter() {
            $('#filter-kategori').val('').trigger('change');
            $('#filter-tingkat').val('').trigger('change');
            table.ajax.reload();
        }

        // Apply filters button with visual feedback
        $('#apply-filters').on('click', function() {
            table.ajax.reload();
            
            // Visual feedback
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Memfilter...');
            setTimeout(() => {
                $(this).html('<i class="fas fa-search"></i> Terapkan Filter');
            }, 800);
        });

        // Enhanced reset filters button
        $('#reset-filters').on('click', function() {
            resetFilter();
            
            // Visual feedback
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            setTimeout(() => {
                $(this).html('<i class="fas fa-undo"></i> Reset');
            }, 800);
        });

        // Fungsi untuk menampilkan modal
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

        // Fungsi untuk reload dataTables
        $(idDataTables).on('change', function() {
            dataTables.ajax.reload();
        })
    </script>
@endpush
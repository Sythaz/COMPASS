@extends('layouts.template')

@section('title', 'Kelola Prestasi | COMPASS')

@section('page-title', 'Kelola Prestasi')

@section('page-description', 'Halaman untuk mengelola prestasi!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Tombol Tambah dan Ekspor --}}
                    <div class="row mb-3">
                        <div class="col-6">
                            <a onclick="modalAction('{{ route('kelola-prestasi.create') }}')"
                                class="btn btn-primary text-white">
                                <i class="fa-solid fa-plus"></i>
                                <strong>Tambah Data</strong>
                            </a>
                        </div>
                        <div class="col-6 text-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-file-export"></i>
                                    <strong>Menu Ekspor</strong>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" id="export-excel">Ekspor ke XLSX</a>
                                    <a class="dropdown-item" href="#" id="export-pdf">Ekspor ke PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Enhanced Filter Section --}}
                    <div class="filter-container mb-4">
                        <div class="filter-header">
                            <div class="filter-title">
                                <i class="fas fa-filter filter-icon"></i>
                                <span class="filter-label">Filter Data</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="reset-filters">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                        <div class="filter-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 filter-item">
                                    <div class="form-group">
                                        <label class="filter-form-label">
                                            <i class="fas fa-check-circle status-icon"></i>
                                            Status Verifikasi
                                        </label>
                                        <select id="filter-status" class="form-control filter-select">
                                            <option value="">Pilih Status...</option>
                                            <option value="menunggu">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </option>
                                            <option value="valid">
                                                <i class="fas fa-check"></i> Valid
                                            </option>
                                            <option value="terverifikasi">
                                                <i class="fas fa-check-double"></i> Terverifikasi
                                            </option>
                                            <option value="ditolak">
                                                <i class="fas fa-times"></i> Ditolak
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 filter-item">
                                    <div class="form-group">
                                        <label class="filter-form-label">
                                            <i class="fas fa-calendar-alt periode-icon"></i>
                                            Periode Semester
                                        </label>
                                        <select id="filter-periode" class="form-control filter-select">
                                            <option value="">Pilih Periode...</option>
                                            @foreach (\App\Models\PeriodeModel::all() as $periode)
                                                <option value="{{ $periode->periode_id }}">{{ $periode->semester_periode }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4 filter-item d-flex align-items-end">
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

                    {{-- Tabel --}}
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="prestasiTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Nama Lomba</th>
                                    <th>Jenis</th>
                                    <th>Juara</th>
                                    <th>Dosen Pembimbing</th>
                                    <th>Tanggal Prestasi</th>
                                    <th>Status Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    {{-- Modal --}}
                    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="ajaxModalContent"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('theme/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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

            0%,
            100% {
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

        .status-icon,
        .periode-icon {
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
        var idDataTables = '#prestasiTable';
        let table;

        $(function() {
            table = $(idDataTables).DataTable({
                // Styling untuk pagination (Jangan diubah)
                pagingType: "simple_numbers",
                language: {
                    lengthMenu: "Tampilkan _MENU_ entri",
                    paginate: {
                        first: "Pertama",
                        previous: "Sebelum",
                        next: "Lanjut",
                        last: "Terakhir"
                    },
                    search: "Cari:",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri"
                },
                
                autoWidth: true,
                ajax: {
                    url: '{{ route('kelola-prestasi.list') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(d) {
                        d.status_verifikasi = $('#filter-status').val();
                        d.periode_id = $('#filter-periode').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'ketua_mahasiswa',
                        name: 'ketua_mahasiswa'
                    },
                    {
                        data: 'nama_lomba',
                        name: 'lomba_id'
                    },
                    {
                        data: 'jenis_prestasi',
                        name: 'jenis_prestasi',
                        className: 'text-center'
                    },
                    {
                        data: 'juara_prestasi',
                        name: 'juara_prestasi',
                        className: 'text-center'
                    },
                    {
                        data: 'dosen_pembimbing',
                        name: 'dosen_id'
                    },
                    {
                        data: 'tanggal_prestasi',
                        name: 'tanggal_prestasi',
                        className: 'text-center'
                    },
                    {
                        data: 'status_verifikasi',
                        name: 'status_verifikasi',
                        className: 'text-center'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                scrollX: true,
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: null,
                    bottomEnd: null
                },

                // Callback untuk styling tambahan
                drawCallback: function() {
                    $(".dataTables_wrapper").css({
                        margin: "0",
                        padding: "0"
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
                    $(idDataTables + '_length select').css({
                        width: "auto",
                        height: "auto",
                        "border-radius": "5px",
                        border: "1px solid #ced4da"
                    });
                    $(idDataTables + '_filter input').css({
                        height: "auto",
                        "border-radius": "5px",
                        border: "1px solid #ced4da"
                    });
                    $(idDataTables + '_wrapper .table-bordered').css({
                        "border-top-left-radius": "5px",
                        "border-top-right-radius": "5px"
                    });
                    $(idDataTables + '_wrapper .dataTables_scrollBody table').css({
                        "border-top-left-radius": "0px",
                        "border-top-right-radius": "0px",
                        "border-bottom-left-radius": "5px",
                        "border-bottom-right-radius": "5px"
                    });
                }
            });

            // Enhanced filter functionality
            $('#filter-status, #filter-periode').change(function() {
                table.ajax.reload();
            });

            // Apply filters button
            $('#apply-filters').on('click', function() {
                table.ajax.reload();

                // Visual feedback
                $(this).html('<i class="fas fa-spinner fa-spin"></i> Memuat...');
                setTimeout(() => {
                    $(this).html('<i class="fas fa-search"></i> Terapkan Filter');
                }, 1000);
            });

            // Reset filters
            $('#reset-filters').on('click', function() {
                $('#filter-status').val('').trigger('change');
                $('#filter-periode').val('').trigger('change');
                table.ajax.reload();

                // Visual feedback
                $(this).html('<i class="fas fa-spinner fa-spin"></i>');
                setTimeout(() => {
                    $(this).html('<i class="fas fa-undo"></i> Reset');
                }, 800);
            });

            // Ekspor ke Excel
            $('#export-excel').on('click', function(e) {
                e.preventDefault();
                const status = $('#filter-status').val();
                const periode = $('#filter-periode').val();
                let url = "{{ route('prestasi.export-excel') }}";
                const params = [];
                if (status) params.push('status=' + encodeURIComponent(status));
                if (periode) params.push('periode_id=' + encodeURIComponent(periode));
                if (params.length > 0) url += '?' + params.join('&');
                window.location.href = url;
            });

            // Ekspor ke PDF
            $('#export-pdf').on('click', function(e) {
                e.preventDefault();
                const status = $('#filter-status').val();
                const periode = $('#filter-periode').val();
                let url = "{{ route('prestasi.export-pdf') }}";
                const params = [];
                if (status) params.push('status=' + encodeURIComponent(status));
                if (periode) params.push('periode_id=' + encodeURIComponent(periode));
                if (params.length > 0) url += '?' + params.join('&');
                window.location.href = url;
            });
        });

        function modalAction(url) {
            $('#ajaxModalContent').html('Loading...');
            $.get(url)
                .done(function(res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function() {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }

        $('#myModal').on('hidden.bs.modal', function() {
            $('#ajaxModalContent').html('');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endpush

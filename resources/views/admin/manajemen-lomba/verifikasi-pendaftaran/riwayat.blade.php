@extends('layouts.template')

@section('title', 'Histori Pendaftaran Lomba | COMPASS')
@section('page-title', 'Histori Pendaftaran Lomba')
@section('page-description', 'Halaman Histori Pendaftaran Lomba')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Filter Status Verifikasi --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form id="filterForm" class="form-inline">
                                <label class="mr-2 font-weight-bold" for="status_verifikasi">Status:</label>
                                <select class="form-control mr-2" id="status_verifikasi" name="status_verifikasi">
                                    <option value="">Semua</option>
                                    <option value="menunggu">Menunggu</option>
                                    <option value="terverifikasi">Terverifikasi</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </form>
                        </div>

                        {{-- Menu Ekspor dan Kembali --}}
                        <div class="col-md-6 text-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-file-export"></i>
                                    <strong>Menu Ekspor</strong>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#" id="exportExcel" class="dropdown-item">Ekspor Data ke XLSX</a>
                                    <a href="#" id="exportPdf" class="dropdown-item">Ekspor Data ke PDF</a>
                                </div>
                                <a href="{{ route('verifikasi-pendaftaran.index') }}"
                                    class="ml-2 btn btn-primary text-white">
                                    <i class="fa-solid fa-circle-arrow-left"></i> <strong>Kembali</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="pendaftaranTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Nama Lomba</th>
                                    <th>Tipe Lomba</th>
                                    <th class="text-center">Tanggal Daftar</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data dimuat via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="ajaxModalContent">
                {{-- Konten modal dimuat dari AJAX --}}
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('theme/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css-custom/pagination-datatables.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var idDataTables = '#pendaftaranTable';
        var prestasiTable;

        $(document).ready(function() {
            let table = $(idDataTables).DataTable({
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
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    zeroRecords: "Tidak ada data yang ditemukan."
                },
                autoWidth: true,
                ajax: {
                    url: '{{ route('riwayat-pendaftaran.list') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.status_verifikasi = $('#status_verifikasi').val();
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
                        data: 'nama_mahasiswa',
                        name: 'mahasiswa.nama_mahasiswa'
                    },
                    {
                        data: 'nama_lomba',
                        name: 'lomba.nama_lomba'
                    },
                    {
                        data: 'tipe_lomba',
                        name: 'lomba.tipe_lomba'
                    },
                    {
                        data: 'tanggal_daftar',
                        name: 'created_at',
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
                order: [
                    [4, 'desc']
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

            // Reload otomatis saat filter status berubah
            $('#status_verifikasi').on('change', function() {
                table.ajax.reload();
            });

            // Modal AJAX
            window.modalAction = function(url) {
                $.get(url)
                    .done(function(res) {
                        $('#ajaxModalContent').html(res);
                        $('#myModal').modal('show');
                    })
                    .fail(function() {
                        Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                    });
            };

            // Tombol ekspor dengan filter
            $('#exportExcel').on('click', function(e) {
                e.preventDefault();
                let status = $('#status_verifikasi').val();
                let url = '{{ route('pendaftaran.export-excel') }}' + (status ? '?status_verifikasi=' +
                    status : '');
                window.location.href = url;
            });

            $('#exportPdf').on('click', function(e) {
                e.preventDefault();
                let status = $('#status_verifikasi').val();
                let url = '{{ route('pendaftaran.export-pdf') }}' + (status ? '?status_verifikasi=' +
                    status : '');
                window.location.href = url;
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endpush

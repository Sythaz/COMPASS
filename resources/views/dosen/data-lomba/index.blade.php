@extends('layouts.template')

@section('title', 'Riwayat Pengajuan Lomba | COMPASS')
@section('page-title', 'Riwayat Pengajuan Lomba')
@section('page-description', 'Halaman Daftar Riwayat Lomba yang pernah Diajukan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <a onclick="modalAction('{{ route('dosen.data-lomba.create') }}')"
                                class="btn btn-primary text-white">
                                <i class="fa-solid fa-plus"></i>
                                <strong> Ajukan Lomba</strong>
                            </a>
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
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="bootstrap-pagination"></div>
                    </div>

                    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
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
@endpush

@push('js')
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            const idDataTables = '#tabel-kelola-lomba';

            // Inisialisasi dropdown
            $('.dropdown-toggle').dropdown();

            const dataTables = $(idDataTables).DataTable({
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
                    emptyTable: "Anda belum pernah mengajukan Lomba",
                },

                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: '{{ route("dosen.data-lomba.list") }}',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_lomba', name: 'nama_lomba' },
                    { data: 'kategori', name: 'kategori' },
                    { data: 'tingkat_lomba', name: 'tingkat_lomba' },
                    { data: 'awal_registrasi_lomba', name: 'awal_registrasi_lomba' },
                    { data: 'akhir_registrasi_lomba', name: 'akhir_registrasi_lomba' },
                    { data: 'status_verifikasi', name: 'status_verifikasi', className: 'text-center' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' },
                ],
                scrollX: true,
                drawCallback: function () {
                    // Styling pagination
                    $(".dataTables_wrapper").css({ margin: "0", padding: "0" });
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
                        width: "auto", height: "auto", "border-radius": "5px", border: "1px solid #ced4da",
                    });
                    $(idDataTables + '_filter input').css({
                        height: "auto", "border-radius": "5px", border: "1px solid #ced4da",
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

            // Reload dataTables jika dibutuhkan
            $(idDataTables).on('change', function () {
                dataTables.ajax.reload();
            });

            // Fungsi modal ajax
            window.modalAction = function (url) {
                $.get(url)
                    .done(function (res) {
                        $('#ajaxModalContent').html(res);
                        $('#myModal').modal('show');
                    })
                    .fail(function () {
                        Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                    });
            };
        });
    </script>
@endpush
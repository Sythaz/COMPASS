@extends('layouts.template')

@section('title', 'Kelola Lomba | COMPASS')

@section('page-title', 'Kelola Lomba')

@section('page-description', 'Halaman untuk mengelola lomba!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <a onclick="modalAction('{{ url('/admin/manajemen-lomba/kelola-lomba/create') }}')"
                                class="btn btn-primary text-white">
                                <i class="fa-solid fa-plus"></i>
                                <strong>Tambah Data</strong>
                            </a>
                            <a href="javascript:void(0)" class="ml-2 btn btn-primary">
                                <i class="fa-solid fa-file-import"></i>
                                <strong> Impor Data</strong>
                            </a>
                        </div>
                        <div class="col-6 text-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="fa-solid fa-file-export"></i> <strong>Menu Ekspor </strong></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Ekspor Data ke XLSX</a>
                                    <a class="dropdown-item" href="#">Ekspor Data ke PDF</a>
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
                <!-- Modal Bootstrap untuk AJAX (Hanya 1 modal) -->
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="ajaxModalContent">
                            <!-- Konten modal akan dimuat via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link href="{{ asset('theme/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    {{-- Custom Pagination DataTables CSS --}}
    <link href="{{ asset('css-custom/pagination-datatables.css') }}" rel="stylesheet">
@endpush

@push('js')
    <!--  Script DataTables -->
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        var idDataTables = '#tabel-kelola-lomba';

        $(document).ready(function() {
            // Dropdown tidak bisa di buka langsung sehingga perlu dipanggil
            $('.dropdown-toggle').dropdown();

            // DataTables
            $(idDataTables).DataTable({
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
                serverSide: true,
                autoWidth: true,
                ajax: {
                    url: "{{ route('kelola-lomba.list') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                        orderable: false,
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                    }
                ],

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
                        "border-radius": "5px",
                    });
                }
            });
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

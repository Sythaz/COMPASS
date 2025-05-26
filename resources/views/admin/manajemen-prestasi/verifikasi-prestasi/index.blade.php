@extends('layouts.template')

@section('title', 'Verifikasi Prestasi | COMPASS')

@section('page-title', 'Verifikasi Prestasi')

@section('page-description', 'Halaman untuk memverifikasi prestasi!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">                    
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="tabel-verifikasi-prestasi">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Nama Lomba</th>
                                    {{-- <th>Dosen Pembimbing</th> --}}
                                    <th>Tanggal</th>
                                    <th>Juara</th>
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

    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css"
        rel="stylesheet" />
@endpush

@push('js')
    <!--  Script DataTables -->
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var idDataTables = '#tabel-verifikasi-prestasi';

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
                    url: "{{ route('verifikasi-prestasi.list') }}",
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
                        data: 'mahasiswa.nim_mahasiswa',
                        name: 'mahasiswa.nim_mahasiswa',
                    },
                    {
                        data: 'mahasiswa.nama_mahasiswa',
                        name: 'mahasiswa.nama_mahasiswa',
                    },
                    {
                        data: 'lomba.nama_lomba',
                        name: 'lomba.nama_lomba',
                    },           
                    // {
                    //     data: 'dosen.nama_dosen',
                    //     name: 'dosen.nama_dosen',
                    // },
                    {
                        data: 'tanggal_prestasi',
                        name: 'tanggal_prestasi',
                    },
                    {
                        data: 'juara_prestasi',
                        name: 'juara_prestasi',
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


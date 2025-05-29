@extends('layouts.template')

@section('title', 'Kelola Mahasiswa Bimbingan | COMPASS')
@section('page-title', 'Kelola Mahasiswa Bimbingan')
@section('page-description', 'Halaman Kelola Mahasiswa Bimbingan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="tabel-kelola-bimbingan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Nama Lomba</th>
                                <th>Kategori</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap Kosong untuk AJAX content -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="ajaxModalContent">
                <!-- Konten modal akan di-load via AJAX -->
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            const idDataTables = '#tabel-kelola-bimbingan';

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
                    emptyTable: "Belum ada mahasiswa bimbingan.",
                },
                serverSide: true,
                processing: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: "{{ route('dosen.kelola-bimbingan.list') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nim_mahasiswa', name: 'mahasiswa.nim_mahasiswa' },
                    { data: 'nama_mahasiswa', name: 'mahasiswa.nama_mahasiswa' },
                    { data: 'nama_lomba', name: 'lomba.nama_lomba' },
                    { data: 'nama_kategori', name: 'kategori.nama_kategori' },
                    { data: 'semester_periode', name: 'periode.semester_periode' },
                    { data: 'status_verifikasi', name: 'status_verifikasi', className: 'text-center' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
                ],
                scrollX: true,
                drawCallback: function () {
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

            // Optional reload trigger
            $(idDataTables).on('change', function () {
                dataTables.ajax.reload();
            });
        });
    </script>
@endpush
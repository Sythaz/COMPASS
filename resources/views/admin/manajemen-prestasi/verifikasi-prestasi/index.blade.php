@extends('layouts.template')

@section('title', 'Verifikasi Prestasi | COMPASS')

@section('page-title', 'Verifikasi Prestasi')

@section('page-description', 'Halaman untuk memverifikasi prestasi!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Tombol Tambah Data --}}
                    <div class="mb-3">
                        <a onclick="modalAction('{{ route('kelola-prestasi.create') }}')" class="btn btn-primary text-white">
                            <i class="fa-solid fa-plus"></i>
                            <strong>Tambah Data</strong>
                        </a>
                    </div>

                    {{-- Tambahkan Tabel DataTables di sini --}}
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="prestasiTable">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>Mahasiswa</th>
                                    <th>Nama Lomba</th>
                                    <th>Jenis</th>
                                    <th>Juara</th>
                                    <th>Dosen Pembimbing</th>
                                    <th>Tanggal Prestasi</th>
                                    <th>Status Verifikasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    {{-- Modal untuk menampilkan form --}}
                    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="ajaxModalContent">
                                {{-- Konten modal akan dimuat lewat Ajax --}}
                            </div>
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
        var idDataTables = '#prestasiTable';
        var prestasiTable;

        $(function() {
            prestasiTable = $(idDataTables).DataTable({
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
                autoWidth: false,
                ajax: {
                    url: '{{ route('verifikasi-prestasi.list') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        name: 'jenis_prestasi'
                    },
                    {
                        data: 'juara_prestasi',
                        name: 'juara_prestasi'
                    },
                    {
                        data: 'dosen_pembimbing',
                        name: 'dosen_id'
                    },
                    {
                        data: 'tanggal_prestasi',
                        name: 'tanggal_prestasi'
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
                drawCallback: function() {
                    // Styling untuk pagination
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
                    $(idDataTables + ' select').css({
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

        // Bersihkan modal saat ditutup
        $('#myModal').on('hidden.bs.modal', function() {
            $('#ajaxModalContent').html('');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Submit form verifikasi (terima/tolak) via AJAX
        $(document).on('submit', '#form-verifikasi', function(e) {
            e.preventDefault();
            var $form = $(this);
            var url = $form.attr('action');
            var method = $form.find('input[name="_method"]').val() || $form.attr('method');
            var data = $form.serialize();

            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function(response) {
                    $('#myModal').modal('hide');
                    Swal.fire('Berhasil', 'Verifikasi prestasi berhasil.', 'success');
                    prestasiTable.ajax.reload(null, false); // reload data tanpa reset paging
                },
                error: function(xhr) {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat verifikasi.', 'error');
                }
            });
        });
    </script>
@endpush

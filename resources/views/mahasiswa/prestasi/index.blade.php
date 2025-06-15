@extends('layouts.template')

@section('title', 'Prestasi Mahasiswa | COMPASS')
@section('page-title', 'Prestasi Mahasiswa')
@section('page-description', 'Halaman Informasi Prestasi Mahasiswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Tombol Tambah Data --}}
                    <div class="mb-3">
                        <a onclick="modalAction('{{ route('mhs.prestasi.create') }}')" class="btn btn-primary text-white">
                            <i class="fa-solid fa-plus"></i>
                            <strong>Tambah Data</strong>
                        </a>
                    </div>

                    {{-- Tambahkan Tabel DataTables di sini --}}
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="prestasiTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Lomba</th>
                                    <th class="text-center">Juara</th>
                                    <th>Dosen Pembimbing</th>
                                    <th class="text-center">Tanggal Prestasi</th>
                                    <th class="text-center">Status Verifikasi</th>
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
        function modalAction(url) {
            console.log('modalAction dipanggil dengan url:', url);
            $('#ajaxModalContent').html('Loading...'); // beri feedback loading

            $.get(url)
                .done(function(res) {
                    console.log('ajax berhasil, isi modal diisi');
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                    console.log('modal di-show');
                })
                .fail(function() {
                    console.log('ajax gagal');
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }

        $('#myModal').on('hidden.bs.modal', function() {
            console.log('modal ditutup, konten dibersihkan');
            $('#ajaxModalContent').html('');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Inisialisasi DataTables
        var idDataTables = '#prestasiTable';

        $(function() {
            $(idDataTables).DataTable({
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
                    emptyTable: "Tidak ada data prestasi yang tersedia.",
                    infoEmpty: "Tidak ada data yang ditemukan.",
                },
                serverSide: true,
                autoWidth: false,
                scrollX: true,
                ajax: '{{ route('mhs.prestasi.list') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_lomba',
                        name: 'lomba_id'
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
                        width: "110px",
                        className: 'text-center'
                    }
                ],
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
        });

        function modalDetailPrestasi(url) {
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
        $(document).on('click', '.btn-detail-prestasi', function() {
            var url = $(this).data('url');
            modalDetailPrestasi(url);
        });
    </script>
@endpush

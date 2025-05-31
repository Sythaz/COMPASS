@extends('layouts.template')

@section('title', 'Input Lomba | COMPASS')
@section('page-title', 'Input Lomba')
@section('page-description', 'Halaman Input dan Kelola Lomba (Mahasiswa)')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-6">
                            <a onclick="modalAction('{{ url('mahasiswa.lomba.create') }}')"
                               class="btn btn-primary text-white">
                                <i class="fa-solid fa-plus"></i>
                                <strong>Tambah Lomba</strong>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tabel-lomba"
                               class="w-100 table table-striped table-bordered custom-datatable">
                            <thead>
                                <tr>
                                    <th style="width:1px; white-space:nowrap;">No</th>
                                    <th>Nama Lomba</th>
                                    <th>Tingkat</th>
                                    <th>Penyelenggara</th>
                                    <th>Periode Registrasi</th>
                                    <th class="text-center" style="width:1px; white-space:nowrap;">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <div class="bootstrap-pagination"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap untuk AJAX -->
    <div class="modal fade" id="myModal" tabindex="-1"
         aria-labelledby="ajaxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="ajaxModalContent">
                {{-- Konten modal akan dimuat via AJAX --}}
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('theme/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}"
          rel="stylesheet">
    <link href="{{ asset('css-custom/pagination-datatables.css') }}" rel="stylesheet">
@endpush

@push('js')
<script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const idDataTables = '#tabel-lomba';

    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();

        $(idDataTables).DataTable({
            pagingType: "simple_numbers",
            serverSide: true,
            autoWidth: true,
            processing: true,

            ajax: {
                url: "{{ route('mahasiswa.lomba.list') }}",
                type: "POST", // ✅ Ganti ke POST
                data: {
                    _token: "{{ csrf_token() }}" // ✅ Wajib untuk POST
                }
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_lomba', name: 'nama_lomba' },
                { data: 'tingkat', name: 'tingkat' },
                { data: 'penyelenggara', name: 'penyelenggara' },
                { data: 'periode_registrasi', name: 'periode_registrasi' },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '160px'
                },
            ],

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

            drawCallback: function () {
                $(".dataTables_wrapper").css({ margin: 0, padding: 0 });
                $(".dataTables_paginate .pagination").addClass("justify-content-end");
                $(".dataTables_paginate .paginate_button")
                    .removeClass("paginate_button")
                    .addClass("page-item");
                $(".dataTables_paginate .paginate_button a")
                    .addClass("page-link")
                    .css("border-radius", "5px");
                $(idDataTables + ' select').css({
                    width: "auto", height: "auto",
                    "border-radius": "5px", border: "1px solid #ced4da"
                });
                $(idDataTables + '_filter input').css({
                    height: "auto",
                    "border-radius": "5px",
                    border: "1px solid #ced4da"
                });
                $(idDataTables + '_wrapper .table-bordered').css({ "border-radius": "5px" });
            }
        });

        // Jika ada filter
        $(idDataTables).on('change', function () {
            $(idDataTables).DataTable().ajax.reload();
        });
    });

    function modalAction(url) {
        $.get(url)
            .done(res => {
                $('#ajaxModalContent').html(res);
                const modalEl = document.getElementById('myModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            })
            .fail(() => {
                Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error');
            });
    }
</script>
@endpush

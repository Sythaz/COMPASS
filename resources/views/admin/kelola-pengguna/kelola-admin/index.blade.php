@extends('layouts.template')

@section('title', 'Kelola Admin | COMPASS')
@section('page-title', 'Kelola Admin')
@section('page-description', 'Halaman Kelola Admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        {{-- Pilihan Menu --}}
                        <div class="col-6">
                            {{-- Tombol Create Data Baru --}}
                            <a onclick="modalAction('{{ url('/admin/kelola-pengguna/admin/create') }}')"
                                class="btn btn-primary text-white">
                                <i class="fa-solid fa-plus"></i>
                                <strong>Tambah Data</strong>
                            </a>
                            {{-- Import Data Excel --}}
                            <a onclick="modalAction('{{ route('admin.import.form') }}')"
                                class="ml-2 btn btn-primary text-white">
                                <i class="fa-solid fa-file-import"></i>
                                <strong> Import Data</strong>
                            </a>
                        </div>
                        {{-- Menu Export Excel/PDF --}}
                        <div class="col-6 text-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-file-export"></i>
                                    <strong>Menu Ekspor</strong>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.export_excel') }}">Ekspor Data ke
                                        XLSX</a>
                                    <a class="dropdown-item" href="{{ route('admin.export_pdf') }}">Ekspor Data ke
                                        PDF</a>
                                </div>
                                {{-- Menu History --}}
                                <a href="{{ route('admin.history') }}" class="ml-2 btn btn-primary text-white">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    <strong> History</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="tabel-admin">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 1px; white-space: nowrap;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="bootstrap-pagination"></div>
                    </div>
                </div>

                <!-- Modal Bootstrap untuk AJAX -->
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="ajaxModalContent">
                            {{-- Konten modal akan dimuat via AJAX --}}
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
    <link href="{{ asset('css-custom/pagination-datatables.css') }}" rel="stylesheet">
@endpush

@push('js')
    <!-- jQuery dan DataTables -->
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap Bundle JS (Wajib! agar Modal bisa jalan) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom show header JS -->
    <script src="{{ asset('js-custom/header-show-bootstrap5.js') }}"></script>

    <script>
        const idDataTables = '#tabel-admin';

        $(document).ready(function () {
            $('.dropdown-toggle').dropdown();

            $(idDataTables).DataTable({
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
                serverSide: true,
                autoWidth: true,
                ajax: {
                    url: "{{ url('admin/kelola-pengguna/admin/list') }}",
                    type: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    {
                        data: 'nip_admin',
                        name: 'nip_admin',
                        render: function (data, type, row, meta) {
                            // Paksa tampilkan sebagai string, agar tidak dibulatkan
                            return data ? data.toString() : '';
                        }
                    },
                    { data: 'nama_admin', name: 'nama_admin' },
                    { data: 'username', name: 'users.username' },
                    { data: 'status', name: 'status', className: 'text-center' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center', width: '150px' },
                ],
                drawCallback: function () {
                    $(".dataTables_wrapper").css({ margin: "0", padding: "0" });
                    $(".dataTables_paginate .pagination").addClass("justify-content-end");
                    $(".dataTables_paginate .paginate_button").removeClass("paginate_button").addClass("page-item");
                    $(".dataTables_paginate .paginate_button a").addClass("page-link").css("border-radius", "5px");
                    $(".dataTables_paginate .paginate_button.previous a").text("Sebelum");
                    $(".dataTables_paginate .paginate_button.next a").text("Lanjut");
                    $(".dataTables_paginate .paginate_button.first a").text("Pertama");
                    $(".dataTables_paginate .paginate_button.last a").text("Terakhir");

                    $(idDataTables + ' select').css({
                        width: "auto", height: "auto", "border-radius": "5px", border: "1px solid #ced4da",
                    });
                    $(idDataTables + '_filter input').css({
                        height: "auto", "border-radius": "5px", border: "1px solid #ced4da",
                    });
                    $(idDataTables + '_wrapper .table-bordered').css({ "border-radius": "5px" });
                }
            });
        });

        function modalAction(url) {
            $.get(url)
                .done(function (res) {
                    $('#ajaxModalContent').html(res);

                    // Bootstrap 5 way to show modal
                    const modalEl = document.getElementById('myModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                })
                .fail(function () {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }


        $(idDataTables).on('change', function () {
            $(idDataTables).DataTable().ajax.reload();
        });
    </script>
@endpush
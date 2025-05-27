@extends('layouts.template')

@section('title', 'Informasi Lomba | COMPASS')
@section('page-title', 'Informasi Lomba')
@section('page-description', 'Halaman Informasi Lomba')

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
                                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-file-export"></i>
                                    <strong>Menu Ekspor </strong></button>
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
                                    {{-- <th class="text-center" style="width: 1px; white-space: nowrap;">Aksi</th> --}}
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

    <!-- Script Select2 Dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endpush

@push('js')
    <script>
        $(function () {
            $('#tabel-kelola-lomba').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("info-lomba.list") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nama_lomba', name: 'nama_lomba' },
                    { data: 'kategori', name: 'kategori' },
                    { data: 'tingkat_lomba', name: 'tingkat_lomba' },
                    { data: 'awal_registrasi_lomba', name: 'awal_registrasi_lomba' },
                    { data: 'akhir_registrasi_lomba', name: 'akhir_registrasi_lomba' },
                    { data: 'status_lomba', name: 'status_lomba', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
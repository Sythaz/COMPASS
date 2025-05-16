@extends('layouts.template')

@section('title', 'Kelola Kategori & Keahlian | COMPASS')

@section('page-title', 'Kelola Kategori & Keahlian')

@section('page-description', 'Halaman untuk mengelola kategori lomba & bidang keahlian dosen!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <a href="javascript:void(0)" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                                <strong>Tambah Data</strong></a>
                            <a href="javascript:void(0)" class="ml-2 btn btn-primary">
                                <i class="fa-solid fa-file-import"></i>
                                <strong> Impor Data</strong>
                            </a>
                        </div>
                        <div class="col-6 text-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-file-export"></i> <strong>Menu Ekspor </strong></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Ekspor Data ke XLSX</a>
                                    <a class="dropdown-item" href="#">Ekspor Data ke PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>Nama Kategori Lomba dan Bidang Keahlian Dosen</th>
                                    <th class="text-center" style="width: 1px; white-space: nowrap;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>System Architect</td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Accountant</td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Junior Technical Author</td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Senior Javascript Developer</td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Accountant</td>
                                    <td class="text-center"></td>
                                </tr>
                            </tbody>
                        </table>
                        {{-- Custom Pagination DataTables --}}
                        <div class="bootstrap-pagination"></div>
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
    <script src="{{ asset('theme/plugins/tables/js/datatable-init/datatable-basic.min.js') }}"></script>
@endpush

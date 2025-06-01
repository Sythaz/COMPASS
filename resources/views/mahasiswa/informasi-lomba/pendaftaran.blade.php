@extends('layouts.template')

@section('title', 'Riwayat Pendaftaran | COMPASS')
@section('page-title', 'Riwayat Pendaftaran Lomba')
@section('page-description', 'Halaman Riwayat Pendaftaran Lomba')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="tabel-kelola-lomba">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>Nama Lomba</th>
                                    <th>Tingkat</th>
                                    <th>Kategori</th>
                                    <th>Tipe Lomba</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="bootstrap-pagination"></div>
                    </div>
                </div>

                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="ajaxModalContent">
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
        $(function () {
            $('#tabel-kelola-lomba').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("mahasiswa.informasi-lomba.list-pendaftaran") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_lomba', name: 'nama_lomba' },
                    { data: 'tingkat_lomba', name: 'tingkat_lomba' },
                    { data: 'kategori', name: 'kategori' },
                    { data: 'tipe_lomba', name: 'tipe_lomba' },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'status', name: 'status' },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '80px'
                    }
                ]
            });
        });

        function modalAction(url) {
            $.get(url)
                .done(function (res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function () {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }
    </script>
@endpush
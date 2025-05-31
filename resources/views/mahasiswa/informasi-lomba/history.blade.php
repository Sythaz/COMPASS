@extends('layouts.template')

@section('title', 'Riwayat Lomba | COMPASS')
@section('page-title', 'Riwayat Lomba')
@section('page-description', 'Halaman Riwayat Pengajuan Lomba')

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
                                    <th>Nama</th>
                                    {{-- <th>Kategori</th> --}}
                                    <th>Tingkat</th>
                                    <th>Awal Reg.</th>
                                    <th>Akhir Reg.</th>
                                    <th>Tipe Lomba</th>
                                    <th class="text-center">Status</th>
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
                ajax: '{{ route("mahasiswa.informasi-lomba.list-history") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nama_lomba', name: 'nama_lomba' },
                    // { data: 'kategori', name: 'kategori' },
                    { data: 'tingkat_lomba', name: 'tingkat_lomba' },
                    { data: 'awal_registrasi_lomba', name: 'awal_registrasi_lomba' },
                    { data: 'akhir_registrasi_lomba', name: 'akhir_registrasi_lomba' },
                    { data: 'tipe_lomba', name: 'tipe_lomba', className: 'text-center' },
                    {
                        data: 'status_verifikasi',
                        name: 'status_verifikasi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '150px'
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
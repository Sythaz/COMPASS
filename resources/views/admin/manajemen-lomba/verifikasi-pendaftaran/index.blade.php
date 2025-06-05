@extends('layouts.template')

@section('title', 'Verifikasi Pendaftaran Lomba | COMPASS')
@section('page-title', 'Verifikasi Pendaftaran Lomba')
@section('page-description', 'Halaman Verifikasi Pendaftaran Lomba')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="pendaftaranTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Nama Lomba</th>
                                <th>Tipe Lomba</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan di-load oleh DataTables AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal utama untuk menampilkan detail pendaftaran -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="ajaxModalContent">
                <!-- Konten modal akan di-load dari AJAX -->
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
        $(document).ready(function () {
            $('#pendaftaranTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("verifikasi-pendaftaran.list") }}',
                    type: 'GET',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_mahasiswa', name: 'mahasiswa.nama_mahasiswa' },
                    { data: 'nama_lomba', name: 'lomba.nama_lomba' },
                    { data: 'tipe_lomba', name: 'lomba.tipe_lomba' },
                    { data: 'tanggal_daftar', name: 'created_at' },
                    { data: 'status_verifikasi', name: 'status_verifikasi' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ],
                order: [[4, 'desc']],
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
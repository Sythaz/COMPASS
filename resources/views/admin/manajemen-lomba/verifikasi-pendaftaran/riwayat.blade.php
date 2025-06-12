@extends('layouts.template')

@section('title', 'Riwayat Pendaftaran Lomba | COMPASS')
@section('page-title', 'Riwayat Pendaftaran Lomba')
@section('page-description', 'Halaman Riwayat Pendaftaran Lomba')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                        </div>
                        {{-- Menu Export Excel/PDF --}}
                        <div class="col-6 text-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-file-export"></i>
                                    <strong>Menu Ekspor</strong>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.export_excel') }}">Ekspor Data ke
                                        XLSX</a>
                                    <a class="dropdown-item" href="{{ route('admin.export_pdf') }}">Ekspor Data ke PDF</a>
                                </div>
                                {{-- Kembali ke index --}}
                                <a href="{{ route('verifikasi-pendaftaran.index') }}"
                                    class="ml-2 btn btn-primary text-white">
                                    <i class="fa-solid fa-circle-arrow-left"></i>
                                    <strong> Kembali</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered" id="pendaftaranTable" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Nama Lomba</th>
                                <th>Tipe Lomba</th>
                                <th class="text-center">Tanggal Daftar</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
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
        $(document).ready(function() {
            $('#pendaftaranTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('riwayat-pendaftaran.list') }}',
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'mahasiswa.nama_mahasiswa'
                    },
                    {
                        data: 'nama_lomba',
                        name: 'lomba.nama_lomba'
                    },
                    {
                        data: 'tipe_lomba',
                        name: 'lomba.tipe_lomba',
                    },
                    {
                        data: 'tanggal_daftar',
                        name: 'created_at',
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
                        className: 'text-center'
                    }
                ],
                order: [
                    [4, 'desc']
                ],
            });
        });

        function modalAction(url) {
            $.get(url)
                .done(function(res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function() {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endpush

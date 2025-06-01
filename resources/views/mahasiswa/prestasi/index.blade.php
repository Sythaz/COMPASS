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

                    {{-- (Tabel atau konten lainnya bisa ditambahkan di sini) --}}

                    {{-- Modal untuk menampilkan form --}}
                    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
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
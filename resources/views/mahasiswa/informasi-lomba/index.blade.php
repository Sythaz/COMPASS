@extends('layouts.template')

@section('title', 'Informasi Lomba | COMPASS')
@section('page-title', 'Informasi Lomba')
@section('page-description', 'Halaman Informasi Lomba yang Tersedia')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-2">
                        <a onclick="modalAction('{{ route('create-lomba') }}')" class="btn btn-primary text-white">
                            <i class="fa-solid fa-plus"></i>
                            <strong> Ajukan Lomba</strong>
                        </a>
                    </div>

                    <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Responsive Images</h4>
                                        <p class="card-title-desc">Images in Bootstrap are made responsive
                                            with <code class="highlighter-rouge">.img-fluid</code>. <code
                                                    class="highlighter-rouge">max-width: 100%;</code> and <code
                                                    class="highlighter-rouge">height: auto;</code> are applied to
                                            the image so that it scales with the parent element.</p>
                                    </div><!-- end card header -->
                                    
                                    <div class="card-body">
                                        <div>
                                            <img src="assets/images/small/img-2.jpg" class="img-fluid" alt="Responsive image">
                                        </div>
                                    </div><!-- end card-body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

    @foreach ($lomba as $l)
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">{{ $l->nama_lomba }}</h4>
            <p class="card-title-desc">{{ $l->deskripsi_lomba }}</p>
            <img src="{{ asset('storage/' . $l->img_lomba) }}" 
                 class="img-fluid rounded-circle profile-img" alt="Gambar Lomba">
        </div><!-- end card-header -->

        <div class="card-body">
            <ul class="list-unstyled">
                <li><strong>Awal Registrasi:</strong> {{ $l->awal_registrasi_lomba}}
                </li>
                <li><strong>Link Pendaftaran:</strong> 
                    <a href="{{ $l->link_pendaftaran_lomba }}" target="_blank">
                        {{ $l->link_pendaftaran_lomba }}
                    </a>
                </li>
            </ul>
        </div><!-- end card-body -->
    </div><!-- end card -->
@endforeach



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
                                    <th class="text-center">Status Lomba</th>
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
                ajax: '{{ route("mahasiswa.informasi-lomba.list") }}',
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
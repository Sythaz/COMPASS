<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kelola Mahasiswa | COMPASS')</title>

    @section('page-title', 'Kelola Mahasiswa')
    @section('page-description', 'Halaman Kelola Mahasiswa')

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/compass-ungu.svg') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('theme/css/style.css') }}" rel="stylesheet">

    @stack('css')
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>

    <div id="main-wrapper">
        @include('layouts.identity')
        @include('layouts.header')
        @include('layouts.sidebar')

        <div class="content-body">
            @include('layouts.breadcrumb')

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <button id="btnTambahMahasiswa" class="btn btn-primary mb-3">Tambah Mahasiswa</button>

                        <!-- Modal -->
                        <div class="modal fade" id="ajaxModal" tabindex="-1" aria-labelledby="ajaxModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id="ajaxModalContent">
                                    <!-- Isi AJAX -->
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Mahasiswa -->
                        <table id="tabel-mahasiswa" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Prodi</th>
                                    <th>Periode</th>
                                    <th>Minat Bakat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('theme/plugins/common/common.min.js') }}"></script>
        <script src="{{ asset('theme/js/custom.min.js') }}"></script>
        <script src="{{ asset('theme/js/settings.js') }}"></script>
        <script src="{{ asset('theme/js/gleek.js') }}"></script>
        <script src="{{ asset('theme/js/styleSwitcher.js') }}"></script>

        <!-- jQuery & Validate -->
        <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('theme/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Bootstrap 5 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Inisialisasi DataTables -->
        <script>
            $(function () {
                var table = $('#tabel-mahasiswa').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('admin/kelola-pengguna/mahasiswa/list') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nim_mahasiswa', name: 'nim_mahasiswa' },
                        { data: 'nama_mahasiswa', name: 'nama_mahasiswa' },
                        { data: 'username', name: 'users.username' },
                        { data: 'prodi', name: 'prodi.prodi_nama' },
                        { data: 'periode', name: 'periode.periode_nama' },
                        { data: 'level_minat_bakat', name: 'level_minat_bakat.level_minbak_nama' },
                        { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' },
                    ]
                });

                $('#btnTambahMahasiswa').on('click', function () {
                    $.get("{{ url('admin/kelola-pengguna/mahasiswa/create') }}")
                        .done(function (data) {
                            $('#ajaxModalContent').html(data);
                            var modal = new bootstrap.Modal(document.getElementById('ajaxModal'));
                            modal.show();
                        })
                        .fail(function () {
                            Swal.fire('Gagal', 'Tidak dapat memuat form tambah mahasiswa.', 'error');
                        });
                });
            });

            function modalAction(url) {
                $.get(url)
                    .done(function (res) {
                        $('#ajaxModalContent').html(res);
                        var modal = new bootstrap.Modal(document.getElementById('ajaxModal'));
                        modal.show();
                    })
                    .fail(function () {
                        Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                    });
            }
        </script>
        @stack('js')
</body>

</html>
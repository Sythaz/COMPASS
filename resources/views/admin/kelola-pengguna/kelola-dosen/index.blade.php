<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kelola Dosen | COMPASS')</title>

    @section('page-title', 'Kelola Dosen')
    @section('page-description', 'Halaman Kelola Dosen')

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/compass-ungu.svg') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
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

    <!-- Main wrapper -->
    <div id="main-wrapper">
        @include('layouts.identity')
        @include('layouts.header')
        @include('layouts.sidebar')

        <!-- Content body -->
        <div class="content-body">
            @include('layouts.breadcrumb')

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <button id="btnTambahDosen" class="btn btn-primary mb-3">Tambah Dosen</button>

                        <!-- Modal untuk AJAX -->
                        <div class="modal fade" id="ajaxModal" tabindex="-1" aria-labelledby="ajaxModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id="ajaxModalContent">
                                    <!-- Konten akan dimuat via AJAX -->
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Dosen -->
                        <table id="tabel-dosen" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th class="text-center">Role</th>
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

        <!-- jQuery & jQuery Validate -->
        <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('theme/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Modal Action & DataTables Init -->
        <script>
            $(function () {
                var table = $('#tabel-dosen').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('admin/kelola-pengguna/dosen/list') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nip_dosen', name: 'nip_dosen' },
                        { data: 'nama_dosen', name: 'nama_dosen' },
                        { data: 'username', name: 'users.username' },
                        { data: 'role', name: 'users.role', className: 'text-center' },
                        { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' },
                    ]
                });

                $('#btnTambahDosen').on('click', function () {
                    $.get("{{ url('admin/kelola-pengguna/dosen/create') }}")
                        .done(function (data) {
                            $('#ajaxModalContent').html(data);
                            var modal = new bootstrap.Modal(document.getElementById('ajaxModal'));
                            modal.show();
                        })
                        .fail(function () {
                            Swal.fire('Gagal', 'Tidak dapat memuat form tambah dosen.', 'error');
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
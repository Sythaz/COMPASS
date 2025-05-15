<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kelola Admin | COMPASS')</title>

    @section('page-title', 'Kelola Admin')
    @section('page-description', 'Halaman Kelola Admin')

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
                {{-- @yield('content') bisa tetap digunakan untuk konten tambahan --}}
                <!-- Tombol Tambah Admin -->
                <div class="card">
                    <div class="card-body">
                        <button id="btnTambahAdmin" class="btn btn-primary mb-3">Tambah Admin</button>
                        <!-- Modal Bootstrap untuk AJAX (Hanya 1 modal) -->
                        <div class="modal fade" id="ajaxModal" tabindex="-1" aria-labelledby="ajaxModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" id="ajaxModalContent">
                                    <!-- Konten modal akan dimuat via AJAX -->
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Admin -->
                        <table id="tabel-admin" class="">
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

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Modal Action & DataTables Init -->
        <script>
            $(function () {
                // Inisialisasi DataTables
                var table = $('#tabel-admin').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('admin/kelola-pengguna/admin/list') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nip_admin', name: 'nip_admin' },
                        { data: 'nama_admin', name: 'nama_admin' },
                        { data: 'username', name: 'users.username' },
                        { data: 'role', name: 'users.role', className: 'text-center' },
                        { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' },
                    ]
                });

                // Tombol Tambah Admin
                $('#btnTambahAdmin').on('click', function () {
                    $.get("{{ url('admin/kelola-pengguna/admin/create') }}")
                        .done(function (data) {
                            $('#ajaxModalContent').html(data);
                            var modal = new bootstrap.Modal(document.getElementById('ajaxModal'));
                            modal.show();
                        })
                        .fail(function () {
                            Swal.fire('Gagal', 'Tidak dapat memuat form tambah.', 'error');
                        });
                });
            });

            // Fungsi untuk membuka modal dan load konten dari URL AJAX
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
        <!-- Tambahkan ini setelah semua script lain, sebelum </body> -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
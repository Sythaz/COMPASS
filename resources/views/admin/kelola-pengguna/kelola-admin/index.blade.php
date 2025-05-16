@extends('layouts.template')

@section('title', 'Kelola Admin | COMPASS')
@section('page-title', 'Kelola Admin')
@section('page-description', 'Halaman Kelola Admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <button id="btnTambahAdmin" class="btn btn-primary mb-3">
                <i class="fa-solid fa-plus"></i> Tambah Admin
            </button>

            <!-- Modal Bootstrap untuk AJAX -->
            <div class="modal fade" id="ajaxModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="ajaxModalContent">
                        {{-- Konten modal akan dimuat via AJAX --}}
                    </div>
                </div>
            </div>

            <!-- Tabel Admin -->
            <div class="table-responsive">
                <table id="tabel-admin" class="table table-striped table-bordered w-100">
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
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link href="{{ asset('theme/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    {{-- Custom Pagination DataTables CSS --}}
    <link href="{{ asset('css-custom/pagination-datatables.css') }}" rel="stylesheet">
@endpush

@push('js')
    <!-- Common Scripts -->
    <script src="{{ asset('theme/plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('theme/js/custom.min.js') }}"></script>
    <script src="{{ asset('theme/js/settings.js') }}"></script>
    <script src="{{ asset('theme/js/gleek.js') }}"></script>
    <script src="{{ asset('theme/js/styleSwitcher.js') }}"></script>

    <!-- jQuery & jQuery Validate -->
    <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <!-- DataTables JS -->
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable-init/datatable-basic.min.js') }}"></script>
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
@endpush
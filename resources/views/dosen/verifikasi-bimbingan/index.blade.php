@extends('layouts.template')

@section('title', 'Verifikasi Mahasiswa Bimbingan | COMPASS')
@section('page-title', 'Verifikasi Mahasiswa Bimbingan')
@section('page-description', 'Halaman Verifikasi Mahasiswa Bimbingan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Tambahkan Tabel DataTables di sini --}}
                    <table class="table table-bordered" id="prestasiTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama Lomba</th>
                                <th>Juara</th>
                                <th>Dosen Pembimbing</th>
                                <th>Tanggal Prestasi</th>
                                <th>Status Verifikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>

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
            console.log('modalAction dipanggil dengan url:', url);
            $('#ajaxModalContent').html('Loading...'); // beri feedback loading

            $.get(url)
                .done(function (res) {
                    console.log('ajax berhasil, isi modal diisi');
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                    console.log('modal di-show');
                })
                .fail(function () {
                    console.log('ajax gagal');
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }

        $('#myModal').on('hidden.bs.modal', function () {
            console.log('modal ditutup, konten dibersihkan');
            $('#ajaxModalContent').html('');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Inisialisasi DataTables
        $(function () {
            $('#prestasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("dosen.verifikasi-bimbingan.list") }}',
                columns: [
                    { data: 'nama_lomba', name: 'lomba_id' },
                    { data: 'juara_prestasi', name: 'juara_prestasi' },
                    { data: 'dosen_pembimbing', name: 'dosen_id' },
                    { data: 'tanggal_prestasi', name: 'tanggal_prestasi' },
                    { data: 'status_verifikasi', name: 'status_verifikasi' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                ],
            });
        });

        function modalDetailPrestasi(url) {
            $('#ajaxModalContent').html('Loading...');

            $.get(url)
                .done(function (res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function () {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }
        $(document).on('click', '.btn-detail-prestasi', function () {
            var url = $(this).data('url');
            modalDetailPrestasi(url);
        });

    </script>
@endpush
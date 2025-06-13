@extends('layouts.template')

@section('title', 'Kelola Prestasi | COMPASS')

@section('page-title', 'Kelola Prestasi')

@section('page-description', 'Halaman untuk mengelola prestasi!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <a onclick="modalAction('{{ route('kelola-prestasi.create') }}')"
                                class="btn btn-primary text-white">
                                <i class="fa-solid fa-plus"></i>
                                <strong>Tambah Data</strong>
                            </a>
                        </div>

                        <div class="col-6 text-right">
                            {{-- Menu Export Excel/PDF --}}
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-file-export"></i>
                                    <strong>Menu Ekspor</strong>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('prestasi.export-excel') }}">Ekspor Data ke
                                        XLSX</a>
                                    <a class="dropdown-item" href="{{ route('admin.export_pdf') }}">Ekspor Data ke
                                        PDF</a>
                                </div>

                                {{-- <a href="{{ route('riwayat-pendaftaran.index') }}" class="ml-2 btn btn-primary text-white">
                                    <i class="fa fa-clock-rotate-left"></i>
                                    <strong> Riwayat</strong>
                                </a> --}}
                            </div>
                        </div>
                    </div>

                    {{-- Tambahkan Tabel DataTables di sini --}}
                    <table class="w-100 table table-striped table-bordered custom-datatable" id="prestasiTable"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 1px; white-space: nowrap;">No</th>
                                <th>Mahasiswa</th>
                                <th>Nama Lomba</th>
                                <th>Jenis</th>
                                <th>Juara</th>
                                <th>Dosen Pembimbing</th>
                                <th>Tanggal Prestasi</th>
                                <th>Status Verifikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>

                    {{-- Modal untuk menampilkan form --}}
                    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel"
                        aria-hidden="true">
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
                .done(function(res) {
                    console.log('ajax berhasil, isi modal diisi');
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                    console.log('modal di-show');
                })
                .fail(function() {
                    console.log('ajax gagal');
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }

        $('#myModal').on('hidden.bs.modal', function() {
            console.log('modal ditutup, konten dibersihkan');
            $('#ajaxModalContent').html('');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Inisialisasi DataTables
        $(function() {
            $('#prestasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('kelola-prestasi.list') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Kirim token CSRF
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'ketua_mahasiswa',
                        name: 'ketua_mahasiswa'
                    },
                    {
                        data: 'nama_lomba',
                        name: 'lomba_id'
                    },
                    {
                        data: 'jenis_prestasi',
                        name: 'jenis_prestasi',
                        className: 'text-center'
                    },
                    {
                        data: 'juara_prestasi',
                        name: 'juara_prestasi',
                        className: 'text-center'
                    },
                    {
                        data: 'dosen_pembimbing',
                        name: 'dosen_id'
                    },
                    {
                        data: 'tanggal_prestasi',
                        name: 'tanggal_prestasi',
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
                    },
                ],
            });
        });

        function modalDetailPrestasi(url) {
            $('#ajaxModalContent').html('Loading...');

            $.get(url)
                .done(function(res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function() {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }
        $(document).on('click', '.btn-detail-prestasi', function() {
            var url = $(this).data('url');
            modalDetailPrestasi(url);
        });
    </script>
@endpush

@extends('layouts.template')

@section('title', 'Kelola Prestasi | COMPASS')

@section('page-title', 'Kelola Prestasi')

@section('page-description', 'Halaman untuk mengelola prestasi!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Tombol Tambah dan Ekspor --}}
                    <div class="row mb-3">
                        <div class="col-6">
                            <a onclick="modalAction('{{ route('kelola-prestasi.create') }}')"
                                class="btn btn-primary text-white">
                                <i class="fa-solid fa-plus"></i>
                                <strong>Tambah Data</strong>
                            </a>
                        </div>
                        <div class="col-6 text-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-file-export"></i>
                                    <strong>Menu Ekspor</strong>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" id="export-excel">Ekspor ke XLSX</a>
                                    <a class="dropdown-item" href="#" id="export-pdf">Ekspor ke PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select id="filter-status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="valid">Valid</option>
                                <option value="terverifikasi">Terverifikasi</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-periode" class="form-control">
                                <option value="">Semua Periode</option>
                                @foreach (\App\Models\PeriodeModel::all() as $periode)
                                    <option value="{{ $periode->periode_id }}">{{ $periode->semester_periode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="prestasiTable">
                            <thead>
                                <tr>
                                    <th>No</th>
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
                    </div>

                    {{-- Modal --}}
                    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="ajaxModalContent"></div>
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

        $('#myModal').on('hidden.bs.modal', function() {
            $('#ajaxModalContent').html('');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        let table;

        $(function() {
            table = $('#prestasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('kelola-prestasi.list') }}',
                    type: 'POST',
                    data: function(d) {
                        d.status_verifikasi = $('#filter-status').val();
                        d.periode_id = $('#filter-periode').val();
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                ]
            });

            $('#filter-status, #filter-periode').change(function() {
                table.ajax.reload();
            });

            // Ekspor ke Excel
            $('#export-excel').on('click', function(e) {
                e.preventDefault();
                const status = $('#filter-status').val();
                const periode = $('#filter-periode').val();
                let url = "{{ route('prestasi.export-excel') }}";
                const params = [];
                if (status) params.push('status=' + encodeURIComponent(status));
                if (periode) params.push('periode_id=' + encodeURIComponent(periode));
                if (params.length > 0) url += '?' + params.join('&');
                window.location.href = url;
            });

            // Ekspor ke PDF
            $('#export-pdf').on('click', function(e) {
                e.preventDefault();
                const status = $('#filter-status').val();
                const periode = $('#filter-periode').val();
                let url = "{{ route('prestasi.export-pdf') }}";
                const params = [];
                if (status) params.push('status=' + encodeURIComponent(status));
                if (periode) params.push('periode_id=' + encodeURIComponent(periode));
                if (params.length > 0) url += '?' + params.join('&');
                window.location.href = url;
            });
        });
    </script>
@endpush

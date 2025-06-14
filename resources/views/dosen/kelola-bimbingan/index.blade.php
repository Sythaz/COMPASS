@extends('layouts.template')

@section('title', 'Kelola Bimbingan | COMPASS')
@section('page-title', 'Kelola Bimbingan')
@section('page-description', 'Halaman Kelola Mahasiswa Bimbingan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="d-flex justify-content-start align-items-center">
                                <!-- Dropdown Filter dan Tombol Reset -->
                                <div class="d-flex gap-2">
                                    <select id="filter-kategori" class="form-control mr-2"
                                        style="border-radius: 8px; height: 40px;" name="kategori">
                                        <option value="">Filter Kategori</option>
                                        @foreach ($daftarKategori as $kategori)
                                            <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select id="filter-tahun" class="form-control mr-2"
                                        style="border-radius: 8px; height: 40px;" name="tahun">
                                        <option value="">Filter Tahun</option>
                                        @foreach ($daftarTahun as $tahun)
                                            <option value="{{ $tahun }}">
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-secondary text-white" onclick="resetFilter()">
                                        Reset Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tambahkan Tabel DataTables di sini --}}
                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable" id="bimbinganTable"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>Nama Lomba</th>
                                    <th>Juara</th>
                                    <th>Nama Peserta</th>
                                    <th>Tanggal Prestasi</th>
                                    <th>Status Verifikasi</th>
                                    <th class="text-center" style="width: 1px; white-space: nowrap;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    {{-- Custom Pagination DataTables --}}
                    <div class="bootstrap-pagination"></div>

                    <!-- Modal Bootstrap untuk AJAX (Hanya 1 modal) -->
                    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="ajaxModalContent">
                                <!-- Konten modal akan dimuat via AJAX -->
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
        var idDataTables = '#bimbinganTable';
        var table;

        $(document).ready(function() {
            // Dropdown tidak bisa di buka langsung sehingga perlu dipanggil
            $('.dropdown-toggle').dropdown();

            // DataTables
            table = $(idDataTables).DataTable({
                // Styling untuk pagination (Jangan diubah)
                pagingType: "simple_numbers",
                language: {
                    lengthMenu: "Tampilkan _MENU_ entri",
                    paginate: {
                        first: "Pertama",
                        previous: "Sebelum",
                        next: "Lanjut",
                        last: "Terakhir",
                    },
                    search: "Cari:",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                },

                // Bisa di Kustomisasi dengan data
                serverSide: true,
                autoWidth: true,
                order: [
                    [4, "desc"]
                ],
                ajax: {
                    url: "{{ route('dosen.kelola-bimbingan.list') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.kategori = $('#filter-kategori').val();
                        d.tahun = $('#filter-tahun').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'nama_lomba',
                        name: 'lomba_id',
                    },
                    {
                        data: 'juara_prestasi',
                        name: 'juara_prestasi',
                    },
                    {
                        data: 'nama_peserta',
                        name: 'nama_peserta',
                        orderable: false,
                    },
                    {
                        data: 'tanggal_prestasi',
                        name: 'tanggal_prestasi',
                    },
                    {
                        data: 'status_verifikasi',
                        name: 'status_verifikasi',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                    }
                ],
                scrollX: true,
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: null,
                    bottomEnd: null
                },

                // Styling untuk pagination (Jangan diubah)
                drawCallback: function() {
                    // Styling untuk pagination
                    $(".dataTables_wrapper").css({
                        margin: "0",
                        padding: "0",
                    });
                    $(".dataTables_paginate .pagination").addClass("justify-content-end");
                    $(".dataTables_paginate .paginate_button")
                        .removeClass("paginate_button")
                        .addClass("page-item");
                    $(".dataTables_paginate .paginate_button a")
                        .addClass("page-link")
                        .css("border-radius", "5px");
                    $(".dataTables_paginate .paginate_button.previous a").text("Sebelum");
                    $(".dataTables_paginate .paginate_button.next a").text("Lanjut");
                    $(".dataTables_paginate .paginate_button.first a").text("Pertama");
                    $(".dataTables_paginate .paginate_button.last a").text("Terakhir");

                    // Styling untuk input dan dropdown
                    $(idDataTables + ' select').css({
                        width: "auto",
                        height: "auto",
                        "border-radius": "5px",
                        border: "1px solid #ced4da",
                    });
                    $(idDataTables + '_filter input').css({
                        height: "auto",
                        "border-radius": "5px",
                        border: "1px solid #ced4da",
                    });
                    $(idDataTables + '_wrapper .table-bordered').css({
                        "border-top-left-radius": "5px",
                        "border-top-right-radius": "5px",
                    });
                    $(idDataTables + '_wrapper .dataTables_scrollBody table').css({
                        "border-top-left-radius": "0px",
                        "border-top-right-radius": "0px",
                        "border-bottom-left-radius": "5px",
                        "border-bottom-right-radius": "5px",
                    });
                }
            });
            // Reload saat filter berubah
            $('#filter-kategori, #filter-tahun').on('change', function() {
                table.ajax.reload();
            });
        });

        function resetFilter() {
            $('#filter-kategori').val('').trigger('change');
            $('#filter-tahun').val('').trigger('change');
            table.ajax.reload();
        }

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
    </script>
@endpush

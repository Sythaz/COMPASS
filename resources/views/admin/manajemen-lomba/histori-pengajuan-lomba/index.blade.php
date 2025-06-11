@extends('layouts.template')

@section('title', 'Histori Pengajuan Lomba | COMPASS')

@section('page-title', 'Histori Pengajuan Lomba')

@section('page-description', 'Halaman untuk melihat histori pengajuan lomba!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="d-flex">
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

                                    <select id="filter-tingkat" class="form-control mr-2"
                                        style="border-radius: 8px; height: 40px;" name="tingkat">
                                        <option value="">Filter Tingkat</option>
                                        @foreach ($daftarTingkatLomba as $tingkatLomba)
                                            <option value="{{ $tingkatLomba->tingkat_lomba_id }}">
                                                {{ $tingkatLomba->nama_tingkat }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select id="filter-status" class="form-control mr-2"
                                        style="border-radius: 8px; height: 40px;" name="status">
                                        <option value="">Filter Status</option>
                                        <option value="Terverifikasi">Terverifikasi</option>
                                        <option value="Menunggu">Menunggu</option>
                                        <option value="Ditolak">Ditolak</option>
                                    </select>

                                    <button class="btn btn-secondary" onclick="resetFilter()">Reset Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="w-100 table table-striped table-bordered custom-datatable"
                            id="tabel-histori-pengajuan-lomba">
                            <thead>
                                <tr>
                                    <th style="width: 1px; white-space: nowrap;">No</th>
                                    <th>Nama Lomba</th>
                                    <th>Kategori</th>
                                    <th>Tingkat</th>
                                    <th>Awal Reg.</th>
                                    <th>Akhir Reg.</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 1px; white-space: nowrap;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        {{-- Custom Pagination DataTables --}}
                        <div class="bootstrap-pagination"></div>
                    </div>
                </div>
                <!-- Modal Bootstrap untuk AJAX (Hanya 1 modal) -->
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="ajaxModalContent">
                            <!-- Konten modal akan dimuat via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link href="{{ asset('theme/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    {{-- Custom Pagination DataTables CSS --}}
    <link href="{{ asset('css-custom/pagination-datatables.css') }}" rel="stylesheet">

    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css"
        rel="stylesheet" />
@endpush

@push('js')
    <!--  Script DataTables -->
    <script src="{{ asset('theme/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var idDataTables = '#tabel-histori-pengajuan-lomba';
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
                ajax: {
                    url: "{{ route('histori-pengajuan-lomba.list') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // Untuk filter kategori, tingkat lomba dan status verifikasi
                    data: function(data) {
                        data.kategori = $('#filter-kategori').val();
                        data.tingkat = $('#filter-tingkat').val();
                        data.status_verifikasi = $('#filter-status').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'nama_lomba',
                        name: 'nama_lomba',
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                    },
                    {
                        data: 'tingkat_lomba.nama_tingkat',
                        name: 'tingkat_lomba.nama_tingkat',
                    },
                    {
                        data: 'awal_registrasi_lomba',
                        name: 'awal_registrasi_lomba',
                    },
                    {
                        data: 'akhir_registrasi_lomba',
                        name: 'akhir_registrasi_lomba',
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
            $('#filter-kategori, #filter-tingkat, #filter-status').on('change', function() {
                table.ajax.reload();
            });
        });

        // Fungsi untuk menampilkan modal
        function modalAction(url) {
            $.get(url)
                .done(function(res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function() {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }

        function resetFilter() {
            $('#filter-kategori').val('').trigger('change');
            $('#filter-tingkat').val('').trigger('change');
            $('#filter-status').val('').trigger('change');
            table.ajax.reload();
        }

        // Fungsi untuk reload dataTables
        $(idDataTables).on('change', function() {
            dataTables.ajax.reload();
        })
    </script>
@endpush

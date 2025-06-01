@extends('layouts.template')

@section('title', 'Input Prestasi | COMPASS')
@section('page-title', 'Input Prestasi')
@section('page-description', 'Halaman Input dan Kelola Prestasi (Mahasiswa)')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <div class="row mb-2">
          <div class="col-6">
            <a onclick="modalAction('{{ route('mahasiswa.prestasi.create') }}')"
               class="btn btn-primary text-white">
              <i class="fa-solid fa-plus"></i>
              <strong>Tambah Prestasi</strong>
            </a>
          </div>
        </div>

        <div class="table-responsive">
          <table id="tabel-prestasi"
                 class="w-100 table table-striped table-bordered custom-datatable">
            <thead>
              <tr>
                <th style="width:1px; white-space:nowrap;">No</th>
                <th>Mahasiswa</th>
                <th>Lomba</th>
                <th>Dosen</th>
                <th>Kategori</th>
                <th>Periode</th>
                <th>Tanggal</th>
                <th>Juara</th>
                <th>Jenis</th>
                <th>Status</th>
                <th class="text-center" style="width:1px; white-space:nowrap;">Aksi</th>
              </tr>
            </thead>
          </table>
          <div class="bootstrap-pagination"></div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal Bootstrap untuk AJAX -->
<div class="modal fade" id="myModal" tabindex="-1"
     aria-labelledby="ajaxModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="ajaxModalContent">
      {{-- Konten modal akan dimuat via AJAX --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const idDataTables = '#tabel-prestasi';

  $(document).ready(function () {
    $('.dropdown-toggle').dropdown();

    $(idDataTables).DataTable({
      pagingType: "simple_numbers",
      serverSide: true,
      processing: true,
      autoWidth: true,

      ajax: {
        url: "{{ route('mahasiswa.prestasi.list') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}" }
      },

      columns: [
        { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
        { data: 'nama_mahasiswa', name: 'mahasiswa.nama' },
        { data: 'nama_lomba',     name: 'lomba.nama' },
        { data: 'nama_dosen',     name: 'dosen.nama' },
        { data: 'kategori',       name: 'kategori.nama' },
        { data: 'periode',        name: 'periode.nama' },
        { data: 'tanggal_prestasi', name: 'tanggal_prestasi' },
        { data: 'juara_prestasi', name: 'juara_prestasi' },
        { data: 'jenis_prestasi', name: 'jenis_prestasi' },
        { data: 'status_prestasi', name: 'status_prestasi' },
        {
          data: 'aksi',
          name: 'aksi',
          orderable: false,
          searchable: false,
          className: 'text-center',
          width: '110px'
        },
      ],

      language: {
        lengthMenu: "Tampilkan _MENU_ entri",
        paginate: { first: "Pertama", previous: "Sebelum", next: "Lanjut", last: "Terakhir" },
        search: "Cari:",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri"
      },

      drawCallback: function () {
        $(".dataTables_paginate .pagination").addClass("justify-content-end");
        $(".dataTables_paginate .paginate_button a").addClass("page-link");
      }
    });

    // reload saat filter berubah
    $(idDataTables).on('change', function () {
      $(idDataTables).DataTable().ajax.reload();
    });
  });

  function modalAction(url) {
    $.get(url)
      .done(res => {
        $('#ajaxModalContent').html(res);
        bootstrap.Modal.getOrCreateInstance($('#myModal')).show();
      })
      .fail(() => {
        Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error');
      });
  }
</script>
@endpush

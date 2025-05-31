@extends('layouts.template')

@section('title', 'Prestasi Mahasiswa | COMPASS')
@section('page-title', 'Prestasi Mahasiswa')
@section('page-description', 'Halaman Informasi Prestasi Mahasiswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">



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

    </script>
@endpush
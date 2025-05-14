@extends('layouts.template')

@section('title', 'Kelola Admin | SIPRESMA')

@section('page-title', 'Kelola Admin')

@section('page-description', 'ini Halaman Kelola Admin ya bub!')

@section('content')
    {{-- Konten --}}
@endsection

@push('css')
@endpush

@push('js')
    <!--  flot-chart js -->
    <script src="{{ asset('theme/plugins/flot/js/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/flot/js/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('theme/plugins/flot/js/jquery.flot.init.js') }}"></script>

    <!-- Memanggil Chart Script -->
    <script src="{{ asset('js-custom/charts/admin/pie-chart.js') }}"></script>
    <script src="{{ asset('js-custom/charts/admin/line-chart.js') }}"></script>

    <!-- Menginisialisasi Chart yang dipanggil dari script diatas -->
    <script>
        //   untuk js
    </script>
@endpush
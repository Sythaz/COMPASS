<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'COMPASS')</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/compass-ungu.svg') }}">

    <!-- Font Awesome CDN (Icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Stylesheet -->
    <link href="{{ asset('theme/css/style.css') }}" rel="stylesheet">

    <!-- SweetAlert2 CSS dan JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('css')
</head>

<body>
    <!-- Preloader start -->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                    stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!-- Preloader end -->

    <!-- Main wrapper start -->
    <div id="main-wrapper">
        <!-- Nav header start -->
        @include('layouts.identity')
        <!-- Nav header end -->

        <!-- Header start -->
        @include('layouts.header')
        <!-- Header end -->

        <!-- Sidebar start -->
        @include('layouts.sidebar')
        <!-- Sidebar end -->

        <!-- Content body start -->
        <div class="content-body">

            <!-- Breadcrumb start -->
            @include('layouts.breadcrumb')
            <!-- Breadcrumb end -->

            <div class="container-fluid">
                <!-- Content start -->

                {{-- <div class="row"> --}}
                @yield('content')
                {{-- </div> --}}

                <!-- Content end -->
            </div>
        </div>
        <!-- Content body end -->
    </div>
    <!-- Main wrapper end -->

    <!-- Scripts -->
    <script src="{{ asset('theme/plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('theme/js/custom.min.js') }}"></script>
    <script src="{{ asset('theme/js/settings.js') }}"></script>
    <script src="{{ asset('theme/js/gleek.js') }}"></script>
    <script src="{{ asset('theme/js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('theme/js/dashboard/dashboard-1.js') }}"></script>

    <!-- jQuery -->
    <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
    
    <!-- jquery-validation -->
    <script src="{{ asset('theme/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS Bundle (modal show hide butuh ini)-->
    <script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    @stack('js')
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Home Page | COMPASS</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/compass-ungu.svg') }}">

    <!-- Font Awesome CDN (Icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link href="{{ asset('theme/css/style.css') }}" rel="stylesheet">

    <!-- Custom Stylesheet Pribadi-->
    <link href="{{ asset('css-custom/home-custom.css') }}" rel="stylesheet">

    <!-- GSAP Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://assets.codepen.io/16327/SplitText3.min.js"></script>

</head>

<body>
    <!-- Navbar Start -->
    @include('layouts.header')
    <!-- Navbar End -->

    <!-- Main wrapper start -->
    <div id="main-wrapper" class="show">
        <!-- Hero Section Start -->
        <section class="hero-section" id="home">
            <div class="hero-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
            <div class="container">
                <div class="row align-items-center p-5">
                    <div class="col-lg-7 col-md-7">
                        <div class="hero-content">
                            <span class="badge badge-compass">Sistem Informasi Prestasi Mahasiswa</span>
                            <h1 class="hero-title text-white font-weight-bold">COMPASS</h1>
                            <p class="hero-subtitle">Catat, Kelola dan Rekomendasi Lomba Menjadi Lebih Mudah</p>
                            <div class="hero-buttons">
                                @if (Auth::check())
                                    @if (Auth::user()->hasRole('Admin'))
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="btn btn-primary-custom hero-btn mr-3">Explore Prestasi</a>
                                    @elseif (Auth::user()->hasRole('Mahasiswa'))
                                        <a href="{{ route('mahasiswa.dashboard') }}"
                                            class="btn btn-primary-custom hero-btn mr-3">Explore Prestasi</a>
                                    @elseif (Auth::user()->hasRole('Dosen'))
                                        <a href="{{ route('dosen.dashboard') }}"
                                            class="btn btn-primary-custom hero-btn mr-3">Explore Prestasi</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary-custom hero-btn mr-3">Eksplor
                                        Prestasi</a>
                                @endif
                                <a href="#about-section" class="btn btn-pelajari hero-btn">Pelajari Lebih Lanjut</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 d-none d-md-block">
                        <div class="hero-image">
                            <img src="{{ asset('assets/images/logo/compass-putih.svg') }}" alt="COMPASS Dashboard"
                                class="img-fluid floating" style="height: 400px; float: right;">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Hero Section End -->

        <!-- About Section Start -->
        <section class="about-section" id="about-section">
            <div class="d-flex flex-column h-100">
                <!-- Baris Pertama (Atas) -->
                <div class="half-section-top-about d-flex align-items-center">
                    <div class="container container-about-stats position-relative">
                        <div>
                            <img src="{{ asset('assets/images/bintang.png') }}"
                                style="position: absolute; top: 26%; left: 21%; width: 8%; rotate: 12deg;">
                            <img src="{{ asset('assets/images/bintang.png') }}"
                                style="position: absolute; top: 52%; right: 20%; width: 8%; rotate: -7deg;">
                            <img src="{{ asset('assets/images/bintang.png') }}"
                                style="position: absolute; top: 85%; left: 50%; width: 5%; rotate: -2deg;">
                        </div>
                        <div class="section-title container-text-split">
                            <h2>Tentang Sistem Kami</h2>
                        </div>
                        <div class="wrap-about">
                            <p class="text-about split-animate">
                                Sistem ini <span style="color: #F7931E">membantu mencatat prestasi mahasiswa</span>
                                secara digital sekaligus merekomendasikan peserta lomba yang sesuai berdasarkan minat,
                                keahlian, dan rekam jejak mereka — menjadikan proses seleksi <span
                                    style="color: #F7931E">lebih cepat, objektif, dan terintegrasi.</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Baris Kedua (Bawah) -->
                <div class="half-section-bottom-about d-flex align-items-end">
                    <div class="stats-section">
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-box">
                                    <div class="stat-number" id="counterInstitusi">0</div>
                                    <div class="stat-title">Institusi Pengguna</div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-box">
                                    <div class="stat-number" id="counterMahasiswa">0</div>
                                    <div class="stat-title">Mahasiswa Terdaftar</div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-box">
                                    <div class="stat-number" id="counterPrestasi">0</div>
                                    <div class="stat-title">Prestasi Tercatat</div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-box">
                                    <div class="stat-number" id="counterLomba">0</div>
                                    <div class="stat-title">Lomba Tercatat</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About Section End -->

        <!-- Time Pengembang Section Start -->
        <section class="tim-pengembang-section" id="tim-pengembang">
            <div class="container">
                <div class="section-title container-text-split container-tim-pengembang">
                    <h2 class="split-text-1">Tim Pengembang</h2>
                    <p class="split-animate text-center section-subtitle">
                        COMPASS dibangun oleh mahasiswa untuk semua. Kami adalah tim mahasiswa dari Politeknik Negeri
                        Malang yang berfokus pada pengembangan solusi teknologi untuk pendidikan.
                    </p>
                </div>

                <div class="row mt-5 justify-content-center">
                    {{-- Card 1 --}}
                    <x-card-home nama="M. Syafiq Aldiansyah" nim="2341720102" role="Frontend"
                        deskripsi="Mahasiswa Politeknik Negeri Malang"
                        quote="Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa tinggi."
                        foto="{{ asset('assets/images/card-img/sythaz-card-img.jpg') }}"
                        link="https://github.com/Sythaz/" />

                    {{-- Card 2 --}}
                    <x-card-home nama="M. Syafiq Aldiansyah" nim="2341720102" role="Frontend"
                        deskripsi="Mahasiswa Politeknik Negeri Malang"
                        quote="Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa tinggi."
                        foto="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                        link="https://github.com/Sythaz/" />

                    {{-- Card 3 --}}
                    <x-card-home nama="M. Syafiq Aldiansyah" nim="2341720102" role="Frontend"
                        deskripsi="Mahasiswa Politeknik Negeri Malang"
                        quote="Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa tinggi."
                        foto="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                        link="https://github.com/Sythaz/" />

                    {{-- Card 4 --}}
                    <x-card-home nama="M. Syafiq Aldiansyah" nim="2341720102" role="Frontend"
                        deskripsi="Mahasiswa Politeknik Negeri Malang"
                        quote="Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa tinggi."
                        foto="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                        link="https://github.com/Sythaz/" />

                    {{-- Card 5 --}}
                    <x-card-home nama="M. Syafiq Aldiansyah" nim="2341720102" role="FrontEnd"
                        deskripsi="Mahasiswa Politeknik Negeri Malang"
                        quote="Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa tinggi."
                        foto="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                        link="https://github.com/Sythaz/" />

                    {{-- Card 1 --}}
                    {{-- <div class="col-12 col-md-6 col-lg-4 mb-4 split-animate">
                        <div class="card-custom">
                            <div class="card-custom-inner">
                                <div class="card-custom-front d-flex flex-column w-100 overflow-hidden relative h-100">
                                    <img src="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                                        alt="Muhammad Syafiq Aldiansyah" class="w-100 h-100"
                                        style="object-fit: cover; border-radius: inherit;">
                                    <div class="overlay"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8)); border-radius: inherit;">
                                    </div>
                                    <div class="position-absolute w-100 bottom-0 p-3" style="bottom: 0;">
                                        <div class="woilah">
                                            <h3 class="text-white mb-0">M. Syafiq Aldiansyah</h2>
                                                <p class="text-muted">Mahasiswa Politeknik Negeri Malang</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-id-badge text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">2341720102</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-laptop-code text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">Frontend</span>
                                            </div>
                                            <div>
                                                <a href="https://github.com/muhammadrafi123"
                                                    class="fw-bold btn btn-success btn-rounded font-weight-bold">Ikuti
                                                    <i class="ml-1 fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-custom-back d-flex flex-column p-3">
                                    <p>"Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa
                                        tinggi."</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    {{-- <div class="col-12 col-md-6 col-lg-4 mb-4 split-animate">
                        <div class="card-custom">
                            <div class="card-custom-inner">
                                <div class="card-custom-front d-flex flex-column w-100 overflow-hidden relative h-100">
                                    <img src="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                                        alt="Muhammad Syafiq Aldiansyah" class="w-100 h-100"
                                        style="object-fit: cover; border-radius: inherit;">
                                    <div class="overlay"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8)); border-radius: inherit;">
                                    </div>
                                    <div class="position-absolute w-100 bottom-0 p-3" style="bottom: 0;">
                                        <div class="woilah">
                                            <h3 class="text-white mb-0">M. Syafiq Aldiansyah</h2>
                                                <p class="text-muted">Mahasiswa Politeknik Negeri Malang</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-id-badge text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">2341720102</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-laptop-code text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">Frontend</span>
                                            </div>
                                            <div>
                                                <a href="https://github.com/muhammadrafi123"
                                                    class="fw-bold btn btn-success btn-rounded font-weight-bold">Ikuti
                                                    <i class="ml-1 fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-custom-back d-flex flex-column p-3">
                                    <p>"Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa
                                        tinggi."</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- Card 2 --}}
                    {{-- <div class="col-12 col-md-6 col-lg-4 mb-4 split-animate">
                        <div class="card-custom">
                            <div class="card-custom-inner">
                                <div class="card-custom-front d-flex flex-column w-100 overflow-hidden relative h-100">
                                    <img src="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                                        alt="Muhammad Syafiq Aldiansyah" class="w-100 h-100"
                                        style="object-fit: cover; border-radius: inherit;">
                                    <div class="overlay"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8)); border-radius: inherit;">
                                    </div>
                                    <div class="position-absolute w-100 bottom-0 p-3" style="bottom: 0;">
                                        <div class="woilah">
                                            <h3 class="text-white mb-0">M. Syafiq Aldiansyah</h2>
                                                <p class="text-muted">Mahasiswa Politeknik Negeri Malang</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-id-badge text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">2341720102</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-laptop-code text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">Frontend</span>
                                            </div>
                                            <div>
                                                <a href=""
                                                    class="fw-bold btn btn-success btn-rounded font-weight-bold">Ikuti
                                                    <i class="ml-1 fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-custom-back d-flex flex-column p-3">
                                    <p>"Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa
                                        tinggi."</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- Card 2 --}}
                    {{-- <div class="col-12 col-md-6 col-lg-4 mb-4 split-animate">
                        <div class="card-custom">
                            <div class="card-custom-inner">
                                <div class="card-custom-front d-flex flex-column w-100 overflow-hidden relative h-100">
                                    <img src="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                                        alt="Muhammad Syafiq Aldiansyah" class="w-100 h-100"
                                        style="object-fit: cover; border-radius: inherit;">
                                    <div class="overlay"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8)); border-radius: inherit;">
                                    </div>
                                    <div class="position-absolute w-100 bottom-0 p-3" style="bottom: 0;">
                                        <div class="woilah">
                                            <h3 class="text-white mb-0">M. Syafiq Aldiansyah</h2>
                                                <p class="text-muted">Mahasiswa Politeknik Negeri Malang</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-id-badge text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">2341720102</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-laptop-code text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">Frontend</span>
                                            </div>
                                            <div>
                                                <a href="https://github.com/muhammadrafi123"
                                                    class="fw-bold btn btn-success btn-rounded font-weight-bold">Ikuti
                                                    <i class="ml-1 fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-custom-back d-flex flex-column p-3">
                                    <p>"Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa
                                        tinggi."</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- Card 2 --}}
                    {{-- <div class="col-12 col-md-6 col-lg-4 mb-4 split-animate">
                        <div class="card-custom">
                            <div class="card-custom-inner">
                                <div class="card-custom-front d-flex flex-column w-100 overflow-hidden relative h-100">
                                    <img src="https://cdn1-production-images-kly.akamaized.net/E2MB-7EF-q6nqQNMNfyMbgxjRJc=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3220607/original/002220400_1598510794-9b3ee95069a9d94d80dfcdba43.jpg"
                                        alt="Muhammad Syafiq Aldiansyah" class="w-100 h-100"
                                        style="object-fit: cover; border-radius: inherit;">
                                    <div class="overlay"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8)); border-radius: inherit;">
                                    </div>
                                    <div class="position-absolute w-100 bottom-0 p-3" style="bottom: 0;">
                                        <div class="woilah">
                                            <h3 class="text-white mb-0">M. Syafiq Aldiansyah</h2>
                                                <p class="text-muted">Mahasiswa Politeknik Negeri Malang</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-id-badge text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">2341720102</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-laptop-code text-success mr-1"></i>
                                                <span class="text-white font-weight-bold">Frontend</span>
                                            </div>
                                            <div>
                                                <a href="https://github.com/muhammadrafi123"
                                                    class="fw-bold btn btn-success btn-rounded font-weight-bold">Ikuti
                                                    <i class="ml-1 fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-custom-back d-flex flex-column p-3">
                                    <p>"Membangun aplikasi berkualitas dengan antarmuka yang elegan dan performa
                                        tinggi."</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
        </section>
        <!-- Time Pengembang Section End -->

        <!-- Scroll To Top Button -->
        <div class="scrollToTop">
            <i class="fas fa-angle-up"></i>
        </div>
    </div>
    <!-- Main wrapper end -->

    <!-- Scripts -->
    <script src="{{ asset('theme/plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('theme/js/custom.min.js') }}"></script>

    <!-- jQuery -->
    <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap JS Bundle (modal show hide butuh ini)-->
    <script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- GSAP Animation Script dan Navbar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).ready(function() {
                $('.navbar-toggler').on('click', function() {
                    $('.navbar-collapse-custom').collapse('toggle');
                });
                $('.navbar-nav a').on('click', function() {
                    $('.navbar-collapse-custom').collapse('hide');
                });

                $('.dropdown-toggle-custom').on('click', function() {
                    $('.dropdown-menu').collapse('toggle');
                });

            });

            // Inisialisasi GSAP
            gsap.registerPlugin(ScrollTrigger, SplitText);

            // GSAP Text Split
            let split, animation;

            function createAnimation() {
                split?.revert();
                animation?.kill();

                split = new SplitText(".split-animate", {
                    type: "lines",
                    linesClass: "line-child"
                });

                animation = gsap.from(split.lines, {
                    rotationX: -100,
                    transformOrigin: "50% 50% -50px",
                    opacity: 0,
                    duration: 1,
                    ease: "power3",
                    stagger: 0.25
                });
            }

            ScrollTrigger.create({
                trigger: ".container-text-split",
                start: "top 80%",
                end: "bottom 20%",
                onEnter: createAnimation,
                onLeaveBack: () => split?.revert()
            });

            // Hero animations
            gsap.to('.badge-compass', {
                opacity: 1,
                duration: 0.8,
                delay: 0.3
            });

            gsap.to('.hero-title', {
                opacity: 1,
                duration: 0.8,
                delay: 0.5
            });

            gsap.to('.hero-subtitle', {
                opacity: 1,
                duration: 0.8,
                delay: 0.7
            });

            gsap.to('.hero-btn', {
                opacity: 1,
                duration: 0.8,
                delay: 0.9,
                stagger: 0.2
            });

            gsap.to('.hero-image', {
                opacity: 1,
                y: 0,
                duration: 1,
                delay: 0.7
            });

            // Stats counter animation
            const counterAnimation = (id, end) => {
                let startTimestamp = null;
                const duration = 1000;
                const element = document.getElementById(id);

                function step(timestamp) {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    element.innerText = Math.floor(progress * end);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                }

                window.requestAnimationFrame(step);
            };

            ScrollTrigger.create({
                trigger: '.stats-section',
                start: 'top 80%',
                onEnter: () => {
                    counterAnimation('counterInstitusi', 1);
                    counterAnimation('counterMahasiswa', {{ $jumlahMahasiswa ?? 0 }});
                    counterAnimation('counterPrestasi', {{ $jumlahPrestasi ?? 0 }});
                    counterAnimation('counterLomba', {{ $jumlahLomba ?? 0 }});

                    gsap.to('.stat-box', {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        stagger: 0.2
                    });
                }
            });

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar-landing');
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }

                // Tombol scroll keatas aktif saat scroll lebih dari 300px
                const scrollToTop = document.querySelector('.scrollToTop');
                if (window.scrollY > 300) {
                    scrollToTop.classList.add('active');
                } else {
                    scrollToTop.classList.remove('active');
                }
            });

            // Tombol scroll keatas
            document.querySelector('.scrollToTop').addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Smooth scroll saat klik link
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        if (targetId === '#') return;
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            window.scrollTo({
                                top: targetElement.offsetTop - 70,
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            });

            // Tambahan
            document.addEventListener('DOMContentLoaded', function() {
                const navbar = document.querySelector('.navbar-landing');

                // Navbar scroll effect
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('navbar-scrolled');
                    } else {
                        navbar.classList.remove('navbar-scrolled');
                    }
                });
            });
        });
    </script>
</body>

</html>

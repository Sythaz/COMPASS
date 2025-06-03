{{-- NAVBAR SAAT di home --}}
@if (request()->routeIs('home'))
    <nav class="navbar navbar-expand-lg navbar-dark navbar-landing">
        <div class="container">
            <div class="row">
                <button class="navbar-toggler" type="button">
                    <span class="fa solid fa-bars"></span>
                </button>
                <a class="ml-2 navbar-brand" href="home">
                    <img src="{{ asset('assets/images/logo/compass-full-putih.png') }}" alt="COMPASS Logo"
                        class="img-fluid">
                </a>
            </div>
            {{-- SAAT di home dan SUDAH LOGIN (Layar Kecil) --}}
            @if (auth()->check())
                <div class="profile-nav-small d-flex justify-content-center align-items-center ml-3">
                    <div class="dropdown">
                        <a href="javascript:void(0)" class="d-flex align-items-center dropdown-toggle-custom"
                            id="dropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ auth()->user()->getProfile() && Storage::exists('storage/img/profile' . auth()->user()->getProfile()) ? asset('storage/img/profile' . auth()->user()->getProfile()) : asset('assets/images/profil/default-profile.png') }}"
                                class="rounded-circle" width="40" height="40" alt="Foto Profil">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUser">
                            <a href="javascript:void(0)" class="dropdown-item">Profil Saya</a>
                            <a href="{{ route('logout') }}" class="dropdown-item-logout">Logout</a>
                        </div>
                    </div>
                </div>
            @endif
            <div class="collapse navbar-collapse navbar-collapse-custom" id="navbarNav">
                <ul class="navbar-nav ml-auto d-flex align-items-center">
                    <li class="nav-item-home">
                        <a class="nav-link nav-btn-custom" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item-home">
                        <a class="nav-link nav-btn-custom" href="#about-section">Tentang</a>
                    </li>
                    <li class="nav-item-home">
                        <a class="nav-link nav-btn-custom" href="#tim-pengembang">Pengembang</a>
                    </li>
                    @if (auth()->check())
                        {{-- SAAT di home dan SUDAH LOGIN --}}
                        <div class="profile-nav nav-item-home d-flex justify-content-center align-items-center ml-3">
                            <div class="dropdown">
                                <a href="javascript:void(0)" class="d-flex align-items-center dropdown-toggle-custom"
                                    id="dropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ auth()->user()->getProfile() && Storage::exists('storage/img/profile' . auth()->user()->getProfile()) ? asset('storage/img/profile' . auth()->user()->getProfile()) : asset('assets/images/profil/default-profile.png') }}"
                                        class="rounded-circle" width="40" height="40" alt="Foto Profil">
                                    <div class="ml-2 text-left">
                                        <div class="font-weight-bold text-white">
                                            {{ Str::limit(auth()->user()->getName() ?? 'User', 12) }}
                                        </div>
                                        <small
                                            class="text-white-50">{{ auth()->user()->getRoleName() ?? 'Role' }}</small>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUser">
                                    <a href="javascript:void(0)" class="dropdown-item"><i
                                            class="icon-user mr-2"></i><span>Profil Saya</span></a>
                                    <a href="{{ route('logout') }}" class="dropdown-item-logout"><i
                                            class="icon-key mr-2"></i><span>Logout</span></a>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- SAAT di home dan BELUM LOGIN --}}
                        <li class="nav-item-home">
                            <a href="{{ route('login') }}" class="btn btn-rounded btn-masuk font-weight-bold">Masuk</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@else
    {{-- Kode saat BUKAN di Home --}}
    <div class="header">
        <div class="header-content clearfix">

            <div class="nav-control">
                <div class="hamburger">
                    <span class="toggle-icon"><i class="icon-menu"></i></span>
                </div>
            </div>
            <div class="header-right">
                <ul class="clearfix">
                    <li class="icons dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle icon-dropdown-none" data-toggle="dropdown">
                            <i class="mdi mdi-bell-outline"></i>
                            @if ($jumlahBelumDibaca > 0)
                                <span id="header-notif-count" class="badge gradient-1 badge-pill badge-primary">{{ $jumlahBelumDibaca }}</span>
                            @endif
                        </a>
                        <div class="drop-down animated dropdown-menu tinggi-notifikasi">
                            <div class="d-flex justify-content-between px-3">
                                <span class="font-weight-semi-bold" style="font-size: 1rem">Notifikasi Anda</span>
                                <div>
                                    <a class="mr-3 font-weight-semi-bold text-muted" href="javascript:void(0)"
                                        id="btn-baca-semua">
                                        <i class="fa-solid fa-check-double" style="font-size: 1rem"></i> Tandai Semua Dibaca
                                    </a>
                                    <a class="font-weight-semi-bold text-muted" href="{{ route('notifikasi.index') }}">
                                        <i class="fa-solid fa-envelope-open-text" style="font-size: 1rem"></i> Lihat
                                    </a>
                                </div>
                            </div>

                            <div class="btn-group btn-group btn-group-toggle px-3" data-toggle="buttons" role="group"
                                aria-label="Notification filter">
                                <label
                                    class="btn btn-outline-light btn-filter-notification font-weight-bold text-secondary active"
                                    style="border-width: 2px;">
                                    <input type="radio" name="options" id="lihatSemuaNotifikasi"
                                        autocomplete="off" checked />
                                    Semua
                                </label>
                                <label
                                    class="btn btn-outline-light btn-filter-notification font-weight-bold text-secondary"
                                    style="border-width: 2px;">
                                    <input type="radio" name="options" id="lihatNotifikasiBelumDibaca"
                                        autocomplete="off" />
                                    Belum Dibaca
                                </label>
                            </div>

                            <div class="dropdown-content-body">
                                <ul style="max-height: 60vh; overflow: auto">
                                    @if ($notifikasi->count() == 0)
                                        <li class="">
                                            <p class="text-center text-muted m-0 no-notifikasi">
                                                Tidak ada notifikasi
                                            </p>
                                        </li>
                                    @else
                                        @foreach ($notifikasi as $n)
                                            @php
                                                $statusKelas =
                                                    $n->status_notifikasi == 'Belum Dibaca'
                                                        ? 'belum-dibaca'
                                                        : 'sudah-dibaca';
                                            @endphp
                                            <li class="p-0 hover-bg-light {{ $statusKelas }}">
                                                <a href="javascript:void()" class="px-1 tandai-sebagai-dibaca"
                                                    data-id="{{ $n->notifikasi_id }}">
                                                    <img class="float-left mr-3 avatar-img" style="height: 2.5rem"
                                                        src="
                                                        @if ($n->pengirim_role == 'Admin') {{ asset('assets/images/profil/default-profile.png') }}
                                                        @elseif ($n->pengirim_role == 'Dosen') {{ asset('assets/images/profil/default-profile.png') }}                                                            
                                                        @elseif ($n->pengirim_role == 'Sistem') {{ asset('assets/images/logo/compass-ungu.svg') }} @endif
                                                         "
                                                        alt="">
                                                    <div class="notification-content mr-0">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="font-weight-semi-bold" style="color: black">
                                                                {{ $n->pengirim_role }} -
                                                                @if ($n->jenis_notifikasi == 'Rekomendasi')
                                                                    Rekomendasi Lomba
                                                                @elseif ($n->jenis_notifikasi == 'Verifikasi Lomba')
                                                                    Verifikasi Lomba Baru
                                                                @elseif ($n->jenis_notifikasi == 'Verifikasi Prestasi')
                                                                    Verifikasi Prestasi
                                                                @else
                                                                    {{ $n->jenis_notifikasi }}
                                                                @endif

                                                            </div>
                                                            @if ($n->status_notifikasi == 'Belum Dibaca')
                                                                <i class="fa fa-circle text-primary"></i>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-1 pr-1 text-muted"
                                                            style="font-size: 0.725rem">
                                                            <p class="mb-2">
                                                                {{ date('d F Y', strtotime($n->created_at)) }}
                                                            </p>
                                                            <p class="mb-2">
                                                                {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}
                                                            </p>
                                                        </div>
                                                        <div class="bg-light rounded">
                                                            <p class="p-2 m-0">
                                                                {{ $n->pesan_notifikasi }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            @if (!$loop->last)
                                                <hr class="m-0">
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="icons dropdown ml-4 mr-3">
                        <div class="user-img c-pointer position-relative dropdown-toggle icon-dropdown-none"
                            data-toggle="dropdown">
                            <div class="row justify-content-center align-items-center">
                                <div class="position-relative">
                                    <span class="activity active"></span>
                                    <img src="{{ auth()->user()->getProfile() && Storage::exists('storage/img/profile' . auth()->user()->getProfile()) ? asset('storage/img/profile' . auth()->user()->getProfile()) : asset('assets/images/profil/default-profile.png') }}"
                                        class="rounded-circle" width="40" height="40" alt="Foto Profil">
                                </div>
                                <div class="ml-2 text-left">
                                    <div class="font-weight-bold text-black d-block" style="line-height: 1.2">
                                        {{ Str::limit(auth()->user()->getName() ?? 'User', 12) }}
                                    </div>
                                    <small class="text-black d-block" style="line-height: 1.2">
                                        {{ auth()->user()->getRoleName() ?? 'Role' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="drop-down dropdown-profile dropdown-menu dropdown-menu-validation">
                            <div class="dropdown-content-body">
                                <ul>
                                    <li>
                                        @if (Auth::user()->role === 'Admin')
                                            <a href="{{ route('admin.profile.index') }}"><i class="icon-user"></i><span>Profile</span></a>
                                        @elseif (Auth::user()->role === 'Dosen')
                                            <a href="{{ route('dosen.profile.index') }}"><i class="icon-user"></i><span>Profile</span></a>
                                        @elseif (Auth::user()->role === 'Mahasiswa')
                                            <a href="{{ route('mahasiswa.profile.index') }}"><i class="icon-user"></i><span>Profile</span></a>
                                        @else
                                            <a href="#"><i class="icon-user"></i><span>Profile</span></a>
                                        @endif
                                    </li>
                                    <li>
                                        <a href="{{ url('email-inbox.html') }}"><i class="icon-envelope-open"></i>
                                            <span>Inbox</span>
                                            <div class="badge gradient-1 badge-pill badge-primary">3</div>
                                        </a>
                                    </li>

                                    <hr class="my-2">
                                    <li>
                                        <a href="{{ route('logout') }}" class="dropdown-item-logout"><i
                                                class="icon-key mr-2"></i><span
                                                class="logout-text-custom">Logout</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Custom CSS --}}
    <link href="{{ asset('css-custom/header-custom.css') }}" rel="stylesheet">
    {{-- Script jQuery --}}
    <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>

    {{-- Script active filter notification --}}
    <script>
        // Event handler untuk tombol "Baca Semua"
        $('#btn-baca-semua').click(function() {
            // Kirim permintaan AJAX untuk menandai semua notifikasi sebagai dibaca
            $.ajax({
                url: '{{ route('notifikasi.bacaSemuaNotifikasi') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Jika berhasil, ubah semua notifikasi yang belum dibaca menjadi sudah dibaca
                        $('.belum-dibaca').each(function() {
                            $(this).removeClass('belum-dibaca').addClass('sudah-dibaca');
                            $(this).find('.fa-circle').remove(); // Hapus ikon "belum dibaca"
                        });

                        // Perbarui badge jumlah notifikasi menjadi 0
                        $('.badge').text('0');
                    }
                }
            });
        });

        // Event handler saat klik notifikasi
        $('.tandai-sebagai-dibaca').click(function() {
            const notifikasiId = $(this).data('id');
            const $li = $(this).closest('li');

            // Validasi jika notifikasi belum dibaca
            if ($li.hasClass('belum-dibaca')) {
                // Kirim permintaan AJAX untuk menandai notifikasi yang di klik sebagai dibaca
                $.ajax({
                    url: '{{ route('notifikasi.tandaiSudahDibacaNotifikasi', '') }}' + '/' + notifikasiId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Ubah kelas dan hapus indikator belum dibaca
                            $li.removeClass('belum-dibaca').addClass('sudah-dibaca');
                            $li.find('.fa-circle').remove(); // hapus ikon belum dibaca

                            // Perbarui badge jumlah notifikasi menjadi berkurang 1
                            const $badge = $('.dropdown-toggle .badge'); // Target badge notifikasi saja
                            let currentBadgeCount = parseInt($badge.text());

                            if (!isNaN(currentBadgeCount) && currentBadgeCount > 0) {
                                $badge.text(currentBadgeCount - 1);
                            } else {
                                $badge.remove(); // Hapus badge jika sudah 0
                            }
                        }
                    }
                });
            }
        });

        $(document).ready(function() {
            // Event handler untuk tombol filter notifikasi
            $('.btn-filter-notification').click(function() {
                // Ubah kelas tombol menjadi aktif
                $('.btn-filter-notification').removeClass('active');
                $(this).addClass('active');

                const filterId = $(this).find('input').attr('id'); // Dapatkan ID filter
                // Tampilkan notifikasi berdasarkan filter yang dipilih
                if (filterId === 'lihatSemuaNotifikasi') {
                    lihatSemuaNotifikasi();
                } else if (filterId === 'lihatNotifikasiBelumDibaca') {
                    lihatNotifikasiBelumDibaca();
                }
            });
        });

        // Fungsi untuk menampilkan semua notifikasi
        function lihatSemuaNotifikasi() {
            $('.text-anda-sudah-membaca').remove(); // Hapus text

            $('.belum-dibaca, .sudah-dibaca').show(); // Tampilkan semua notifikasi
            $('hr').show(); // Tampilkan semua garis pemisah

            // hapus p dengan text-anda-sudah membaca
        }

        // Fungsi untuk menampilkan hanya notifikasi yang belum dibaca
        function lihatNotifikasiBelumDibaca() {
            $('.sudah-dibaca').hide(); // Sembunyikan notifikasi yang sudah dibaca
            $('.belum-dibaca').show(); // Tampilkan notifikasi yang belum dibaca
            $('hr').hide(); // Sembunyikan semua garis pemisah

            $('.text-anda-sudah-membaca').remove(); // Hapus pesan jika sudah ada

            // Jika tidak ada notifikasi yang belum dibaca, tetapi ada notifikasi yang sudah dibaca
            if ($('.belum-dibaca').length === 0 && $('.sudah-dibaca').length > 0) {
                // Jika tidak ada notifikasi yang belum dibaca, tampilkan pesan
                $('.dropdown-menu .dropdown-menu-validation').append(
                    '<p class="text-center text-anda-sudah-membaca text-muted m-0">Anda sudah membaca semua notifikasi.</p>'
                );
            } else {
                $('.belum-dibaca').each(function(index) {
                    // Tampilkan garis pemisah kecuali setelah notifikasi terakhir yang belum dibaca
                    if (index !== $('.belum-dibaca').length - 1) {
                        $(this).next('hr').show();
                    }
                });
            }
        }
    </script>
@endif

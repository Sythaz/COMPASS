{{-- NAVBAR SAAT di home --}}
@if (request()->routeIs('home'))
    {{-- NAVBAR SAAT di home dan BELUM LOGIN --}}
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
                                    <a href="javascript:void(0)" class="dropdown-item">Profil Saya</a>
                                    <a href="{{ route('logout') }}" class="dropdown-item-logout">Logout</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <li class="nav-item-home">
                            <a href="{{ route('login') }}" class="btn btn-rounded btn-masuk font-weight-bold">Masuk</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    {{-- kode SAAT di home dan BELUM LOGIN --}}
@else
    {{-- Kode saat BUKAN di home --}}
    <div class="header">
        <div class="header-content clearfix">

            <div class="nav-control">
                <div class="hamburger">
                    <span class="toggle-icon"><i class="icon-menu"></i></span>
                </div>
            </div>
            <div class="header-right">
                <ul class="clearfix">
                    <li class="icons dropdown"><a href="javascript:void(0)" data-toggle="dropdown">
                            <i class="mdi mdi-email-outline"></i>
                            <span class="badge gradient-1 badge-pill badge-primary">3</span>
                        </a>
                        <div class="drop-down animated fadeIn dropdown-menu">
                            <div class="dropdown-content-heading d-flex justify-content-between">
                                <span class="">3 New Messages</span>

                            </div>
                            <div class="dropdown-content-body">
                                <ul>
                                    <li class="notification-unread">
                                        <a href="javascript:void()">
                                            <img class="float-left mr-3 avatar-img"
                                                src="{{ asset('theme/images/avatar/1.jpg') }}" alt="">
                                            <div class="notification-content">
                                                <div class="notification-heading">Saiful Islam</div>
                                                <div class="notification-timestamp">08 Hours ago</div>
                                                <div class="notification-text">Hi Teddy, Just wanted to let you ...
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="notification-unread">
                                        <a href="javascript:void()">
                                            <img class="float-left mr-3 avatar-img"
                                                src="{{ asset('theme/images/avatar/2.jpg') }}" alt="">
                                            <div class="notification-content">
                                                <div class="notification-heading">Adam Smith</div>
                                                <div class="notification-timestamp">08 Hours ago</div>
                                                <div class="notification-text">Can you do me a favour?</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void()">
                                            <img class="float-left mr-3 avatar-img"
                                                src="{{ asset('theme/images/avatar/3.jpg') }}" alt="">
                                            <div class="notification-content">
                                                <div class="notification-heading">Barak Obama</div>
                                                <div class="notification-timestamp">08 Hours ago</div>
                                                <div class="notification-text">Hi Teddy, Just wanted to let you ...
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void()">
                                            <img class="float-left mr-3 avatar-img"
                                                src="{{ asset('theme/images/avatar/4.jpg') }}" alt="">
                                            <div class="notification-content">
                                                <div class="notification-heading">Hilari Clinton</div>
                                                <div class="notification-timestamp">08 Hours ago</div>
                                                <div class="notification-text">Hello</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </li>
                    <li class="icons dropdown"><a href="javascript:void(0)" data-toggle="dropdown">
                            <i class="mdi mdi-bell-outline"></i>
                            <span class="badge badge-pill gradient-2 badge-primary">3</span>
                        </a>
                        <div class="drop-down animated fadeIn dropdown-menu dropdown-notfication">
                            <div class="dropdown-content-heading d-flex justify-content-between">
                                <span class="">2 New Notifications</span>

                            </div>
                            <div class="dropdown-content-body">
                                <ul>
                                    <li>
                                        <a href="javascript:void()">
                                            <span class="mr-3 avatar-icon bg-success-lighten-2"><i
                                                    class="icon-present"></i></span>
                                            <div class="notification-content">
                                                <h6 class="notification-heading">Events near you</h6>
                                                <span class="notification-text">Within next 5 days</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void()">
                                            <span class="mr-3 avatar-icon bg-danger-lighten-2"><i
                                                    class="icon-present"></i></span>
                                            <div class="notification-content">
                                                <h6 class="notification-heading">Event Started</h6>
                                                <span class="notification-text">One hour ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void()">
                                            <span class="mr-3 avatar-icon bg-success-lighten-2"><i
                                                    class="icon-present"></i></span>
                                            <div class="notification-content">
                                                <h6 class="notification-heading">Event Ended Successfully</h6>
                                                <span class="notification-text">One hour ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void()">
                                            <span class="mr-3 avatar-icon bg-danger-lighten-2"><i
                                                    class="icon-present"></i></span>
                                            <div class="notification-content">
                                                <h6 class="notification-heading">Events to Join</h6>
                                                <span class="notification-text">After two days</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </li>
                    <li class="icons dropdown d-none d-md-flex">
                        <a href="javascript:void(0)" class="log-user" data-toggle="dropdown">
                            <span>English</span> <i class="fa fa-angle-down f-s-14" aria-hidden="true"></i>
                        </a>
                        <div class="drop-down dropdown-language animated fadeIn  dropdown-menu">
                            <div class="dropdown-content-body">
                                <ul>
                                    <li><a href="javascript:void()">English</a></li>
                                    <li><a href="javascript:void()">Dutch</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="icons dropdown">
                        <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                            <span class="activity active"></span>
                            <img src="{{ asset('theme/images/user/1.png') }}" height="40" width="40"
                                alt="">
                        </div>
                        <div class="drop-down dropdown-profile dropdown-menu">
                            <div class="dropdown-content-body">
                                <ul>
                                    <li>
                                        <a href="{{ url('app-profile.html') }}"><i class="icon-user"></i>
                                            <span>Profile</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ url('email-inbox.html') }}"><i class="icon-envelope-open"></i>
                                            <span>Inbox</span>
                                            <div class="badge gradient-3 badge-pill badge-primary">3</div>
                                        </a>
                                    </li>

                                    <hr class="my-2">
                                    <li>
                                        <a href="{{ url('page-lock.html') }}"><i class="icon-lock"></i> <span>Lock
                                                Screen</span></a>
                                    </li>
                                    <li><a href="{{ url('page-login.html') }}"><i class="icon-key"></i>
                                            <span>Logout</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif

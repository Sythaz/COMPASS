<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">Menu Utama</li>
            <li>
                <a href="{{ route('home') }}" aria-expanded="false">
                    <i class="fas fa-home"></i><span class="nav-text">Home</span>
                </a>
            </li>
            <li>
                <!-- Jika aktif tambahkan class "active" -->
                {{-- <a class="has-arrow {{ $activeMenu == 'dashboard' ? 'active' : '' }}" href="javascript:void()"
                    aria-expanded="false"> --}}
                    @php
                        $user = auth()->user();
                    @endphp

                    @if ($user->hasRole('Admin'))
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    @elseif ($user->hasRole('Dosen'))
                        <a href="{{ route('dosen.dashboard') }}">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    @elseif ($user->hasRole('Mahasiswa'))
                        <a href="{{ route('mahasiswa.dashboard') }}">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    @endif

            </li>

            <!-- Akses Menu untuk Admin -->
            {{-- @if (Auth::user()->role == 'Admin') --}}
            <li class="nav-label mt-2">Manajemen Data</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-user-cog"></i><span class="nav-text">Kelola Pengguna</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li><a href="{{ route('dosen.index') }}">Dosen</a></li>
                    <li><a href="{{ route('mahasiswa.index') }}">Mahasiswa</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-database"></i><span class="nav-text">Master Data</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('program-studi.index') }}">Program Studi</a></li>
                    <li><a href="{{ route('periode-semester.index') }}">Periode Semester</a></li>
                    <li><a href="{{ route('kategori-keahlian.index') }}">Kategori & Keahlian</a></li>
                    <li><a href="{{ route('tingkat-lomba.index') }}">Tingkat Lomba</a></li>
                    <li><a href="javascript:void()">Kriteria Penilaian</a></li>
                </ul>
            </li>

            <li class="nav-label mt-2">Prestasi & Lomba</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-list-alt"></i><span class="nav-text">Manajemen Prestasi</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('kelola-prestasi.index') }}">Kelola Prestasi</a></li>
                    <li><a href="javascript:void()">Verifikasi Prestasi</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fa-solid fa-trophy"></i><span class="nav-text">Manajemen Lomba</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('kelola-lomba.index') }}">Kelola Lomba</a></li>
                    <li><a href="javascript:void()">Rekomendasi Lomba</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ url('/logout') }}">
                    <i class="nav-icon fas fa-arrow-right-from-bracket"></i>
                    <p>Logout</p>
                </a>
            </li>
            {{-- <!-- Akses Menu untuk Dosen -->
            @elseif(Auth::user()->role == 'Dosen')
            <!-- Akses Menu untuk Mahasiswa -->
            @elseif(Auth::user()->role == 'Mahasiswa')
            @endif --}}
    </div>
</div>
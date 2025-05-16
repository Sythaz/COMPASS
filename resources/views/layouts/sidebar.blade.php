<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">Menu Utama</li>
            <li>
                <a href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-home"></i><span class="nav-text">Home</span>
                </a>
            </li>
            <li>
                <!-- Jika aktif tambahkan class "active" -->
                {{-- <a class="has-arrow {{ $activeMenu == 'dashboard' ? 'active' : '' }}" href="javascript:void()"
                    aria-expanded="false"> --}}
                    <a href="{{ route('dashboard') }}" aria-expanded="false">
                        <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                    </a>
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
                    <li><a href="javascript:void()">Program Studi</a></li>
                    <li><a href="javascript:void()">Periode Semester</a></li>
                    <li><a href="{{ route('kategori-keahlian.index') }}">Kategori & Keahlian</a></li>
                    <li><a href="javascript:void()">Tingkat Lomba</a></li>
                    {{-- <li><a href="javascript:void()">Bidang Keahlian</a></li> --}}
                    <li><a href="javascript:void()">Kriteria Penilaian</a></li>
                </ul>
            </li>

            <li class="nav-label mt-2">Prestasi & Lomba</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-list-alt"></i><span class="nav-text">Manajemen Prestasi</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="javascript:void()">Kelola Prestasi</a></li>
                    <li><a href="javascript:void()">Verifikasi Prestasi</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fa-solid fa-trophy"></i><span class="nav-text">Manajemen Lomba</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="javascript:void()">Kelola Lomba</a></li>
                    <li><a href="javascript:void()">Verifikasi Lomba</a></li>
                    <li><a href="javascript:void()">Rekomendasi Lomba</a></li>
                </ul>
            </li>

            {{-- <!-- Akses Menu untuk Dosen -->
            @elseif(Auth::user()->role == 'Dosen')
            <!-- Akses Menu untuk Mahasiswa -->
            @elseif(Auth::user()->role == 'Mahasiswa')
            @endif --}}
    </div>
</div>
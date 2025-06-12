<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">Menu Utama</li>

            {{-- Kembali Ke Halaman Home --}}
            <li>
                <a href="{{ route('home') }}" aria-expanded="false">
                    <i class="fas fa-home"></i><span class="nav-text">Home</span>
                </a>
            </li>

            {{-- Cek User Berdasarkan Role --}}
            @php
                $user = auth()->user();
            @endphp

            <!-- Menu Khusus Admin -->
            @if ($user->hasRole('Admin'))
                <!-- Isi Menu Khusus Mahasiswa Disini ya!! -->
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                    </a>
                </li>
                {{-- Menu Kelola Pengguna --}}
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
                {{-- Menu Manajemen Data --}}
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

                {{-- Menu Manajemen Prestasi --}}
                <li class="nav-label mt-2">Prestasi & Lomba</li>
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="fas fa-list-alt"></i><span class="nav-text">Manajemen Prestasi</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('kelola-prestasi.index') }}">Kelola Prestasi</a></li>
                        <li><a href="{{ route('verifikasi-prestasi.index') }}">Verifikasi Prestasi</a></li>
                    </ul>
                </li>

                {{-- Menu Manajemen Lomba --}}
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="fa-solid fa-trophy"></i><span class="nav-text">Manajemen Lomba</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('kelola-lomba.index') }}">Kelola Lomba</a></li>
                        <li><a href="{{ route('verifikasi-lomba.index') }}">Verifikasi Pengajuan Lomba</a></li>
                        <li><a href="{{ route('verifikasi-pendaftaran.index') }}">Verifikasi Pendaftaran Lomba</a></li>
                        <li><a href="{{ route('histori-pengajuan-lomba.index') }}">Riwayat Pengajuan Lomba</a></li>
                        <li><a href="{{ route('rekomendasi-lomba.index') }}">Rekomendasi Lomba</a></li>
                    </ul>
                </li>

                <!-- Menu Khusus Dosen -->
            @elseif ($user->hasRole('Dosen'))
                <!-- Isi Menu Khusus Dosen Disini ya!! -->
                <li>
                    <a href="{{ route('dosen.dashboard') }}">
                        <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-label mt-2">Manajemen Data</li>
                {{-- Manajemen Mahasiswa Bimbingan --}}
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="fas fa-chalkboard-teacher"></i><span class="nav-text">Manajemen Bimbingan</span>
                    </a>
                    <ul aria-expanded="false">
                        {{-- Halaman untuk melihat Daftar Mahasiswa Bimbingan --}}
                        <li><a href="{{ route('dosen.kelola-bimbingan.index') }}">Kelola Bimbingan</a></li>
                        {{-- Halaman untuk verifikasi daftar Mahasiswa Bimbingan --}}
                        <li><a href="{{ route('dosen.verifikasi-bimbingan.index') }}">Verifikasi Bimbingan</a></li>
                    </ul>
                </li>
                {{-- Halaman Lomba --}}
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="fa-solid fa-trophy"></i><span class="nav-text">Manajemen Lomba</span>
                    </a>
                    <ul aria-expanded="false">
                        {{-- Dosen dapat melihat Lomba yang Tersedia dan mengajukan Lomba baru --}}
                        <li><a href="{{ route('dosen.info-lomba.index') }}">Informasi Lomba</a></li>
                        {{-- Halaman untuk melihat Daftar Lomba yang pernah diajukan Dosen --}}
                        <li><a href="{{ route('dosen.data-lomba.index') }}">Riwayat Pengajuan Lomba</a></li>
                    </ul>
                </li>

                <!-- Menu Khusus Mahasiswa -->
            @elseif ($user->hasRole('Mahasiswa'))
                <!-- Isi Menu Khusus Mahasiswa Disini ya!! -->
                <li>
                    <a href="{{ route('mahasiswa.dashboard') }}">
                        <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-label mt-2">Prestasi & Lomba</li>
                {{-- Manajemen Prestasi Mahasiswa --}}
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="fas fa-award"></i><span class="nav-text">Prestasi Mahasiswa</span>
                    </a>
                    <ul aria-expanded="false">
                        {{-- Halaman Informasi Prestasi --}}
                        <li><a href="{{ route('mhs.prestasi.index') }}">Informasi Prestasi</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="fas fa-trophy"></i><span class="nav-text">Informasi Lomba</span>
                    </a>
                    <ul aria-expanded="false">
                        {{-- Mahasiswa dapat melihat Lomba yang Tersedia dan mengajukan Lomba baru --}}
                        <li><a href="{{ route('mahasiswa.informasi-lomba.index') }}">Informasi Lomba</a></li>
                        {{-- Halaman untuk melihat Daftar Lomba yang pernah diajukan mahasiswa --}}
                        <li><a href="{{ route('mahasiswa.informasi-lomba.history') }}">Riwayat Pengajuan Lomba</a>
                        </li>
                        {{-- Halaman untuk melihat Daftar Lomba yang pernah diajukan mahasiswa --}}
                        <li><a href="{{ route('mahasiswa.informasi-lomba.riwayat-pendaftaran') }}">Riwayat
                                Pendaftaran</a>
                        </li>
                        {{-- Laporan Promethee untuk melihat rekomendasi Lomba --}}
                        <li>
                            <a href="{{ route('laporan-promethee') }}">Perhitungan Promethee</a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</div>

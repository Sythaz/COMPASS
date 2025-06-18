@extends('layouts.template')

@section('title', 'Profile Mahasiswa | COMPASS')

@section('page-title', 'Profile Mahasiswa')

@section('page-description', 'Halaman untuk melihat dan mengelola profile mahasiswa!')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card profile-card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="true">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-profile-tab" data-toggle="tab" href="#edit-profile" role="tab"
                                aria-controls="edit-profile" aria-selected="false">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-preferensi-tab" data-toggle="tab" href="#edit-preferensi"
                                role="tab" aria-controls="edit-preferensi" aria-selected="false">
                                <i class="fas fa-sliders-h"></i> Edit Preferensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="change-password-tab" data-toggle="tab" href="#change-password"
                                role="tab" aria-controls="change-password" aria-selected="false">
                                <i class="fas fa-lock"></i> Ubah Password
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="profile-section">
                                <div class="profile-sidebar">
                                    <img src="{{ auth()->user()->getProfile() && Storage::exists('public/img/profile/' . auth()->user()->getProfile())
                                        ? asset('storage/img/profile/' . auth()->user()->getProfile())
                                        : asset('assets/images/profil/default-profile.png') }}"
                                        alt="Profile Photo" class="profile-avatar">
                                    <h4 class="profile-name">
                                        {{ auth()->user()->getName() ?? 'Anonim' }}</h4>
                                    <p class="profile-role">{{ auth()->user()->getRoleName() ?? 'Role Tidak Diketahui' }}
                                    </p>
                                </div>
                                <div class="profile-info">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-label">NIM</span>
                                            <span
                                                class="info-value">{{ $mahasiswa->nim_mahasiswa ?? 'NIM Tidak Diketahui' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Nama Lengkap</span>
                                            <span class="info-value">{{ auth()->user()->getName() ?? 'Anonim' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Program Studi</span>
                                            <span class="info-value">{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Periode Semester</span>
                                            <span
                                                class="info-value">{{ $mahasiswa->periode->semester_periode ?? '-' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Angkatan</span>
                                            <span class="info-value">{{ $mahasiswa->angkatan ?? '-' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Alamat</span>
                                            <span
                                                class="info-value">{{ $mahasiswa->alamat ?? 'Alamat Tidak Diketahui' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Email</span>
                                            <span
                                                class="info-value">{{ $mahasiswa->email ?? 'Email Tidak Diketahui' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">No HP</span>
                                            <span class="info-value">{{ $mahasiswa->no_hp ?? '-' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Jenis Kelamin</span>
                                            <span class="info-value">
                                                {{ $mahasiswa->kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Tab -->
                        <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                            <div class="row">
                                <div class="col-md-7">
                                    <form id="formEdit" action="{{ route('mahasiswa.profile.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group custom-validation">
                                            <label for="username">Username</label>
                                            <input type="text" placeholder="Masukkan Username Baru"
                                                class="form-control @error('username') is-invalid @enderror" id="username"
                                                name="username" value="{{ old('username') }}">
                                            <small>Biarkan kosong jika tidak ingin mengubah username</small>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="nim_mahasiswa">NIM</label>
                                            <input type="text" placeholder="Masukkan NIM Baru"
                                                class="form-control @error('nim_mahasiswa') is-invalid @enderror"
                                                id="nim_mahasiswa" name="nim_mahasiswa"
                                                value="{{ old('nim_mahasiswa', $mahasiswa->nim_mahasiswa ?? 'NIM Tidak Diketahui') }}"
                                                required readonly>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="nama_mahasiswa">Nama Lengkap <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" placeholder="Masukkan Nama Lengkap Baru"
                                                class="form-control @error('nama_mahasiswa') is-invalid @enderror"
                                                id="nama_mahasiswa" name="nama_mahasiswa"
                                                value="{{ old('nama_mahasiswa', auth()->user()->getName() ?? 'Anonim') }}"
                                                required>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="prodi">Program Studi <span class="text-danger">*</span></label>
                                            <select name="prodi_id" id="prodi_id" class="form-control" required>
                                                <option value="">- Pilih Program Studi -</option>
                                                @foreach ($prodi as $p)
                                                    <option value="{{ $p->prodi_id }}"
                                                        {{ $mahasiswa->prodi_id == $p->prodi_id ? 'selected' : '' }}>
                                                        {{ $p->nama_prodi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="periode">Tahun Periode <span class="text-danger">*</span></label>
                                            <select name="periode_id" id="periode_id" class="form-control" required>
                                                <option value="">- Pilih Tahun Periode -</option>
                                                @foreach ($periode as $p)
                                                    <option value="{{ $p->periode_id }}"
                                                        {{ $mahasiswa->periode_id == $p->periode_id ? 'selected' : '' }}>
                                                        {{ $p->semester_periode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat Baru"
                                                id="alamat" name="alamat" rows="3" required>{{ old('alamat', $mahasiswa->alamat ?? 'Alamat Tidak Diketahui') }}</textarea>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" placeholder="Masukkan Email Baru"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                name="email"
                                                value="{{ old('email', $mahasiswa->email ?? 'Email Tidak Diketahui') }}"
                                                required>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="no_hp">No. HP <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Masukkan No. HP Baru"
                                                class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                                                name="no_hp"
                                                value="{{ old('no_hp', $mahasiswa->no_hp ?? 'No. HP Tidak Diketahui') }}"
                                                required>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="kelamin"> Jenis Kelamin <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('kelamin') is-invalid @enderror"
                                                id="kelamin" name="kelamin" required>
                                                <option value="P"
                                                    {{ old('kelamin', $mahasiswa->kelamin) == 'P' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                                <option value="L"
                                                    {{ old('kelamin', $mahasiswa->kelamin) == 'L' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                            </select>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="foto">Foto Profile</label>
                                            <input type="file"
                                                class="form-control-file @error('foto') is-invalid @enderror"
                                                id="foto" name="img_profile" accept="image/*">
                                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin
                                                mengubah foto.</small>
                                            <small class="form-text text-muted">Format yang didukung: JPG, PNG, JPEG (Max:
                                                2MB)</small>
                                        </div>

                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <div class="photo-preview-card">
                                        <h5 class="card-title">Foto Profile Saat Ini</h5>
                                        <div class="text-center">
                                            <img src="{{ auth()->user()->getProfile() && Storage::exists('public/img/profile/' . auth()->user()->getProfile())
                                                ? asset('storage/img/profile/' . auth()->user()->getProfile())
                                                : asset('assets/images/profil/default-profile.png') }}"
                                                alt="Current Profile Photo" class="current-photo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Preferensi Tab -->
                        <div class="tab-pane fade" id="edit-preferensi" role="tabpanel"
                            aria-labelledby="edit-preferensi-tab">
                            <div class="row">
                                <div class="col-md-8">
                                    <form id="formEditPreferensi" action="{{ route('mahasiswa.profile.preferensi') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        {{-- Bidang Minat --}}
                                        <div class="form-group">
                                            <label for="bidang_id">Bidang Minat <span class="text-danger">*</span></label>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="mt-3 custom-validation">
                                                    <select name="bidang{{ $i }}_id"
                                                        id="bidang{{ $i }}_id" class="form-control select2"
                                                        {{ $i == 1 ? 'required' : '' }}>
                                                        <option value="">-- Pilih Bidang Minat #{{ $i }}
                                                            --
                                                        </option>
                                                        @php
                                                            $preferensiBidang = $dataPreferensiBidang->get($i);
                                                            $selectedKategori = $preferensiBidang
                                                                ? $preferensiBidang->nilai
                                                                : old("bidang{$i}_id");
                                                        @endphp
                                                        @foreach ($daftarKategori->keyBy('nama_kategori') as $kategori)
                                                            <option value="{{ $kategori->kategori_id }}"
                                                                {{ $selectedKategori == $kategori->nama_kategori ? 'selected' : '' }}>
                                                                {{ $kategori->nama_kategori }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Tombol Hapus -->
                                                    <button type="button" class="btn btn-danger btn ml-2 remove-select"
                                                        data-target="bidang{{ $i }}_id">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endfor
                                        </div>

                                        {{-- Tingkat Lomba --}}
                                        <div class="form-group">
                                            <label for="tingkat_lomba_id">Tingkat Lomba <span
                                                    class="text-danger">*</span></label>
                                            @for ($i = 1; $i <= count($daftarTingkat); $i++)
                                                <div class="mt-3 custom-validation">
                                                    <select name="tingkat_lomba{{ $i }}_id"
                                                        id="tingkat_lomba{{ $i }}_id"
                                                        class="form-control select2" required>
                                                        <option value="">-- Pilih Tingkat Lomba #{{ $i }}
                                                            --
                                                        </option>
                                                        @foreach ($daftarTingkat as $tingkat)
                                                            @php
                                                                $preferensiTingkat = $dataPreferensiTingkat->get($i);
                                                                $isSelected =
                                                                    $preferensiTingkat &&
                                                                    $preferensiTingkat->nilai == $tingkat->nama_tingkat;
                                                            @endphp
                                                            <option value="{{ $tingkat->tingkat_lomba_id }}"
                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                {{ $tingkat->nama_tingkat }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Tombol Hapus -->
                                                    <button type="button" class="btn btn-danger btn ml-2 remove-select"
                                                        data-target="tingkat_lomba{{ $i }}_id">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endfor
                                        </div>

                                        {{-- Jenis Penyelenggara Lomba --}}
                                        <div class="form-group">
                                            <label for="jenis_penyelenggara_id">Jenis Penyelenggara Lomba <span
                                                    class="text-danger">*</span></label>
                                            @for ($i = 1; $i <= 3; $i++)
                                                <div class="mt-3 custom-validation">
                                                    <select name="jenis_penyelenggara{{ $i }}_id"
                                                        id="jenis_penyelenggara{{ $i }}_id"
                                                        class="form-control select2" required>
                                                        <option value="">-- Pilih Jenis Penyelenggara Lomba
                                                            #{{ $i }} --
                                                        </option>
                                                        @php
                                                            $preferensiPenyelenggara = $dataPreferensiPenyelenggara->get(
                                                                $i,
                                                            );
                                                            $daftarJenisPenyelenggara = [
                                                                'Institusi',
                                                                'Kampus',
                                                                'Komunitas',
                                                            ];
                                                        @endphp
                                                        @foreach ($daftarJenisPenyelenggara as $jenis)
                                                            <option value="{{ $jenis }}"
                                                                {{ $preferensiPenyelenggara && $preferensiPenyelenggara->nilai == $jenis ? 'selected' : '' }}>
                                                                {{ $jenis }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Tombol Hapus -->
                                                    <button type="button" class="btn btn-danger btn ml-2 remove-select"
                                                        data-target="jenis_penyelenggara{{ $i }}_id">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endfor
                                        </div>

                                        {{-- Lokasi --}}
                                        <div class="form-group">
                                            <label for="">Lokasi Lomba <span class="text-danger">*</span></label>
                                            @for ($i = 1; $i <= 4; $i++)
                                                <div class="mt-3 custom-validation">
                                                    <select name="lokasi{{ $i }}_id"
                                                        id="lokasi{{ $i }}_id" class="form-control select2"
                                                        required>
                                                        <option value="">-- Pilih Lokasi Lomba #{{ $i }}
                                                            --
                                                        </option>
                                                        @php
                                                            $preferensiLokasi = $dataPreferensiLokasi->get($i);
                                                            $daftarLokasi = [
                                                                'Offline Dalam Kota',
                                                                'Online',
                                                                'Hybrid',
                                                                'Offline Luar Kota',
                                                            ];
                                                        @endphp
                                                        @foreach ($daftarLokasi as $lokasi)
                                                            <option value="{{ $lokasi }}"
                                                                {{ $preferensiLokasi && $preferensiLokasi->nilai == $lokasi ? 'selected' : '' }}>
                                                                {{ $lokasi }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Tombol Hapus -->
                                                    <button type="button" class="btn btn-danger btn ml-2 remove-select"
                                                        data-target="lokasi{{ $i }}_id">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endfor
                                        </div>

                                        {{-- Biaya --}}
                                        <div class="form-group">
                                            <label for="biaya{{ $i }}_id">Biaya Lomba <span
                                                    class="text-danger">*</span></label>
                                            @for ($i = 1; $i <= 2; $i++)
                                                <div class="mt-3 custom-validation">
                                                    <select name="biaya{{ $i }}_id"
                                                        id="biaya{{ $i }}_id" class="form-control select2"
                                                        required>
                                                        <option value="">-- Pilih Biaya Lomba #{{ $i }}
                                                            --
                                                        </option>
                                                        @php
                                                            $preferensiBiaya = $dataPreferensiBiaya->get($i);
                                                            $daftarBiaya = ['Tanpa Biaya', 'Dengan Biaya'];
                                                        @endphp
                                                        @foreach ($daftarBiaya as $biaya)
                                                            <option value="{{ $biaya }}"
                                                                {{ $preferensiBiaya && $preferensiBiaya->nilai == $biaya ? 'selected' : '' }}>
                                                                {{ $biaya }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Tombol Hapus -->
                                                    <button type="button" class="btn btn-danger btn ml-2 remove-select"
                                                        data-target="biaya{{ $i }}_id">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endfor
                                        </div>

                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Lupa password & ganti phrase -->
                        <div class="tab-pane fade" id="change-password" role="tabpanel"
                            aria-labelledby="change-password-tab">
                            <div class="row">
                                <div class="col-md-7">
                                    <form action="{{ route('ubah-password-mahasiswa') }}" method="POST"
                                        id="passwordForm">
                                        @csrf

                                        <input type="hidden" name="username" value="{{ auth()->user()->username }}">

                                        <div class="form-group custom-validation">
                                            <label for="phrase">Phrase Pemulihan <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" placeholder="Masukkan Phrase Pemulihan"
                                                class="form-control @error('phrase') is-invalid @enderror" id="phrase"
                                                name="phrase" required>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="new_phrase">Ganti Phrase Pemulihan</label>
                                            <input type="text" placeholder="Masukkan Phrase Baru"
                                                class="form-control @error('new_phrase') is-invalid @enderror"
                                                id="new_phrase" name="phraseBaru"> <!-- Sesuai dengan controller -->
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin diubah</small>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="new_password">Password Baru <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" placeholder="Masukkan Password Baru"
                                                    class="form-control @error('new_password') is-invalid @enderror"
                                                    id="new_password" name="passwordBaru" required>
                                                <!-- Sesuai controller -->
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="toggleNewPassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Password minimal 6 karakter.</small>
                                        </div>

                                        <div class="form-group custom-validation">
                                            <label for="new_password_confirmation">Konfirmasi Password Baru <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" placeholder="Masukkan Konfirmasi Password"
                                                    class="form-control" id="new_password_confirmation"
                                                    name="passwordBaru_confirmation" required> <!-- Sesuai controller -->
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="toggleConfirmPassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-key"></i> Ubah Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <div class="alert alert-info" style="border-radius: 1rem">
                                        <h5 class="text-info">
                                            <i class="fas fa-info-circle text-info mr-2"></i>
                                            Tips Keamanan Password
                                        </h5>
                                        <p class="mb-0">
                                            Gunakan minimal 6 karakter, kombinasikan huruf besar dan kecil, jangan gunakan
                                            informasi pribadi, dan ubah password secara berkala.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Profile CSS --}}
    <link href="{{ asset('css-custom/profile-custom.css') }}" rel="stylesheet">

    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Script Select2 (Dropdown Multiselect/Search) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    {{-- Memanggil Custom CSS Select2 --}}
    <link href="{{ asset('css-custom/select2-custom.css') }}" rel="stylesheet">
    
    {{-- Custom CSS untuk tab --}}
    <link href="{{ asset('css-custom/tab-custom.css') }}" rel="stylesheet">
@endpush

@push('js')
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Memanggil Fungsi Form Validation Custom -->
    <script src="{{ asset('js-custom/form-validation.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Form Edit Preferensi Validation
        $(document).ready(function() {
            // Menghapus value input select
            $('.remove-select').on('click', function() {
                const targetId = $(this).data('target');
                $('#' + targetId).val('').trigger('change');
            });

            // Initialize Select2
            for (let i = 1; i <= 5; i++) {
                $(`#bidang${i}_id`).select2({
                    placeholder: `-- Pilih Bidang Minat #${i} --`,
                    width: '80%',
                    allowClear: false
                });
            }

            // Assuming daftarTingkat count is 6, adjust as needed
            for (let i = 1; i <= 6; i++) {
                $(`#tingkat_lomba${i}_id`).select2({
                    placeholder: `-- Pilih Tingkat Lomba #${i} --`,
                    width: '80%',
                    allowClear: false
                });
            }

            for (let i = 1; i <= 3; i++) {
                $(`#jenis_penyelenggara${i}_id`).select2({
                    placeholder: `-- Pilih Jenis Penyelenggara #${i} --`,
                    width: '80%',
                    allowClear: false
                });
            }

            for (let i = 1; i <= 4; i++) {
                $(`#lokasi${i}_id`).select2({
                    placeholder: `-- Pilih Lokasi #${i} --`,
                    width: '80%',
                    allowClear: false
                });
            }

            for (let i = 1; i <= 2; i++) {
                $(`#biaya${i}_id`).select2({
                    placeholder: `-- Pilih Biaya #${i} --`,
                    width: '80%',
                    allowClear: false
                });
            }

            // Custom form validation
            let validationRules = {
                'bidang1_id': {
                    required: true
                }
            };

            let validationMessages = {
                'bidang1_id': {
                    required: "Bidang minat #1 wajib diisi"
                }
            };

            // Add validation for bidang 2-5 (optional)
            for (let i = 2; i <= 5; i++) {
                validationMessages[`bidang${i}_id`] = {
                    required: `Bidang minat #${i} wajib diisi`
                };
            }

            // Add validation for tingkat lomba (all required)
            for (let i = 1; i <= 6; i++) {
                validationRules[`tingkat_lomba${i}_id`] = {
                    required: true
                };
                validationMessages[`tingkat_lomba${i}_id`] = {
                    required: `Tingkat Lomba #${i} wajib diisi`
                };
            }

            // Add validation for jenis penyelenggara (all required)
            for (let i = 1; i <= 3; i++) {
                validationRules[`jenis_penyelenggara${i}_id`] = {
                    required: true
                };
                validationMessages[`jenis_penyelenggara${i}_id`] = {
                    required: `Jenis Penyelenggara #${i} wajib diisi`
                };
            }

            // Add validation for lokasi (all required)
            for (let i = 1; i <= 4; i++) {
                validationRules[`lokasi${i}_id`] = {
                    required: true
                };
                validationMessages[`lokasi${i}_id`] = {
                    required: `Lokasi #${i} wajib diisi`
                };
            }

            // Add validation for biaya (all required)
            for (let i = 1; i <= 2; i++) {
                validationRules[`biaya${i}_id`] = {
                    required: true
                };
                validationMessages[`biaya${i}_id`] = {
                    required: `Biaya #${i} wajib diisi`
                };
            }

            // Apply custom validation
            customFormValidation(
                "#formEditPreferensi",
                validationRules,
                validationMessages,
                function(response, form) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(function() {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        let errorMessage = response.message || 'Gagal menyimpan preferensi';

                        // Show validation errors if available
                        if (response.errors) {
                            let errorList = '';
                            Object.keys(response.errors).forEach(function(key) {
                                errorList += '• ' + response.errors[key][0] + '\n';
                            });
                            errorMessage += '\n\nDetail Error:\n' + errorList;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: errorMessage,
                            showConfirmButton: true
                        });
                    }
                }
            );

            // Add change event handlers to prevent duplicate selections
            function handleDuplicateSelection(selectType, maxCount) {
                for (let i = 1; i <= maxCount; i++) {
                    $(`#${selectType}${i}_id`).on('change', function() {
                        let selectedValue = $(this).val();
                        if (selectedValue) {
                            // Check for duplicates in other selects of same type
                            for (let j = 1; j <= maxCount; j++) {
                                if (i !== j) {
                                    let otherSelect = $(`#${selectType}${j}_id`);
                                    if (otherSelect.val() === selectedValue) {
                                        // Show warning and clear the duplicate
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Duplikasi Terdeteksi',
                                            text: 'Pilihan ini sudah dipilih di prioritas lain. Silakan pilih opsi yang berbeda.',
                                            showConfirmButton: true
                                        });
                                        $(this).val('').trigger('change');
                                        break;
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Apply duplicate checking for each category
            handleDuplicateSelection('bidang', 5);
            handleDuplicateSelection('tingkat_lomba', 6);
            handleDuplicateSelection('jenis_penyelenggara', 3);
            handleDuplicateSelection('lokasi', 4);
            handleDuplicateSelection('biaya', 2);
        });

        $(document).ready(function() {
            // Toastr untuk menampilkan notifikasi jika preferensi belum lengkap
            toastr.options = {
                closeButton: true,
                positionClass: "toast-bottom-full-width",
                timeOut: "5000",
                hideDuration: "1000",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };

            @if ($preferensi == false)
                toastr.error(
                    "Anda belum mengisi preferensi secara lengkap. Silakan pergi ke halaman profil untuk mengisi preferensi.",
                    "Peringatan");
            @endif
        });

        // Toggle password visibility
        $('#toggleNewPassword').click(function() {
            const passwordInput = $('#new_password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        $('#toggleConfirmPassword').click(function() {
            const confirmInput = $('#new_password_confirmation');
            const type = confirmInput.attr('type') === 'password' ? 'text' : 'password';
            confirmInput.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        // Custom validators
        $.validator.addMethod("validImageExtension", function(value, element) {
            // Jika tidak ada file diupload → anggap valid
            if (!element.files || element.files.length === 0) {
                return true;
            }

            // Cek ekstensi file
            var fileName = element.files[0].name;
            var allowedExtensions = /(\.|\/)(png|jpe?g)$/i;
            return allowedExtensions.test(fileName);
        }, "Ekstensi file harus .png, .jpg, atau .jpeg");

        // Validasi Ukuran File maks 2MB
        $.validator.addMethod("maxFileSize", function(value, element, param) {
            if (!element.files || element.files.length === 0) {
                return true;
            }

            var fileSize = element.files[0].size;
            return fileSize <= 2 * 1024 * 1024;
        }, "Ukuran file maksimal 2MB");

        // Form Edit Profile Validation
        customFormValidation(
            "#formEdit", {
                username: {
                    remote: {
                        url: "{{ route('mahasiswa.profile.cek-username') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            username: function() {
                                return $("#username").val();
                            }
                        }
                    },
                    number: true,
                    minlength: 6
                },
                nim_mahasiswa: {
                    required: true,
                    number: true
                },
                nama_mahasiswa: {
                    required: true
                },
                prodi_id: {
                    required: true
                },
                periode_id: {
                    required: true
                },
                alamat: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                no_hp: {
                    required: true
                },
                kelamin: {
                    required: true
                },
                img_profile: {
                    validImageExtension: true,
                    maxFileSize: true
                }
            }, {
                username: {
                    remote: "Username sudah digunakan",
                    number: "Username harus berupa angka",
                    minlength: "Username minimal 6 karakter"
                },
                nama_mahasiswa: {
                    required: "Nama wajib diisi"
                },
                prodi_id: {
                    required: "Program Studi wajib dipilih"
                },
                periode_id: {
                    required: "Tahun Periode wajib dipilih"
                },
                alamat: {
                    required: "Alamat wajib diisi"
                },
                email: {
                    required: "Email wajib diisi",
                    email: "Format email tidak valid"
                },
                no_hp: {
                    required: "No. HP wajib diisi"
                },
                kelamin: {
                    required: "Jenis Kelamin wajib dipilih"
                },
                img_profile: {
                    validImageExtension: "Ekstensi file harus .png, .jpg, atau .jpeg",
                    maxFileSize: "Ukuran file maksimal 2MB"
                }
            },
            function(response, form) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    }).then(function() {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message || 'Gagal mengupdate profile'
                    });
                }
            }
        );

        // Form Change Password Validation
        customFormValidation(
            "#passwordForm", {
                phrase: {
                    required: true
                },
                passwordBaru: {
                    required: true,
                    minlength: 6
                },
                passwordBaru_confirmation: {
                    required: true,
                    equalTo: "#new_password"
                }
            }, {
                phrase: {
                    required: "Phrase pemulihan wajib diisi"
                },
                passwordBaru: {
                    required: "Password baru wajib diisi",
                    minlength: "Password baru minimal 6 karakter"
                },
                passwordBaru_confirmation: {
                    required: "Konfirmasi password wajib diisi",
                    equalTo: "Konfirmasi password tidak sesuai"
                }
            },
            function(response, form) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    }).then(function() {
                        window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message || 'Gagal mengubah password'
                    });
                }
            }
        );
    </script>
@endpush

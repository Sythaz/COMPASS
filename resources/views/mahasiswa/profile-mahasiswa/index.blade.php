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

                        <!-- Edit Profile -->
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
@endpush

@push('js')
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Memanggil Fungsi Form Validation Custom -->
    <script src="{{ asset('js-custom/form-validation.js') }}"></script>

    <script>
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

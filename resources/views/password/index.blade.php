@extends('layouts.guest_template')

@section('title', 'Lupa Password')

@section('content')
    <div class="container-login d-flex justify-content-center py-5">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="align-items-center card mb-0">
                <!-- no-gutters: Menghilangkan jarak margin default sebuah kolom dari bootstrap-->
                <div class="row no-gutters">
                    <div class="p-3 p-md-4 order-1 order-md-0">
                        <div class="text-center text-md-left text-sm-center">
                            <div class="mb-3 d-inline-flex align-items-center">
                                <img src="{{ asset('assets/images/polinema-bg.png') }}" class="img-fluid" width="60px"
                                    alt="Logo-Polinema">
                                <p class="my-2 mx-3">x</p>
                                <img src="{{ asset('assets/images/logo/compass-full-ungu.png') }}" class="img-fluid"
                                    width="110px" alt="Logo-COMPASS">
                            </div>
                            <h4 style="font-weight: bolder;">Sistem Pemulihan Password</h4>
                            <p style="font-size: small; color: grey;">Silakan masukkan informasi yang diperlukan untuk
                                memulihkan akses ke akun Anda.</p>
                        </div>

                        <form action="{{ route('post-lupa-password') }}" method="POST" id="form-reset"
                            class="mb-3 mt-md-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-size: small">Username <span
                                        class="text-danger">*</span></label>
                                <div class="custom-validation">
                                    <input type="text" class="form-control rounded" style="font-size: small"
                                        id="username" name="username" placeholder="Masukkan Username"
                                        value="{{ old('username') }}" required>
                                    <span class="error-text text-danger" id="error-username"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: small">Phrase Pemulihan <span
                                        class="text-danger">*</span></label>
                                <div class="custom-validation">
                                    <input type="text" class="form-control rounded" style="font-size: small"
                                        id="phrase" name="phrase" placeholder="Masukkan Phrase Pemulihan"
                                        value="{{ old('phrase') }}" required>
                                    <span class="error-text text-danger" id="error-phrase"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: small">Password Baru <span
                                        class="text-danger">*</span></label>
                                <div class="input-group custom-validation">
                                    <input type="password" class="form-control rounded-left fakepassword-baru"
                                        style="font-size: small" id="passwordBaru" name="passwordBaru"
                                        placeholder="Masukkan Password Baru" value="{{ old('passwordBaru') }}" required>
                                    <div class="input-group-append pass-toggle-baru" style="cursor: pointer">
                                        <span class="input-group-text">
                                            <i class="icon fa-solid fa-eye-slash" style="width: 1.25rem"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="error-text text-danger" id="error-passwordBaru"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: small">Konfirmasi Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group custom-validation">
                                    <input type="password" class="form-control rounded-left fakepassword"
                                        style="font-size: small" id="passwordBaru_confirmation"
                                        name="passwordBaru_confirmation" placeholder="Masukkan Konfirmasi Password"
                                        value="{{ old('passwordBaru_confirmation') }}" required>
                                    <div class="input-group-append pass-toggle" style="cursor: pointer">
                                        <span class="input-group-text">
                                            <i class="icon fa-solid fa-eye-slash" style="width: 1.25rem"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="error-text text-danger" id="error-passwordBaru_confirmation"></span>
                            </div>
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-block">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <!-- Memanggil Fungsi Form Validation Custom -->
    <script src="{{ asset('js-custom/form-validation.js') }}"></script>

    <!-- Memanggil Custom Validation untuk form reset password -->
    <script>
        customFormValidation(
            // ID form untuk validasi (DIPERBAIKI: menggunakan #form-reset)
            "#form-reset", {
                // Field yang akan di validasi (name)
                username: {
                    required: true,
                },
                phrase: {
                    required: true,
                },
                passwordBaru: {
                    required: true,
                    minlength: 6
                },
                passwordBaru_confirmation: {
                    required: true,
                    equalTo: "#passwordBaru"
                }
            }, {
                // Pesan validasi untuk setiap field saat tidak valid
                username: {
                    required: "Username wajib diisi",
                },
                phrase: {
                    required: "Phrase pemulihan wajib diisi",
                },
                passwordBaru: {
                    required: "Password baru wajib diisi",
                    minlength: "Password minimal 6 karakter"
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
                        // Redirect ke halaman login jika ada URL redirect
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    });
                } else {
                    // Hapus pesan kesalahan sebelumnya
                    $('.error-text').text('');

                    // Tampilkan pesan kesalahan spesifik jika ada
                    if (response.msgField) {
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }

                    // Tampilkan pesan kesalahan umum
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message
                    });
                }
            }
        );

        document.addEventListener("DOMContentLoaded", function() {
            const togglePassword = (toggler, passwordField) => {
                const password = document.querySelector(passwordField);
                const icon = toggler.querySelector('.icon');

                toggler.addEventListener('click', () => {
                    const isHidden = password.type === 'password';
                    password.type = isHidden ? 'text' : 'password';

                    icon.classList.toggle('fa-eye', isHidden);
                    icon.classList.toggle('fa-eye-slash', !isHidden);
                });
            }

            togglePassword(document.querySelector('.pass-toggle'), '.fakepassword');
            togglePassword(document.querySelector('.pass-toggle-baru'), '.fakepassword-baru');
        });
    </script>
@endpush

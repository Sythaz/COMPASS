@extends('layouts.guest_template')

@section('title', 'Login')

@section('content')
    <div class="container-login d-flex justify-content-center py-5">
        <div class="col-lg-10 col-md-10 col-sm-12">
            <div class="card mb-0">
                <!-- no-gutters: Menghilangkan jarak margin default sebuah kolom dari bootstrap-->
                <div class="row no-gutters">
                    <div class="col-lg-6 col-md-6 col-sm-12 p-4 p-md-5 order-1 order-md-0">
                        <div class="text-center text-md-left text-sm-center">
                            <div class="mb-3 d-inline-flex align-items-center">
                                <img src="{{ asset('assets/images/polinema-bg.png') }}" class="img-fluid" width="60px"
                                    alt="Logo-Polinema">
                                <p class="my-2 mx-3">x</p>
                                <img src="{{ asset('assets/images/logo/compass-full-ungu.png') }}" class="img-fluid"
                                    width="110px" alt="Logo-COMPASS">
                            </div>
                            <p class="mb-1">Selamat Datang!</p>
                            <h4 style="font-weight: bolder;">Sistem Informasi Prestasi Mahasiswa dan Rekomendasi Peserta
                                Lomba</h4>
                            <p style="font-size: small; color: grey;">Merupakan sebuah sistem berbasis web yang
                                digunakan untuk mengelola data prestasi mahasiswa dan rekomendasi peserta lomba.</p>
                        </div>

                        <form action="{{ route('login') }}" method="POST" id="form-login" class="mb-3 mt-md-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-size: small">Username <span
                                        class="text-danger">*</span></label>
                                <div class="custom-validation">
                                    <input type="text" class="form-control rounded" style="font-size: small" id="username"
                                        name="username" placeholder="Masukkan Username" value="{{ old('username') }}"
                                        required>
                                </div>
                            </div>

                            <div class="mb-1 position-relative">
                                <label class="form-label" style="font-size: small">Password <span
                                        style="color: red;">*</span></label>
                                <div class="input-group custom-validation">
                                    <input type="password" class="form-control rounded-left fakepassword"
                                        style="font-size: small" id="password" name="password"
                                        placeholder="Masukkan Password" required>
                                    <div class="input-group-append pass-toggle" style="cursor: pointer">
                                        <span class="input-group-text">
                                            <i class="icon fa-solid fa-eye-slash" style="width: 1.25rem"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('lupa-password') }}" class="text-primary">Lupa password?</a>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>


                    </div>
                    <div class="col-lg-6 col-md-6 d-none d-md-flex">
                        <img src="{{ asset('assets/images/image-side-login.png') }}" class="img-fluid h-100"
                            style="border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; object-fit: cover;"
                            alt="Login Image">
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

    <!-- Memanggil Custom Validation untuk form login -->
    <script>
        customFormValidation(
            "#form-login",
            {
                username: {
                    required: true,
                    digits: true,
                    minlength: 4,
                    maxlength: 20
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                }
            },
            {
                username: {
                    required: "Username wajib diisi",
                    digits: "Hanya boleh angka",
                    minlength: "Minimal 4 karakter",
                    maxlength: "Maksimal 20 karakter"
                },
                password: {
                    required: "Kata sandi wajib diisi",
                    minlength: "Minimal 6 karakter",
                    maxlength: "Maksimal 20 karakter"
                }
            },
            function (response, form) {
                let data = response;

                // Pastikan response adalah objek (parse jika perlu)
                if (typeof response === 'string') {
                    try {
                        data = JSON.parse(response);
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Respon server tidak valid.'
                        });
                        return;
                    }
                }

                if (data.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                    }).then(function () {
                        window.location = data.redirect;
                    });
                } else {
                    $('.error-text').text('');

                    if (data.msgField) {
                        $.each(data.msgField, function (prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                }
            }
        ).fail(function (xhr) {
            let errorMessage = 'Terjadi kesalahan pada server.';

            if (xhr.status === 403) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        let parsed = JSON.parse(xhr.responseText);
                        errorMessage = parsed.message || errorMessage;
                    } catch (e) {
                        errorMessage = 'Akses ditolak.';
                    }
                }
            }

            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: errorMessage
            });
        });
    </script>

    <!-- Password icon toggle -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggler = document.querySelector('.pass-toggle');
            const password = document.querySelector('.fakepassword');
            const icon = document.querySelector('.icon');

            if (password && toggler) {
                toggler.addEventListener('click', () => {
                    const isHidden = password.type === 'password';
                    password.type = isHidden ? 'text' : 'password';

                    icon.classList.toggle('fa-eye', isHidden);
                    icon.classList.toggle('fa-eye-slash', !isHidden);
                });
            }
        });
    </script>
@endpush
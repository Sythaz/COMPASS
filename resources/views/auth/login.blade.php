@extends('layouts.guest_template')

@section('content')
    <div class="container-login d-flex justify-content-center py-lg-5">
        <div class="col-lg-10 col-md-10 col-sm-12">
            <div class="card mb-0">
                <!-- no-gutters: Menghilangkan jarak margin default sebuah kolom dari bootstrap-->
                <div class="row no-gutters">
                    <div class="col-lg-6 col-md-6 col-sm-12 p-4 p-md-5 order-1 order-md-0">
                        <div class="text-center text-md-left text-sm-center">
                            <img src="{{ asset('assets/images/polinema-bg.png') }}" class="img-fluid mb-3" width="60px"
                                alt="Logo">
                            <p class="mb-1">Selamat Datang!</p>
                            <h4 style="font-weight: bolder;">Sistem Informasi Prestasi Mahasiswa dan Rekomendasi Peserta
                                Lomba</h4>
                            <p style="font-size: small; color: grey;">Merupakan sebuah sistem berbasis web yang
                                digunakan untuk mengelola data prestasi mahasiswa dan rekomendasi peserta lomba.</p>
                        </div>
                        <form class="mb-3 mt-md-3" action="/login" method="POST" id="form-login">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-size: small"> NIM <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control rounded" style="font-size: small" id="identifier"
                                    name="identifier" placeholder="Masukkan NIM" value="{{ old('identifier') }}" required>
                                <small id="error-identifier" class="error-text text-danger"></small>
                            </div>

                            <div class="mb-1 position-relative">
                                <label class="form-label" style="font-size: small">Password <span
                                        style="color: red;">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control rounded-left fakepassword"
                                        style="font-size: small" id="password" name="password"
                                        placeholder="Masukkan Password" required>
                                    <div class="input-group-append pass-toggle" style="cursor: pointer">
                                        <span class="input-group-text">
                                            <i class="icon fa-solid fa-eye-slash" style="width: 1.25rem"></i>
                                        </span>
                                    </div>
                                    <small id="error-password" class="error-text text-danger"></small>
                                </div>
                            </div>

                            {{-- Lupa password --}}
                            <div class="mb-3">
                                <a href="" class="text-primary">Lupa password?</a>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>

                            <div>
                                <p>Belum punya akun? <a class="text-primary" href="">Daftar disini</a></p>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 col-md-6 d-none d-md-flex">
                        <img src="{{ asset('assets/images/image-side-login.png') }}" class="img-fluid h-100"
                            style="border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; background-size: cover;"
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
   <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#form-login").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    }
                },
                submitHandler: function(form) { // ketika valid, maka bagian yg akan dijalankan
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) { // jika sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect;
                                });
                            } else { // jika error
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
    {{-- 
    <!-- Password icon toggle -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
    </script> --}}
@endpush

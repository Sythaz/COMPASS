@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $page->title }}</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <div class="default-tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#profile-info">
                                        <i class="la la-user mr-2"></i> Informasi Profil
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#edit-profile">
                                        <i class="la la-edit mr-2"></i> Edit Profil
                                    </a>
                                </li>
                            </ul>
                            
                            <div class="tab-content">
                                <!-- Profile Info Tab -->
                                <div class="tab-pane fade show active" id="profile-info" role="tabpanel">
                                    <div class="pt-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="profile-photo text-center">
                                                    @if($dosen->img_dosen)
                                                        <img src="{{ asset('storage/' . $dosen->img_dosen) }}" 
                                                            alt="Profile Photo" 
                                                            class="img-fluid rounded-circle profile-img">
                                                    @else
                                                        <img src="{{ asset('img/default-profile.png') }}" 
                                                            alt="Default Profile" 
                                                            class="img-fluid rounded-circle profile-img">
                                                    @endif
                                                    <h4 class="mt-3 mb-1">{{ $dosen->nama_dosen }}</h4>
                                                    <p class="text-muted">{{ $dosen->kategori->kategori_nama ?? 'Dosen' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="profile-personal-info">
                                                    <h4 class="text-primary mb-4">Informasi Personal</h4>
                                                    <div class="row mb-3">
                                                        <div class="col-3">
                                                            <h5 class="f-w-500">NIP <span class="pull-right">:</span></h5>
                                                        </div>
                                                        <div class="col-9">
                                                            <span>{{ $dosen->nip_dosen ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-3">
                                                            <h5 class="f-w-500">Nama Lengkap <span class="pull-right">:</span></h5>
                                                        </div>
                                                        <div class="col-9">
                                                            <span>{{ $dosen->nama_dosen ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-3">
                                                            <h5 class="f-w-500">Kategori <span class="pull-right">:</span></h5>
                                                        </div>
                                                        <div class="col-9">
                                                            <span>{{ $dosen->kategori->kategori_nama ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-3">
                                                            <h5 class="f-w-500">Email <span class="pull-right">:</span></h5>
                                                        </div>
                                                        <div class="col-9">
                                                            <span>{{ $dosen->email ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-3">
                                                            <h5 class="f-w-500">No. HP <span class="pull-right">:</span></h5>
                                                        </div>
                                                        <div class="col-9">
                                                            <span>{{ $dosen->no_hp ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-3">
                                                            <h5 class="f-w-500">Alamat <span class="pull-right">:</span></h5>
                                                        </div>
                                                        <div class="col-9">
                                                            <span>{{ $dosen->alamat ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Edit Profile Tab -->
                                <div class="tab-pane fade" id="edit-profile" role="tabpanel">
                                    <div class="pt-4">
                                        <form id="form-edit" action="{{ url('/dosen/profile-dosen/update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group text-center">
                                                        <label class="form-label">Foto Profil</label>
                                                        <div class="profile-photo-edit">
                                                            @if($dosen->img_dosen)
                                                                <img src="{{ asset('storage/' . $dosen->img_dosen) }}" 
                                                                    alt="Current Profile" 
                                                                    class="img-fluid rounded-circle profile-img mb-3">
                                                            @else
                                                                <img src="{{ asset('img/default-profile.png') }}" 
                                                                    alt="No Profile Image" 
                                                                    class="img-fluid rounded-circle profile-img mb-3">
                                                            @endif
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="img_dosen" name="img_dosen" accept=".jpeg,.jpg">
                                                                <label class="custom-file-label" for="img_dosen">Pilih foto baru...</label>
                                                            </div>
                                                            <small class="form-text text-muted">Format: JPEG, Maksimal 2MB</small>
                                                            <small id="error-img_dosen" class="error-text form-text text-danger"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Kategori</label>
                                                                <select name="kategori_id" id="kategori_id" class="form-control" required>
                                                                    <option value="">- Pilih Kategori -</option>
                                                                    @foreach($kategori as $k)
                                                                        <option value="{{ $k->kategori_id }}" {{ $dosen->kategori_id == $k->kategori_id ? 'selected' : '' }}>
                                                                            {{ $k->kategori_nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">NIP</label>
                                                                <input value="{{ $dosen->nip_dosen }}" type="text" name="nip_dosen" id="nip_dosen" class="form-control" placeholder="Masukkan NIP" required>
                                                                <small id="error-nip_dosen" class="error-text form-text text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Nama Lengkap</label>
                                                                <input value="{{ $dosen->nama_dosen }}" type="text" name="nama_dosen" id="nama_dosen" class="form-control" placeholder="Masukkan nama lengkap" required>
                                                                <small id="error-nama_dosen" class="error-text form-text text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Email</label>
                                                                <input value="{{ $dosen->email }}" type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
                                                                <small id="error-email" class="error-text form-text text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">No. HP</label>
                                                                <input value="{{ $dosen->no_hp }}" type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukkan no HP" required>
                                                                <small id="error-no_hp" class="error-text form-text text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Alamat</label>
                                                                <textarea name="alamat" id="alamat" class="form-control" rows="4" placeholder="Masukkan alamat lengkap" required>{{ $dosen->alamat }}</textarea>
                                                                <small id="error-alamat" class="error-text form-text text-danger"></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary btn-lg">
                                                            <i class="la la-save mr-2"></i>Simpan Perubahan
                                                        </button>
                                                        <button type="button" class="btn btn-light btn-lg ml-2" onclick="resetForm()">
                                                            <i class="la la-refresh mr-2"></i>Reset
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('css')
<style>
    .default-tab .nav-tabs {
        border-bottom: 1px solid #eee;
        margin-bottom: 0;
    }
    
    .default-tab .nav-tabs .nav-link {
        border: none;
        padding: 15px 25px;
        color: #333;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        background: none;
    }
    
    .default-tab .nav-tabs .nav-link:hover {
        color: #007bff;
        border-bottom-color: #007bff;
        background: none;
    }
    
    .default-tab .nav-tabs .nav-link.active {
        color: #007bff;
        border-bottom-color: #007bff;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
    }
    
    .tab-content {
        padding: 0;
    }
    
    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid #f8f9fa;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .profile-photo h4 {
        font-weight: 600;
        color: #333;
    }
    
    .profile-personal-info h5 {
        font-size: 14px;
        color: #666;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .profile-personal-info span {
        color: #333;
        font-weight: 500;
    }
    
    .f-w-500 {
        font-weight: 500;
    }
    
    .text-primary {
        color: #007bff !important;
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .btn-lg {
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 500;
    }
    
    .custom-file-label::after {
        content: "Browse";
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }
    
    .profile-photo-edit .profile-img {
        width: 120px;
        height: 120px;
    }
    
    .card {
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border: none;
    }
    
    .card-header {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border-bottom: none;
    }
    
    .card-title {
        margin: 0;
        font-weight: 600;
    }
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Custom file input label update
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Form validation and submission
    $("#form-edit").validate({
        rules: {
            kategori_id: {
                required: true
            },
            nip_dosen: {
                required: true,
                minlength: 5
            },
            nama_dosen: {
                required: true,
                minlength: 3
            },
            alamat: {
                required: true,
                minlength: 10
            },
            email: {
                required: true,
                email: true
            },
            no_hp: {
                required: true,
                minlength: 10
            },
            img_dosen: {
                extension: "jpeg|jpg",
                filesize: 2048000
            }
        },
        messages: {
            kategori_id: {
                required: "Kategori harus dipilih"
            },
            nip_dosen: {
                required: "NIP harus diisi",
                minlength: "NIP minimal 5 karakter"
            },
            nama_dosen: {
                required: "Nama harus diisi",
                minlength: "Nama minimal 3 karakter"
            },
            alamat: {
                required: "Alamat harus diisi",
                minlength: "Alamat minimal 10 karakter"
            },
            email: {
                required: "Email harus diisi",
                email: "Format email tidak valid"
            },
            no_hp: {
                required: "No HP harus diisi",
                minlength: "No HP minimal 10 karakter"
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form);
            
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).html('<i class="la la-spinner la-spin mr-2"></i>Menyimpan...');
                },
                success: function(response) {
                    $('button[type="submit"]').prop('disabled', false).html('<i class="la la-save mr-2"></i>Simpan Perubahan');
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    $('button[type="submit"]').prop('disabled', false).html('<i class="la la-save mr-2"></i>Simpan Perubahan');
                    
                    $('.error-text').text('');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Gagal menyimpan data'
                    });
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    // Custom file size validation
    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0] && element.files[0].size <= param);
    }, 'Ukuran file maksimal 2MB');
});

function resetForm() {
    $('#form-edit')[0].reset();
    $('.custom-file-label').removeClass("selected").html("Pilih foto baru...");
    $('.error-text').text('');
    $('.form-control').removeClass('is-invalid');
}
</script>
@endpush
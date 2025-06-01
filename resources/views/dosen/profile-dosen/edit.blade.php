<!-- Modal Edit Profile (Optional - jika ingin tetap menggunakan modal) -->
<form action="{{ url('/dosen/profile-dosen/update/' . $dosen->dosen_id) }}" method="POST" id="form-edit-modal" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="la la-edit mr-2"></i>Edit Profil Dosen
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Current Profile Image -->
                    <div class="col-md-4 text-center">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Foto Profil Saat Ini</label>
                            <div class="current-image-container mb-3">
                                @if($dosen->img_dosen)
                                    <img src="{{ asset('storage/' . $dosen->img_dosen) }}" 
                                        alt="Current Profile" 
                                        class="img-fluid rounded-circle modal-profile-img">
                                @else
                                    <img src="{{ asset('img/default-profile.png') }}" 
                                        alt="No Profile Image" 
                                        class="img-fluid rounded-circle modal-profile-img">
                                @endif
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="img_dosen_modal" name="img_dosen" accept=".jpeg,.jpg">
                                <label class="custom-file-label" for="img_dosen_modal">Pilih foto baru...</label>
                            </div>
                            <small class="form-text text-muted">Format: JPEG, Maksimal 2MB</small>
                            <small id="error-img_dosen" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    
                    <!-- Form Fields -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Kategori</label>
                                    <select name="kategori_id" id="kategori_id_modal" class="form-control" required>
                                        <option value="">- Pilih Kategori -</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->kategori_id }}" {{ $dosen->kategori_id == $k->kategori_id ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">NIP Dosen</label>
                                    <input value="{{ $dosen->nip_dosen }}" type="text" name="nip_dosen" id="nip_dosen_modal" class="form-control" placeholder="Masukkan NIP" required>
                                    <small id="error-nip_dosen" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-weight-bold">Nama Dosen</label>
                            <input value="{{ $dosen->nama_dosen }}" type="text" name="nama_dosen" id="nama_dosen_modal" class="form-control" placeholder="Masukkan nama lengkap" required>
                            <small id="error-nama_dosen" class="error-text form-text text-danger"></small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Email</label>
                                    <input value="{{ $dosen->email }}" type="email" name="email" id="email_modal" class="form-control" placeholder="Masukkan email" required>
                                    <small id="error-email" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">No HP</label>
                                    <input value="{{ $dosen->no_hp }}" type="text" name="no_hp" id="no_hp_modal" class="form-control" placeholder="Masukkan no HP" required>
                                    <small id="error-no_hp" class="error-text form-text text-danger"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-weight-bold">Alamat</label>
                            <textarea name="alamat" id="alamat_modal" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required>{{ $dosen->alamat }}</textarea>
                            <small id="error-alamat" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">
                    <i class="la la-times mr-2"></i>Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="la la-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<style>
.current-image-container {
    border: 2px solid #dee2e6;
    border-radius: 50%;
    padding: 10px;
    background-color: #f8f9fa;
    display: inline-block;
}

.modal-profile-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.custom-file-label::after {
    content: "Browse";
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.modal-header.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}
</style>

<script>
$(document).ready(function() {
    // Custom file input label update for modal
    $('#img_dosen_modal').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    $("#form-edit-modal").validate({
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
                        $('#myModal').modal('hide');
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
</script>
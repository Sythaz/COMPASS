<form id="form-edit-admin" method="POST" action="{{ url('admin/kelola-pengguna/admin/' . $admin->admin_id) }}">
    @csrf
    @method('PUT')
    
    <!-- Modal Header -->
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Admin</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body">
        <!-- Informasi Personal -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-id-card mr-2"></i>Informasi Personal</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nip_admin" class="col-form-label font-weight-bold">
                                NIP <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nip_admin" id="nip_admin" 
                                       value="{{ $admin->nip_admin }}" required placeholder="Masukkan NIP">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-id-badge"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_admin" class="col-form-label font-weight-bold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama_admin" id="nama_admin" 
                                       value="{{ $admin->nama_admin }}" required placeholder="Masukkan nama lengkap">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kontak -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-address-book mr-2"></i>Informasi Kontak</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="col-form-label font-weight-bold">
                                Email
                                <small class="text-muted font-weight-normal">(boleh dikosongkan)</small>
                            </label>
                            <div class="input-group">
                                <input type="email" class="form-control" name="email" id="email" 
                                       value="{{ old('email', $admin->email) }}" placeholder="contoh: admin@example.com">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-warning text-dark">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_hp" class="col-form-label font-weight-bold">
                                No Handphone
                                <small class="text-muted font-weight-normal">(boleh dikosongkan)</small>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="no_hp" id="no_hp" 
                                       value="{{ old('no_hp', $admin->no_hp) }}" placeholder="08xxxxxxxxxx">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat" class="col-form-label font-weight-bold">
                        Alamat
                        <small class="text-muted font-weight-normal">(boleh dikosongkan)</small>
                    </label>
                    <div class="input-group">
                        <textarea class="form-control" name="alamat" id="alamat" rows="2" 
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', $admin->alamat) }}</textarea>
                        <div class="input-group-append">
                            <span class="input-group-text bg-info text-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Akun -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user-cog mr-2"></i>Informasi Akun</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username" class="col-form-label font-weight-bold">
                                Username <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="username" id="username" 
                                       value="{{ $admin->users->username ?? '' }}" required placeholder="Masukkan username">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-secondary text-white">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="col-form-label font-weight-bold">
                                Password
                                <small class="text-muted font-weight-normal">(kosongkan jika tidak ingin diubah)</small>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password" 
                                       placeholder="Masukkan password baru">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-danger text-white">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phrase" class="col-form-label font-weight-bold">
                        Phrase (Pemulihan Password)
                        <small class="text-muted font-weight-normal">(biarkan jika tidak ingin diubah)</small>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="phrase" id="phrase" 
                               value="{{ old('phrase', $admin->users->phrase ?? $admin->users->username) }}" 
                               placeholder="Phrase untuk pemulihan password">
                        <div class="input-group-append">
                            <span class="input-group-text bg-dark text-white">
                                <i class="fas fa-key"></i>
                            </span>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Phrase digunakan untuk pemulihan password jika lupa
                    </small>
                </div>

                <!-- Role Hidden Input -->
                <input type="hidden" name="role" value="admin">
                
                <!-- Role Display (Read-only) -->
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Role</label>
                    <div class="input-group">
                        <input type="text" class="form-control font-weight-bold" value="Admin" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text bg-danger text-white">
                                <i class="fas fa-user-shield"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Simpan Perubahan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<style>
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }

    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa !important;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    .form-text {
        margin-top: 0.5rem;
    }

    .input-group textarea {
        resize: vertical;
        min-height: 60px;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
    });

    // Submit form edit admin dengan AJAX
    $(document).on('submit', '#form-edit-admin', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        // Show loading state
        let submitBtn = form.find('button[type="submit"]');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        let modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            let modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
                        // Reload datatable if exists
                        if (typeof table !== 'undefined' && table.ajax) {
                            table.ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Update gagal.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function (xhr) {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
                
                // Tampilkan pesan error validasi atau lainnya
                let errors = xhr.responseJSON?.errors;
                let message = '';
                if (errors) {
                    $.each(errors, function (key, val) {
                        message += val + '<br>';
                    });
                } else {
                    message = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                }
                
                Swal.fire({
                    title: 'Error!',
                    html: message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
</script>
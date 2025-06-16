<form id="form-edit-dosen" method="POST" action="{{ url('admin/kelola-pengguna/dosen/' . $dosen->dosen_id) }}">
    @csrf
    @method('PUT')
    
    <!-- Modal Header -->
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Dosen</h5>
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
                            <label for="nip_dosen" class="col-form-label font-weight-bold">
                                NIP <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nip_dosen" id="nip_dosen" 
                                       value="{{ $dosen->nip_dosen }}" required placeholder="Masukkan NIP">
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
                            <label for="nama_dosen" class="col-form-label font-weight-bold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama_dosen" id="nama_dosen" 
                                       value="{{ $dosen->nama_dosen }}" required placeholder="Masukkan nama lengkap">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="kelamin" class="col-form-label font-weight-bold">
                        Jenis Kelamin <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <select name="kelamin" id="kelamin" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('kelamin', $dosen->kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('kelamin', $dosen->kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text bg-secondary text-white">
                                <i class="fas fa-venus-mars"></i>
                            </span>
                        </div>
                    </div>
                    <span class="error-text text-danger" id="error-kelamin"></span>
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
                                       value="{{ old('email', $dosen->email) }}" placeholder="contoh: dosen@example.com">
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
                                       value="{{ old('no_hp', $dosen->no_hp) }}" placeholder="08xxxxxxxxxx">
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
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', $dosen->alamat) }}</textarea>
                        <div class="input-group-append">
                            <span class="input-group-text bg-info text-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Akademik -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Informasi Akademik</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="kategori_id" class="col-form-label font-weight-bold">
                        Bidang Dosen <span class="text-danger">*</span>
                    </label>
                    <div class="input-group d-none">
                        <select name="kategori_id[]" id="kategori_id" class="form-control multiselect-dropdown-kategori"
                            multiple="multiple" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->kategori_id }}" {{ in_array($kategori->kategori_id, $dosen->kategoris->pluck('kategori_id')->toArray()) ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" id="kategori_display"
                            value="{{ $dosen->kategoris->pluck('nama_kategori')->implode(', ') }}" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text bg-purple text-white">
                                <i class="fas fa-tags"></i>
                            </span>
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

                <div class="form-group">
                    <label for="phrase" class="col-form-label font-weight-bold">
                        Phrase (Pemulihan Password)
                        <small class="text-muted font-weight-normal">(biarkan jika tidak ingin diubah)</small>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="phrase" id="phrase" 
                               value="{{ old('phrase', $dosen->users->phrase ?? $dosen->users->username) }}" 
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
                <input type="hidden" name="role" value="dosen">
                
                <!-- Role Display (Read-only) -->
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Role</label>
                    <div class="input-group">
                        <input type="text" class="form-control font-weight-bold" value="Dosen" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text bg-success text-white">
                                <i class="fas fa-chalkboard-teacher"></i>
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    
    /* Custom purple color for bg-purple */
    .bg-purple {
        background-color: #7571F9 !important;
    }

    /* Select2 Styling */
    .select2-container .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0.25rem;
        border: 1px solid #ced4da !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #7571F9;
        background-color: white !important;
        outline: 2px solid #7571F9 !important;
        border: none;
        border-radius: 4px;
        margin-top: 10px;
        margin-left: 12px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        background-color: #7571F9;
    }

    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 12px;
        margin-left: 12px;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #7571F9;
    }
    
    /* Fix for select2 with input-group */
    .input-group .select2-container {
        flex: 1 1 auto;
        width: auto !important;
    }
    
    .input-group .select2-container .select2-selection {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: none;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        
        // Inisialisasi Select2
        $('.multiselect-dropdown-kategori').select2({
            width: '100%',
            placeholder: 'Belum ada kategori terpilih',
        });
    });

    // Submit form edit dosen dengan AJAX
    $(document).on('submit', '#form-edit-dosen', function (e) {
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
                        if (typeof $('#tabel-dosen').DataTable !== 'undefined') {
                            $('#tabel-dosen').DataTable().ajax.reload(null, false);
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
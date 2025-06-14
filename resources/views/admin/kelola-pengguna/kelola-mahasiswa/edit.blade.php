<form id="form-edit-mahasiswa" method="POST" action="{{ url('admin/kelola-pengguna/mahasiswa/' . $mahasiswa->mahasiswa_id) }}">
    @csrf
    @method('PUT')
    
    <!-- Modal Header -->
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Mahasiswa</h5>
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
                            <label for="nim_mahasiswa" class="col-form-label font-weight-bold">
                                NIM <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nim_mahasiswa" id="nim_mahasiswa" 
                                       value="{{ old('nim_mahasiswa', $mahasiswa->nim_mahasiswa ?? '') ?? $mahasiswa->nim_mahasiswa }}" 
                                       required placeholder="Masukkan NIM">
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
                            <label for="nama_mahasiswa" class="col-form-label font-weight-bold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama_mahasiswa" id="nama_mahasiswa" 
                                       value="{{ old('nama_mahasiswa', $mahasiswa->nama_mahasiswa) }}" 
                                       required placeholder="Masukkan nama lengkap">
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
                            <option value="L" {{ old('kelamin', $mahasiswa->kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('kelamin', $mahasiswa->kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
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

        <!-- Informasi Akademik -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Informasi Akademik</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prodi_id" class="col-form-label font-weight-bold">
                                Program Studi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <select name="prodi_id" id="prodi_id" class="form-control" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($list_prodi as $prodi)
                                        <option value="{{ $prodi->prodi_id }}" {{ old('prodi_id', $mahasiswa->prodi_id ?? '') == $prodi->prodi_id ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-university"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="periode_id" class="col-form-label font-weight-bold">
                                Periode <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <select name="periode_id" id="periode_id" class="form-control" required>
                                    <option value="">-- Pilih Periode --</option>
                                    @foreach ($list_periode as $periode)
                                        <option value="{{ $periode->periode_id }}" {{ old('periode_id', $mahasiswa->periode_id ?? '') == $periode->periode_id ? 'selected' : '' }}>
                                            {{ $periode->semester_periode }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-warning text-dark">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="angkatan" class="col-form-label font-weight-bold">
                                Angkatan
                                <small class="text-muted font-weight-normal">(boleh dikosongkan)</small>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="angkatan" id="angkatan" 
                                       value="{{ old('angkatan', $mahasiswa->angkatan ?? '') }}" placeholder="Contoh: 2024">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-dark text-white">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kategori_id" class="col-form-label font-weight-bold">
                                Bidang Minat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <select name="kategori_id[]" id="kategori_id" class="form-control multiselect-dropdown-kategori" multiple="multiple">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->kategori_id }}" {{ in_array($kategori->kategori_id, $mahasiswa->kategoris->pluck('kategori_id')->toArray()) ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-purple text-white">
                                        <i class="fas fa-tags"></i>
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
                                       value="{{ old('email', $mahasiswa->email) }}" placeholder="contoh: mahasiswa@example.com">
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
                                       value="{{ old('no_hp', $mahasiswa->no_hp) }}" placeholder="08xxxxxxxxxx">
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
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
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
                                       value="{{ $mahasiswa->users->username ?? '' }}" required placeholder="Masukkan username">
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
                               value="{{ old('phrase', $mahasiswa->users->phrase ?? $mahasiswa->users->username) }}" 
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
                <input type="hidden" name="role" value="mahasiswa">
                
                <!-- Role Display (Read-only) -->
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Role</label>
                    <div class="input-group">
                        <input type="text" class="form-control font-weight-bold" value="Mahasiswa" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text bg-info text-white">
                                <i class="fas fa-user-graduate"></i>
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

    // Submit form edit mahasiswa dengan AJAX
    $(document).on('submit', '#form-edit-mahasiswa', function (e) {
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
                        if (typeof $('#tabel-mahasiswa').DataTable !== 'undefined') {
                            $('#tabel-mahasiswa').DataTable().ajax.reload(null, false);
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
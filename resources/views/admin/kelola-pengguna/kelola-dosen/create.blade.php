<form id="formTambahDosen" method="POST" action="{{ url('admin/kelola-pengguna/dosen/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Dosen</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Informasi Identitas -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-id-card mr-2"></i>Informasi Identitas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nip_dosen" class="col-form-label font-weight-bold">NIP 
                                <span class="text-danger">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nip_dosen" id="nip_dosen" 
                                           placeholder="Masukkan NIP Dosen" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-id-badge"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="error-text text-danger" id="error-nip_dosen"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_dosen" class="col-form-label font-weight-bold">Nama Lengkap 
                                <span class="text-danger">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nama_dosen" id="nama_dosen" 
                                           placeholder="Masukkan Nama Lengkap" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="error-text text-danger" id="error-nama_dosen"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="kelamin" class="col-form-label font-weight-bold">Jenis Kelamin 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="custom-validation">
                        <div class="input-group">
                            <select name="kelamin" id="kelamin" class="form-control" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('kelamin', $dosen->kelamin ?? '') == 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="P" {{ old('kelamin', $dosen->kelamin ?? '') == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info text-white">
                                    <i class="fas fa-venus-mars"></i>
                                </span>
                            </div>
                        </div>
                        <span class="error-text text-danger" id="error-kelamin"></span>
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
                            <label for="email" class="col-form-label font-weight-bold">Email 
                                <small class="text-muted">(Opsional)</small>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="email" name="email" id="email" class="form-control"
                                           placeholder="user@example.com" value="{{ old('email') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-warning text-dark">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="error-text text-danger" id="error-email"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_hp" class="col-form-label font-weight-bold">No. Handphone 
                                <small class="text-muted">(Opsional)</small>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="text" name="no_hp" id="no_hp" class="form-control"
                                           placeholder="08123456789" value="{{ old('no_hp') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-secondary text-white">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="error-text text-danger" id="error-no_hp"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat" class="col-form-label font-weight-bold">Alamat 
                        <small class="text-muted">(Opsional)</small>
                    </label>
                    <div class="custom-validation">
                        <div class="input-group">
                            <textarea name="alamat" id="alamat" class="form-control" rows="3"
                                      placeholder="Jl. Contoh No. 1, Kota, Provinsi">{{ old('alamat') }}</textarea>
                            <div class="input-group-append align-items-start">
                                <span class="input-group-text bg-dark text-white" style="height: 100%;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                            </div>
                        </div>
                        <span class="error-text text-danger" id="error-alamat"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Bidang Keahlian -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Bidang Keahlian</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="kategori_id" class="col-form-label font-weight-bold">Bidang Dosen
                        <span class="text-danger">*</span>
                    </label>
                    <div class="custom-validation">
                        <div class="select2-wrapper">
                            <select name="kategori_id[]" id="kategori_id" class="form-control multiselect-dropdown-kategori"
                                    multiple="multiple" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->kategori_id }}">
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="select2-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <span class="error-text text-danger" id="error-kategori_id"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Sistem -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-cog mr-2"></i>Informasi Sistem</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x mr-3"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Informasi Role</h6>
                            <p class="mb-0">Pengguna ini akan didaftarkan sebagai <strong>Dosen</strong> sistem.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="role" value="dosen">
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Dosen
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<!-- CSS Styling -->
<style>
    /* Card styling konsisten dengan form admin */
    .card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .card-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa !important;
        border-radius: 8px 8px 0 0 !important;
    }

    .card-header h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 1rem;
    }

    .col-form-label {
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
        margin-bottom: 0;
    }

    .font-weight-bold {
        font-weight: 600 !important;
        color: #495057;
    }

    /* Input group styling */
    .input-group-text {
        border: 1px solid #ced4da;
        font-weight: 500;
    }

    .input-group-append .input-group-text {
        border-left: 0;
    }

    /* Special styling for purple background */
    .bg-purple {
        background-color: #7571F9 !important;
    }

    /* Textarea input group special styling */
    .input-group-append.align-items-start {
        align-items: stretch !important;
    }

    .input-group-append.align-items-start .input-group-text {
        display: flex;
        align-items: flex-start;
        padding-top: 0.75rem;
        border-radius: 0 0.375rem 0.375rem 0;
    }

    /* Alert styling */
    .alert-info {
        background-color: #e3f2fd;
        border-color: #bbdefb;
        color: #0277bd;
    }

    .alert-heading {
        color: #01579b !important;
        font-weight: 600;
    }

    /* Modal styling */
    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    /* Text styling */
    .text-danger {
        color: #dc3545 !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* Error text styling */
    .error-text {
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }

    /* Form control focus states */
    .form-control:focus {
        border-color: #7571F9;
        box-shadow: 0 0 0 0.2rem rgba(117, 113, 249, 0.25);
    }

    .form-control:focus + .input-group-append .input-group-text {
        border-color: #7571F9;
    }

    /* Placeholder styling */
    .form-control::placeholder {
        color: #adb5bd;
        font-style: italic;
    }

    /* Button styling */
    .btn-primary {
        background-color: #7571F9;
        border-color: #7571F9;
    }

    .btn-primary:hover {
        background-color: #5f5bd1;
        border-color: #5f5bd1;
    }

    /* Select2 wrapper dengan icon */
    .select2-wrapper {
        position: relative;
    }
    
    .select2-icon {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        background-color: #7571F9;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        z-index: 10;
        pointer-events: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Select2 styling untuk multiselect */
    .select2-container .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0.375rem !important;
        border: 1px solid #ced4da !important;
        padding-right: 50px !important;
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .input-group-append .input-group-text {
            min-width: auto;
        }
    }
</style>

<!-- External Libraries -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Custom Scripts -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    // Animasi card saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        
        // Initialize Select2 multiselect
        $('.multiselect-dropdown-kategori').select2({
            width: '100%',
            placeholder: 'Belum ada kategori terpilih',
        });
    });

    // Fungsi untuk mendapatkan instance modal bootstrap versi 5
    function getModalInstance() {
        const modalEl = document.getElementById('myModal');
        return bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    }

    // Custom validation untuk no HP (hanya angka)
    $.validator.addMethod("phoneNumber", function(value, element) {
        if (!value) return true; // Optional field
        return /^[0-9+\-\s()]+$/.test(value);
    }, "Nomor handphone hanya boleh berisi angka, spasi, tanda +, -, dan kurung");

    // Custom validation untuk NIP (format tertentu jika diperlukan)
    $.validator.addMethod("nipFormat", function(value, element) {
        if (!value) return false;
        return /^[0-9]{8,}$/.test(value); // Minimal 8 digit angka
    }, "NIP harus berupa angka minimal 8 digit");

    // Fungsi validasi dan submit form
    customFormValidation(
        "#formTambahDosen",
        {
            nip_dosen: { 
                required: true,
                nipFormat: true
            },
            nama_dosen: { 
                required: true,
                minlength: 2
            },
            kelamin: { 
                required: true 
            },
            'kategori_id[]': { 
                required: true 
            },
            email: {
                email: true // Optional tapi harus valid jika diisi
            },
            no_hp: {
                phoneNumber: true // Optional tapi harus valid jika diisi
            }
        },
        {
            nip_dosen: { 
                required: "NIP wajib diisi",
                nipFormat: "NIP harus berupa angka minimal 8 digit"
            },
            nama_dosen: { 
                required: "Nama wajib diisi",
                minlength: "Nama minimal 2 karakter"
            },
            kelamin: { 
                required: "Jenis Kelamin wajib dipilih" 
            },
            'kategori_id[]': { 
                required: "Pilih Minimal 1 Bidang" 
            },
            email: {
                email: "Format email tidak valid"
            },
            no_hp: {
                phoneNumber: "Format nomor handphone tidak valid"
            }
        },
        function (response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function () {
                    // Tutup modal dengan benar
                    getModalInstance().hide();

                    // Reload DataTable (sesuaikan id tabel)
                    $('#tabel-dosen').DataTable().ajax.reload();
                });
            } else {
                // Clear error text dulu
                $('.error-text').text('');

                // Tampilkan pesan error di field masing-masing
                $.each(response.msgField, function (prefix, val) {
                    $('#error-' + prefix).text(val[0]);
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: response.message
                });
            }
        }
    );

    // Fungsi load form via ajax ke modal dan tampilkan modal
    function modalAction(url) {
        $.get(url)
            .done(function (res) {
                $('#ajaxModalContent').html(res);
                getModalInstance().show();
            })
            .fail(function () {
                Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
            });
    }
</script>
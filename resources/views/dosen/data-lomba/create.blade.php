<form id="form-create" method="POST" action="{{ route('dosen.data-lomba.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="modal-body">
        <!-- Informasi Dasar Lomba -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-trophy mr-2"></i>Informasi Dasar Lomba</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lomba" class="col-form-label font-weight-bold">Nama Lomba 
                                <span class="text-danger" style="color: red;">*</span>
                            </label>
                            <div class="custom-validation">
                                <input type="text" class="form-control" name="nama_lomba" id="nama_lomba" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="penyelenggara_lomba" class="col-form-label font-weight-bold">Penyelenggara Lomba 
                                <span class="text-danger" style="color: red;">*</span>
                            </label>
                            <div class="custom-validation">
                                <input type="text" class="form-control" name="penyelenggara_lomba" id="penyelenggara_lomba" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi_lomba" class="col-form-label font-weight-bold">Deskripsi Lomba 
                        <span class="text-danger" style="color: red;">*</span>
                    </label>
                    <div class="custom-validation">
                        <textarea name="deskripsi_lomba" id="deskripsi_lomba" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori dan Tingkat Lomba -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-tags mr-2"></i>Kategori dan Tingkat Lomba</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kategori_id" class="col-form-label font-weight-bold">Kategori Lomba 
                                <span class="text-danger" style="color: red;">*</span>
                            </label>
                            <div class="custom-validation">
                                <select name="kategori_id[]" id="kategori_id" class="form-control multiselect-dropdown-kategori"
                                    multiple="multiple" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($daftarKategori as $kategori)
                                        <option value="{{ $kategori->kategori_id }}">
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tingkat_lomba_id" class="col-form-label font-weight-bold">Tingkat Lomba 
                                <span class="text-danger" style="color: red;">*</span>
                            </label>
                            <div class="custom-validation">
                                <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control" required>
                                    <option value="">-- Pilih Tingkat --</option>
                                    @foreach ($daftarTingkatLomba as $tingkat_lomba)
                                        <option value="{{ $tingkat_lomba->tingkat_lomba_id }}">
                                            {{ $tingkat_lomba->nama_tingkat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Pendaftaran -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Jadwal Pendaftaran</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="awal_registrasi_lomba" class="col-form-label font-weight-bold">Awal Registrasi Lomba 
                                <span class="text-danger" style="color: red;">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="awal_registrasi_lomba" id="awal_registrasi_lomba" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-calendar-check"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="akhir_registrasi_lomba" class="col-form-label font-weight-bold">Akhir Registrasi Lomba 
                                <span class="text-danger" style="color: red;">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="akhir_registrasi_lomba" id="akhir_registrasi_lomba" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-danger text-white">
                                            <i class="fas fa-calendar-times"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="link_pendaftaran_lomba" class="col-form-label font-weight-bold">Link Pendaftaran Lomba 
                        <span class="text-danger" style="color: red;">*</span>
                    </label>
                    <div class="custom-validation">
                        <div class="input-group">
                            <input type="text" class="form-control" name="link_pendaftaran_lomba" id="link_pendaftaran_lomba" value="https://" required>
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-link"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Gambar Poster -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-image mr-2"></i>Gambar Poster Lomba</h6>
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    <label for="img_lomba" class="col-form-label font-weight-bold">
                        Gambar Poster Lomba 
                        <small class="text-muted">(Maksimal 2MB - Format: PNG, JPG, JPEG)</small>
                    </label>
                    <div class="custom-validation">
                        <div class="file-upload-container">
                            <div class="file-preview mb-3 text-center" id="file-preview" style="display: none;">
                                <i class="fas fa-file-image fa-3x text-primary mb-2"></i>
                                <p class="mb-0" id="file-name">File terpilih</p>
                            </div>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="img_lomba" id="img_lomba" 
                                           accept=".png, .jpg, .jpeg" onchange="previewFile(this)" nullable>
                                    <label class="custom-file-label" id="img_lomba_label" for="img_lomba">Pilih File Gambar</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-upload"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Lomba
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<!-- CSS Styling -->
<style>
    /* Card styling mirip dengan detail pendaftaran */
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
    }

    .input-group-append .input-group-text {
        border-left: 0;
    }

    /* File upload styling */
    .file-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .file-upload-container:hover {
        border-color: #7571F9;
        background-color: rgba(117, 113, 249, 0.05);
    }

    .file-preview {
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background-color: #ffffff;
    }

    /* Select2 styling tetap sama */
    .select2-container .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0.375rem;
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

    /* Modal styling */
    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    /* Text color for required asterisk */
    .text-danger {
        color: #dc3545 !important;
    }

    /* Small text styling */
    .text-muted {
        color: #6c757d !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .file-upload-container {
            padding: 15px;
        }
    }
</style>

<!-- JavaScript -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<!-- Script Select2 (Dropdown Multiselect/Search) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // File preview function
    function previewFile(input) {
        const file = input.files[0];
        const preview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const label = document.getElementById('img_lomba_label');
        
        if (file) {
            fileName.textContent = file.name;
            label.textContent = file.name;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
            label.textContent = 'Pilih File Gambar';
        }
    }

    // Memanggil Select2 multiselect
    $(document).ready(function () {
        // Animasi card saat modal dibuka
        $('.card').hide().fadeIn(600);
        
        // Initialize Select2
        $('.multiselect-dropdown-kategori').select2({
            width: '100%',
            placeholder: 'Belum ada kategori terpilih',
        });
    });

    // Form validation
    // Fungsi untuk validasi ekstensi gambar
    $.validator.addMethod("validImageExtension", function (value, element) {
        // Jika tidak ada file diupload → anggap valid
        if (!element.files || element.files.length === 0) {
            return true;
        }

        // Cek ekstensi file
        var fileName = element.files[0].name;
        var allowedExtensions = /(\.|\/)(png|jpe?g)$/i;
        return allowedExtensions.test(fileName);
    }, "Ekstensi file harus .png, .jpg, .jpeg");

    // Validasi Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal (afterOrEqual)
    $.validator.addMethod("afterOrEqual", function (value, element, param) {
        if (!value || !$(param).val()) return true;
        var startDate = new Date($(param).val());
        var endDate = new Date(value);
        return endDate >= startDate;
    }, "Tanggal akhir harus lebih besar atau sama dengan tanggal awal.");

    customFormValidation(
        // Validasi form
        // ID form untuk validasi
        "#form-create", {
        // Field yang akan di validasi (name)
        nama_lomba: {
            required: true,
        },
        deskripsi_lomba: {
            required: true,
        },
        kategori_id: {
            required: true,
        },
        tingkat_lomba_id: {
            required: true,
        },
        penyelenggara_lomba: {
            required: true,
        },
        awal_registrasi_lomba: {
            required: true,
        },
        akhir_registrasi_lomba: {
            required: true,
            afterOrEqual: "#awal_registrasi_lomba",
        },
        link_pendaftaran_lomba: {
            required: true,
            url: true,
        },
        status_verifikasi: {
            required: true,
        },
        img_lomba: {
            // Menggunakan custom validasi untuk gambar
            validImageExtension: true,
        }
    }, {
        // Pesan validasi untuk setiap field saat tidak valid
        nama_lomba: {
            required: "Nama Lomba wajib diisi",
        },
        deskripsi_lomba: {
            required: "Deskripsi Lomba wajib diisi",
        },
        kategori_id: {
            required: "Kategori wajib diisi",
        },
        tingkat_lomba_id: {
            required: "Tingkat Lomba wajib diisi",
        },
        penyelenggara_lomba: {
            required: "Penyelenggara Lomba wajib diisi",
        },
        awal_registrasi_lomba: {
            required: "Awal Registrasi Lomba wajib diisi",
        },
        akhir_registrasi_lomba: {
            required: "Akhir Registrasi Lomba wajib diisi",
            afterOrEqual: "Tanggal akhir harus lebih besar atau sama dengan tanggal awal"
        },
        link_pendaftaran_lomba: {
            required: "Link Pendaftaran Lomba wajib diisi",
            url: "Link harus berupa URL dan dimulai dengan http:// atau https://",
        },
        status_verifikasi: {
            required: "Status Lomba wajib diisi",
        },
        img_lomba: {
            extension: 'Ekstensi file harus .png, .jpg, .jpeg',
        }
    },

        function (response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function () {
                    // Tutup modal
                    $('#myModal').modal('hide');

                    // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                    $('#tabel-kelola-lomba').DataTable().ajax.reload();
                });
            } else {
                $('.error-text').text('');
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
</script>
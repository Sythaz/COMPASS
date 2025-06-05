<form id="form-create" method="POST" action="{{ route('store-lomba') }}" enctype="multipart/form-data">
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
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Dasar Lomba</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lomba" class="col-form-label font-weight-bold">Nama Lomba <span
                                    class="text-danger">*</span></label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nama_lomba" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="penyelenggara_lomba" class="col-form-label font-weight-bold">Penyelenggara Lomba
                                <span class="text-danger">*</span></label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="penyelenggara_lomba" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-info text-white">
                                            <i class="fas fa-building"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi_lomba" class="col-form-label font-weight-bold">Deskripsi Lomba <span
                            class="text-danger">*</span></label>
                    <div class="custom-validation">
                        <div class="input-group">
                            <textarea name="deskripsi_lomba" id="deskripsi_lomba" class="form-control" rows="3"
                                required></textarea>
                            <div class="input-group-append align-self-start">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="fas fa-align-left"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kategori_id" class="col-form-label font-weight-bold">Kategori Lomba <span
                                    class="text-danger">*</span></label>
                            <div class="custom-validation">
                                <select name="kategori_id[]" id="kategori_id"
                                    class="form-control multiselect-dropdown-kategori" multiple="multiple" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($daftarKategori as $kategori)
                                        <option value="{{ $kategori->kategori_id }}">
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <label for="tipe_lomba_id" class="col-form-label font-weight-bold">Tipe Lomba <span
                                    class="text-danger">*</span></label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <select name="tipe_lomba" id="tipe_lomba_id" class="form-control select2" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="Individu">Individu</option>
                                        <option value="Tim">Tim</option>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-warning text-dark">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tingkat_lomba_id" class="col-form-label font-weight-bold">Tingkat Lomba <span
                                    class="text-danger">*</span></label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control select2"
                                        required>
                                        <option value="">-- Pilih Tingkat --</option>
                                        @foreach ($daftarTingkatLomba as $tingkat_lomba)
                                            <option value="{{ $tingkat_lomba->tingkat_lomba_id }}">
                                                {{ $tingkat_lomba->nama_tingkat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-warning text-dark">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Registrasi -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Informasi Registrasi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="awal_registrasi_lomba" class="col-form-label font-weight-bold">Awal Registrasi
                                Lomba <span class="text-danger">*</span></label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="awal_registrasi_lomba"
                                        id="awal_registrasi_lomba" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-calendar-plus"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="akhir_registrasi_lomba" class="col-form-label font-weight-bold">Akhir Registrasi
                                Lomba <span class="text-danger">*</span></label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="akhir_registrasi_lomba"
                                        id="akhir_registrasi_lomba" required>
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
                        <span class="text-danger">*</span></label>
                    <div class="custom-validation">
                        <div class="input-group">
                            <input type="text" class="form-control" name="link_pendaftaran_lomba" value="https://"
                                required>
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

        <!-- Gambar Poster -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-image mr-2"></i>Gambar Poster Lomba</h6>
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    <label for="img_lomba" class="col-form-label font-weight-bold">Gambar Poster Lomba <small>(Maksimal
                            2MB)</small></label>
                    <div class="custom-validation">
                        <div class="input-group mt-1">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="img_lomba" accept=".png, .jpg, .jpeg"
                                    onchange="$('#img_lomba_label').text(this.files[0].name)" nullable>
                                <label class="custom-file-label" id="img_lomba_label" for="img_lomba">Pilih File</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal</button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<!-- Script Select2 (Dropdown Multiselect/Search) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #7571F9;
        background-color: white !important;
        outline: 2px solid #7571F9 !important;
        border: none;
        border-radius: 4px;
        margin-top: 10px;
        margin-left: 12px
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

    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .input-group-text {
        border-left: 1px solid #ced4da;
    }

    .custom-file-label {
        border-radius: 0.25rem;
    }
</style>

<script>
    // Memanggil Select2 multiselect
    $(document).ready(function () {
        $('.multiselect-dropdown-kategori').select2({
            width: '100%',
            placeholder: 'Belum ada kategori terpilih',
        });

        // Animasi saat modal dibuka
        $('.card').hide().fadeIn(600);
    });
</script>

<!-- Memanggil Custom validation untuk Form -->
<script>
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
        tipe_lomba_id: {
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
        tipe_lomba_id: {
            required: "Tipe Lomba wajib diisi",
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
<form id="form-create" method="POST" action="{{ url('admin/manajemen-prestasi/kelola-prestasi/store') }}"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            {{-- Mahasiswa ID --}}
            {{-- NIM dan Nama Mahasiswa --}}
            <label for="mahasiswa_id" class="col-form-label mt-2">Mahasiswa <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="mahasiswa_id select2" id="mahasiswa_id" class="form-control select2" required>
                    @foreach ($daftarMahasiswa as $m)
                        <option value="{{ $m->mahasiswa_id }}">
                            {{ $m->nim_mahasiswa }} - {{ $m->nama_mahasiswa }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Lomba --}}
            <label for="lomba_id" class="col-form-label mt-2">Nama Lomba <small>(Lomba harus terdaftar terlebih dahulu)</small> <span
                    class="text-danger" style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="lomba_id select2" id="lomba_id" class="form-control select2" required>
                    @foreach ($daftarLomba as $lomba)
                        <option value="{{ $lomba->lomba_id }}">
                            {{ $lomba->nama_lomba }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dosen --}}
            <label for="dosen_id" class="col-form-label mt-2">Dosen <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="dosen_id select2" id="dosen_id" class="form-control select2" required>
                    @foreach ($daftarDosen as $dosen)
                        <option value="{{ $dosen->dosen_id }}">
                            {{ $dosen->nama_dosen }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kategori --}}
            <label for="kategori_id" class="col-form-label mt-2">Kategori <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="kategori_id select2" id="kategori_id" class="form-control select2" required>
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->kategori_id }}">
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Periode --}}
            <label for="periode_id" class="col-form-label mt-2">Periode <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="periode_id select2" id="periode_id" class="form-control select2" required>
                    @foreach ($daftarPeriode as $periode)
                        <option value="{{ $periode->periode_id }}">
                            {{ $periode->semester_periode }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal Prestasi --}}
            <label for="tanggal_prestasi" class="col-form-label mt-2">Tanggal Prestasi <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <input type="date" class="form-control" name="tanggal_prestasi" required>
            </div>

            {{-- Juara Prestasi --}}
            <label for="juara_prestasi" class="col-form-label mt-2">Juara Prestasi <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="juara_prestasi" required>
            </div>

            {{-- Jenis Prestasi --}}
            <label for="jenis_prestasi" class="col-form-label mt-2">Jenis Prestasi <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="jenis_prestasi select2" id="jenis_prestasi" class="form-control select2" required>
                    <option value="">-- Pilih Jenis Prestasi --</option>
                    <option value="Individu">Individu</option>
                    <option value="Tim">Tim</option>
                </select>
            </div>

            {{-- Gambar Kegiatan --}}
            <label for="img_kegiatan" class="col-form-label mt-2">Gambar Kegiatan <small>(Maksimal 2MB)</small> </label>
            <div class="custom-validation">
                <div class="input-group mt-1">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_kegiatan" accept=".png, .jpg, .jpeg"
                            onchange="$('#img_kegiatan_label').text(this.files[0].name)" nullable>
                        <label class="custom-file-label" id="img_kegiatan_label" for="img_kegiatan">Pilih File</label>
                    </div>
                </div>
            </div>

            {{-- Bukti Prestasi --}}
            <label for="bukti_prestasi" class="col-form-label mt-2">Bukti Prestasi <small>(Maksimal 2MB)</small>
            </label>
            <div class="custom-validation">
                <div class="input-group mt-1">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="bukti_prestasi"
                            accept=".png, .jpg, .jpeg" onchange="$('#bukti_prestasi_label').text(this.files[0].name)"
                            nullable>
                        <label class="custom-file-label" id="bukti_prestasi_label" for="bukti_prestasi">Pilih
                            File</label>
                    </div>
                </div>
            </div>

            {{-- Surat Tugas Prestasi --}}
            <label for="surat_tugas_prestasi" class="col-form-label mt-2">Surat Tugas Prestasi <small>(Maksimal
                    2MB)</small>
            </label>
            <div class="custom-validation">
                <div class="input-group mt-1">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="surat_tugas_prestasi"
                            accept=".png, .jpg, .jpeg"
                            onchange="$('#surat_tugas_prestasi_label').text(this.files[0].name)" nullable>
                        <label class="custom-file-label" id="surat_tugas_prestasi_label"
                            for="surat_tugas_prestasi">Pilih
                            File</label>
                    </div>
                </div>
            </div>

            {{-- Status Verifikasi --}}
            <label for="status_verifikasi" class="col-form-label mt-2">Status Verifikasi <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="status_verifikasi" id="status_verifikasi" class="form-control" required>
                    <option value="Terverifikasi">Terverifikasi</option>
                    <option value="Valid">Valid</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
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

    .select2-container--default .select2-selection--single {
        border: none;
        margin-top: 9px;
        margin-left: 9px;
    }

    .select2-container {
        min-height: 45px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
        z-index: 9999;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        margin-top: 9px;
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
</style>

{{-- Memanggil Select2 single select --}}
<script>
    $(document).ready(function() {
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).parent().parent()
            });
        });
    });
</script>

<!-- Memanggil Custom validation untuk Form -->
<script>
    // Fungsi untuk validasi ekstensi gambar
    $.validator.addMethod("validImageExtension", function(value, element) {
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
    $.validator.addMethod("afterOrEqual", function(value, element, param) {
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
            mahasiswa_id: {
                required: true,
            },
            lomba_id: {
                required: true,
            },
            kategori_id: {
                required: true,
            },
            jenis_prestasi: {
                required: true,
            },
            dosen_id: {
                required: true,
            },
            periode_id: {
                required: true,
            },
            tanggal_prestasi: {
                required: true,
            },
            juara_prestasi: {
                required: true,
            },
            img_kegiatan: {
                // Menggunakan custom validasi untuk gambar
                validImageExtension: true,
            },
            bukti_prestasi: {
                validPdfExtension: true,
            },
            surat_tugas_prestasi: {
                validPdfExtension: true,
            },
            status_verifikasi: {
                required: true,
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            mahasiswa_id: {
                required: "Mahasiswa wajib diisi",
            },
            lomba_id: {
                required: "Lomba wajib diisi",
            },
            kategori_id: {
                required: "Kategori wajib diisi",
            },
            jenis_prestasi: {
                required: "Jenis Prestasi wajib diisi",
            },
            dosen_id: {
                required: "Dosen wajib diisi",
            },
            periode_id: {
                required: "Periode wajib diisi",
            },
            tanggal_prestasi: {
                required: "Tanggal Prestasi wajib diisi",
            },
            juara_prestasi: {
                required: "Juara Prestasi wajib diisi",
            },
            img_kegiatan: {
                validImageExtension: "Ekstensi file harus .png, .jpg, .jpeg",
            },
            bukti_prestasi: {
                validPdfExtension: "Ekstensi file harus.pdf",
            },
            surat_tugas_prestasi: {
                validPdfExtension: "Ekstensi file harus.pdf",
            },
            status_verifikasi: {
                required: "Status Verifikasi wajib diisi",
            }
        },

        function(response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function() {
                    // Tutup modal
                    $('#myModal').modal('hide');

                    // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                    $('#tabel-kelola-prestasi').DataTable().ajax.reload();
                });
            } else {
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
    );
</script>

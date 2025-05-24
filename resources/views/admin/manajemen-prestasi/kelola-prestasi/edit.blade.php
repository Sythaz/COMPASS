<form id="form-edit" method="POST"
    action="{{ url('admin/manajemen-prestasi/kelola-prestasi/' . $kelolaPrestasi->prestasi_id) }}"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            {{-- NIM dan Nama Mahasiswa --}}
            <label for="mahasiswa_id" class="col-form-label mt-2">Mahasiswa <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="mahasiswa_id" id="mahasiswa_id" class="form-control select2" required>
                    @foreach ($daftarMahasiswa as $m)
                        <option value="{{ $m->mahasiswa_id }}"
                            {{ old('mahasiswa_id', $kelolaPrestasi->mahasiswa->mahasiswa_id) == $m->mahasiswa_id ? 'selected' : '' }}>
                            {{ $m->nim_mahasiswa }} - {{ $m->nama_mahasiswa }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Lomba --}}
            <label for="lomba_id" class="col-form-label mt-2">Nama Lomba <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="lomba_id" id="lomba_id" class="form-control select2" required>
                    @foreach ($daftarLomba as $lomba)
                        <option value="{{ $lomba->lomba_id }}"
                            {{ old('lomba_id', $kelolaPrestasi->lomba->lomba_id ?? '') == $lomba->lomba_id ? 'selected' : '' }}>
                            {{ $lomba->nama_lomba }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kategori --}}
            <label for="kategori_id" class="col-form-label mt-2">Kategori
                <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="kategori_id" id="kategori_id" class="form-control select2" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->kategori_id }}"
                            {{ old('kategori_id', $kelolaPrestasi->kategori->kategori_id ?? '') == $kategori->kategori_id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jenis Prestasi --}}
            <label for="jenis_prestasi" class="col-form-label mt-2">Jenis Prestasi <span
                    class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="jenis_prestasi" id="jenis_prestasi" class="form-control" required>
                    <option value="">-- Pilih Jenis Prestasi --</option>
                    <option value="Individu"
                        {{ old('jenis_prestasi', $kelolaPrestasi->jenis_prestasi) == 'Individu' ? 'selected' : '' }}>
                        Individu
                    </option>
                    <option value="Tim"
                        {{ old('jenis_prestasi', $kelolaPrestasi->jenis_prestasi) == 'Tim' ? 'selected' : '' }}>
                        Tim
                    </option>
                </select>
            </div>

            {{-- Dosen Pembimbing --}}
            <label for="dosen_id" class="col-form-label mt-2">Dosen Pembimbing <span
                    class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="dosen_id" id="dosen_id" class="form-control select2" required>
                    <option value="">-- Pilih Dosen Pembimbing --</option>
                    @foreach ($daftarDosen as $dosen)
                        <option value="{{ $dosen->dosen_id }}"
                            {{ old('dosen_id', $kelolaPrestasi->dosen->dosen_id) == $dosen->dosen_id ? 'selected' : '' }}>
                            {{ $dosen->nama_dosen }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Periode --}}
            <label for="periode_id" class="col-form-label mt-2">Periode <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="periode_id" id="periode_id" class="form-control select2" required>
                    <option value="">-- Pilih Periode --</option>
                    @foreach ($daftarPeriode as $periode)
                        <option value="{{ $periode->periode_id }}"
                            {{ old('periode_id', $kelolaPrestasi->periode->periode_id ?? '') == $periode->periode_id ? 'selected' : '' }}>
                            {{ $periode->semester_periode }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal Prestasi --}}
            <label for="tanggal_prestasi" class="col-form-label mt-2">Tanggal Prestasi <span
                    class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="date" class="form-control" name="tanggal_prestasi"
                    value="{{ old('tanggal_prestasi', $kelolaPrestasi->tanggal_prestasi) }}" required>
            </div>

            {{-- Juara Prestasi --}}
            <label for="juara_prestasi" class="col-form-label mt-2">Juara Prestasi <span
                    class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="juara_prestasi"
                    value="{{ old('juara_prestasi', $kelolaPrestasi->juara_prestasi) }}" required>
            </div>

            {{-- Gambar Kegiatan --}}
            <label for="img_kegiatan" class="col-form-label mt-2">Gambar Kegiatan</label>
            <div class="custom-validation">
                @if (
                    !is_null($kelolaPrestasi->img_kegiatan) &&
                        file_exists(public_path('storage/prestasi/img/' . $kelolaPrestasi->img_kegiatan)))
                    <a href="{{ asset('storage/prestasi/img/' . $kelolaPrestasi->img_kegiatan) }}"
                        data-lightbox="kegiatan" data-title="Gambar Kegiatan">
                        <img src="{{ asset('storage/prestasi/img/' . $kelolaPrestasi->img_kegiatan) }}" width="100"
                            class="d-block mx-auto img-thumbnail" alt="Gambar Kegiatan" style="cursor: zoom-in;" />
                    </a>
                @else
                    <p class="text-center text-muted">Gambar tidak ada atau belum di upload</p>
                @endif
                <div class="input-group mt-1">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img_kegiatan" accept=".png, .jpg, .jpeg"
                            onchange="$('#img_kegiatan_label').text(this.files[0].name)" nullable>
                        <label class="custom-file-label" id="img_kegiatan_label" for="img_kegiatan">Pilih
                            File</label>
                    </div>
                </div>
            </div>

            {{-- Bukti Prestasi --}}
            <label for="bukti_prestasi" class="col-form-label mt-2">Bukti Prestasi</label>
            <div class="custom-validation">
                @if (
                    !is_null($kelolaPrestasi->bukti_prestasi) &&
                        file_exists(public_path('storage/prestasi/bukti/' . $kelolaPrestasi->bukti_prestasi)))
                    <div class="text-center">
                        <a class="btn btn-primary"
                            href="{{ asset('storage/prestasi/bukti/' . $kelolaPrestasi->bukti_prestasi) }}"
                            target="_blank">
                            <i class="fa fa-file-alt"></i>
                            <span class="ml-1">Lihat Bukti</span>
                        </a>
                    </div>
                @else
                    <p class="text-center text-muted">Bukti tidak ada atau belum di upload</p>
                @endif
                <div class="input-group mt-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="bukti_prestasi" accept=".pdf"
                            onchange="$('#bukti_prestasi_label').text(this.files[0].name)" nullable>
                        <label class="custom-file-label" id="bukti_prestasi_label" for="bukti_prestasi">Pilih
                            File</label>
                    </div>
                </div>
            </div>

            {{-- Surat Tugas Prestasi --}}
            <label for="surat_tugas_prestasi" class="col-form-label mt-2">Surat Tugas Prestasi</label>
            <div class="custom-validation">
                @if (
                    !is_null($kelolaPrestasi->surat_tugas_prestasi) &&
                        file_exists(public_path('storage/prestasi/surat/' . $kelolaPrestasi->surat_tugas_prestasi)))
                    <div class="text-center">
                        <a class="btn btn-primary"
                            href="{{ asset('storage/prestasi/surat/' . $kelolaPrestasi->surat_tugas_prestasi) }}"
                            target="_blank">
                            <i class="fa fa-file-alt"></i>
                            <span class="ml-1">Lihat Surat Tugas</span>
                        </a>
                    </div>
                @else
                    <p class="text-center text-muted">Surat Tugas tidak ada atau belum di upload</p>
                @endif
                <div class="input-group mt-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="surat_tugas_prestasi" accept=".pdf"
                            onchange="$('#surat_tugas_prestasi_label').text(this.files[0].name)" nullable>
                        <label class="custom-file-label" id="surat_tugas_prestasi_label"
                            for="surat_tugas_prestasi">Pilih File</label>
                    </div>
                </div>
            </div>

            {{-- Status Prestasi --}}
            <label for="status_verifikasi" class="col-form-label mt-2">Status Prestasi <span
                    class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="status_verifikasi" id="status_verifikasi" class="form-control" required>
                    <option value="Terverifikasi"
                        {{ old('status_verifikasi', $kelolaPrestasi->status_verifikasi) == 'Terverifikasi' ? 'selected' : '' }}>
                        Terverifikasi</option>
                    <option value="Menunggu"
                        {{ old('status_verifikasi', $kelolaPrestasi->status_verifikasi) == 'Menunggu' ? 'selected' : '' }}>
                        Menunggu</option>
                    <option value="Ditolak"
                        {{ old('status_verifikasi', $kelolaPrestasi->status_verifikasi) == 'Ditolak' ? 'selected' : '' }}>
                        Ditolak</option>
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

<div id="error-messages"></div>

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

<script>
    // Memanggil Select2 single select
    $(document).ready(function() {
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).parent().parent()
            });
        });
    });
</script>

{{-- Memanggil Custom validation untuk Form --}}
<script>
    $.validator.addMethod("validPdfExtension", function(value, element) {
        // Jika tidak ada file diupload → anggap valid
        if (!element.files || element.files.length === 0) {
            return true;
        }

        // Cek ekstensi file
        var fileName = element.files[0].name;
        var allowedExtensions = /(\.|\/)(pdf)$/i;
        return allowedExtensions.test(fileName);
    }, "Ekstensi file harus .pdf");

    $.validator.addMethod("validImageExtension", function(value, element) {
        // Jika tidak ada file diupload → anggap valid
        if (!element.files || element.files.length === 0) {
            return true;
        }

        // Cek ekstensi file
        var fileName = element.files[0].name;
        var allowedExtensions = /(\.|\/)(png|jpe?g)$/i;
        return allowedExtensions.test(fileName);
    }, "Ekstensi file harus .png, .jpg, atau .jpeg");

    // Validasi Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal (afterOrEqual)
    $.validator.addMethod("afterOrEqual", function(value, element, param) {
        if (!value || !$(param).val()) return true;
        var startDate = new Date($(param).val());
        var endDate = new Date(value);
        return endDate >= startDate;
    }, "Tanggal akhir harus lebih besar atau sama dengan tanggal awal.");

    // Validasi Ukuran File maks 2MB
    $.validator.addMethod("maxFileSize", function(value, element, param) {
        if (!element.files || element.files.length === 0) {
            return true;
        }

        var fileSize = element.files[0].size;
        return fileSize <= 2 * 1024 * 1024;
    }, "Ukuran file maksimal 2MB");

    customFormValidation(
        // Validasi form
        // ID form untuk validasi
        "#form-edit", {
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
                maxFileSize: 2048 // 2MB
            },
            bukti_prestasi: {
                validPdfExtension: true,
                maxFileSize: 2048 // 2MB
            },
            surat_tugas_prestasi: {
                validPdfExtension: true,
                maxFileSize: 2048 // 2MB
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
                maxFileSize: 'Ukuran file maksimal 2MB'
            },
            bukti_prestasi: {
                validPdfExtension: "Ekstensi file harus.pdf",
                maxFileSize: 'Ukuran file maksimal 2MB'
            },
            surat_tugas_prestasi: {
                validPdfExtension: "Ekstensi file harus.pdf",
                maxFileSize: 'Ukuran file maksimal 2MB'
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

{{-- Style untuk Lightbox untuk membesarkan gambar --}}
<style>
    .lightbox .lb-data {
        top: 0;
        bottom: auto;
        background: rgba(0, 0, 0, 0.7);
    }

    .lightbox .lb-data .lb-caption {
        color: #fff;
        padding: 10px;
        font-size: 16px;
        text-align: center;
    }

    .lightbox .lb-close {
        top: 10px;
        right: 10px;
    }
</style>

{{-- Library Lightbox untuk membesarkan gambar --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<form id="form-create" method="POST" action="{{ url('admin/manajemen-lomba/kelola-lomba/store') }}"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <div class="row">
                <!-- Nama Lomba -->
                <div class="col-md-6">
                    <label for="nama_lomba" class="col-form-label">Nama Lomba <span class="text-danger"
                            style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <input type="text" class="form-control" name="nama_lomba" required>
                    </div>
                </div>
                <!-- Penyelenggara Lomba -->
                <div class="col-md-6">
                    <label for="penyelenggara_lomba" class="col-form-label">Penyelenggara Lomba <span
                            class="text-danger" style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <input type="text" class="form-control" name="penyelenggara_lomba" required>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Lomba -->
            <div class="row mt-2">
                <div class="col-12">
                    <label for="deskripsi_lomba" class="col-form-label">Deskripsi Lomba <span class="text-danger"
                            style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <textarea name="deskripsi_lomba" id="deskripsi_lomba" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Kategori Lomba -->
                <div class="col-md-6">
                    <label for="kategori_id" class="col-form-label">Kategori Lomba <span class="text-danger"
                            style="color: red;">*</span></label>
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
                <!-- Tingkat Lomba -->
                <div class="col-md-6">
                    <label for="tingkat_lomba_id" class="col-form-label">Tingkat Lomba <span class="text-danger"
                            style="color: red;">*</span></label>
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
            
            <div class="row mt-2">
                <!-- Tipe Lomba -->
                <div class="col-md-6">
                    <label for="tipe_lomba" class="col-form-label">Tipe Lomba <span class="text-danger"
                            style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <select name="tipe_lomba" id="tipe_lomba" class="form-control" required>
                            <option value="">-- Pilih Tipe Lomba --</option>
                            <option value="Tim">Tim</option>
                            <option value="Individu">Individu</option>
                        </select>
                    </div>
                </div>
                <!-- Biaya Pendaftaran -->
                <div class="col-md-6">
                    <label for="biaya_lomba" class="col-form-label">Biaya Pendaftaran <span class="text-danger"
                            style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <select name="biaya_lomba" id="biaya_lomba" class="form-control" required>
                            <option value="">-- Pilih Biaya Pendaftaran --</option>
                            <option value="Tanpa Biaya">Tanpa Biaya</option>
                            <option value="Dengan Biaya">Dengan Biaya</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Tanggal Registrasi Lomba -->
                <div class="col-md-6">
                    <label for="awal_registrasi_lomba" class="col-form-label">Awal Registrasi Lomba <span
                            class="text-danger" style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <input type="date" class="form-control" name="awal_registrasi_lomba"
                            id="awal_registrasi_lomba" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="akhir_registrasi_lomba" class="col-form-label">Akhir Registrasi Lomba <span
                            class="text-danger" style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <input type="date" class="form-control" name="akhir_registrasi_lomba"
                            id="akhir_registrasi_lomba" required>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Lokasi Lomba -->
                <div class="col-md-6">
                    <label for="lokasi_lomba" class="col-form-label">Lokasi Lomba <span class="text-danger">*</span></label>
                    <div class="input-group custom-validation">
                        <select name="lokasi_lomba" id="lokasi_lomba" class="form-control" required>
                            <option value="">-- Pilih Lokasi Lomba --</option>
                            <option value="Online">Online</option>
                            <option value="Offline Dalam Kota">Offline Dalam Kota</option>
                            <option value="Offline Luar Kota">Offline Luar Kota</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                </div>

                <!-- Status Lomba -->
                <div class="col-md-6">
                    <label for="status_verifikasi" class="col-form-label">Status Lomba <span class="text-danger"
                            style="color: red;">*</span></label>
                    <div class="custom-validation">
                        <select name="status_verifikasi" id="status_verifikasi" class="form-control" required>
                            <option value="Terverifikasi">Terverifikasi</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Link Pendaftaran Lomba -->
            <div class="row mt-2">
                <div class="col-12">
                    <label for="link_pendaftaran_lomba" class="col-form-label">Link Pendaftaran Lomba <span
                            class="text-danger" style="color: red;">*</span></label>
                    <div class="input-group custom-validation">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-link"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="link_pendaftaran_lomba" value="https://"
                            required>
                    </div>
                </div>
            </div>

            <!-- Gambar Lomba -->
            <div class="row mt-2">
                <div class="col-12">
                    <label for="img_lomba" class="col-form-label">Gambar Poster Lomba <small>(Maksimal
                            2MB)</small></label>
                    <div class="custom-validation">
                        <div class="input-group mt-1">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="img_lomba"
                                    accept=".png, .jpg, .jpeg"
                                    onchange="$('#img_lomba_label').text(this.files[0].name)" nullable>
                                <label class="custom-file-label" id="img_lomba_label" for="img_lomba">Pilih
                                    File</label>
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

{{-- Memanggil Custom CSS Select2 --}}
<link href="{{ asset('css-custom/select2-custom.css') }}" rel="stylesheet">

<script>
    // Memanggil Select2 multiselect
    $(document).ready(function() {
        $('.multiselect-dropdown-kategori').select2({
            width: '100%',
            placeholder: 'Belum ada kategori terpilih',
        });
    });

    // Memanggil Custom validation untuk Form
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
            nama_lomba: {
                required: true,
            },
            deskripsi_lomba: {
                required: true,
            },
            'kategori_id[]': {
                required: true,
            },
            tingkat_lomba_id: {
                required: true,
            },
            tipe_lomba: {
                required: true,
            },
            biaya_lomba: {
                required: true,
            },
            lokasi_lomba: {
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
            'kategori_id[]': {
                required: "Kategori wajib diisi",
            },
            tingkat_lomba_id: {
                required: "Tingkat Lomba wajib diisi",
            },
            tipe_lomba: {
                required: "Tipe Lomba wajib diisi",
            },
            biaya_lomba: {
                required: "Biaya Pendaftaran wajib diisi",
            },
            lokasi_lomba: {
                required: "Lokasi Lomba wajib diisi",
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
                    $('#tabel-kelola-lomba').DataTable().ajax.reload();
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

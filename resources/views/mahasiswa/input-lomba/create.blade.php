<form id="form-create-lomba" method="POST" action="{{ url('mahasiswa/input-lomba') }}" enctype="multipart/form-data">
    @csrf

    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-plus mr-2"></i>Tambah Lomba
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">

        {{-- Kategori Lomba --}}
        <div class="form-group">
            <label for="kategori_id" class="col-form-label">
                Kategori Lomba <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <select name="kategori_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(\App\Models\KategoriModel::all() as $kat)
                        <option value="{{ $kat->kategori_id }}">
                            {{ $kat->kategori_nama }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-kategori_id"></span>
            </div>
        </div>

        {{-- Tingkat Lomba --}}
        <div class="form-group">
            <label for="tingkat_lomba_id" class="col-form-label">
                Tingkat Lomba <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <select name="tingkat_lomba_id" class="form-control" required>
                    <option value="">-- Pilih Tingkat --</option>
                    @foreach($tingkat as $t)
                        <option value="{{ $t->tingkat_lomba_id }}">
                            {{ $t->nama_tingkat }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-tingkat_lomba_id"></span>
            </div>
        </div>

        {{-- Nama Lomba --}}
        <div class="form-group">
            <label for="nama_lomba" class="col-form-label">
                Nama Lomba <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <input type="text" name="nama_lomba" class="form-control" required>
                <span class="error-text text-danger" id="error-nama_lomba"></span>
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label for="deskripsi_lomba" class="col-form-label">
                Deskripsi
            </label>
            <div class="custom-validation">
                <textarea name="deskripsi_lomba" class="form-control" rows="3"></textarea>
                <span class="error-text text-danger" id="error-deskripsi_lomba"></span>
            </div>
        </div>

        {{-- Penyelenggara --}}
        <div class="form-group">
            <label for="penyelenggara_lomba" class="col-form-label">
                Penyelenggara <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <input type="text" name="penyelenggara_lomba" class="form-control" required>
                <span class="error-text text-danger" id="error-penyelenggara_lomba"></span>
            </div>
        </div>

        {{-- Periode Registrasi --}}
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="awal_registrasi_lomba" class="col-form-label">
                    Awal Registrasi <span class="text-danger">*</span>
                </label>
                <div class="custom-validation">
                    <input type="date" name="awal_registrasi_lomba" class="form-control" required>
                    <span class="error-text text-danger" id="error-awal_registrasi_lomba"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="akhir_registrasi_lomba" class="col-form-label">
                    Akhir Registrasi <span class="text-danger">*</span>
                </label>
                <div class="custom-validation">
                    <input type="date" name="akhir_registrasi_lomba" class="form-control" required>
                    <span class="error-text text-danger" id="error-akhir_registrasi_lomba"></span>
                </div>
            </div>
        </div>

        {{-- Link Pendaftaran --}}
        <div class="form-group">
            <label for="link_pendaftaran_lomba" class="col-form-label">
                Link Pendaftaran
            </label>
            <div class="custom-validation">
                <input type="url" name="link_pendaftaran_lomba" class="form-control" placeholder="https://">
                <span class="error-text text-danger" id="error-link_pendaftaran_lomba"></span>
            </div>
        </div>

        {{-- Upload Gambar --}}
        <div class="form-group">
            <label for="img_lomba" class="col-form-label">
                Gambar Poster
            </label>
            <div class="custom-validation">
                <input type="file" name="img_lomba" class="form-control-file" accept="image/*">
                <span class="error-text text-danger" id="error-img_lomba"></span>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<script src="{{ asset('js-custom/form-validation.js') }}"></script>
<script>
    function getModalInstance() {
        const el = document.getElementById('myModal');
        return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
    }

    customFormValidation(
        "#form-create-lomba",
        {
            kategori_id: { required: true },
            tingkat_lomba_id: { required: true },
            nama_lomba: { required: true },
            penyelenggara_lomba: { required: true },
            awal_registrasi_lomba: { required: true },
            akhir_registrasi_lomba: {
                required: true,
                greaterThanField: 'awal_registrasi_lomba'
            },
            // link_pendaftaran_lomba: { url: true },  // jika ingin validasi URL
            // img_lomba: { extension: "jpg|jpeg|png|gif" }
        },
        {
            kategori_id: { required: "Kategori wajib dipilih" },
            tingkat_lomba_id: { required: "Tingkat wajib dipilih" },
            nama_lomba: { required: "Nama lomba wajib diisi" },
            penyelenggara_lomba: { required: "Penyelenggara wajib diisi" },
            awal_registrasi_lomba: { required: "Awal registrasi wajib diisi" },
            akhir_registrasi_lomba: {
                required: "Akhir registrasi wajib diisi",
                greaterThanField: "Akhir harus sama atau setelah awal registrasi"
            },
            link_pendaftaran_lomba: { url: "Format URL tidak valid" },
            img_lomba: { extension: "Format gambar tidak valid (jpg/png/gif)" }
        },
        function(response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(() => {
                    getModalInstance().hide();
                    $('#tabel-lomba').DataTable().ajax.reload();
                });
            } else {
                $('.error-text').text('');
                $.each(response.msgField || {}, function(field, msgArr) {
                    $('#error-' + field).text(msgArr[0]);
                });
                Swal.fire('Gagal', response.message, 'error');
            }
        }
    );
</script>

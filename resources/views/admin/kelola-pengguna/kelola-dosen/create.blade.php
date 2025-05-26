<form id="formTambahDosen" method="POST" action="{{ url('admin/kelola-pengguna/dosen/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Dosen</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{-- NIP Dosen --}}
    <div class="modal-body">
        <div class="form-group">
            <label for="nip_dosen" class="col-form-label">NIP <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" name="nip_dosen" id="nip_dosen" class="form-control" required>
                <span class="error-text text-danger" id="error-nip_dosen"></span>
            </div>
        </div>

        {{-- Nama Dosen --}}
        <div class="form-group">
            <label for="nama_dosen" class="col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" name="nama_dosen" id="nama_dosen" class="form-control" required>
                <span class="error-text text-danger" id="error-nama_dosen"></span>
            </div>
        </div>

        {{-- Jenis Kelamin Dosen --}}
        <div class="form-group">
            <label for="kelamin" class="col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="kelamin" id="kelamin" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('kelamin', $dosen->kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki
                    </option>
                    <option value="P" {{ old('kelamin', $dosen->kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan
                    </option>
                </select>
                <span class="error-text text-danger" id="error-kelamin"></span>
            </div>
        </div>

        {{-- Email Dosen (Boleh dikosongi) --}}
        <div class="form-group">
            <label for="email" class="col-form-label">Email <small class="text-muted">(Boleh
                    dikosongkan)</small></label>
            <div class="custom-validation">
                <input type="email" name="email" id="email" class="form-control"
                    placeholder="Contoh: user@example.com (boleh dikosongkan)" value="{{ old('email') }}">
                <span class="error-text text-danger" id="error-email"></span>
            </div>
        </div>

        {{-- No. Handphone Dosen (Boleh dikosongi) --}}
        <div class="form-group">
            <label for="no_hp" class="col-form-label">No. Handphone <small class="text-muted">(Boleh
                    dikosongkan)</small></label>
            <div class="custom-validation">
                <input type="text" name="no_hp" id="no_hp" class="form-control"
                    placeholder="Contoh: 08123456789 (boleh dikosongkan)" value="{{ old('no_hp') }}">
                <span class="error-text text-danger" id="error-no_hp"></span>
            </div>
        </div>

        {{-- Alamat Dosen (Boleh dikosongi) --}}
        <div class="form-group">
            <label for="alamat" class="col-form-label">Alamat <small class="text-muted">(Boleh
                    dikosongkan)</small></label>
            <div class="custom-validation">
                <textarea name="alamat" id="alamat" class="form-control" rows="2"
                    placeholder="Contoh: Jl. Contoh No. 1 (boleh dikosongkan)">{{ old('alamat') }}</textarea>
                <span class="error-text text-danger" id="error-alamat"></span>
            </div>
        </div>

        {{-- Pilih Bidang --}}
        <div class="form-group">
            <label for="kategori_id" class="col-form-label">Pilih Bidang <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="kategori_id" id="kategori_id" class="form-control" required>
                    <option value="">-- Pilih Bidang --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->kategori_id }}" {{ (old('kategori_id', $dosen->kategori_id ?? '') == $k->kategori_id) ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-kategori_id"></span>
            </div>
        </div>

        <input type="hidden" name="role" value="dosen">

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<!-- Pastikan js-custom/form-validation.js sudah dimuat -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    // Fungsi validasi dan submit form
    customFormValidation(
        "#formTambahDosen",
        {
            nip_dosen: { required: true },
            nama_dosen: { required: true },
            kategori_id: { required: true },
            // username: { required: true },
            // password: { required: true, minlength: 6 },
        },
        {
            nip_dosen: { required: "NIP wajib diisi" },
            nama_dosen: { required: "Nama wajib diisi" },
            kategori_id: { required: "Kategori wajib dipilih" },
            username: { required: "Username wajib diisi" },
            password: {
                required: "Password wajib diisi",
                minlength: "Password minimal 6 karakter"
            }
        },
        function (response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function () {
                    getModalInstance().hide();
                    $('#tabel-dosen').DataTable().ajax.reload();
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

    function getModalInstance() {
        const modalEl = document.getElementById('myModal');
        return bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    }
</script>
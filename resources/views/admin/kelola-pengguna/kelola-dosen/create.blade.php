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
        <label for="kategori_id" class="col-form-label mt-2">Bidang Dosen<span class="text-danger"
                style="color: red;">*</span></label>
        <div class="custom-validation">
            <select name="kategori_id[]" id="kategori_id" class="form-control multiselect-dropdown-kategori"
                multiple="multiple" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->kategori_id }}">
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
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

<script src="{{ asset('js-custom/form-validation.js') }}"></script>

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
</style>

<script>
    // Memanggil Select2 multiselect
    $(document).ready(function () {
        $('.multiselect-dropdown-kategori').select2({
            width: '100%',
            placeholder: 'Belum ada kategori terpilih',
        });
    });
</script>

<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    // Fungsi validasi dan submit form
    customFormValidation(
        "#formTambahDosen",
        {
            nip_dosen: { required: true },
            nama_dosen: { required: true },
            kelamin: { required: true },
            'kategori_id[]': { required: true },
        },
        {
            nip_dosen: { required: "NIP wajib diisi" },
            nama_dosen: { required: "Nama wajib diisi" },
            kelamin: { required: "Jenis Kelamin wajib dipilih" },
            'kategori_id[]': { required: "Pilih Minimal 1 Bidang" },
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
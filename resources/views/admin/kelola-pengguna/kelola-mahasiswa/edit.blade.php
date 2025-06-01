<form id="form-edit-mahasiswa" method="POST"
    action="{{ url('admin/kelola-pengguna/mahasiswa/' . $mahasiswa->mahasiswa_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit me-2"></i>Edit Mahasiswa</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">

        <div class="form-group mb-3">
            <label for="nim_mahasiswa" class="col-form-label">NIM <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nim_mahasiswa" id="nim_mahasiswa"
                value="{{ old('nim_mahasiswa', $mahasiswa->nim_mahasiswa ?? '') ?? $mahasiswa->nim_mahasiswa }}"
                required>
        </div>

        <div class="form-group mb-3">
            <label for="nama_mahasiswa" class="col-form-label">Nama <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_mahasiswa" id="nama_mahasiswa"
                value="{{ old('nama_mahasiswa', $mahasiswa->nama_mahasiswa) }}" required>
        </div>

        <div class="form-group">
            <label for="kelamin" class="col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="kelamin" id="kelamin" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('kelamin', $mahasiswa->kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki
                    </option>
                    <option value="P" {{ old('kelamin', $mahasiswa->kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan
                    </option>
                </select>
                <span class="error-text text-danger" id="error-kelamin"></span>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="prodi_id" class="col-form-label">Program Studi <span class="text-danger">*</span></label>
            <select name="prodi_id" id="prodi_id" class="form-control" required>
                <option value="">-- Pilih Program Studi --</option>
                @foreach ($list_prodi as $prodi)
                    <option value="{{ $prodi->prodi_id }}" {{ old('prodi_id', $mahasiswa->prodi_id ?? '') == $prodi->prodi_id ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="periode_id" class="col-form-label">Periode <span class="text-danger">*</span></label>
            <select name="periode_id" id="periode_id" class="form-control" required>
                <option value="">-- Pilih Periode --</option>
                @foreach ($list_periode as $periode)
                    <option value="{{ $periode->periode_id }}" {{ old('periode_id', $mahasiswa->periode_id ?? '') == $periode->periode_id ? 'selected' : '' }}>
                        {{ $periode->semester_periode }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="kategori_id" class="col-form-label mt-2">Bidang Minat Mahasiswa<span
                    class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="kategori_id[]" id="kategori_id" class="form-control multiselect-dropdown-kategori"
                    multiple="multiple">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->kategori_id }}" {{ in_array($kategori->kategori_id, $mahasiswa->kategoris->pluck('kategori_id')->toArray()) ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="angkatan" class="col-form-label">Angkatan</label>
            <input type="number" class="form-control" name="angkatan" id="angkatan"
                value="{{ old('angkatan', $mahasiswa->angkatan ?? '') }}">
        </div>

        <div class="form-group mb-3">
            <label for="email" class="col-form-label">Email
                <small class="text-muted">(boleh dikosongkan)</small>
            </label>
            <input type="email" class="form-control" name="email" id="email"
                value="{{ old('email', $mahasiswa->email) }}" placeholder="contoh: email@example.com">
        </div>

        <div class="form-group mb-3">
            <label for="alamat" class="col-form-label">Alamat
                <small class="text-muted">(boleh dikosongkan)</small>
            </label>
            <input type="text" class="form-control" name="alamat" id="alamat"
                value="{{ old('alamat', $mahasiswa->alamat) }}" placeholder="Alamat tempat tinggal">
        </div>

        <div class="form-group mb-3">
            <label for="no_hp" class="col-form-label">No Handphone
                <small class="text-muted">(boleh dikosongkan)</small>
            </label>
            <input type="text" class="form-control" name="no_hp" id="no_hp"
                value="{{ old('no_hp', $mahasiswa->no_hp) }}" placeholder="08xxxxxxxxxx">
        </div>


        <div class="form-group mb-3">
            <label for="username" class="col-form-label">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="username" id="username"
                value="{{ $mahasiswa->users->username ?? '' }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="password" class="col-form-label">
                Password <small>(kosongkan jika tidak ingin diubah)</small>
            </label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password baru">
        </div>

        <div class="form-group mb-3">
            <label for="phrase" class="col-form-label">Phrase
                <small>(kosongkan jika tidak ingin diubah)</small></label>
            <input type="text" class="form-control" name="phrase" id="phrase"
                value="{{ old('phrase', $mahasiswa->users->phrase ?? $mahasiswa->users->username) }}">
        </div>

        <input type="hidden" name="role" value="mahasiswa">

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

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

<script>
    // Submit form edit mahasiswa dengan AJAX
    $(document).on('submit', '#form-edit-mahasiswa', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function (response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        let modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            let modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
                        $('#tabel-mahasiswa').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Update gagal.', 'error');
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let message = '';
                if (errors) {
                    $.each(errors, function (key, val) {
                        message += val + '<br>';
                    });
                } else {
                    message = 'Terjadi kesalahan.';
                }
                Swal.fire('Error', message, 'error');
            }
        });
    });
</script>
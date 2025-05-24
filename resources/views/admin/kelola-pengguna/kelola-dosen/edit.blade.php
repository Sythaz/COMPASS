<form id="form-edit-dosen" method="POST" action="{{ url('admin/kelola-pengguna/dosen/' . $dosen->dosen_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Dosen</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        {{-- NIP Dosen --}}
        <div class="form-group">
            <label for="nip_dosen" class="col-form-label">NIP <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nip_dosen" value="{{ $dosen->nip_dosen }}" required>
            </div>
        </div>
        {{-- Nama Dosen --}}
        <div class="form-group">
            <label for="nama_dosen" class="col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nama_dosen" value="{{ $dosen->nama_dosen }}" required>
            </div>
        </div>
        {{-- Jenis Kelamin --}}
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
        {{-- Email Dosen --}}
        <div class="form-group mb-3">
            <label for="email" class="col-form-label">Email
                <small class="text-muted">(boleh dikosongkan)</small>
            </label>
            <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $dosen->email) }}"
                placeholder="contoh: email@example.com">
        </div>
        {{-- No Handphone Dosen --}}
        <div class="form-group mb-3">
            <label for="no_hp" class="col-form-label">No Handphone
                <small class="text-muted">(boleh dikosongkan)</small>
            </label>
            <input type="text" class="form-control" name="no_hp" id="no_hp" value="{{ old('no_hp', $dosen->no_hp) }}"
                placeholder="08xxxxxxxxxx">
        </div>
        {{-- Alamat Dosen --}}
        <div class="form-group mb-3">
            <label for="alamat" class="col-form-label">Alamat
                <small class="text-muted">(boleh dikosongkan)</small>
            </label>
            <input type="text" class="form-control" name="alamat" id="alamat"
                value="{{ old('alamat', $dosen->alamat) }}" placeholder="Alamat tempat tinggal">
        </div>
        {{-- Bidang Minat Bakat --}}
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
            </div>
        </div>
        {{-- Username --}}
        <div class="form-group">
            <label for="username" class="col-form-label">Username <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="username" value="{{ $dosen->users->username ?? '' }}"
                    required>
            </div>
        </div>
        {{-- Password --}}
        <div class="form-group">
            <label for="password" class="col-form-label">Password <small>(kosongkan jika tidak ingin
                    diubah)</small></label>
            <div class="custom-validation">
                <input type="password" class="form-control" name="password" placeholder="Password baru">
            </div>
        </div>
        {{-- Pharase (Lupa Password) --}}
        <div class="mb-3">
            <label for="password" class="form-label">Phrase <small> (Biarkan jika tidak ingin diubah)</small></label>
            <input type="text" class="form-control" name="phrase" id="phrase"
                value="{{ old('phrase', $dosen->users->phrase ?? $dosen->users->username) }}">
        </div>
        {{-- Role --}}
        <input type="hidden" name="role" value="dosen">
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

<script>
    // Submit form edit dosen dengan AJAX
    $(document).on('submit', '#form-edit-dosen', function (e) {
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
                        $('#tabel-dosen').DataTable().ajax.reload(null, false);
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
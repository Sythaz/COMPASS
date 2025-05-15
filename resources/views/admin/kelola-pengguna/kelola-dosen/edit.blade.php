<form id="form-edit-dosen" method="POST" action="{{ url('admin/kelola-pengguna/dosen/' . $dosen->dosen_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Dosen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="nip_dosen" class="form-label">NIP</label>
            <input type="text" class="form-control" name="nip_dosen" value="{{ $dosen->nip_dosen }}" required>
        </div>
        <div class="mb-3">
            <label for="nama_dosen" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama_dosen" value="{{ $dosen->nama_dosen }}" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="{{ $dosen->user->username ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                Password <small>(kosongkan jika tidak ingin diubah)</small>
            </label>
            <input type="password" class="form-control" name="password" placeholder="Password baru">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="dosen" {{ ($dosen->user->role ?? '') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="admin" {{ ($dosen->user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                <!-- Tambahkan role lain jika ada -->
            </select>
        </div>

        <div class="mb-3">
            <label for="bidang_dosen" class="form-label">Bidang</label>
            <input type="text" class="form-control" name="bidang_dosen" value="{{ $dosen->bidang_dosen ?? '' }}">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
                        $('#ajaxModal').modal('hide');
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
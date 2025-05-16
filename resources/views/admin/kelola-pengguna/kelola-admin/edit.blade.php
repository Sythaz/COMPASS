<form id="form-edit-admin" method="POST" action="{{ url('admin/kelola-pengguna/admin/' . $admin->admin_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="nip_admin" class="form-label">NIP</label>
            <input type="text" class="form-control" name="nip_admin" value="{{ $admin->nip_admin }}" required>
        </div>
        <div class="mb-3">
            <label for="nama_admin" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama_admin" value="{{ $admin->nama_admin }}" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="{{ $admin->users->username ?? '' }}"
                required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password <small>(kosongkan jika tidak ingin diubah)</small></label>
            <input type="password" class="form-control" name="password" placeholder="Password baru">
        </div>
        <div class="mb-3">
            <input type="hidden" name="role" value="admin">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    </div>
</form>

<script>
    // Submit form edit admin dengan AJAX
    $(document).on('submit', '#form-edit-admin', function (e) {
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
                        // tutup modal
                        $('#ajaxModal').modal('hide');
                        // reload tabel DataTables
                        $('#tabel-admin').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Update gagal.', 'error');
                }
            },
            error: function (xhr) {
                // Tampilkan pesan error validasi atau lainnya
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
<form id="form-edit-admin" method="POST" action="{{ url('admin/kelola-pengguna/admin/' . $admin->admin_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Admin</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="nip_admin" class="col-form-label">NIP <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nip_admin" value="{{ $admin->nip_admin }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="nama_admin" class="col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nama_admin" value="{{ $admin->nama_admin }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="username" class="col-form-label">Username <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="username" value="{{ $admin->users->username ?? '' }}"
                    required>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-form-label">Password <small>(kosongkan jika tidak ingin
                    diubah)</small></label>
            <div class="custom-validation">
                <input type="password" class="form-control" name="password" placeholder="Password baru">
            </div>
        </div>
        {{-- <div class="mb-3">
            <label for="password" class="form-label">Phrase <small>(kosongkan jika tidak ingin diubah)</small></label>
            <input type="password" class="form-control" name="password" placeholder="Password baru">
        </div> --}}
        <input type="hidden" name="role" value="admin">
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
                        let modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            let modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
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
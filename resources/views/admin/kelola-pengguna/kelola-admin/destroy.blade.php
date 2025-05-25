<form id="form-delete-admin" method="POST" action="{{ route('admin.history.destroy', $admin->admin_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-trash-alt mr-2"></i>Hapus Admin
        </h5>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk menghapus data ini?</strong>
            <hr class="my-2">
            Data User akan dihapus PERMANEN dari database, Data yang sudah dihapus tidak dapat dikembalikan.
            Pertimbangkan pilihan Anda!
        </div>

        <table class="table table-bordered mt-3 mb-0">
            <tr>
                <th style="width: 30%;">NIP</th>
                <td><code>{{ $admin->nip_admin }}</code></td>
            </tr>
            <tr>
                <th>Nama Admin</th>
                <td>{{ $admin->nama_admin }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $admin->users->username ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
            <i class="fas fa-trash-alt"></i> Hapus
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Batal
        </button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-delete-admin'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-delete-admin', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',   // Laravel expects POST with _method=DELETE
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        // Tutup modal bootstrap
                        var modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            let modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }

                        // Reload datatable, sesuaikan id tabel kamu
                        $('#tabel-admin').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Hapus gagal.', 'error');
                }
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan saat menghapus data.';
                // Optional: bisa tangani error detail dari response
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>
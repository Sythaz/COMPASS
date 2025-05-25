<form id="form-delete-admin" method="POST" action="{{ url('admin/kelola-pengguna/admin/' . $admin->admin_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-trash-alt mr-2"></i>Hapus Admin
        </h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk menghapus data ini?</strong>
            <hr class="my-2">
            Data User akan tetap tersimpan di database, hanya statusnya yang akan diubah dari "Aktif" menjadi
            "Nonaktif".
        </div>

        <table class="table table-bordered mt-3 mb-0">
            <tr>
                <th>NIP</th>
                <td class="text-start"><code>{{ $admin->nip_admin }}</code></td>
            </tr>
            <tr>
                <th>Nama Admin</th>
                <td class="text-start">{{ $admin->nama_admin }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td class="text-start">{{ $admin->email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td class="text-start">{{ $admin->users->username ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
            <i class="fas fa-trash-alt mr-2"></i>Hapus
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-delete-admin');
    $(document).on('submit', '#form-delete-admin', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            type: 'DELETE',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        var modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            let modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }

                        // Ganti id tabel sesuai nama tabel admin yang digunakan
                        $('#tabel-admin').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Hapus gagal.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    });
</script>
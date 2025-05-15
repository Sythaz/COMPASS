<form id="form-delete-admin" method="POST" action="{{ url('admin/kelola-pengguna/admin/' . $admin->admin_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-danger text-white">
        <h5 class="modal-title d-flex align-items-center gap-2">
            <i class="fas fa-trash-alt"></i> Konfirmasi Hapus
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <p class="fs-5">
            Apakah Anda yakin ingin menghapus admin
            <strong>{{ $admin->nama_admin }}</strong> (<code>{{ $admin->nip_admin }}</code>)?
        </p>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
            <i class="fas fa-trash"></i> Hapus
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Batal
        </button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-delete-admin');  // Hapus event handler lama (jika ada)
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
                        $('#ajaxModal').modal('hide');  // tutup modal delete
                        $('#tabel-admin').DataTable().ajax.reload(null, false); // reload datatable
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
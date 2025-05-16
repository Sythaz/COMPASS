<form id="form-delete-mahasiswa" method="POST" action="{{ url('admin/kelola-pengguna/mahasiswa/' . $mahasiswa->mahasiswa_id) }}">
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
            Apakah Anda yakin ingin menghapus mahasiswa
            <strong>{{ $mahasiswa->nama_mahasiswa }}</strong> (<code>{{ $mahasiswa->nim_mahasiswa }}</code>)?
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
    $(document).off('submit', '#form-delete-mahasiswa');  
    $(document).on('submit', '#form-delete-mahasiswa', function (e) {
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
                        $('#ajaxModal').modal('hide');  
                        $('#tabel-mahasiswa').DataTable().ajax.reload(null, false); 
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

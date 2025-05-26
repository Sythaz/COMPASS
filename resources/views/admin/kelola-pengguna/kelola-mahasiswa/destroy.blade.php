<form id="form-delete-mahasiswa" method="POST"
    action="{{ route('mahasiswa.history.destroy', $mahasiswa->mahasiswa_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-trash-alt mr-2"></i>Hapus Mahasiswa
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
                <th>NIM</th>
                <td class="text-start"><code>{{ $mahasiswa->nim_mahasiswa }}</code></td>
            </tr>
            <tr>
                <th>Nama Mahasiswa</th>
                <td class="text-start">{{ $mahasiswa->nama_mahasiswa }}</td>
            </tr>
            <tr>
                <th>Program Studi</th>
                <td class="text-start">{{ $mahasiswa->prodi->nama_prodi }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td class="text-start">{{ $mahasiswa->users->username ?? '-' }}</td>
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
    $(document).off('submit', '#form-delete-mahasiswa'); // Hapus event handler lama (jika ada)
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
                        // Tutup modal, pastikan id modal benar
                        var modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            let modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }

                        // Reload tabel DataTables (ganti sesuai id tabel mahasiswa)
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
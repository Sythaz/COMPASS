<form id="form-delete-dosen" method="POST" action="{{ url('admin/kelola-pengguna/dosen/' . $dosen->dosen_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-trash-alt mr-2"></i>Nonaktifkan Dosen
        </h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Konfirmasi Penonaktifan Data</strong>
            <hr class="my-2">
            Data pengguna akan tetap tersimpan di dalam sistem, namun statusnya akan diubah menjadi
            <strong>Nonaktif</strong>. Proses ini dapat dibatalkan kapan saja jika diperlukan.
        </div>

        <table class="table table-bordered mt-3 mb-0">
            <tr>
                <th>NIP</th>
                <td class="text-start"><code>{{ $dosen->nip_dosen }}</code></td>
            </tr>
            <tr>
                <th>Nama Dosen</th>
                <td class="text-start">{{ $dosen->nama_dosen }}</td>
            </tr>
            <tr>
                <th>Bidang</th>
                <td class="text-start">
                    {{ $dosen->kategoris->pluck('nama_kategori')->implode(', ') ?: '-' }}
                </td>
            </tr>
            <tr>
                <th>Username</th>
                <td class="text-start">{{ $dosen->users->username ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
            <i class="fas fa-lock mr-2"></i>Nonaktifkan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-delete-dosen'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-delete-dosen', function (e) {
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

                        // Reload tabel DataTables (ganti sesuai id tabel dosen)
                        $('#tabel-dosen').DataTable().ajax.reload(null, false);
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
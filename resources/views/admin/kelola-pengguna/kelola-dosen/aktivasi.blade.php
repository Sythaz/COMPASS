<form id="form-aktivasi-dosen" method="POST" action="{{ route('dosen.history.aktivasi', $dosen->dosen_id) }}">
    @csrf
    @method('PUT')

    <div class="modal-header bg-success rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-check-circle mr-2"></i>Aktifkan Dosen
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
    </div>

    <div class="modal-body">
        <div class="alert alert-success">
            <i class="fas fa-info-circle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk mengaktifkan dosen ini?</strong>
            <hr class="my-2">
            Data dosen akan diaktifkan dan dapat digunakan kembali dalam sistem.
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
                <td class="text-start">{{ $dosen->kategori->nama_kategori ?? '-' }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td class="text-start">{{ $dosen->users->username ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success d-flex align-items-center gap-2">
            <i class="fas fa-check-circle mr-2"></i>Aktifkan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<script>
    // Hapus event handler lama (jika ada) agar tidak dobel
    $(document).off('submit', '#form-aktivasi-dosen');

    // Pasang event handler baru untuk submit form aktivasi dosen
    $(document).on('submit', '#form-aktivasi-dosen', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            type: 'PUT',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        // Cari modal yang sedang aktif (class .modal.show)
                        var modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            // Dapatkan instance bootstrap modal dari elemen modal
                            let modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) {
                                modalInstance.hide(); // tutup modal
                            }
                        }

                        // Reload data tabel dosen tanpa reset paging
                        $('#tabel-dosen').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', response.message || 'Aktivasi gagal.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Terjadi kesalahan saat mengaktifkan data.', 'error');
            }
        });
    });
</script>
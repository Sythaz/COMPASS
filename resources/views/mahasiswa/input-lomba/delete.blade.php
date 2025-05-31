<form id="form-delete-lomba" method="POST" action="{{ url('mahasiswa/input-lomba/' . $lomba->lomba_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-danger rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-trash-alt mr-2"></i>Hapus Lomba
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="alert alert-warning d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-triangle fa-lg"></i>
            <strong class="alert-heading h5 mb-0">
                Apakah Anda yakin ingin menghapus data lomba ini?
            </strong>
        </div>

        <table class="table table-bordered mt-3 mb-0">
            <tr>
                <th style="width: 30%">Nama Lomba:</th>
                <td class="text-start">{{ $lomba->nama_lomba }}</td>
            </tr>
            <tr>
                <th>Tingkat:</th>
                <td class="text-start">{{ $lomba->tingkat->nama_tingkat ?? '-' }}</td>
            </tr>
            <tr>
                <th>Penyelenggara:</th>
                <td class="text-start">{{ $lomba->penyelenggara_lomba }}</td>
            </tr>
            <tr>
                <th>Periode Registrasi:</th>
                <td class="text-start">
                    {{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('Y-m-d') }}
                    &nbsp;—&nbsp;
                    {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('Y-m-d') }}
                </td>
            </tr>
            <tr>
                <th>Link Pendaftaran:</th>
                <td class="text-start">
                    <a href="{{ $lomba->link_pendaftaran_lomba }}" target="_blank">
                        {{ $lomba->link_pendaftaran_lomba }}
                    </a>
                </td>
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
    // Hapus event handler lama (jika ada)
    $(document).off('submit', '#form-delete-lomba');

    // Tangani AJAX delete
    $(document).on('submit', '#form-delete-lomba', function (e) {
        e.preventDefault();
        let form = $(this);
        let url  = form.attr('action');

        $.ajax({
            url: url,
            type: 'DELETE',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success')
                        .then(() => {
                            // Tutup modal
                            let modalEl = document.querySelector('.modal.show');
                            if (modalEl) {
                                let modal = bootstrap.Modal.getInstance(modalEl);
                                modal.hide();
                            }
                            // Reload DataTable lomba
                            $('#tabel-lomba').DataTable().ajax.reload(null, false);
                        });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    });
</script>

<form id="form-delete" method="POST"
    action="{{ url('admin/master-data/tingkat-lomba/' . $tingkatLomba->tingkat_lomba_id) . '/delete' }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-trash-alt mr-2"></i>Hapus Tingkat Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk menghapus data ini?</strong>
            <hr class="my-2">
            Data akan tetap tersimpan di database, hanya statusnya yang akan diubah dari "Aktif" menjadi "Inaktif".
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Tingkat Lomba: </th>
                <td class="text-start">{{ $tingkatLomba->nama_tingkat }}</td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Hapus</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-delete'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-delete', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        // Tutup modal
                        $('#myModal').modal('hide');

                        // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                        $('#tabel-tingkat-lomba').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Hapus gagal.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    });
</script>


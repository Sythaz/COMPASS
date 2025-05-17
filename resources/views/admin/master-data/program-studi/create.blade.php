<form id="form-create" method="POST" action="{{ url('admin/master-data/program-studi/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Program Studi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_prodi" class="col-form-label">Program Studi <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nama_prodi" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fa-solid fa-xmark mr-2"></i>Batal</button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

{{-- Memanggil Custom validation untuk Form --}}
<script>
    customFormValidation(
        // Validasi form
        // ID form untuk validasi
        "#form-create", {
            // Field yang akan di validasi
            nama_prodi: {
                required: true,
            },
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            nama_prodi: {
                required: "Program studi wajib diisi",
            }
        },

        function(response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function() {
                    // Tutup modal
                    $('#myModal').modal('hide');

                    // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                    $('#tabel-program-studi').DataTable().ajax.reload();
                });

            } else {
                $('.error-text').text('');
                $.each(response.msgField, function(prefix, val) {
                    $('#error-' + prefix).text(val[0]);
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: response.message
                });
            }
        }
    );
</script>


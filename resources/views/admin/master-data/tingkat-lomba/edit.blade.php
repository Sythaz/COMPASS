<form id="form-edit" method="POST"
    action="{{ url('admin/master-data/tingkat-lomba/' . $tingkatLomba->tingkat_lomba_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Tingkat Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="nama_tingkat" class="col-form-label">Tingkat Lomba <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nama_tingkat"
                    value="{{ old('nama_tingkat', $tingkatLomba->nama_tingkat) }}" required>
            </div>
            <label for="status_tingkat_lomba" class="col-form-label">Status Tingkat Lomba <span class="text-danger"
                    style="color: red;">*</span></label>
            <div class="custom-validation">
                <select name="status_tingkat_lomba" id="status_tingkat_lomba" class="form-control" required>
                    <option value="Aktif" {{ old('status_tingkat_lomba', $tingkatLomba->status_tingkat_lomba) == 'Aktif' ? 'selected' : '' }}>
                        Aktif</option>
                    <option value="Nonaktif"
                        {{ old('status_tingkat_lomba', $tingkatLomba->status_tingkat_lomba) == 'Nonaktif' ? 'selected' : '' }}>
                        Nonaktif</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal</button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

{{-- Memanggil Custom validation untuk Form --}}
<script>
    customFormValidation(
        // Validasi form
        // ID form untuk validasi
        "#form-edit", {
            // Field yang akan di validasi (name)
            nama_tingkat: {
                required: true,
            },
            status_tingkat_lomba: {
                required: true, 
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            nama_tingkat: {
                required: "Nama tingkat lomba wajib diisi",
            },
            status_tingkat_lomba: {
                required: "Status tingkat lomba wajib diisi", 
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
                    $('#tabel-tingkat-lomba').DataTable().ajax.reload();
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


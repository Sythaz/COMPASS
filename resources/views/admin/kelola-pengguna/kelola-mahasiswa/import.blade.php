<form action="{{ route('mahasiswa.import') }}" method="POST" enctype="multipart/form-data" id="import-mahasiswa">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-file-import mr-2"></i>Import Data Mahasiswa</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Download Template</label><br>
            <a href="{{ asset('Template_Mahasiswa.xlsx') }}" class="btn btn-info btn-sm">
                <i class="fa fa-file-excel mr-1"></i> Download
            </a>
        </div>

        <div class="form-group">
            <label for="file_mahasiswa" class="col-form-label">Pilih File <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="file" name="file_mahasiswa" id="file_mahasiswa" class="form-control" required>
                <span class="error-text text-danger" id="error-file_mahasiswa"></span>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-file-upload mr-2"></i>Upload
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    customFormValidation(
        "#import-mahasiswa",
        {
            file_mahasiswa: { required: true }
        },
        {
            file_mahasiswa: { required: "File mahasiswa wajib dipilih" }
        },
        function (response, form) {
            if (response.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function () {
                    getModalInstance().hide();
                    $('#tabel-mahasiswa').DataTable().ajax.reload();
                });
            } else {
                $('.error-text').text('');
                $.each(response.msgField, function (prefix, val) {
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

    function getModalInstance() {
        const modalEl = document.getElementById('myModal');
        return bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    }
</script>
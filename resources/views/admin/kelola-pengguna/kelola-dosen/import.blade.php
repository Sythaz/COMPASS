<form action="{{ route('dosen.import') }}" method="POST" enctype="multipart/form-data" id="import-dosen">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-file-import mr-2"></i>Import Data Dosen</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Download Template</label><br>
            <a href="{{ asset('Template_Dosen.xlsx') }}" class="btn btn-info btn-sm">
                <i class="fa fa-file-excel mr-1"></i> Download
            </a>
        </div>

        <div class="form-group">
            <label for="file_dosen" class="col-form-label">Pilih File <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="file" name="file_dosen" id="file_dosen" class="form-control" required>
                <span class="error-text text-danger" id="error-file_dosen"></span>
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

<!-- Spinner Loading -->
<div id="loading-spinner" style="display:none; position: fixed; top: 50%; left: 50%;
    transform: translate(-50%, -50%); z-index: 1055;">
    <img src="{{ asset('assets/images/loading/load.gif') }}" alt="Loading..." style="width: 80px; height: 80px;">
</div>

<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    function beforeSubmit() {
        $('#loading-spinner').show();
    }

    function afterSubmit() {
        $('#loading-spinner').hide();
    }

    function getModalInstance() {
        const modalEl = document.getElementById('myModal');
        return bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    }

    $(document).ready(function () {
        customFormValidation(
            "#import-dosen",
            {
                file_dosen: { required: true }
            },
            {
                file_dosen: { required: "File dosen wajib dipilih" }
            },
            function (response, form) {
                afterSubmit();

                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    }).then(function () {
                        getModalInstance().hide();
                        $('#tabel-dosen').DataTable().ajax.reload();
                    });
                } else {
                    $('.error-text').text('');
                    if (response.msgField) {
                        $.each(response.msgField, function (prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message
                    });
                }
            },
            function () {
                beforeSubmit();
            }
        );

        // Fallback jika user submit manual
        $('#import-dosen').on('submit', function () {
            beforeSubmit();
        });
    });
</script>
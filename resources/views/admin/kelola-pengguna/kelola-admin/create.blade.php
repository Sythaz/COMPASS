<form id="form-create" method="POST" action="{{ url('admin/kelola-pengguna/admin/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Admin</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="nip_admin" class="col-form-label">NIP <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nip_admin" required>
                <span class="error-text text-danger" id="error-nip_admin"></span>
            </div>
        </div>

        <div class="form-group">
            <label for="nama_admin" class="col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="nama_admin" required>
                <span class="error-text text-danger" id="error-nama_admin"></span>
            </div>
        </div>

        {{-- <div class="form-group">
            <label for="username" class="col-form-label">Username <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" class="form-control" name="username" required>
                <span class="error-text text-danger" id="error-username"></span>
            </div>
        </div> --}}

        {{-- <div class="form-group">
            <label for="password" class="col-form-label">Password <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="password" class="form-control" name="password" required minlength="6">
                <span class="error-text text-danger" id="error-password"></span>
            </div>
        </div> --}}

        <input type="hidden" name="role" value="admin">
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    // Fungsi untuk mendapatkan instance modal bootstrap versi 5
    function getModalInstance() {
        const modalEl = document.getElementById('myModal');
        return bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    }

    // Panggil form validation custom, sesuaikan dengan js-custom/form-validation.js kamu
    customFormValidation(
        "#form-create",
        {
            nip_admin: { required: true },
            nama_admin: { required: true },
            // username: { required: true },
            // password: { required: true, minlength: 6 },
        },
        {
            nip_admin: { required: "NIP wajib diisi" },
            nama_admin: { required: "Nama wajib diisi" },
            username: { required: "Username wajib diisi" },
            password: {
                required: "Password wajib diisi",
                minlength: "Password minimal 6 karakter"
            }
        },
        function (response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function () {
                    // Tutup modal dengan benar
                    getModalInstance().hide();

                    // Reload DataTable (sesuaikan id tabel)
                    $('#tabel-admin').DataTable().ajax.reload();
                });
            } else {
                // Clear error text dulu
                $('.error-text').text('');

                // Tampilkan pesan error di field masing-masing
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

    // Fungsi load form via ajax ke modal dan tampilkan modal
    function modalAction(url) {
        $.get(url)
            .done(function (res) {
                $('#ajaxModalContent').html(res);
                getModalInstance().show();
            })
            .fail(function () {
                Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
            });
    }

</script>
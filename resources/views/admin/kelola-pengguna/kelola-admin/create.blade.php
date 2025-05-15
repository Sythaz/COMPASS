<div class="modal-header">
    <h5 class="modal-title" id="ajaxModalLabel">Tambah Admin</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="formTambahAdmin">
    <div class="modal-body">
        <div class="mb-3">
            <label for="nip_admin" class="form-label">NIP</label>
            <input type="text" name="nip_admin" id="nip_admin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nama_admin" class="form-label">Nama</label>
            <input type="text" name="nama_admin" id="nama_admin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="" disabled selected>-- Pilih Role --</option>
                <option value="admin">Admin</option>
                {{-- <option value="superadmin">Super Admin</option> --}}
                <!-- Tambah role lain sesuai kebutuhan -->
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#formTambahAdmin').validate({
            submitHandler: function (form) {
                $.ajax({
                    url: "{{ url('admin/kelola-pengguna/admin/store') }}",
                    method: "POST",
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#ajaxModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            $('#tabel-admin').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
                        }
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON?.errors;
                        let msg = '';
                        if (errors) {
                            $.each(errors, function (key, val) {
                                msg += val[0] + '<br>';
                            });
                        } else {
                            msg = 'Terjadi kesalahan.';
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
                return false; // mencegah form submit normal
            }
        });
    });
</script>
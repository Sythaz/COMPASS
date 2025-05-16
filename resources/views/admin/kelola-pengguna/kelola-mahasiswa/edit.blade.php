<form id="form-edit-mahasiswa" method="POST"
    action="{{ url('admin/kelola-pengguna/mahasiswa/' . $mahasiswa->mahasiswa_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="nim_mahasiswa" class="form-label">NIM</label>
            <input type="text" class="form-control" name="nim_mahasiswa" value="{{ $mahasiswa->nim_mahasiswa }}"
                required>
        </div>

        <div class="mb-3">
            <label for="nama_mahasiswa" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama_mahasiswa" value="{{ $mahasiswa->nama_mahasiswa }}"
                required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="{{ $mahasiswa->users->username ?? '' }}"
                required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                Password <small>(kosongkan jika tidak ingin diubah)</small>
            </label>
            <input type="password" class="form-control" name="password" placeholder="Password baru">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="mahasiswa" {{ ($mahasiswa->users->role ?? '') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa
                </option>
                <option value="admin" {{ ($mahasiswa->users->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="prodi_id" class="form-label">Program Studi</label>
            <select name="prodi_id" class="form-select" required>
                @foreach($list_prodi as $prodi)
                    <option value="{{ $prodi->prodi_id }}" {{ $mahasiswa->prodi_id == $prodi->prodi_id ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="periode_id" class="form-label">Periode</label>
            <select name="periode_id" class="form-select" required>
                @foreach($list_periode as $periode)
                    <option value="{{ $periode->periode_id }}" {{ $mahasiswa->periode_id == $periode->periode_id ? 'selected' : '' }}>
                        {{ $periode->semester_periode }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="level_minbak_id" class="form-label">Level Minat Bakat</label>
            <select name="level_minbak_id" class="form-select" required>
                @foreach($list_level as $level)
                    <option value="{{ $level->level_minbak_id }}" {{ $mahasiswa->level_minbak_id == $level->level_minbak_id ? 'selected' : '' }}>
                        {{ $level->level_minbak }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    </div>
</form>

<script>
    // Submit form edit mahasiswa dengan AJAX
    $(document).on('submit', '#form-edit-mahasiswa', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function (response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        $('#ajaxModal').modal('hide');
                        $('#tabel-mahasiswa').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Update gagal.', 'error');
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let message = '';
                if (errors) {
                    $.each(errors, function (key, val) {
                        message += val + '<br>';
                    });
                } else {
                    message = 'Terjadi kesalahan.';
                }
                Swal.fire('Error', message, 'error');
            }
        });
    });
</script>
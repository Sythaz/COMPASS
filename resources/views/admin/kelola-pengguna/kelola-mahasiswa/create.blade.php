<form id="formTambahMahasiswa">
    <div class="modal-body">
        <div class="mb-3">
            <label for="nim_mahasiswa" class="form-label">NIM</label>
            <input type="text" name="nim_mahasiswa" id="nim_mahasiswa" class="form-control" required value="{{ old('nim_mahasiswa') }}">
        </div>
        <div class="mb-3">
            <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
            <input type="text" name="nama_mahasiswa" id="nama_mahasiswa" class="form-control" required value="{{ old('nama_mahasiswa') }}">
        </div>
        <div class="mb-3">
            <label for="prodi_id" class="form-label">Program Studi</label>
            <select name="prodi_id" id="prodi_id" class="form-select" required>
                <option value="" disabled {{ old('prodi_id') ? '' : 'selected' }}>-- Pilih Program Studi --</option>
                @foreach ($list_prodi as $prodi)
                    <option value="{{ $prodi->prodi_id }}" {{ old('prodi_id') == $prodi->prodi_id ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="periode_id" class="form-label">Periode</label>
            <select name="periode_id" id="periode_id" class="form-select" required>
                <option value="" disabled {{ old('periode_id') ? '' : 'selected' }}>-- Pilih Periode --</option>
                @foreach ($list_periode as $periode)
                    <option value="{{ $periode->periode_id }}" {{ old('periode_id') == $periode->periode_id ? 'selected' : '' }}>
                        {{ $periode->semester_periode }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="level_minbak_id" class="form-label">Level Minat Bakat</label>
            <select name="level_minbak_id" id="level_minbak_id" class="form-select" required>
                <option value="" disabled {{ old('level_minbak_id') ? '' : 'selected' }}>-- Pilih Level Minat Bakat --</option>
                @foreach ($list_level as $level)
                    <option value="{{ $level->level_minbak_id }}" {{ old('level_minbak_id') == $level->level_minbak_id ? 'selected' : '' }}>
                        {{ $level->level_minbak }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required value="{{ old('username') }}">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
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
        $('#formTambahMahasiswa').validate({
            submitHandler: function (form) {
                $.ajax({
                    url: "{{ url('admin/kelola-pengguna/mahasiswa/store') }}",
                    method: "POST",
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#ajaxModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            $('#tabel-mahasiswa').DataTable().ajax.reload();
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
                return false; 
            }
        });
    });
</script>

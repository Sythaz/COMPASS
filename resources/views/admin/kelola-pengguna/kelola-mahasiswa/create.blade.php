<form id="formTambahMahasiswa" method="POST" action="{{ url('admin/kelola-pengguna/mahasiswa/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Mahasiswa</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">

        {{-- Nim Mahasiswa --}}
        <div class="form-group">
            <label for="nim_mahasiswa" class="col-form-label">NIM <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" name="nim_mahasiswa" id="nim_mahasiswa" class="form-control" required
                    value="{{ old('nim_mahasiswa') }}">
                <span class="error-text text-danger" id="error-nim_mahasiswa"></span>
            </div>
        </div>

        {{-- Nama Mahasiswa --}}
        <div class="form-group">
            <label for="nama_mahasiswa" class="col-form-label">Nama Mahasiswa <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" name="nama_mahasiswa" id="nama_mahasiswa" class="form-control" required
                    value="{{ old('nama_mahasiswa') }}">
                <span class="error-text text-danger" id="error-nama_mahasiswa"></span>
            </div>
        </div>

        {{-- Jenis Kelamin Mahasiswa --}}
        <div class="form-group">
            <label for="kelamin" class="col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="kelamin" id="kelamin" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('kelamin', $mahasiswa->kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki
                    </option>
                    <option value="P" {{ old('kelamin', $mahasiswa->kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan
                    </option>
                </select>
                <span class="error-text text-danger" id="error-kelamin"></span>
            </div>
        </div>

        {{-- Email Mahasiswa (Boleh dikosongi) --}}
        <div class="form-group">
            <label for="email" class="col-form-label">Email <small class="text-muted">(Boleh
                    dikosongkan)</small></label>
            <div class="custom-validation">
                <input type="email" name="email" id="email" class="form-control"
                    placeholder="Contoh: user@example.com (boleh dikosongkan)" value="{{ old('email') }}">
                <span class="error-text text-danger" id="error-email"></span>
            </div>
        </div>

        {{-- No. Handphone Mahasiswa (Boleh dikosongi) --}}
        <div class="form-group">
            <label for="no_hp" class="col-form-label">No. Handphone <small class="text-muted">(Boleh
                    dikosongkan)</small></label>
            <div class="custom-validation">
                <input type="text" name="no_hp" id="no_hp" class="form-control"
                    placeholder="Contoh: 08123456789 (boleh dikosongkan)" value="{{ old('no_hp') }}">
                <span class="error-text text-danger" id="error-no_hp"></span>
            </div>
        </div>

        {{-- Alamat Mahasiswa (Boleh dikosongi) --}}
        <div class="form-group">
            <label for="alamat" class="col-form-label">Alamat <small class="text-muted">(Boleh
                    dikosongkan)</small></label>
            <div class="custom-validation">
                <textarea name="alamat" id="alamat" class="form-control" rows="2"
                    placeholder="Contoh: Jl. Contoh No. 1 (boleh dikosongkan)">{{ old('alamat') }}</textarea>
                <span class="error-text text-danger" id="error-alamat"></span>
            </div>
        </div>

        {{-- Program Studi --}}
        <div class="form-group">
            <label for="prodi_id" class="col-form-label">Program Studi <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="prodi_id" id="prodi_id" class="form-control" required>
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach ($list_prodi as $prodi)
                        <option value="{{ $prodi->prodi_id }}" {{ old('prodi_id', $mahasiswa->prodi_id ?? '') == $prodi->prodi_id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-prodi_id"></span>
            </div>
        </div>

        {{-- Periode --}}
        <div class="form-group">
            <label for="periode_id" class="col-form-label">Periode <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="periode_id" id="periode_id" class="form-control" required>
                    <option value="">-- Pilih Periode --</option>
                    @foreach ($list_periode as $periode)
                        <option value="{{ $periode->periode_id }}" {{ old('periode_id', $mahasiswa->periode_id ?? '') == $periode->periode_id ? 'selected' : '' }}>
                            {{ $periode->semester_periode }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-periode_id"></span>
            </div>
        </div>

        {{-- Level Minat Bakat --}}
        <div class="form-group">
            <label for="level_minbak_id" class="col-form-label">Level Minat Bakat <span
                    class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="level_minbak_id" id="level_minbak_id" class="form-control" required>
                    <option value="">-- Pilih Level Minat Bakat --</option>
                    @foreach ($list_level as $level)
                        <option value="{{ $level->level_minbak_id }}" {{ old('level_minbak_id', $mahasiswa->level_minbak_id ?? '') == $level->level_minbak_id ? 'selected' : '' }}>
                            {{ $level->level_minbak }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-level_minbak_id"></span>
            </div>
        </div>

        {{-- Komentar input username karena otomatis di backend --}}
        {{--
        <div class="form-group">
            <label for="username" class="col-form-label">Username <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="text" name="username" id="username" class="form-control" required
                    value="{{ old('username') }}">
                <span class="error-text text-danger" id="error-username"></span>
            </div>
        </div>
        --}}

        {{-- Komentar input password karena otomatis di backend --}}
        {{--
        <div class="form-group">
            <label for="password" class="col-form-label">Password <span class="text-danger">*</span></label>
            <div class="custom-validation">
                <input type="password" name="password" id="password" class="form-control" required minlength="6">
                <span class="error-text text-danger" id="error-password"></span>
            </div>
        </div>
        --}}

        <input type="hidden" name="role" value="mahasiswa">
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    customFormValidation(
        "#formTambahMahasiswa",
        {
            nim_mahasiswa: { required: true },
            nama_mahasiswa: { required: true },
            prodi_id: { required: true },
            periode_id: { required: true },
            level_minbak_id: { required: true },
            // username dan password dihilangkan validasinya
        },
        {
            nim_mahasiswa: { required: "NIM wajib diisi" },
            nama_mahasiswa: { required: "Nama wajib diisi" },
            prodi_id: { required: "Program Studi wajib dipilih" },
            periode_id: { required: "Periode wajib dipilih" },
            level_minbak_id: { required: "Level Minat Bakat wajib dipilih" },
            kelamin: { required: "Jenis Kelamin wajib dipilih" },
            // pesan error username dan password dihapus
        },
        function (response, form) {
            if (response.success) {
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
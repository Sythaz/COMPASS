<form id="formTambahMahasiswa" method="POST" action="{{ url('admin/kelola-pengguna/mahasiswa/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Mahasiswa</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
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

        {{-- Bidang Mahasiswa --}}
        <div class="form-group">
            <label for="kategori_id" class="col-form-label mt-2">Minat Bakat<span class="text-danger">*</span></label>
            <div class="custom-validation">
                <select name="kategori_id[]" id="kategori_id" class="form-control multiselect-dropdown-kategori"
                    multiple="multiple" required>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->kategori_id }}" @if(isset($mahasiswa) && $mahasiswa->kategoris->contains('kategori_id', $kategori->kategori_id)) selected @endif>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #7571F9;
        background-color: white !important;
        outline: 2px solid #7571F9 !important;
        border: none;
        border-radius: 4px;
        margin-top: 10px;
        margin-left: 12px
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        background-color: #7571F9;
    }

    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 12px;
        margin-left: 12px;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #7571F9;
    }
</style>

<script>
    // Memanggil Select2 multiselect
    $(document).ready(function () {
        $('.multiselect-dropdown-kategori').select2({
            width: '100%',
            placeholder: 'Belum ada kategori terpilih',
        });
    });
</script>

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
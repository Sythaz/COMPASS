<form id="form-edit-lomba" method="POST" action="{{ url('mahasiswa/input-lomba/' . $lomba->lomba_id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-edit mr-2"></i>Edit Lomba
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">

        {{-- Kategori Lomba --}}
        <div class="form-group">
            <label for="kategori_id" class="col-form-label">
                Kategori Lomba <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <select name="kategori_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(\App\Models\KategoriModel::all() as $kat)
                        <option value="{{ $kat->kategori_id }}"
                            {{ $lomba->kategori_id == $kat->kategori_id ? 'selected' : '' }}>
                            {{ $kat->kategori_nama }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-kategori_id"></span>
            </div>
        </div>

        {{-- Tingkat Lomba --}}
        <div class="form-group">
            <label for="tingkat_lomba_id" class="col-form-label">
                Tingkat Lomba <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <select name="tingkat_lomba_id" class="form-control" required>
                    <option value="">-- Pilih Tingkat --</option>
                    @foreach($tingkat as $t)
                        <option value="{{ $t->tingkat_lomba_id }}"
                            {{ $lomba->tingkat_lomba_id == $t->tingkat_lomba_id ? 'selected' : '' }}>
                            {{ $t->nama_tingkat }}
                        </option>
                    @endforeach
                </select>
                <span class="error-text text-danger" id="error-tingkat_lomba_id"></span>
            </div>
        </div>

        {{-- Nama Lomba --}}
        <div class="form-group">
            <label for="nama_lomba" class="col-form-label">
                Nama Lomba <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <input type="text" name="nama_lomba" class="form-control"
                       value="{{ $lomba->nama_lomba }}" required>
                <span class="error-text text-danger" id="error-nama_lomba"></span>
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label for="deskripsi_lomba" class="col-form-label">Deskripsi</label>
            <div class="custom-validation">
                <textarea name="deskripsi_lomba" class="form-control" rows="3">{{ $lomba->deskripsi_lomba }}</textarea>
                <span class="error-text text-danger" id="error-deskripsi_lomba"></span>
            </div>
        </div>

        {{-- Penyelenggara --}}
        <div class="form-group">
            <label for="penyelenggara_lomba" class="col-form-label">
                Penyelenggara <span class="text-danger">*</span>
            </label>
            <div class="custom-validation">
                <input type="text" name="penyelenggara_lomba" class="form-control"
                       value="{{ $lomba->penyelenggara_lomba }}" required>
                <span class="error-text text-danger" id="error-penyelenggara_lomba"></span>
            </div>
        </div>

        {{-- Periode Registrasi --}}
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="awal_registrasi_lomba" class="col-form-label">
                    Awal Registrasi <span class="text-danger">*</span>
                </label>
                <div class="custom-validation">
                    <input type="date" name="awal_registrasi_lomba" class="form-control"
                           value="{{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('Y-m-d') }}" required>
                    <span class="error-text text-danger" id="error-awal_registrasi_lomba"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="akhir_registrasi_lomba" class="col-form-label">
                    Akhir Registrasi <span class="text-danger">*</span>
                </label>
                <div class="custom-validation">
                    <input type="date" name="akhir_registrasi_lomba" class="form-control"
                           value="{{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('Y-m-d') }}" required>
                    <span class="error-text text-danger" id="error-akhir_registrasi_lomba"></span>
                </div>
            </div>
        </div>

        {{-- Link Pendaftaran --}}
        <div class="form-group">
            <label for="link_pendaftaran_lomba" class="col-form-label">Link Pendaftaran</label>
            <div class="custom-validation">
                <input type="url" name="link_pendaftaran_lomba" class="form-control"
                       value="{{ $lomba->link_pendaftaran_lomba }}" placeholder="https://">
                <span class="error-text text-danger" id="error-link_pendaftaran_lomba"></span>
            </div>
        </div>

        {{-- Gambar Poster (opsional) --}}
        <div class="form-group">
            <label for="img_lomba" class="col-form-label">Gambar Poster</label>
            <div class="custom-validation">
                @if($lomba->img_lomba)
                    <div class="mb-2">
                        <img src="{{ asset('storage/lomba/' . $lomba->img_lomba) }}"
                             alt="Poster" class="img-fluid" style="max-height:120px;">
                    </div>
                @endif
                <input type="file" name="img_lomba" class="form-control-file" accept="image/*">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah.</small>
                <span class="error-text text-danger" id="error-img_lomba"></span>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<script>
    // Tangani AJAX submit untuk edit lomba
    $(document).off('submit', '#form-edit-lomba');
    $(document).on('submit', '#form-edit-lomba', function(e) {
        e.preventDefault();
        let form = $(this);
        let url  = form.attr('action');
        let data = new FormData(this);

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            headers: { 'X-HTTP-Method-Override': 'PUT' },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        // tutup modal
                        let modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            bootstrap.Modal.getInstance(modalEl).hide();
                        }
                        // reload DataTable
                        $('#tabel-lomba').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function(xhr) {
                let errs = xhr.responseJSON?.errors || {};
                $('.error-text').text('');
                $.each(errs, function(field, msgs) {
                    $('#error-' + field).text(msgs[0]);
                });
                Swal.fire('Error', 'Periksa kembali inputan Anda.', 'error');
            }
        });
    });
</script>

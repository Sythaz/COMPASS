<form id="form-create" method="POST" action="{{ route('mahasiswa.prestasi.store') }}"
      enctype="multipart/form-data">
    @csrf

    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-plus mr-2"></i>Tambah Prestasi
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        {{-- Pilih Lomba --}}
        <div class="form-group">
            <label for="lomba_id">Lomba <span class="text-danger">*</span></label>
            <select name="lomba_id" id="lomba_id" class="form-control" required>
                <option value="">-- Pilih Lomba --</option>
                @foreach($list_lomba as $lomba)
                    <option value="{{ $lomba->lomba_id }}">
                        {{ $lomba->nama_lomba }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Dosen (verifikator) --}}
        <div class="form-group">
            <label for="dosen_id">Dosen <span class="text-danger">*</span></label>
            <select name="dosen_id" id="dosen_id" class="form-control" required>
                <option value="">-- Pilih Dosen --</option>
                @foreach($list_dosen as $dosen)
                    <option value="{{ $dosen->dosen_id }}">
                        {{ $dosen->nama_dosen }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Kategori --}}
        <div class="form-group">
            <label for="kategori_id">Kategori Prestasi <span class="text-danger">*</span></label>
            <select name="kategori_id" id="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($list_kategori as $kat)
                    <option value="{{ $kat->kategori_id }}">
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Periode --}}
        <div class="form-group">
            <label for="periode_id">Periode Prestasi <span class="text-danger">*</span></label>
            <select name="periode_id" id="periode_id" class="form-control" required>
                <option value="">-- Pilih Periode --</option>
                @foreach($list_periode as $prd)
                    <option value="{{ $prd->periode_id }}">
                        {{ $prd->nama_periode }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tanggal Prestasi --}}
        <div class="form-group">
            <label for="tanggal_prestasi">Tanggal Prestasi <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="tanggal_prestasi" id="tanggal_prestasi" required>
        </div>

        {{-- Juara Prestasi --}}
        <div class="form-group">
            <label for="juara_prestasi">Juara Prestasi <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="juara_prestasi" id="juara_prestasi" 
                   placeholder="Contoh: Juara 1" required>
        </div>

        {{-- Jenis Prestasi --}}
        <div class="form-group">
            <label for="jenis_prestasi">Jenis Prestasi <span class="text-danger">*</span></label>
            <select name="jenis_prestasi" id="jenis_prestasi" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Individu">Individu</option>
                <option value="Tim">Tim</option>
            </select>
        </div>

        {{-- Upload Foto Kegiatan --}}
        <div class="form-group">
            <label for="img_kegiatan">Foto Kegiatan <small>(png/jpg, max 2MB)</small></label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="img_kegiatan" id="img_kegiatan"
                       accept=".png,.jpg,.jpeg"
                       onchange="$('#label-img_kegiatan').text(this.files[0]?.name || 'Pilih file')">
                <label class="custom-file-label" id="label-img_kegiatan" for="img_kegiatan">
                    Pilih file
                </label>
            </div>
        </div>

        {{-- Upload Bukti Prestasi (sertifikat) --}}
        <div class="form-group">
            <label for="bukti_prestasi">Bukti Prestasi <small>(pdf, max 2MB)</small></label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="bukti_prestasi" id="bukti_prestasi"
                       accept=".pdf"
                       onchange="$('#label-bukti_prestasi').text(this.files[0]?.name || 'Pilih file')">
                <label class="custom-file-label" id="label-bukti_prestasi" for="bukti_prestasi">
                    Pilih file
                </label>
            </div>
        </div>

        {{-- Upload Surat Tugas --}}
        <div class="form-group">
            <label for="surat_tugas_prestasi">Surat Tugas <small>(pdf, max 2MB)</small></label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="surat_tugas_prestasi" id="surat_tugas_prestasi"
                       accept=".pdf"
                       onchange="$('#label-surat_tugas_prestasi').text(this.files[0]?.name || 'Pilih file')">
                <label class="custom-file-label" id="label-surat_tugas_prestasi" for="surat_tugas_prestasi">
                    Pilih file
                </label>
            </div>
        </div>

        {{-- Status Prestasi (hidden) --}}
        <input type="hidden" name="status_prestasi" value="Aktif">
        {{-- Status Verifikasi (hidden default Menunggu) --}}
        <input type="hidden" name="status_verifikasi" value="Menunggu">

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

{{-- Custom file-input label update --}}
<script>
    $('.custom-file-input').on('change', function(){
        let label = $(this).next('.custom-file-label');
        let fileName = this.files[0]?.name || 'Pilih file';
        label.text(fileName);
    });
</script>

{{-- (Opsional) Jika menggunakan jQuery Validation --}}
<script src="{{ asset('js-custom/form-validation.js') }}"></script>
<script>
    customFormValidation(
        "#form-create", {
            lomba_id: { required: true },
            dosen_id: { required: true },
            kategori_id: { required: true },
            periode_id: { required: true },
            tanggal_prestasi: { required: true, date: true },
            juara_prestasi: { required: true },
            jenis_prestasi: { required: true },
            img_kegiatan: { extension: "png|jpe?g" },
            bukti_prestasi: { extension: "pdf" },
            surat_tugas_prestasi: { extension: "pdf" },
        }, {
            lomba_id: { required: "Lomba wajib dipilih" },
            dosen_id: { required: "Dosen wajib dipilih" },
            kategori_id: { required: "Kategori wajib dipilih" },
            periode_id: { required: "Periode wajib dipilih" },
            tanggal_prestasi: { required: "Tanggal wajib diisi", date: "Format tanggal tidak valid" },
            juara_prestasi: { required: "Juara wajib diisi" },
            jenis_prestasi: { required: "Jenis prestasi wajib dipilih" },
            img_kegiatan: { extension: "Hanya .png/.jpg/.jpeg" },
            bukti_prestasi: { extension: "Hanya .pdf" },
            surat_tugas_prestasi: { extension: "Hanya .pdf" },
        },
        function(response) {
            if (response.success) {
                Swal.fire('Berhasil', response.message, 'success').then(() => {
                    $('#myModal').modal('hide');
                    $('#tabel-prestasi').DataTable().ajax.reload();
                });
            } else {
                Swal.fire('Gagal', response.message, 'error');
            }
        }
    );
</script>

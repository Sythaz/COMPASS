@if (empty($prestasi))
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Kesalahan</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <i class="fas fa-ban fa-2x mr-3"></i>
          <div>
            <h5>Data Tidak Ditemukan</h5>
            <p>Maaf, data prestasi yang ingin Anda edit tidak ada.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-dismiss="modal">
          <i class="fas fa-arrow-left mr-1"></i>Kembali
        </button>
      </div>
    </div>
@else
<form id="form-edit" method="POST"
      action="{{ route('mahasiswa.prestasi.update', $prestasi->prestasi_id) }}"
      enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">
      <i class="fas fa-edit mr-2"></i>Edit Prestasi
    </h5>
    <button type="button" class="close" data-dismiss="modal">
      <span>&times;</span>
    </button>
  </div>

  <div class="modal-body">
    {{-- Pilih Lomba --}}
    <div class="form-group">
      <label for="lomba_id">Lomba <span class="text-danger">*</span></label>
      <select name="lomba_id" id="lomba_id" class="form-control" required>
        <option value="">-- Pilih Lomba --</option>
        @foreach($list_lomba as $lomba)
          <option value="{{ $lomba->lomba_id }}"
            {{ old('lomba_id', $prestasi->lomba_id)==$lomba->lomba_id ? 'selected':'' }}>
            {{ $lomba->nama_lomba }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Tanggal Prestasi --}}
    <div class="form-group">
      <label for="tanggal_prestasi">Tanggal Prestasi <span class="text-danger">*</span></label>
      <input type="date" name="tanggal_prestasi" id="tanggal_prestasi"
             class="form-control"
             value="{{ old('tanggal_prestasi', $prestasi->tanggal_prestasi) }}"
             required>
    </div>

    {{-- Juara Prestasi --}}
    <div class="form-group">
      <label for="juara_prestasi">Juara Prestasi <span class="text-danger">*</span></label>
      <input type="text" name="juara_prestasi" id="juara_prestasi"
             class="form-control"
             value="{{ old('juara_prestasi', $prestasi->juara_prestasi) }}"
             placeholder="Contoh: Juara 1" required>
    </div>

    {{-- Jenis Prestasi --}}
    <div class="form-group">
      <label for="jenis_prestasi">Jenis Prestasi <span class="text-danger">*</span></label>
      <select name="jenis_prestasi" id="jenis_prestasi" class="form-control" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="Individu"
          {{ old('jenis_prestasi', $prestasi->jenis_prestasi)=='Individu'?'selected':'' }}>
          Individu
        </option>
        <option value="Tim"
          {{ old('jenis_prestasi', $prestasi->jenis_prestasi)=='Tim'?'selected':'' }}>
          Tim
        </option>
      </select>
    </div>

    {{-- Foto Kegiatan --}}
    <div class="form-group">
      <label for="img_kegiatan">Foto Kegiatan <small>(png/jpg, max 2MB)</small></label>
      <div class="custom-file">
        <input type="file" name="img_kegiatan" id="img_kegiatan"
               class="custom-file-input"
               accept=".png,.jpg,.jpeg">
        <label class="custom-file-label" id="label-img_kegiatan" for="img_kegiatan">
          {{ $prestasi->img_kegiatan ?? 'Pilih file' }}
        </label>
      </div>
      @if($prestasi->img_kegiatan)
        <small class="form-text text-muted">File saat ini: {{ $prestasi->img_kegiatan }}</small>
      @endif
    </div>

    {{-- Bukti Prestasi --}}
    <div class="form-group">
      <label for="bukti_prestasi">Bukti Prestasi <small>(pdf, max 2MB)</small></label>
      <div class="custom-file">
        <input type="file" name="bukti_prestasi" id="bukti_prestasi"
               class="custom-file-input"
               accept=".pdf">
        <label class="custom-file-label" id="label-bukti_prestasi" for="bukti_prestasi">
          {{ $prestasi->bukti_prestasi ?? 'Pilih file' }}
        </label>
      </div>
      @if($prestasi->bukti_prestasi)
        <small class="form-text text-muted">File saat ini: {{ $prestasi->bukti_prestasi }}</small>
      @endif
    </div>

    {{-- Surat Tugas --}}
    <div class="form-group">
      <label for="surat_tugas_prestasi">Surat Tugas <small>(pdf, max 2MB)</small></label>
      <div class="custom-file">
        <input type="file" name="surat_tugas_prestasi" id="surat_tugas_prestasi"
               class="custom-file-input"
               accept=".pdf">
        <label class="custom-file-label" id="label-surat_tugas_prestasi" for="surat_tugas_prestasi">
          {{ $prestasi->surat_tugas_prestasi ?? 'Pilih file' }}
        </label>
      </div>
      @if($prestasi->surat_tugas_prestasi)
        <small class="form-text text-muted">File saat ini: {{ $prestasi->surat_tugas_prestasi }}</small>
      @endif
    </div>

    {{-- Jangan edit status --}}
    <input type="hidden" name="status_prestasi" value="{{ $prestasi->status_prestasi }}">
    <input type="hidden" name="status_verifikasi" value="{{ $prestasi->status_verifikasi }}">
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-save mr-2"></i>Simpan Perubahan
    </button>
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
      <i class="fas fa-times mr-2"></i>Batal
    </button>
  </div>
</form>

<script>
  // update label file-input
  $('.custom-file-input').on('change', function(){
    const name = this.files[0]?.name || 'Pilih file';
    $(this).next('.custom-file-label').text(name);
  });

  // validasi & AJAX submit
  customFormValidation(
    "#form-edit",
    {
      lomba_id: { required: true },
      tanggal_prestasi: { required: true, date: true },
      juara_prestasi: { required: true },
      jenis_prestasi: { required: true },
      img_kegiatan: { extension: "png|jpe?g", maxFileSize: 2048 },
      bukti_prestasi: { extension: "pdf", maxFileSize: 2048 },
      surat_tugas_prestasi: { extension: "pdf", maxFileSize: 2048 }
    },
    {
      lomba_id: { required: "Lomba wajib dipilih" },
      tanggal_prestasi: { required: "Tanggal wajib diisi" },
      juara_prestasi: { required: "Juara wajib diisi" },
      jenis_prestasi: { required: "Jenis wajib dipilih" },
      img_kegiatan: { extension: "Hanya .png/.jpg", maxFileSize: "Max 2MB" },
      bukti_prestasi: { extension: "Hanya .pdf", maxFileSize: "Max 2MB" },
      surat_tugas_prestasi: { extension: "Hanya .pdf", maxFileSize: "Max 2MB" }
    },
    function(response){
      if(response.success){
        Swal.fire('Berhasil', response.message, 'success')
          .then(()=> {
            $('#myModal').modal('hide');
            $('#tabel-prestasi').DataTable().ajax.reload(null,false);
          });
      } else {
        Swal.fire('Error', response.message, 'error');
      }
    }
  );
</script>
@endif

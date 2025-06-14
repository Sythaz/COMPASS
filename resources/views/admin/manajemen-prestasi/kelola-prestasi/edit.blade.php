<form id="form-prestasi" action="{{ route('kelola-prestasi.update', $prestasi->prestasi_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Informasi Lomba -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-trophy mr-2"></i>Informasi Lomba</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="lomba_id" class="col-form-label font-weight-bold">Nama Lomba <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="lomba_id" id="lomba_id" class="form-control select2" required>
                                    <option value="">-- Pilih Lomba --</option>
                                    @foreach ($daftarLomba as $lomba)
                                        <option value="{{ $lomba->lomba_id }}"
                                            data-tingkat="{{ $lomba->tingkat_lomba->nama_tingkat ?? '' }}"
                                            data-tingkat-id="{{ $lomba->tingkat_lomba->tingkat_lomba_id ?? '' }}"
                                            data-kategori="{{ optional($lomba->kategori)->pluck('nama_kategori')->implode(', ') }}"
                                            data-kategori-json='@json(
                                                $lomba->kategori->map(function ($k) {
                                                    return ['id' => $k->kategori_id, 'text' => $k->nama_kategori];
                                                }))' data-tipe="{{ $lomba->tipe_lomba }}"
                                            {{ $prestasi->lomba_id == $lomba->lomba_id ? 'selected' : '' }}>
                                            {{ $lomba->nama_lomba }}
                                        </option>
                                    @endforeach
                                    <option value="lainnya" {{ is_null($prestasi->lomba_id) ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-trophy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tingkat dan Kategori (Readonly jika pilih dari DB) -->
                <div class="row">
                    <div class="col-md-6">
                        <div id="form-tingkat-lomba" class="form-group" style="{{ is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                            <label for="nama_tingkat_lomba" class="col-form-label font-weight-bold">Tingkat Lomba</label>
                            <div class="input-group">
                                <input type="text" id="nama_tingkat_lomba" class="form-control"
                                    value="{{ $prestasi->lomba_id ? $prestasi->lomba->tingkat_lomba->nama_tingkat ?? '' : '' }}"
                                    readonly>
                                <input type="hidden" name="tingkat_lomba_id" id="tingkat_lomba_id"
                                    value="{{ $prestasi->lomba_id ? $prestasi->lomba->tingkat_lomba->tingkat_lomba_id ?? '' : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-layer-group"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="form-kategori-lomba" class="form-group" style="{{ is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                            <label for="kategori_id" class="col-form-label font-weight-bold">Kategori Lomba <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="kategori_id" id="kategori_id" class="form-control select2" required>
                                    @if ($prestasi->lomba_id)
                                        @foreach ($prestasi->lomba->kategori as $kategori)
                                            <option value="{{ $kategori->kategori_id }}"
                                                {{ $prestasi->kategori_id == $kategori->kategori_id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-tags"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Manual (Jika "Lainnya") -->
                <div id="input-lomba-lainnya" class="lomba-manual-section" style="{{ !is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="lomba_lainnya" class="col-form-label font-weight-bold">Nama Lomba (Lainnya) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="lomba_lainnya" id="lomba_lainnya" class="form-control"
                                        value="{{ $prestasi->lomba_lainnya }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-warning text-dark">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tingkat_lomba_id" class="col-form-label font-weight-bold">Tingkat Lomba <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control select2">
                                        <option value="">-- Pilih Tingkat Lomba --</option>
                                        @foreach ($daftarTingkatLomba as $tingkat)
                                            <option value="{{ $tingkat->tingkat_lomba_id }}"
                                                {{ $prestasi->tingkat_lomba_id == $tingkat->tingkat_lomba_id ? 'selected' : '' }}>
                                                {{ $tingkat->nama_tingkat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-info text-white">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="kategori-lomba-manual" class="form-group" style="{{ !is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                                <label for="kategori_id_manual" class="col-form-label font-weight-bold">Kategori Lomba <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="kategori_id" id="kategori_id_manual" class="form-control select2" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($daftarKategori as $kategori)
                                            <option value="{{ $kategori->kategori_id }}"
                                                {{ $prestasi->kategori_id == $kategori->kategori_id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-tags"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Prestasi -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-medal mr-2"></i>Detail Prestasi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Tipe Prestasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="jenis_prestasi" id="jenis_prestasi" class="form-control select2" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="individu"
                                        {{ strtolower(old('jenis_prestasi', $prestasi->jenis_prestasi ?? '')) == 'individu' ? 'selected' : '' }}>
                                        Individu</option>
                                    <option value="tim"
                                        {{ strtolower(old('jenis_prestasi', $prestasi->jenis_prestasi ?? '')) == 'tim' ? 'selected' : '' }}>
                                        Tim</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-users"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Jumlah Anggota</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-minus">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                <input type="number" id="jumlah_anggota" class="form-control text-center"
                                    value="{{ count($anggotaTim) > 0 ? count($anggotaTim) : 1 }}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-plus">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="juara_prestasi" class="col-form-label font-weight-bold">Juara Prestasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="juara_prestasi"
                                    value="{{ $prestasi->juara_prestasi }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-warning text-dark">
                                        <i class="fas fa-medal"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_prestasi" class="col-form-label font-weight-bold">Tanggal Prestasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="tanggal_prestasi"
                                    value="{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('Y-m-d') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="periode_id" class="col-form-label font-weight-bold">Periode <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="periode_id" id="periode_id" class="form-control select2" required>
                                    @foreach ($daftarPeriode as $periode)
                                        <option value="{{ $periode->periode_id }}"
                                            {{ $prestasi->periode_id == $periode->periode_id ? 'selected' : '' }}>
                                            {{ $periode->semester_periode }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dosen_id" class="col-form-label font-weight-bold">Dosen Pembimbing</label>
                            <div class="input-group">
                                <select name="dosen_id" id="dosen_id" class="form-control select2">
                                    <option value="">-- Tidak ada dosen pembimbing --</option>
                                    @foreach ($daftarDosen as $dosen)
                                        <option value="{{ $dosen->dosen_id }}"
                                            {{ $prestasi->dosen_id == $dosen->dosen_id ? 'selected' : '' }}>
                                            {{ $dosen->nama_dosen }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-user-tie"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Status Verifikasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="status_verifikasi" id="status_verifikasi" class="form-control select2" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Ditolak"
                                        {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Ditolak') === 0 ? 'selected' : '' }}>Ditolak
                                    </option>
                                    <option value="Valid"
                                        {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Valid') === 0 ? 'selected' : '' }}>Valid
                                    </option>
                                    <option value="Menunggu"
                                        {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Menunggu') === 0 ? 'selected' : '' }}>Menunggu
                                    </option>
                                    <option value="Terverifikasi"
                                        {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Terverifikasi') === 0 ? 'selected' : '' }}>
                                        Terverifikasi</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Peserta/Tim -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-users mr-2"></i>Informasi Peserta
                    <span class="badge badge-primary ml-2" id="badge-anggota">
                        {{ count($anggotaTim) }} {{ count($anggotaTim) > 1 ? 'Anggota' : 'Peserta' }}
                    </span>
                </h6>
            </div>
            <div class="card-body">
                <div id="anggota-container">
                    @foreach ($anggotaTim as $index => $anggota)
                        <div class="form-group anggota-item">
                            <div class="anggota-section bg-light rounded p-3 mb-2">
                                <label class="col-form-label font-weight-bold">
                                    <i class="fas fa-{{ $index === 0 ? 'crown' : 'user' }} mr-2 {{ $index === 0 ? 'text-warning' : '' }}"></i>
                                    {{ $index === 0 ? 'Ketua Tim' : 'Anggota ' . $index }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <select name="mahasiswa_id[]" class="form-control anggota-select"
                                        {{ $index === 0 ? 'required' : '' }}>
                                        <option value="">-- Pilih {{ $index === 0 ? 'Ketua Tim' : 'Anggota ' . $index }} --
                                        </option>
                                        @foreach ($daftarMahasiswa as $mhs)
                                            <option value="{{ $mhs->mahasiswa_id }}"
                                                {{ $anggota['mahasiswa_id'] == $mhs->mahasiswa_id ? 'selected' : '' }}>
                                                {{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-{{ $index === 0 ? 'warning text-dark' : 'info text-white' }}">
                                            <i class="fas fa-{{ $index === 0 ? 'crown' : 'user-check' }}"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dokumentasi & Bukti -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-images mr-2"></i>Dokumentasi & Bukti</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Gambar Kegiatan -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-camera mr-2"></i>Gambar Kegiatan</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="img_kegiatan" class="col-form-label font-weight-bold">Upload Gambar <small>(Maksimal 2MB)</small></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="img_kegiatan" accept=".png, .jpg, .jpeg"
                                                onchange="$('#img_kegiatan_label').text(this.files[0] ? this.files[0].name : '{{ $prestasi->img_kegiatan ? basename($prestasi->img_kegiatan) : 'Pilih File' }}')">
                                            <label class="custom-file-label" id="img_kegiatan_label" for="img_kegiatan">
                                                {{ $prestasi->img_kegiatan ? basename($prestasi->img_kegiatan) : 'Pilih File' }}
                                            </label>
                                        </div>
                                    </div>
                                    @if ($prestasi->img_kegiatan)
                                        <small class="text-muted mt-2 d-block">File saat ini: 
                                            <a href="{{ asset($prestasi->img_kegiatan) }}" target="_blank" class="text-primary">
                                                <i class="fas fa-eye mr-1"></i>{{ basename($prestasi->img_kegiatan) }}
                                            </a>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Prestasi -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-certificate mr-2"></i>Bukti Prestasi</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="bukti_prestasi" class="col-form-label font-weight-bold">Upload Bukti <small>(Maksimal 2MB)</small></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="bukti_prestasi" accept=".png, .jpg, .jpeg"
                                                onchange="$('#bukti_prestasi_label').text(this.files[0] ? this.files[0].name : '{{ $prestasi->bukti_prestasi ? basename($prestasi->bukti_prestasi) : 'Pilih File' }}')">
                                            <label class="custom-file-label" id="bukti_prestasi_label" for="bukti_prestasi">
                                                {{ $prestasi->bukti_prestasi ? basename($prestasi->bukti_prestasi) : 'Pilih File' }}
                                            </label>
                                        </div>
                                    </div>
                                    @if ($prestasi->bukti_prestasi)
                                        <small class="text-muted mt-2 d-block">File saat ini: 
                                            <a href="{{ asset($prestasi->bukti_prestasi) }}" target="_blank" class="text-success">
                                                <i class="fas fa-eye mr-1"></i>{{ basename($prestasi->bukti_prestasi) }}
                                            </a>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Surat Tugas -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Surat Tugas</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="surat_tugas_prestasi" class="col-form-label font-weight-bold">Upload Surat <small>(Maksimal 2MB)</small></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="surat_tugas_prestasi"
                                                accept=".png, .jpg, .jpeg"
                                                onchange="$('#surat_tugas_prestasi_label').text(this.files[0] ? this.files[0].name : '{{ $prestasi->surat_tugas_prestasi ? basename($prestasi->surat_tugas_prestasi) : 'Pilih File' }}')">
                                            <label class="custom-file-label" id="surat_tugas_prestasi_label" for="surat_tugas_prestasi">
                                                {{ $prestasi->surat_tugas_prestasi ? basename($prestasi->surat_tugas_prestasi) : 'Pilih File' }}
                                            </label>
                                        </div>
                                    </div>
                                    @if ($prestasi->surat_tugas_prestasi)
                                        <small class="text-muted mt-2 d-block">File saat ini: 
                                            <a href="{{ asset($prestasi->surat_tugas_prestasi) }}" target="_blank" class="text-warning">
                                                <i class="fas fa-eye mr-1"></i>{{ basename($prestasi->surat_tugas_prestasi) }}
                                            </a>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Modal --}}
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="btn-submit">
            <i class="fas fa-save mr-2"></i>Simpan Perubahan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<style>
    .anggota-section {
        border-left: 4px solid #7571F9;
    }
    
    .anggota-section:first-child {
        border-left-color: #ffc107 !important;
    }
    
    .lomba-manual-section {
        background-color: rgba(255, 193, 7, 0.1);
        border-radius: 8px;
        border: 1px dashed #ffc107;
        padding: 15px;
        margin: 10px 0;
    }
    
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }
    
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }
    
    .file-preview {
        padding: 20px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
    
    .input-group-text {
        border: 1px solid #ced4da;
    }
    
    .btn-outline-secondary {
        border: 1px solid #6c757d;
    }
    
    .custom-file-label::after {
        content: "Browse";
    }
    
    .anggota-item .anggota-section {
        transition: all 0.3s ease;
    }
    
    .anggota-item .anggota-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

<script>
    const daftarMahasiswa = @json($daftarMahasiswa);
</script>

<script>
    $(document).ready(function() {
        const MAX_ANGGOTA_TIM = 5;
        const MIN_ANGGOTA_TIM = 2;
        const JUMLAH_INDIVIDU = 1;

        const jenisPrestasiSelect = $('#jenis_prestasi');
        const jumlahAnggotaInput = $('#jumlah_anggota');
        const badgeAnggota = $('#badge-anggota');

        // Function to update badge
        function updateBadge(jumlah) {
            const label = jumlah > 1 ? 'Anggota' : 'Peserta';
            badgeAnggota.text(`${jumlah} ${label}`);
        }

        // Event handler tombol PLUS
        $('#btn-plus').on('click', function() {
            const tipe = jenisPrestasiSelect.val();
            let current = parseInt(jumlahAnggotaInput.val());

            if (tipe === 'individu') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa',
                    text: 'Jumlah anggota untuk individu harus 1'
                });
                return;
            }

            if (tipe === 'tim') {
                if (current > MIN_ANGGOTA_TIM) {
                    current--;
                    jumlahAnggotaInput.val(current);
                    renderAnggota(current);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: `Jumlah anggota minimal adalah ${MIN_ANGGOTA_TIM}`
                    });
                }
            }
        });

        // Event handler saat memilih lomba
        $('#lomba_id').on('change', function() {
            const selected = $(this).val();
            const selectedOption = $('option:selected', this);

            let kategoriJsonRaw = selectedOption.attr('data-kategori-json') || '[]';
            let kategoriJson = [];
            try {
                kategoriJson = JSON.parse(kategoriJsonRaw);
            } catch (e) {
                console.error('JSON parse error kategori-json:', e);
                kategoriJson = [];
            }

            const tingkat = selectedOption.data('tingkat') || '';
            const tipeLomba = selectedOption.data('tipe') || '';

            if (selected === 'lainnya') {
                // Sembunyikan bagian readonly
                $('#form-tingkat-lomba').hide();
                $('#form-kategori-lomba').hide();
                $('#form-kategori-dropdown').hide(); // tambahan: dropdown untuk kategori dari database
                $('#kategori_id').removeAttr('name').prop('required', false);
                $('#kategori_id_manual').attr('name', 'kategori_id').prop('required', true);
                // Kosongkan nilai readonly
                $('#nama_tingkat_lomba').val('');
                $('#nama_kategori_lomba').val('');

                // Tampilkan input manual lainnya
                $('#input-lomba-lainnya').show();
                $('#kategori-lomba-manual').show();

                // Aktifkan dropdown tipe prestasi & set ke nilai lama (biar user bisa ubah atau tidak)
                jenisPrestasiSelect.prop('disabled', false);
                jenisPrestasiSelect.val(oldJenisPrestasi || 'individu');

                // Set jumlah anggota sesuai tipe prestasi lama
                if (jenisPrestasiSelect.val() === 'tim') {
                    jumlahAnggotaInput.val(oldJumlahAnggota || MIN_ANGGOTA_TIM).prop('readonly', false);
                    renderAnggota(oldJumlahAnggota || MIN_ANGGOTA_TIM);
                } else {
                    jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                    renderAnggota(JUMLAH_INDIVIDU);
                }
            } else if (selected) {
                const tingkat = $('option:selected', this).data('tingkat') || '';
                const tingkatId = $('option:selected', this).data('tingkat-id') || '';
                const kategoriLabel = $('option:selected', this).data('kategori') || '';
                const kategoriJson = $('option:selected', this).data('kategori-json') || [];

                // Tampilkan form readonly
                $('#form-tingkat-lomba').show();
                $('#form-kategori-lomba').show();
                $('#form-kategori-dropdown').show();

                // Set nilai readonly dan hidden input
                $('#nama_tingkat_lomba').val(tingkat);
                $('#tingkat_lomba_id').val(tingkatId);
                $('#nama_kategori_lomba').val(kategoriLabel);

                // Atur kategori dropdown
                $('#kategori_id_manual').removeAttr('name').prop('required', false);
                $('#kategori_id').attr('name', 'kategori_id').prop('required', true);
                $('#input-lomba-lainnya').hide();
                $('#kategori-lomba-manual').hide();

                $('#kategori_id').html('');
                kategoriJson.forEach(item => {
                    $('#kategori_id').append(
                        `<option value="${item.id}">${item.text}</option>`);
                });

                if (tipeLomba) {
                    jenisPrestasiSelect.val(tipeLomba.toLowerCase()).prop('disabled', true);

                    if (tipeLomba.toLowerCase() === 'individu') {
                        jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                        renderAnggota(JUMLAH_INDIVIDU);
                    } else {
                        const jumlah = jumlahAnggotaInput.val() > MIN_ANGGOTA_TIM ? jumlahAnggotaInput
                            .val() : MIN_ANGGOTA_TIM;
                        jumlahAnggotaInput.val(jumlah).prop('readonly', false);
                        renderAnggota(jumlah);
                    }
                } else {
                    jenisPrestasiSelect.prop('disabled', false);

                    const currentType = jenisPrestasiSelect.val();
                    if (currentType === 'individu') {
                        jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                        renderAnggota(JUMLAH_INDIVIDU);
                    } else if (currentType === 'tim') {
                        jumlahAnggotaInput.val(MIN_ANGGOTA_TIM).prop('readonly', false);
                        renderAnggota(MIN_ANGGOTA_TIM);
                    } else {
                        jenisPrestasiSelect.val('individu');
                        jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                        renderAnggota(JUMLAH_INDIVIDU);
                    }
                }
            } else {
                $('#form-tingkat-lomba').hide();
                $('#form-kategori-lomba').hide();
                $('#input-lomba-lainnya').hide();
                $('#kategori-lomba-manual').hide();

                jenisPrestasiSelect.prop('disabled', false);
                jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);
            }
        });

        // Event handler perubahan tipe prestasi
        jenisPrestasiSelect.on('change', function() {
            const tipe = $(this).val();
            if (tipe === 'individu') {
                jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);
            } else {
                // minimal anggota tim
                jumlahAnggotaInput.val(MIN_ANGGOTA_TIM).prop('readonly', false);
                renderAnggota(MIN_ANGGOTA_TIM);
            }
        });

        // Fungsi render input anggota tim (ketua + anggota)
        function renderAnggota(jumlah) {
            const container = $('#anggota-container');
            container.empty();

            for (let i = 0; i < jumlah; i++) {
                const label = i === 0 ? 'Ketua Tim' : `Anggota ${i}`;
                const requiredAttr = i === 0 ? 'required' : '';

                let options = `<option value="">-- Pilih ${label} --</option>`;
                daftarMahasiswa.forEach(mhs => {
                    options +=
                        `<option value="${mhs.mahasiswa_id}">${mhs.nim_mahasiswa} - ${mhs.nama_mahasiswa}</option>`;
                });

                const anggotaHtml = `
                <div class="form-group anggota-item">
                    <label class="col-form-label mt-2">${label} <span class="text-danger">*</span></label>
                    <select name="mahasiswa_id[]" class="form-control anggota-select" ${requiredAttr}>
                        ${options}
                    </select>
                </div>
            `;
                container.append(anggotaHtml);
            }

            // Set nilai anggota yang sudah ada jika ada data dari server
            @foreach ($anggotaTim as $index => $anggota)
                $(`select[name="mahasiswa_id[]"]:eq({{ $index }})`).val(
                    '{{ $anggota['mahasiswa_id'] }}');
            @endforeach
        }

        // Inisialisasi awal form berdasarkan data yang ada (misal edit mode)
        if ('{{ $prestasi->lomba_id }}' === '') {
            $('#input-lomba-lainnya').show();
            $('#kategori-lomba-manual').show();
            $('#form-tingkat-lomba').hide();
            $('#form-kategori-lomba').hide();
        }

        // Trigger manual change pada load page untuk inisialisasi form
        $('#lomba_id').trigger('change');

    });
</script>

<script>
    $(document).on('change', '.anggota-select', function() {
        const selectedVals = $('.anggota-select').map(function() {
            return $(this).val();
        }).get();

        const duplicates = selectedVals.filter((item, index) => selectedVals.indexOf(item) !== index);

        if (duplicates.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Duplikat Terdeteksi',
                text: 'Mahasiswa yang sama tidak boleh dipilih lebih dari sekali.'
            });

            $(this).val('').trigger('change'); // kosongkan input duplikat
        }
    });

    // Unbind dulu sebelum bind, supaya event submit tidak double
    $(document).off('submit', '#form-prestasi').on('submit', '#form-prestasi', function(e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = new FormData(this);

        $.ajax({
            url: actionUrl,
            method: 'POST', // Laravel handle PUT lewat @method('PUT') hidden input
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#btn-submit').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message || 'Data prestasi berhasil diperbarui'
                });

                $('#myModal').modal('hide');
                $('#prestasiTable').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan';
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    msg = Object.values(errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: msg
                });
            },
            complete: function() {
                $('#btn-submit').prop('disabled', false).text('Simpan');
            }
        });
    });
</script>


<div class="modal-header bg-info rounded">
    <h5 class="modal-title text-white"><i class="fas fa-eye mr-2"></i>Detail Pendaftaran Lomba</h5>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Lomba</label>
                        <input type="text" class="form-control" value="{{ $pendaftaran->lomba->nama_lomba ?? '-' }}"
                            disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Tipe Lomba</label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                value="{{ ucfirst($pendaftaran->lomba->tipe_lomba ?? '-') }}" disabled>
                            <div class="input-group-append">
                                @if (($pendaftaran->lomba->tipe_lomba ?? '') === 'individu')
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-user"></i>
                                    </span>
                                @else
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-users"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Status Pendaftaran</label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                value="{{ ucfirst($pendaftaran->status_pendaftaran ?? '') }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $status = $pendaftaran->status_pendaftaran ?? '';
                                    $statusClass = '';
                                    $statusIcon = '';

                                    switch (strtolower($status)) {
                                        case 'Terverifikasi':
                                        case 'terverifikasi':
                                            $statusClass = 'bg-success';
                                            $statusIcon = 'fas fa-check';
                                            break;
                                        case 'Ditolak':
                                        case 'ditolak':
                                            $statusClass = 'bg-danger';
                                            $statusIcon = 'fas fa-times';
                                            break;
                                        default:
                                            $statusClass = 'bg-warning';
                                            $statusIcon = 'fas fa-clock';
                                    }
                                @endphp
                                <span class="input-group-text {{ $statusClass }} text-white">
                                    <i class="{{ $statusIcon }}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Tanggal Daftar</label>
                        <input type="text" class="form-control"
                            value="{{ $pendaftaran->created_at ? $pendaftaran->created_at->translatedFormat('d F Y H:i') : '-' }}"
                            disabled>
                    </div>
                </div>
            </div>

            {{-- Menampilkan Alasan jika pendaftaran Lomba ditolak  --}}
            @if ($pendaftaran->status_pendaftaran === 'Nonaktif' || !empty($pendaftaran->alasan_tolak))
                <div class="mt-3">
                    {{-- <h5 class="font-weight-bold mb-3">Informasi Status Pendaftaran</h5> --}}

                    @if ($pendaftaran->status_pendaftaran === 'Nonaktif')
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Status</label>
                            <input type="text" class="form-control" value="Terhapus" disabled>
                        </div>
                    @endif

                    @if (!empty($pendaftaran->alasan_tolak))
                        <div class="form-group">
                            <label for="alasan_tolak" class="text-danger font-weight-bold">
                                <i class="fa fa-exclamation-circle"></i> Alasan Penolakan <span
                                    class="text-danger">*</span>
                            </label>
                            <textarea name="alasan_tolak" id="alasan_tolak" class="form-control" disabled>{{ $pendaftaran->alasan_tolak }}</textarea>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Informasi Peserta/Tim -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                @if (($pendaftaran->lomba->tipe_lomba ?? '') === 'individu')
                    <i class="fas fa-user mr-2"></i>Informasi Peserta
                @else
                    <i class="fas fa-users mr-2"></i>Informasi Tim
                    <span class="badge badge-primary ml-2">
                        {{ $pendaftaran->anggota->count() ?? 1 }} Anggota
                    </span>
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if (($pendaftaran->lomba->tipe_lomba ?? '') === 'individu')
                <!-- Lomba Individu -->
                <div class="peserta-section bg-light rounded p-3">
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-bold">
                            <i class="fas fa-user mr-2"></i>Peserta
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                value="{{ $pendaftaran->mahasiswa->nim_mahasiswa ?? '-' }} - {{ $pendaftaran->mahasiswa->nama_mahasiswa ?? '-' }}"
                                disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fas fa-user-check"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Lomba Tim -->
                @php
                    $anggotaTim = $pendaftaran->anggota ?? collect();
                    $ketuaTim =
                        $anggotaTim->where('pivot.is_ketua', true)->first() ??
                        ($anggotaTim->where('mahasiswa_id', $pendaftaran->mahasiswa_id)->first() ??
                            $pendaftaran->mahasiswa);

                    $anggotaLainnya = $anggotaTim->where('mahasiswa_id', '!=', $ketuaTim->mahasiswa_id ?? 0);
                @endphp

                <!-- Ketua Tim -->
                <div class="ketua-section bg-primary bg-opacity-10 rounded p-3 mb-3">
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-bold">
                            <i class="fas fa-crown mr-2 text-warning"></i>Ketua Tim
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control font-weight-bold"
                                value="{{ $ketuaTim->nim_mahasiswa ?? '-' }} - {{ $ketuaTim->nama_mahasiswa ?? '-' }}"
                                disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-warning text-dark">
                                    <i class="fas fa-crown"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Anggota Tim -->
                @if ($anggotaLainnya->count() > 0)
                    <div class="anggota-section">
                        <h6 class="mb-3">
                            <i class="fas fa-users mr-2"></i>Anggota Tim
                            <span class="badge badge-secondary">{{ $anggotaLainnya->count() }} orang</span>
                        </h6>

                        @foreach ($anggotaLainnya as $index => $anggota)
                            <div class="anggota-item bg-light rounded p-3 mb-2">
                                <div class="form-group mb-0">
                                    <label class="col-form-label font-weight-bold">
                                        <i class="fas fa-user mr-2"></i>Anggota {{ $index + 1 }}
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            value="{{ $anggota->nim_mahasiswa ?? '-' }} - {{ $anggota->nama_mahasiswa ?? '-' }}"
                                            disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-info text-white">
                                                <i class="fas fa-user-check"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Total Anggota -->
                <div class="total-anggota mt-3 p-2 bg-light rounded">
                    <div class="row">
                        <div class="col-6">
                            <strong><i class="fas fa-calculator mr-2"></i>Total Anggota:</strong>
                        </div>
                        <div class="col-6 text-right">
                            <span class="badge badge-primary badge-lg">
                                {{ $anggotaTim->count() }} Orang
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bukti Pendaftaran -->
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-file-image mr-2"></i>Bukti Pendaftaran</h6>
        </div>
        <div class="card-body">
            @if ($pendaftaran->bukti_pendaftaran)
                <div class="text-center">
                    <div class="file-preview mb-3">
                        <i class="fas fa-file-image fa-3x text-primary mb-2"></i>
                        <p class="mb-2">File berhasil diunggah</p>

                    </div>
                    <div class="btn-group">
                        <a href="{{ asset('uploads/bukti/' . $pendaftaran->bukti_pendaftaran) }}" target="_blank"
                            class="btn btn-primary">
                            <i class="fas fa-eye mr-2"></i>Lihat Bukti
                        </a>
                        <a href="{{ asset('uploads/bukti/' . $pendaftaran->bukti_pendaftaran) }}" download
                            class="btn btn-outline-primary">
                            <i class="fas fa-download mr-2"></i>Unduh
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="fas fa-file-times fa-3x mb-2"></i>
                    <p>Tidak ada file bukti pendaftaran yang diunggah</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
        <i class="fas fa-times mr-2"></i>Tutup
    </button>
</div>

<style>
    .peserta-section,
    .ketua-section,
    .anggota-item {
        border-left: 4px solid #7571F9;
    }

    .ketua-section {
        border-left-color: #ffc107 !important;
    }

    .anggota-item {
        border-left-color: #17a2b8 !important;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .bg-opacity-10 {
        background-color: rgba(117, 113, 249, 0.1) !important;
    }

    .file-preview {
        padding: 20px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .total-anggota {
        border: 1px solid #dee2e6;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);

        // Tooltip untuk status
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Force hide processing text after any modal operation
    setInterval(function() {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>

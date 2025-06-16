<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">
        <i class="fas fa-trophy mr-2"></i>Detail Lomba
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <!-- Informasi Lomba -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-info-circle mr-2"></i>Informasi Lomba
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Lomba</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $lomba->nama_lomba }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-trophy"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Tingkat Lomba</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $lomba->tingkat_lomba->nama_tingkat }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fas fa-level-up-alt"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Deskripsi Lomba</label>
                        <div class="input-group">
                            <textarea class="form-control" rows="3" disabled>{{ $lomba->deskripsi_lomba }}</textarea>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info text-white">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Kategori Lomba</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-warning text-white">
                                    <i class="fas fa-tags"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Penyelenggara</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $lomba->penyelenggara_lomba }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="fas fa-building"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Registrasi -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-calendar-check mr-2"></i>Periode Registrasi
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Awal Registrasi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fas fa-calendar-plus"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Akhir Registrasi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y') }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-danger text-white">
                                    <i class="fas fa-calendar-minus"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Link Pendaftaran</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $lomba->link_pendaftaran_lomba }}" disabled>
                            <div class="input-group-append">
                                <a href="{{ $lomba->link_pendaftaran_lomba }}" target="_blank" class="input-group-text bg-primary text-white" style="text-decoration: none;">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Status -->
            <div class="registration-status mt-3 p-3 bg-light rounded">
                <div class="row align-items-center">
                    <div class="col-6">
                        <strong>
                            <i class="fas fa-clock mr-2"></i>Status Registrasi:
                        </strong>
                    </div>
                    <div class="col-6 text-right">
                        @php
                            $now = \Carbon\Carbon::now();
                            $startReg = \Carbon\Carbon::parse($lomba->awal_registrasi_lomba);
                            $endReg = \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba);
                        @endphp
                        @if($now < $startReg)
                            <span class="badge badge-warning badge-lg">
                                <i class="fas fa-hourglass-start mr-1"></i>Belum Dibuka
                            </span>
                        @elseif($now >= $startReg && $now <= $endReg)
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-door-open mr-1"></i>Sedang Dibuka
                            </span>
                        @else
                            <span class="badge badge-danger badge-lg">
                                <i class="fas fa-door-closed mr-1"></i>Sudah Ditutup
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Duration Info -->
            <div class="duration-info mt-3 p-3 bg-light rounded">
                <div class="row align-items-center">
                    <div class="col-6">
                        <strong>
                            <i class="fas fa-calendar-day mr-2"></i>Durasi Registrasi:
                        </strong>
                    </div>
                    <div class="col-6 text-right">
                        @php
                            $duration = $startReg->diffInDays($endReg) + 1;
                        @endphp
                        <span class="badge badge-info badge-lg">
                            <i class="fas fa-clock mr-1"></i>{{ $duration }} Hari
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Poster Lomba -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-image mr-2"></i>Poster Lomba
            </h6>
        </div>
        <div class="card-body text-center">
            @if (!is_null($lomba->img_lomba) && file_exists(public_path('storage/img/lomba/' . $lomba->img_lomba)))
                <div class="poster-container">
                    <a href="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" data-lightbox="lomba"
                        data-title="Gambar Poster Lomba">
                        <img src="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" 
                             class="img-fluid img-thumbnail poster-image" 
                             alt="Gambar Poster Lomba" 
                             style="max-height: 300px; cursor: zoom-in;" />
                    </a>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-search-plus mr-1"></i>Klik gambar untuk memperbesar
                        </small>
                    </div>
                </div>
            @else
                <div class="no-poster p-4">
                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Gambar poster tidak tersedia</p>
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
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .registration-status, .duration-info {
        border: 1px solid #dee2e6;
        border-left: 4px solid #007bff;
    }

    .badge-lg {
        font-size: 0.95rem;
        padding: 0.5rem 0.75rem;
    }

    .form-group label {
        color: #495057;
        font-size: 0.9rem;
    }

    .input-group-text {
        border: none;
    }

    .form-control:disabled, .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .poster-container {
        position: relative;
    }

    .poster-image {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .poster-image:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        transform: scale(1.02);
    }

    .no-poster {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
    }

    /* Lightbox customization */
    .lightbox .lb-data {
        top: 0;
        bottom: auto;
        background: rgba(0, 0, 0, 0.7);
    }

    .lightbox .lb-data .lb-caption {
        color: #fff;
        padding: 10px;
        font-size: 16px;
        text-align: center;
    }

    .lightbox .lb-close {
        top: 10px;
        right: 10px;
    }

    /* Link styling */
    .input-group-text a {
        color: inherit !important;
    }

    .input-group-text a:hover {
        opacity: 0.8;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        
        // Tooltip untuk status
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

{{-- Library Lightbox untuk membesarkan gambar --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
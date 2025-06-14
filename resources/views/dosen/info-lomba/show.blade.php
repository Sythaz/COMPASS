<div class="modal-header bg-info rounded">
    <h5 class="modal-title text-white"><i class="fas fa-trophy mr-2"></i>Detail Lomba</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <!-- Informasi Dasar Lomba -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Dasar</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Lomba</label>
                        <input type="text" class="form-control" value="{{ $lomba->nama_lomba }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Pengusul</label>
                        <input type="text" class="form-control" value="{{ $namaPengusul }}" disabled>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Kategori</label>
                        <input type="text" class="form-control"
                            value="{{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui' }}"
                            disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Tingkat</label>
                        <input type="text" class="form-control" value="{{ $lomba->tingkat_lomba->nama_tingkat }}"
                            disabled>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Penyelenggara</label>
                        <input type="text" class="form-control" value="{{ $lomba->penyelenggara_lomba }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Status</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ strip_tags($badgeStatus) }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info text-white">
                                    <i class="fas fa-flag"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label font-weight-bold">Deskripsi</label>
                <textarea class="form-control" rows="3" disabled>{{ $lomba->deskripsi_lomba }}</textarea>
            </div>
        </div>
    </div>

    <!-- Informasi Pendaftaran -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Informasi Pendaftaran</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Awal Registrasi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $lomba->awal_registrasi_lomba }}"
                                disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fas fa-play"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Akhir Registrasi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $lomba->akhir_registrasi_lomba }}"
                                disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-danger text-white">
                                    <i class="fas fa-stop"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label font-weight-bold">Link Pendaftaran</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ $lomba->link_pendaftaran_lomba }}" disabled>
                    <div class="input-group-append">
                        <a href="{{ $lomba->link_pendaftaran_lomba }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Poster Lomba -->
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-image mr-2"></i>Poster Lomba</h6>
        </div>
        <div class="card-body">
            @if (!is_null($lomba->img_lomba) && file_exists(public_path('storage/img/lomba/' . $lomba->img_lomba)))
                <div class="text-center">
                    <div class="file-preview mb-3">
                        <a href="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" data-lightbox="lomba"
                            data-title="Poster Lomba - {{ $lomba->nama_lomba }}">
                            <img src="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" class="img-thumbnail"
                                style="max-width: 300px; cursor: zoom-in;" alt="Poster Lomba" />
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" target="_blank"
                            class="btn btn-primary">
                            <i class="fas fa-eye mr-2"></i>Lihat Poster
                        </a>
                        <a href="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" download
                            class="btn btn-outline-primary">
                            <i class="fas fa-download mr-2"></i>Unduh
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="fas fa-image-times fa-3x mb-2"></i>
                    <p>Tidak ada poster lomba yang tersedia</p>
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

    .img-thumbnail {
        transition: transform 0.3s ease;
    }

    .img-thumbnail:hover {
        transform: scale(1.05);
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function () {
        $('.card').hide().fadeIn(600);

        // Tooltip untuk status
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Force hide processing text after any modal operation
    setInterval(function () {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>

{{-- Library Lightbox untuk membesarkan gambar --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
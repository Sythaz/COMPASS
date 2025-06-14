<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">
        <i class="fas fa-layer-group mr-2"></i>Detail Tingkat Lomba
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <!-- Informasi Tingkat Lomba -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-medal mr-2"></i>Informasi Tingkat Lomba
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Tingkat Lomba</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $tingkatLomba->nama_tingkat }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $tingkat = strtolower($tingkatLomba->nama_tingkat ?? '');
                                    $tingkatClass = '';
                                    $tingkatIcon = '';

                                    switch ($tingkat) {
                                        case 'internasional':
                                            $tingkatClass = 'bg-warning';
                                            $tingkatIcon = 'fas fa-globe';
                                            break;
                                        case 'nasional':
                                            $tingkatClass = 'bg-danger';
                                            $tingkatIcon = 'fas fa-flag';
                                            break;
                                        case 'regional':
                                        case 'provinsi':
                                            $tingkatClass = 'bg-info';
                                            $tingkatIcon = 'fas fa-map-marker-alt';
                                            break;
                                        case 'lokal':
                                        case 'kota':
                                        case 'kabupaten':
                                            $tingkatClass = 'bg-success';
                                            $tingkatIcon = 'fas fa-building';
                                            break;
                                        default:
                                            $tingkatClass = 'bg-secondary';
                                            $tingkatIcon = 'fas fa-layer-group';
                                    }
                                @endphp
                                <span class="input-group-text {{ $tingkatClass }} text-white">
                                    <i class="{{ $tingkatIcon }}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Status Tingkat Lomba</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ucfirst($tingkatLomba->status_tingkat_lomba) }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $status = $tingkatLomba->status_tingkat_lomba ?? '';
                                    $statusClass = '';
                                    $statusIcon = '';

                                    switch (strtolower($status)) {
                                        case 'aktif':
                                            $statusClass = 'bg-success';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'nonaktif':
                                        case 'tidak aktif':
                                            $statusClass = 'bg-danger';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $statusClass = 'bg-warning';
                                            $statusIcon = 'fas fa-exclamation-circle';
                                    }
                                @endphp
                                <span class="input-group-text {{ $statusClass }} text-white">
                                    <i class="{{ $statusIcon }}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Level Indicator -->
            <div class="level-indicator mt-3 p-3 rounded">
                <div class="row align-items-center">
                    <div class="col-6">
                        <strong>
                            <i class="fas fa-trophy mr-2"></i>Level Prestise:
                        </strong>
                    </div>
                    <div class="col-6 text-right">
                        @php
                            $tingkatName = strtolower($tingkatLomba->nama_tingkat ?? '');
                            $levelClass = '';
                            $levelText = '';
                            $levelIcon = '';
                            
                            switch ($tingkatName) {
                                case 'internasional':
                                    $levelClass = 'badge-warning';
                                    $levelText = 'Tertinggi';
                                    $levelIcon = 'fas fa-crown';
                                    break;
                                case 'nasional':
                                    $levelClass = 'badge-danger';
                                    $levelText = 'Tinggi';
                                    $levelIcon = 'fas fa-star';
                                    break;
                                case 'regional':
                                case 'provinsi':
                                    $levelClass = 'badge-info';
                                    $levelText = 'Menengah';
                                    $levelIcon = 'fas fa-medal';
                                    break;
                                default:
                                    $levelClass = 'badge-success';
                                    $levelText = 'Dasar';
                                    $levelIcon = 'fas fa-award';
                            }
                        @endphp
                        <span class="badge {{ $levelClass }} badge-lg">
                            <i class="{{ $levelIcon }} mr-1"></i>{{ $levelText }}
                        </span>
                    </div>
                </div>
            </div>
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

    .level-indicator {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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

    .form-control:disabled {
        background-color: #f8f9fa;
        border-color: #dee2e6;
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
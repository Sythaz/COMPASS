<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">
        <i class="fas fa-graduation-cap mr-2"></i>Detail Program Studi
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <!-- Informasi Program Studi -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-university mr-2"></i>Informasi Program Studi
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Program Studi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $prodi->nama_prodi }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Status Program Studi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ucfirst($prodi->status_prodi) }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $status = $prodi->status_prodi ?? '';
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

            <!-- Status Summary -->
            <div class="status-summary mt-3 p-3 bg-light rounded">
                <div class="row align-items-center">
                    <div class="col-6">
                        <strong>
                            <i class="fas fa-info-circle mr-2"></i>Status Ringkasan:
                        </strong>
                    </div>
                    <div class="col-6 text-right">
                        @if($prodi->status_prodi === 'Aktif')
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-check mr-1"></i>Program Studi Aktif
                            </span>
                        @else
                            <span class="badge badge-danger badge-lg">
                                <i class="fas fa-times mr-1"></i>Program Studi Nonaktif
                            </span>
                        @endif
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

    .status-summary {
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
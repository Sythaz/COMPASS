<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white"><i class="fas fa-eye mr-2"></i>Detail Kategori</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <!-- Informasi Kategori -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-tags mr-2"></i>Informasi Kategori</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Kategori</label>
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                value="{{ $kategori->nama_kategori }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info text-white">
                                    <i class="fas fa-tag"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Status Kategori</label>
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                value="{{ ucfirst($kategori->status_kategori) }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $status = strtolower($kategori->status_kategori ?? '');
                                    $statusClass = '';
                                    $statusIcon = '';
                                    
                                    switch($status) {
                                        case 'aktif':
                                            $statusClass = 'bg-success';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'nonaktif':
                                        case 'tidak aktif':
                                            $statusClass = 'bg-danger';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        case 'pending':
                                        case 'menunggu':
                                            $statusClass = 'bg-warning';
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        default:
                                            $statusClass = 'bg-secondary';
                                            $statusIcon = 'fas fa-question-circle';
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

            <!-- Deskripsi Kategori (jika ada) -->
            @if(isset($kategori->deskripsi_kategori) && !empty($kategori->deskripsi_kategori))
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Deskripsi Kategori</label>
                        <textarea class="form-control" rows="3" disabled>{{ $kategori->deskripsi_kategori }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            <!-- Informasi Tambahan -->
            <div class="mt-3">
                <div class="category-info bg-light rounded p-3">
                    <div class="row">
                        <div class="col-8">
                            <strong><i class="fas fa-info-circle mr-2"></i>ID Kategori:</strong>
                        </div>
                        <div class="col-4 text-right">
                            <span class="badge badge-secondary badge-lg">
                                #{{ $kategori->id ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Badge Summary -->
            <div class="mt-3 text-center">
                @php
                    $status = strtolower($kategori->status_kategori ?? '');
                    $badgeClass = '';
                    $badgeText = '';
                    
                    switch($status) {
                        case 'aktif':
                            $badgeClass = 'badge-success';
                            $badgeText = 'Kategori ini sedang aktif dan dapat digunakan';
                            break;
                        case 'nonaktif':
                        case 'tidak aktif':
                            $badgeClass = 'badge-danger';
                            $badgeText = 'Kategori ini tidak aktif dan tidak dapat digunakan';
                            break;
                        case 'pending':
                        case 'menunggu':
                            $badgeClass = 'badge-warning';
                            $badgeText = 'Kategori ini sedang menunggu persetujuan';
                            break;
                        default:
                            $badgeClass = 'badge-secondary';
                            $badgeText = 'Status kategori tidak diketahui';
                    }
                @endphp
                <div class="alert alert-light border-0">
                    <span class="badge {{ $badgeClass }} px-3 py-2">
                        <i class="fas fa-info mr-1"></i>{{ $badgeText }}
                    </span>
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
    .category-info {
        border-left: 4px solid #7571F9;
        border: 1px solid #dee2e6;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .alert {
        background-color: rgba(248, 249, 250, 0.8) !important;
    }

    .badge {
        font-size: 0.9rem;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        
        // Tooltip untuk status
        $('[data-toggle="tooltip"]').tooltip();
        
        // Animasi badge
        $('.badge').addClass('animate__animated animate__pulse');
    });

    // Force hide processing text after any modal operation
    setInterval(function() {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>
<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white"><i class="fas fa-user-shield mr-2"></i>Detail Admin</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <!-- Informasi Personal -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-id-card mr-2"></i>Informasi Personal</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">NIP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $admin->nip_admin }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-id-badge"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Lengkap</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $admin->nama_admin }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info text-white">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Jenis Kelamin</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $admin->kelamin ?? '-' }}" disabled>
                            <div class="input-group-append">
                                @if (($admin->kelamin ?? '') === 'Laki-laki' || ($admin->kelamin ?? '') === 'L')
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-mars"></i>
                                    </span>
                                @else
                                    <span class="input-group-text bg-danger text-white">
                                        <i class="fas fa-venus"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Status</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ucfirst($admin->status ?? '-') }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $status = strtolower($admin->status ?? '');
                                    $statusClass = '';
                                    $statusIcon = '';

                                    switch ($status) {
                                        case 'aktif':
                                        case 'active':
                                            $statusClass = 'bg-success';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'nonaktif':
                                        case 'inactive':
                                            $statusClass = 'bg-danger';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $statusClass = 'bg-warning';
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
        </div>
    </div>

    <!-- Informasi Kontak -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-address-book mr-2"></i>Informasi Kontak</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" value="{{ $admin->email ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-warning text-dark">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">No Handphone</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $admin->no_hp ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fas fa-phone"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label font-weight-bold">Alamat</label>
                <div class="input-group">
                    <textarea class="form-control" rows="2" disabled>{{ $admin->alamat ?? '-' }}</textarea>
                    <div class="input-group-append">
                        <span class="input-group-text bg-info text-white">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Akun -->
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-user-cog mr-2"></i>Informasi Akun</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Username</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $admin->users->username ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Role</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-weight-bold" value="{{ ucfirst($admin->users->role ?? '-') }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $role = strtolower($admin->users->role ?? '');
                                    $roleClass = '';
                                    $roleIcon = '';

                                    switch ($role) {
                                        case 'admin':
                                            $roleClass = 'bg-danger';
                                            $roleIcon = 'fas fa-user-shield';
                                            break;
                                        case 'superadmin':
                                        case 'super_admin':
                                            $roleClass = 'bg-dark';
                                            $roleIcon = 'fas fa-crown';
                                            break;
                                        default:
                                            $roleClass = 'bg-primary';
                                            $roleIcon = 'fas fa-user';
                                    }
                                @endphp
                                <span class="input-group-text {{ $roleClass }} text-white">
                                    <i class="{{ $roleIcon }}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times mr-2"></i>Tutup
    </button>
</div>

<style>
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }

    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa !important;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
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
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-user-shield me-2"></i>Detail Admin
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <dl class="row">
        <dt class="col-sm-4 fw-semibold text-secondary">NIP</dt>
        <dd class="col-sm-8">{{ $admin->nip_admin }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Nama</dt>
        <dd class="col-sm-8">{{ $admin->nama_admin }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Username</dt>
        <dd class="col-sm-8">{{ $admin->users->username ?? '-' }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Role</dt>
        <dd class="col-sm-8">
            @if(isset($admin->users->role))
                <span class="badge bg-info text-dark text-uppercase">
                    {{ $admin->users->role }}
                </span>
            @else
                <span class="text-muted">-</span>
            @endif
        </dd>
    </dl>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class=""></i>Tutup
    </button>
</div>
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-user-tie me-2"></i>Detail Dosen
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <dl class="row">
        <dt class="col-sm-4 fw-semibold text-secondary">NIP</dt>
        <dd class="col-sm-8">{{ $dosen->nip_dosen }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Nama</dt>
        <dd class="col-sm-8">{{ $dosen->nama_dosen }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Username</dt>
        <dd class="col-sm-8">{{ $dosen->users?->username ?? '-' }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Role</dt>
        <dd class="col-sm-8">
            @if(isset($dosen->users->role))
                <span class="badge bg-info text-dark text-uppercase">
                    {{ $dosen->users->role }}
                </span>
            @else
                <span class="text-muted">-</span>
            @endif
        </dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Bidang</dt>
        <dd class="col-sm-8">{{ $dosen->kategori->nama_kategori ?? '-' }}</dd>
    </dl>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        Tutup
    </button>
</div>
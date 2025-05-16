<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-user-graduate me-2"></i>Detail Mahasiswa
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <dl class="row">
        <dt class="col-sm-4 fw-semibold text-secondary">NIM</dt>
        <dd class="col-sm-8">{{ $mahasiswa->nim_mahasiswa }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Nama</dt>
        <dd class="col-sm-8">{{ $mahasiswa->nama_mahasiswa }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Username</dt>
        <dd class="col-sm-8">{{ $mahasiswa->users?->username ?? '-' }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Role</dt>
        <dd class="col-sm-8">
            @if(isset($mahasiswa->users->role))
                <span class="badge bg-info text-dark text-uppercase">
                    {{ $mahasiswa->users->role }}
                </span>
            @else
                <span class="text-muted">-</span>
            @endif
        </dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Program Studi</dt>
        <dd class="col-sm-8">{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Periode</dt>
        <dd class="col-sm-8">{{ $mahasiswa->periode->semester_periode ?? '-' }}</dd>

        <dt class="col-sm-4 fw-semibold text-secondary">Level Minat Bakat</dt>
        <dd class="col-sm-8">{{ $mahasiswa->level_minat_bakat->level_minbak ?? '-' }}</dd>
    </dl>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        Tutup
    </button>
</div>
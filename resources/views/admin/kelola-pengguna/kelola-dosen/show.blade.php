<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white"><i class="fas fa-chalkboard-teacher mr-2"></i>Detail Dosen</h5>
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
                <!-- NIP -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">NIP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $dosen->nip_dosen }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info text-white">
                                    <i class="fas fa-id-badge"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nama Lengkap -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Nama Lengkap</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $dosen->nama_dosen }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Jenis Kelamin -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Jenis Kelamin</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $dosen->kelamin === 'L' ? 'Laki-laki' : ($dosen->kelamin === 'P' ? 'Perempuan' : '-') }}" disabled>
                            <div class="input-group-append">
                                @if (($dosen->kelamin ?? '') === 'Laki-laki' || ($dosen->kelamin ?? '') === 'L')
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-mars"></i></span>
                                @else
                                <span class="input-group-text bg-danger text-white"><i class="fas fa-venus"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Status -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Status</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ ucfirst($dosen->status ?? '-') }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $st = strtolower($dosen->status ?? '');
                                    $cls = $st === 'aktif' ? 'bg-success' : ($st === 'nonaktif' ? 'bg-danger' : 'bg-warning');
                                    $icon = $st === 'aktif' ? 'fas fa-check-circle' : ($st === 'nonaktif' ? 'fas fa-times-circle' : 'fas fa-question-circle');
                                @endphp
                                <span class="input-group-text {{ $cls }} text-white"><i class="{{ $icon }}"></i></span>
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
                <!-- Email -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" value="{{ $dosen->email ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-warning text-dark"><i class="fas fa-envelope"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- No HP -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">No Handphone</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $dosen->no_hp ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white"><i class="fas fa-phone"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alamat -->
            <div class="form-group">
                <label class="col-form-label font-weight-bold">Alamat</label>
                <div class="input-group">
                    <textarea class="form-control" rows="2" disabled>{{ $dosen->alamat ?? '-' }}</textarea>
                    <div class="input-group-append">
                        <span class="input-group-text bg-info text-white"><i class="fas fa-map-marker-alt"></i></span>
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
                <!-- Username -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Username</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $dosen->users->username ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-secondary text-white"><i class="fas fa-user-tag"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Role -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Role</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-weight-bold" value="{{ ucfirst($dosen->users->role ?? '-') }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $r = strtolower($dosen->users->role ?? '');
                                    $cls = $r === 'dosen' ? 'bg-primary' : 'bg-warning';
                                    $ic = $r === 'dosen' ? 'fas fa-chalkboard-teacher' : 'fas fa-user';
                                @endphp
                                <span class="input-group-text {{ $cls }} text-white"><i class="{{ $ic }}"></i></span>
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
    .form-group { margin-bottom: 1rem; }
    .input-group-text { min-width: 45px; justify-content: center; }
    .card { border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card-header { border-bottom: 1px solid #dee2e6; background-color: #f8f9fa !important; }
    .modal-header, .modal-footer { border-color: #dee2e6; }
</style>

<script>
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
    });
    setInterval(function(){ if($('.modal').is(':hidden')) $('.processing, [class*="processing"]').hide(); }, 500);
</script>

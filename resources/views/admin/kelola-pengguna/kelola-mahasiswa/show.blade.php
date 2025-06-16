<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white"><i class="fas fa-user-graduate mr-2"></i>Detail Mahasiswa</h5>
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
                <!-- NIM -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">NIM</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $mahasiswa->nim_mahasiswa }}" disabled>
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
                            <input type="text" class="form-control" value="{{ $mahasiswa->nama_mahasiswa }}" disabled>
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
                            <input type="text" class="form-control" value="{{ $mahasiswa->kelamin === 'L' ? 'Laki-laki' : ($mahasiswa->kelamin === 'P' ? 'Perempuan' : '-') }}" disabled>
                            <div class="input-group-append">
                                @if (($mahasiswa->kelamin ?? '') === 'Laki-laki' || ($mahasiswa->kelamin ?? '') === 'L')
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
                            <input type="text" class="form-control" value="{{ ucfirst($mahasiswa->status ?? '-') }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $st = strtolower($mahasiswa->status ?? '');
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

    <!-- Informasi Akademik -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Informasi Akademik</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Program Studi -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Program Studi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $mahasiswa->prodi->nama_prodi ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-university"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Periode -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Periode</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $mahasiswa->periode->semester_periode ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info text-white"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Angkatan -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Angkatan</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $mahasiswa->angkatan ?? '-' }}" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text bg-warning text-dark"><i class="fas fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Minat Bakat -->
            <div class="form-group">
                <label class="col-form-label font-weight-bold">Minat Bakat</label>
                <div class="input-group">
                    <textarea class="form-control" rows="2" disabled>{{ $mahasiswa->kategoris->pluck('nama_kategori')->implode(', ') ?: '-' }}</textarea>
                    <div class="input-group-append">
                        <span class="input-group-text bg-secondary text-white"><i class="fas fa-star"></i></span>
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
                            <input type="email" class="form-control" value="{{ $mahasiswa->email ?? '-' }}" disabled>
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
                            <input type="text" class="form-control" value="{{ $mahasiswa->no_hp ?? '-' }}" disabled>
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
                    <textarea class="form-control" rows="2" disabled>{{ $mahasiswa->alamat ?? '-' }}</textarea>
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
                            <input type="text" class="form-control" value="{{ $mahasiswa->users?->username ?? '-' }}" disabled>
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
                            <input type="text" class="form-control font-weight-bold" value="{{ ucfirst($mahasiswa->users->role ?? '-') }}" disabled>
                            <div class="input-group-append">
                                @php
                                    $r = strtolower($mahasiswa->users->role ?? '');
                                    $cls = $r === 'mahasiswa' ? 'bg-success' : 'bg-warning';
                                    $ic = $r === 'mahasiswa' ? 'fas fa-user-graduate' : 'fas fa-user';
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
    
    .form-group { 
        margin-bottom: 1.2rem; 
    }
    
    .input-group-text { 
        min-width: 45px; 
        justify-content: center; 
        font-size: 0.9rem;
    }
    
    .card { 
        border: none; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    
    .card-header { 
        border-bottom: 1px solid #e9ecef; 
        background-color: #f8f9fa !important;
        padding: 1rem 1.25rem;
        border-radius: 8px 8px 0 0 !important;
    }
    
    .card-body {
        padding: 1.5rem 1.25rem;
    }
    
    .modal-header, .modal-footer { 
        border-color: #e9ecef; 
    }
    
    .modal-header {
        padding: 1.2rem 1.5rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
    }
    
    .form-control[disabled] {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #495057;
    }
    
    .col-form-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.3rem;
    }
    
    textarea.form-control {
        resize: none;
        min-height: 60px;
    }
</style>

<script>
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
    });
    setInterval(function(){ if($('.modal').is(':hidden')) $('.processing, [class*="processing"]').hide(); }, 500);
</script>
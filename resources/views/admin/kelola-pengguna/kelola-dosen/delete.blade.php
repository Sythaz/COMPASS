<form id="form-delete-dosen" method="POST" action="{{ url('admin/kelola-pengguna/dosen/' . $dosen->dosen_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-danger rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-user-lock mr-2"></i>Nonaktifkan Dosen
        </h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Alert Konfirmasi -->
        <div class="alert alert-danger border-left-danger">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                <div>
                    <strong class="h5 mb-1">Konfirmasi Penonaktifan Data</strong>
                    <hr class="my-2">
                    <p class="mb-0">
                        Data pengguna akan tetap tersimpan di dalam sistem, namun statusnya akan diubah menjadi
                        <strong>Nonaktif</strong>. Proses ini dapat dibatalkan kapan saja jika diperlukan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Informasi Dosen -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i>Informasi Dosen</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">NIP Dosen</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $dosen->nip_dosen }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Nama Dosen</label>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Bidang Keahlian</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                       value="{{ $dosen->kategoris->pluck('nama_kategori')->implode(', ') ?: '-' }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-graduation-cap"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $dosen->users->username ?? '-' }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-warning text-dark">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bidang Keahlian Detail (jika lebih dari 1) -->
                @if($dosen->kategoris && $dosen->kategoris->count() > 1)
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">
                                <i class="fas fa-list mr-2"></i>Detail Bidang Keahlian
                            </label>
                            <div class="bidang-keahlian-list bg-light rounded p-3">
                                @foreach($dosen->kategoris as $index => $kategori)
                                    <span class="badge badge-secondary mr-2 mb-2">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $kategori->nama_kategori }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Status Information -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Status</h6>
            </div>
            <div class="card-body">
                <div class="status-info bg-danger bg-opacity-10 rounded p-3">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="form-group mb-0">
                                <label class="col-form-label font-weight-bold">
                                    <i class="fas fa-user-lock mr-2 text-danger"></i>Status Setelah Penonaktifan
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control font-weight-bold text-danger" 
                                           value="Nonaktif" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="status-badge">
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-user-slash mr-1"></i>
                                    Akan Dinonaktifkan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="mt-3 p-2 bg-light rounded">
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Catatan:</strong> Dosen yang dinonaktifkan tidak akan dapat login ke sistem dan tidak akan muncul dalam daftar dosen aktif.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-user-lock mr-2"></i>Nonaktifkan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<style>
    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
    }

    .status-info {
        border-left: 4px solid #dc3545;
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .bg-opacity-10 {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .alert-danger {
        border-left: 4px solid #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.2);
    }

    .status-badge {
        padding: 10px;
        border: 2px dashed #dc3545;
        border-radius: 8px;
        background-color: rgba(220, 53, 69, 0.05);
    }

    .bidang-keahlian-list {
        border-left: 4px solid #28a745;
        min-height: 50px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .badge-secondary {
        background-color: #6c757d;
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);

        // Tooltip untuk status
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).off('submit', '#form-delete-dosen'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-delete-dosen', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            type: 'DELETE',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        // Tutup modal, pastikan id modal benar
                        var modalEl = document.querySelector('.modal.show');
                        if (modalEl) {
                            let modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }

                        // Reload tabel DataTables (ganti sesuai id tabel dosen)
                        $('#tabel-dosen').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Hapus gagal.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    });

    // Force hide processing text after any modal operation
    setInterval(function() {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>
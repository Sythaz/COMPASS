<form id="form-delete" method="POST" action="{{ url('admin/master-data/kategori-keahlian/' . $kategori->kategori_id) . '/delete' }}">
    @csrf
    @method('PUT')

    <div class="modal-header bg-warning rounded">
        <h5 class="modal-title text-dark"><i class="fas fa-archive mr-2"></i>Nonaktifkan Kategori Keahlian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Alert Konfirmasi -->
        <div class="alert alert-warning border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x mr-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Konfirmasi Nonaktifkan Kategori Keahlian</h5>
                    <p class="mb-0">Data akan tetap tersimpan di database, hanya statusnya yang akan diubah dari <strong>"Aktif"</strong> menjadi <strong>"Nonaktif"</strong>.</p>
                </div>
            </div>
        </div>

        <!-- Informasi Kategori -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-tags mr-2"></i>Detail Kategori Keahlian</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Nama Kategori</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $kategori->nama_kategori }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($kategori->kode_kategori))
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Kode Kategori</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $kategori->kode_kategori }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-code"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Status Saat Ini</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $kategori->status ?? 'Aktif' }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Status Change Info -->
                <div class="status-change mt-3 p-3 bg-light rounded border-left-warning">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-arrow-right fa-2x text-warning mr-3"></i>
                                <div>
                                    <small class="text-muted">Status akan berubah menjadi:</small>
                                    <div class="font-weight-bold text-warning">NONAKTIF</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <span class="badge badge-warning badge-lg">
                                <i class="fas fa-pause mr-1"></i>Akan Dinonaktifkan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Tambahan -->
        <div class="card border-info">
            <div class="card-body text-center">
                <div class="info-section bg-info bg-opacity-10 rounded p-3">
                    <i class="fas fa-info-circle fa-2x text-info mb-2"></i>
                    <h6 class="text-info font-weight-bold">Informasi Penting</h6>
                    <p class="text-muted small mb-0">
                        Kategori Keahlian yang dinonaktifkan tidak akan muncul dalam pilihan baru, 
                        tetapi data historis tetap tersimpan dan dapat diaktifkan kembali jika diperlukan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-archive mr-2"></i>Ya, Nonaktifkan Kategori Keahlian
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<style>
    .info-section {
        border-left: 4px solid #17a2b8;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .bg-opacity-10 {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }

    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .status-change {
        border: 1px solid #dee2e6;
    }

    .alert {
        border-radius: 10px;
    }

    .form-control:disabled {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .text-warning {
        color: #856404 !important;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        $('.alert').hide().slideDown(500);

        // Tooltip untuk elemen
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).off('submit', '#form-delete'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-delete', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        // Tambah loading state
        let submitBtn = form.find('button[type="submit"]');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        // Tutup modal
                        $('#myModal').modal('hide');

                        // Reload tabel DataTables
                        $('#tabel-kategori-keahlian').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Nonaktifkan gagal.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat memproses data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            },
            complete: function() {
                // Kembalikan state button
                submitBtn.html(originalText).prop('disabled', false);
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
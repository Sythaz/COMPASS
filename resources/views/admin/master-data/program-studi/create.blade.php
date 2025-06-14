<form id="form-create" method="POST" action="{{ url('admin/master-data/program-studi/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Program Studi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Form Program Studi -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Informasi Program Studi</h6>
            </div>
            <div class="card-body">
                <div class="form-section bg-light rounded p-3">
                    <div class="form-group mb-0">
                        <label for="nama_prodi" class="col-form-label font-weight-bold">
                            <i class="fas fa-university mr-2"></i>Nama Program Studi 
                            <span class="text-danger" style="color: red;">*</span>
                        </label>
                        <div class="custom-validation">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama_prodi" id="nama_prodi" 
                                       placeholder="Masukkan nama program studi" required>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-graduation-cap"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="error-text text-danger mt-1" id="error-nama_prodi"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Petunjuk</h6>
            </div>
            <div class="card-body">
                <div class="info-section p-2 bg-light rounded">
                    <div class="text-muted">
                        <i class="fas fa-lightbulb mr-2 text-warning"></i>
                        <small>
                            Pastikan nama program studi yang dimasukkan sudah benar dan sesuai dengan 
                            standar penamaan program studi di institusi.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<style>
    .form-section {
        border-left: 4px solid #007bff;
    }

    .info-section {
        border: 1px solid #dee2e6;
        border-left: 4px solid #ffc107;
    }

    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .form-group .input-group {
        transition: all 0.3s ease;
    }

    .form-group .input-group:focus-within {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .error-text {
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        
        // Focus pada input pertama
        $('#nama_prodi').focus();
        
        // Tooltip untuk info
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Custom form validation
    customFormValidation(
        // ID form untuk validasi
        "#form-create", {
            // Field yang akan di validasi
            nama_prodi: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            nama_prodi: {
                required: "Program studi wajib diisi",
                minlength: "Program studi minimal 3 karakter",
                maxlength: "Program studi maksimal 100 karakter"
            }
        },

        function(response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    timerProgressBar: true
                }).then(function() {
                    // Tutup modal
                    $('#myModal').modal('hide');

                    // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                    $('#tabel-program-studi').DataTable().ajax.reload();
                });

            } else {
                $('.error-text').text('');
                $.each(response.msgField, function(prefix, val) {
                    $('#error-' + prefix).text(val[0]);
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: response.message,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        }
    );

    // Force hide processing text after any modal operation
    setInterval(function() {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>
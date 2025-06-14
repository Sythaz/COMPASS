<form id="form-create" method="POST" action="{{ url('admin/master-data/kategori-keahlian/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Kategori Keahlian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Informasi Kategori Keahlian -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-star mr-2"></i>Informasi Kategori Keahlian</h6>
            </div>
            <div class="card-body">
                <div class="form-section bg-light rounded p-3">
                    <div class="form-group mb-0">
                        <label for="nama_kategori" class="col-form-label font-weight-bold">
                            <i class="fas fa-tags mr-2"></i>Nama Kategori 
                            <span class="text-danger" style="color: red;">*</span>
                        </label>
                        <div class="custom-validation">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama_kategori" 
                                       id="nama_kategori" placeholder="Masukkan nama kategori keahlian" required>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-star"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="error-text text-danger mt-1" id="error-nama_kategori"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Kategori -->
        <div class="card mb-3" id="preview-section" style="display: none;">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-eye mr-2"></i>Preview Kategori</h6>
            </div>
            <div class="card-body">
                <div class="preview-section bg-light rounded p-3">
                    <div class="d-flex align-items-center">
                        <div class="preview-icon mr-3">
                            <i class="fas fa-star fa-2x text-primary"></i>
                        </div>
                        <div class="preview-content">
                            <h6 class="mb-1 font-weight-bold" id="preview-name">Nama Kategori</h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar mr-1"></i>
                                Dibuat: <span id="preview-date"></span>
                            </small>
                        </div>
                        <div class="ml-auto">
                            <span class="badge badge-primary badge-lg">
                                <i class="fas fa-tag mr-1"></i>Kategori Baru
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Petunjuk dan Contoh -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Petunjuk & Contoh</h6>
            </div>
            <div class="card-body">
                <div class="info-section p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <i class="fas fa-lightbulb mr-2 text-warning"></i>
                                <small class="text-muted">
                                    <strong>Format Penulisan:</strong>
                                    <br>• Gunakan huruf kapital di awal kata
                                    <br>• Hindari singkatan yang tidak jelas
                                    <br>• Maksimal 50 karakter
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="examples-section">
                                <i class="fas fa-list-ul mr-2 text-info"></i>
                                <small class="text-muted">
                                    <strong>Contoh Kategori:</strong>
                                    <br>• Teknologi Informasi
                                    <br>• Desain Grafis
                                    <br>• Manajemen Bisnis
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-2 border-top">
                        <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
                        <small class="text-muted">
                            <strong>Catatan:</strong> Pastikan kategori yang dibuat tidak duplikat dengan yang sudah ada
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
        transition: all 0.3s ease;
    }

    .form-section:hover {
        border-left-color: #0056b3;
        background-color: #f8f9fa !important;
    }

    .preview-section {
        border-left: 4px solid #28a745;
        transition: all 0.3s ease;
    }

    .info-section {
        border: 1px solid #dee2e6;
        border-left: 4px solid #17a2b8;
    }

    .examples-section {
        padding-left: 1rem;
        border-left: 2px solid #6c757d;
    }

    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .form-group .input-group {
        transition: all 0.3s ease;
    }

    .form-group .input-group:focus-within {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.15);
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .error-text {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .badge-lg {
        font-size: 0.9rem;
        padding: 0.4rem 0.6rem;
    }

    .preview-icon {
        transition: all 0.3s ease;
    }

    .preview-section:hover .preview-icon {
        transform: scale(1.1);
    }

    .animate-slide-down {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from { 
            opacity: 0; 
            transform: translateY(-20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .char-counter {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: right;
        margin-top: 0.25rem;
    }

    .char-counter.warning {
        color: #ffc107;
    }

    .char-counter.danger {
        color: #dc3545;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        
        // Focus pada input pertama
        $('#nama_kategori').focus();
        
        // Tooltip untuk info
        $('[data-toggle="tooltip"]').tooltip();
        
        // Set tanggal preview
        const today = new Date();
        const formattedDate = today.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        $('#preview-date').text(formattedDate);
    });

    // Live preview functionality
    $('#nama_kategori').on('input', function() {
        const value = $(this).val().trim();
        const maxLength = 50;
        const currentLength = value.length;
        
        // Update preview
        if (value.length > 0) {
            $('#preview-name').text(value);
            $('#preview-section').fadeIn().addClass('animate-slide-down');
            
            // Add pulse animation
            $('#preview-section .preview-icon i').addClass('animate-pulse');
            setTimeout(() => {
                $('#preview-section .preview-icon i').removeClass('animate-pulse');
            }, 2000);
        } else {
            $('#preview-section').fadeOut();
        }
        
        // Character counter
        let counterHtml = `<div class="char-counter">`;
        if (currentLength > maxLength * 0.8) {
            if (currentLength > maxLength) {
                counterHtml += `<span class="danger">${currentLength}/${maxLength} karakter (melebihi batas)</span>`;
            } else {
                counterHtml += `<span class="warning">${currentLength}/${maxLength} karakter</span>`;
            }
        } else {
            counterHtml += `<span>${currentLength}/${maxLength} karakter</span>`;
        }
        counterHtml += `</div>`;
        
        // Remove existing counter and add new one
        $('.char-counter').remove();
        $('#nama_kategori').parent().parent().append(counterHtml);
    });

    // Auto capitalize first letter of each word
    $('#nama_kategori').on('blur', function() {
        const value = $(this).val();
        const capitalizedValue = value.replace(/\b\w/g, function(match) {
            return match.toUpperCase();
        });
        $(this).val(capitalizedValue);
        
        // Update preview
        if (capitalizedValue.trim().length > 0) {
            $('#preview-name').text(capitalizedValue);
        }
    });

    // Custom form validation
    customFormValidation(
        // ID form untuk validasi
        "#form-create", {
            // Field yang akan di validasi
            nama_kategori: {
                required: true,
                minlength: 3,
                maxlength: 50
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            nama_kategori: {
                required: "Nama kategori wajib diisi",
                minlength: "Nama kategori minimal 3 karakter",
                maxlength: "Nama kategori maksimal 50 karakter"
            }
        },

        function(response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(function() {
                    // Tutup modal
                    $('#myModal').modal('hide');

                    // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                    $('#tabel-kategori-keahlian').DataTable().ajax.reload();
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

    // Additional validation for duplicate checking (if needed)
    $('#form-create').on('submit', function(e) {
        const kategoriName = $('#nama_kategori').val().trim();
        
        if (kategoriName.length < 3) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Nama kategori terlalu pendek, minimal 3 karakter',
                timer: 3000,
                timerProgressBar: true
            });
            return false;
        }
        
        if (kategoriName.length > 50) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Nama kategori terlalu panjang, maksimal 50 karakter',
                timer: 3000,
                timerProgressBar: true
            });
            return false;
        }
    });

    // Force hide processing text after any modal operation
    setInterval(function() {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>
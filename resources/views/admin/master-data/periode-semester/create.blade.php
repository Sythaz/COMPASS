<form id="form-create" method="POST" action="{{ url('admin/master-data/periode-semester/store') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Tambah Periode Semester</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Informasi Periode Semester -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Informasi Periode Semester</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-section bg-light rounded p-3 mb-3">
                            <div class="form-group mb-0">
                                <label for="semester_periode" class="col-form-label font-weight-bold">
                                    <i class="fas fa-tag mr-2"></i>Periode Semester 
                                    <span class="text-danger" style="color: red;">*</span>
                                </label>
                                <div class="custom-validation">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="semester_periode" 
                                               id="semester_periode" placeholder="Contoh: 2024/2025 Ganjil" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fas fa-graduation-cap"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="error-text text-danger mt-1" id="error-semester_periode"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rentang Waktu -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section bg-light rounded p-3">
                            <div class="form-group mb-0">
                                <label for="tanggal_mulai" class="col-form-label font-weight-bold">
                                    <i class="fas fa-play mr-2 text-success"></i>Tanggal Mulai 
                                    <span class="text-danger" style="color: red;">*</span>
                                </label>
                                <div class="custom-validation">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="tanggal_mulai" 
                                               id="tanggal_mulai" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-calendar-plus"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="error-text text-danger mt-1" id="error-tanggal_mulai"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-section bg-light rounded p-3">
                            <div class="form-group mb-0">
                                <label for="tanggal_akhir" class="col-form-label font-weight-bold">
                                    <i class="fas fa-stop mr-2 text-danger"></i>Tanggal Akhir 
                                    <span class="text-danger" style="color: red;">*</span>
                                </label>
                                <div class="custom-validation">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="tanggal_akhir" 
                                               id="tanggal_akhir" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-danger text-white">
                                                <i class="fas fa-calendar-minus"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="error-text text-danger mt-1" id="error-tanggal_akhir"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Durasi Otomatis -->
                <div class="durasi-section mt-3 p-2 bg-light rounded" id="durasi-info" style="display: none;">
                    <div class="row">
                        <div class="col-6">
                            <strong><i class="fas fa-clock mr-2"></i>Durasi Semester:</strong>
                        </div>
                        <div class="col-6 text-right">
                            <span class="badge badge-info badge-lg" id="durasi-text">
                                -
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Petunjuk Pengisian -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Petunjuk Pengisian</h6>
            </div>
            <div class="card-body">
                <div class="info-section p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <i class="fas fa-lightbulb mr-2 text-warning"></i>
                                <small class="text-muted">
                                    <strong>Format Periode:</strong> Gunakan format "YYYY/YYYY Semester" 
                                    <br>Contoh: "2024/2025 Ganjil" atau "2024/2025 Genap"
                                </small>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-calendar-check mr-2 text-info"></i>
                                <small class="text-muted">
                                    <strong>Rentang Tanggal:</strong> Pastikan tanggal akhir lebih besar dari tanggal mulai
                                </small>
                            </div>
                            <div>
                                <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
                                <small class="text-muted">
                                    <strong>Perhatian:</strong> Periode semester yang sudah dibuat tidak dapat diubah jika sudah ada data terkait
                                </small>
                            </div>
                        </div>
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

    .form-section:nth-child(1) .form-section {
        border-left-color: #007bff;
    }

    .col-md-6 .form-section:first-child {
        border-left-color: #28a745 !important;
    }

    .col-md-6:last-child .form-section {
        border-left-color: #dc3545 !important;
    }

    .info-section {
        border: 1px solid #dee2e6;
        border-left: 4px solid #17a2b8;
    }

    .durasi-section {
        border: 1px solid #dee2e6;
        border-left: 4px solid #6f42c1;
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

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);
        
        // Focus pada input pertama
        $('#semester_periode').focus();
        
        // Tooltip untuk info
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Fungsi untuk menghitung durasi semester
    function calculateDuration() {
        const startDate = $('#tanggal_mulai').val();
        const endDate = $('#tanggal_akhir').val();
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            if (end > start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const diffMonths = Math.round(diffDays / 30);
                
                let durationText = '';
                if (diffMonths > 0) {
                    durationText = `${diffMonths} Bulan (${diffDays} Hari)`;
                } else {
                    durationText = `${diffDays} Hari`;
                }
                
                $('#durasi-text').text(durationText);
                $('#durasi-info').fadeIn().addClass('animate-fade-in');
            } else {
                $('#durasi-info').fadeOut();
            }
        } else {
            $('#durasi-info').fadeOut();
        }
    }

    // Event listener untuk perubahan tanggal
    $('#tanggal_mulai, #tanggal_akhir').on('change', calculateDuration);

    // Custom form validation
    customFormValidation(
        // ID form untuk validasi
        "#form-create", {
            // Field yang akan di validasi
            semester_periode: {
                required: true,
                minlength: 5,
                maxlength: 50
            },
            tanggal_mulai: {
                required: true,
                date: true
            },
            tanggal_akhir: {
                required: true,
                date: true
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            semester_periode: {
                required: "Semester periode wajib diisi",
                minlength: "Semester periode minimal 5 karakter",
                maxlength: "Semester periode maksimal 50 karakter"
            },
            tanggal_mulai: {
                required: "Tanggal mulai wajib diisi",
                date: "Format tanggal tidak valid"
            },
            tanggal_akhir: {
                required: "Tanggal akhir wajib diisi",
                date: "Format tanggal tidak valid"
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
                    $('#tabel-periode-semester').DataTable().ajax.reload();
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

    // Validasi tanggal custom
    $('#form-create').on('submit', function(e) {
        const startDate = new Date($('#tanggal_mulai').val());
        const endDate = new Date($('#tanggal_akhir').val());
        
        if (endDate <= startDate) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Tanggal akhir harus lebih besar dari tanggal mulai',
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
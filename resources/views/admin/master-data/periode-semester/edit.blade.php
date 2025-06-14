<form id="form-edit" method="POST" action="{{ url('admin/master-data/periode-semester/' . $periode->periode_id) }}">
    @csrf
    @method('PUT')
    
    <div class="modal-header bg-info rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Periode Semester</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Informasi Periode -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Informasi Periode</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="semester_periode" class="col-form-label font-weight-bold">
                                Periode Semester <span class="text-danger">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="semester_periode"
                                           value="{{ $periode->semester_periode }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-graduation-cap"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_mulai" class="col-form-label font-weight-bold">
                                Tanggal Mulai <span class="text-danger">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="tanggal_mulai" 
                                           value="{{ $periode->tanggal_mulai }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-play"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_akhir" class="col-form-label font-weight-bold">
                                Tanggal Akhir <span class="text-danger">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="tanggal_akhir" 
                                           value="{{ $periode->tanggal_akhir }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-danger text-white">
                                            <i class="fas fa-stop"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Durasi Periode (Auto Calculate) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info" style="display: none;" id="durasi-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Durasi Periode: </strong><span id="durasi-text">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Perubahan
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fa-solid fa-xmark mr-2"></i>Batal
        </button>
    </div>
</form>

<style>
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }

    .card {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    .font-weight-bold {
        font-weight: 600 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);

        // Hitung durasi periode
        function hitungDurasi() {
            const tanggalMulai = $('input[name="tanggal_mulai"]').val();
            const tanggalAkhir = $('input[name="tanggal_akhir"]').val();

            if (tanggalMulai && tanggalAkhir) {
                const mulai = new Date(tanggalMulai);
                const akhir = new Date(tanggalAkhir);
                
                if (akhir >= mulai) {
                    const diffTime = Math.abs(akhir - mulai);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    const diffMonths = Math.round(diffDays / 30);
                    
                    let durasiText = `${diffDays} hari`;
                    if (diffMonths > 0) {
                        durasiText += ` (≈ ${diffMonths} bulan)`;
                    }
                    
                    $('#durasi-text').text(durasiText);
                    $('#durasi-info').show();
                } else {
                    $('#durasi-info').hide();
                }
            } else {
                $('#durasi-info').hide();
            }
        }

        // Event listener untuk perubahan tanggal
        $('input[name="tanggal_mulai"], input[name="tanggal_akhir"]').on('change', hitungDurasi);

        // Hitung durasi saat pertama kali load
        hitungDurasi();

        // Tooltip untuk status
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Force hide processing text after any modal operation
    setInterval(function() {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

{{-- Memanggil Custom validation untuk Form --}}
<script>
    customFormValidation(
        // Validasi form
        // ID form untuk validasi
        "#form-edit", {
            // Field yang akan di validasi (name)
            semester_periode: {
                required: true,
            },
            tanggal_mulai: {
                required: true,
            },
            tanggal_akhir: {
                required: true,
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            semester_periode: {
                required: "Semester periode wajib diisi",
            },
            tanggal_mulai: {
                required: "Tanggal mulai wajib diisi",
            },
            tanggal_akhir: {
                required: "Tanggal akhir wajib diisi",
            }
        },

        function(response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
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
                    text: response.message
                });
            }
        }
    );
</script>
<form id="form-create" method="POST" action="{{ url('admin/master-data/tingkat-lomba/store') }}">
    @csrf
    <div class="modal-header bg-gradient-primary">
        <h5 class="modal-title text-white font-weight-bold">
            <i class="fas fa-plus-circle mr-2"></i>Tambah Tingkat Lomba
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-12">
                <div class="form-group mb-3">
                    <label for="nama_tingkat" class="form-label font-weight-semibold">
                        Nama Tingkat Lomba 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-trophy text-primary"></i>
                            </span>
                        </div>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               id="nama_tingkat"
                               name="nama_tingkat" 
                               placeholder="Masukkan nama tingkat lomba"
                               required>
                    </div>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Contoh: Tingkat Provinsi, Tingkat Nasional, dll.
                    </small>
                    <div class="invalid-feedback" id="error-nama_tingkat"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer bg-light border-top-0 px-4 py-3">
        <button type="submit" class="btn btn-primary btn-lg px-4 mr-2">
            <i class="fas fa-save mr-2"></i>Simpan Data
        </button>
        <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

<!-- Loading Overlay -->
<div id="loading-overlay" class="d-none">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .modal-header.bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border-bottom: none;
    }
    
    .form-control-lg {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
        border: 2px solid #e9ecef;
        border-right: none;
    }
    
    .btn-lg {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-1px);
    }
    
    .form-label {
        color: #495057;
        margin-bottom: 8px;
    }
    
    .modal-body {
        background-color: #fafafa;
    }
    
    .invalid-feedback {
        display: block;
        font-size: 0.875em;
        color: #dc3545;
        margin-top: 5px;
    }
    
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
</style>

<!-- Form Validation Script -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    $(document).ready(function() {
        // Enhanced form validation
        customFormValidation(
            "#form-create", 
            {
                nama_tingkat: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
            }, 
            {
                nama_tingkat: {
                    required: "Nama tingkat lomba wajib diisi",
                    minlength: "Nama tingkat lomba minimal 3 karakter",
                    maxlength: "Nama tingkat lomba maksimal 100 karakter"
                }
            },
            function(response, form) {
                // Show loading
                $('#loading-overlay').removeClass('d-none');
                
                if (response.success) {
                    // Hide loading
                    $('#loading-overlay').addClass('d-none');
                    
                    // Success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(function() {
                        // Close modal with animation
                        $('#myModal').modal('hide');
                        
                        // Reset form
                        form[0].reset();
                        
                        // Reload DataTable
                        if ($.fn.DataTable.isDataTable('#tabel-tingkat-lomba')) {
                            $('#tabel-tingkat-lomba').DataTable().ajax.reload(null, false);
                        }
                        
                        // Show success toast
                        toastr.success('Data tingkat lomba berhasil ditambahkan!', 'Sukses');
                    });
                } else {
                    // Hide loading
                    $('#loading-overlay').addClass('d-none');
                    
                    // Clear previous errors
                    $('.invalid-feedback').text('');
                    $('.form-control').removeClass('is-invalid');
                    
                    // Show field errors
                    if (response.msgField) {
                        $.each(response.msgField, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                            $('input[name="' + field + '"]').addClass('is-invalid');
                        });
                    }
                    
                    // Show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: response.message || 'Terjadi kesalahan saat menyimpan data',
                        confirmButtonColor: '#dc3545'
                    });
                }
            }
        );
        
        // Real-time validation feedback
        $('#nama_tingkat').on('input', function() {
            var value = $(this).val().trim();
            var errorElement = $('#error-nama_tingkat');
            
            if (value.length === 0) {
                $(this).removeClass('is-valid').addClass('is-invalid');
                errorElement.text('Nama tingkat lomba wajib diisi');
            } else if (value.length < 3) {
                $(this).removeClass('is-valid').addClass('is-invalid');
                errorElement.text('Nama tingkat lomba minimal 3 karakter');
            } else if (value.length > 100) {
                $(this).removeClass('is-valid').addClass('is-invalid');
                errorElement.text('Nama tingkat lomba maksimal 100 karakter');
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                errorElement.text('');
            }
        });
        
        // Modal reset on close
        $('#myModal').on('hidden.bs.modal', function () {
            $('#form-create')[0].reset();
            $('.form-control').removeClass('is-invalid is-valid');
            $('.invalid-feedback').text('');
        });
        
        // Auto focus on modal show
        $('#myModal').on('shown.bs.modal', function () {
            $('#nama_tingkat').focus();
        });
    });
</script>
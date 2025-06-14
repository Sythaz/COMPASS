<form id="form-edit" method="POST" action="{{ url('admin/master-data/kategori-keahlian/' . $kategori->kategori_id) }}">
    @csrf
    @method('PUT')
    
    <div class="modal-header bg-info rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Kategori Keahlian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Informasi Kategori -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-tag mr-2"></i>Informasi Kategori</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nama_kategori" class="col-form-label font-weight-bold">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nama_kategori" 
                                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="status_kategori" class="col-form-label font-weight-bold">
                                Status Kategori <span class="text-danger">*</span>
                            </label>
                            <div class="custom-validation">
                                <div class="input-group">
                                    <select name="status_kategori" id="status_kategori" class="form-control" required>
                                        <option value="Aktif" {{ old('status_kategori', $kategori->status_kategori) == 'Aktif' ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="Nonaktif" {{ old('status_kategori', $kategori->status_kategori) == 'Nonaktif' ? 'selected' : '' }}>
                                            Nonaktif
                                        </option>
                                    </select>
                                    <div class="input-group-append">
                                        @php
                                            $currentStatus = old('status_kategori', $kategori->status_kategori);
                                            $statusClass = $currentStatus === 'Aktif' ? 'bg-success' : 'bg-warning';
                                            $statusIcon = $currentStatus === 'Aktif' ? 'fas fa-check' : 'fas fa-clock';
                                        @endphp
                                        <span class="input-group-text {{ $statusClass }} text-white" id="status-indicator">
                                            <i class="{{ $statusIcon }}" id="status-icon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
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
</style>

<script>
    // Animasi saat modal dibuka
    $(document).ready(function() {
        $('.card').hide().fadeIn(600);

        // Update status indicator saat select berubah
        $('#status_kategori').on('change', function() {
            const selectedValue = $(this).val();
            const statusIndicator = $('#status-indicator');
            const statusIcon = $('#status-icon');

            if (selectedValue === 'Aktif') {
                statusIndicator.removeClass('bg-warning').addClass('bg-success');
                statusIcon.removeClass('fa-clock').addClass('fa-check');
            } else {
                statusIndicator.removeClass('bg-success').addClass('bg-warning');
                statusIcon.removeClass('fa-check').addClass('fa-clock');
            }
        });

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
            // Field yang akan di validasi
            nama_kategori: {
                required: true,
            },
            status_kategori: {
                required: true, 
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            nama_kategori: {
                required: "Nama kategori wajib diisi",
            },
            status_kategori: {
                required: "Status kategori wajib diisi",
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
                    text: response.message
                });
            }
        }
    );
</script>
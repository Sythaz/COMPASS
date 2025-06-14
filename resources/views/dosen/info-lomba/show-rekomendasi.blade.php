<form id="form-notifikasi" method="POST" action="{{ route('info-lomba.rekomendasi-lomba.notifikasi') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-trophy mr-2"></i>Rekomendasi Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="modal-body">
        <!-- Informasi Lomba -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Lomba</h6>
            </div>
            <div class="card-body">
                <input type="hidden" name="lomba_id" value="{{ $lomba->lomba_id }}" required>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Nama Lomba</label>
                            <input type="text" class="form-control" value="{{ $lomba->nama_lomba }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Tingkat</label>
                            <input type="text" class="form-control" value="{{ $lomba->tingkat_lomba->nama_tingkat }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Kategori</label>
                            <input type="text" class="form-control" 
                                value="{{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Penyelenggara</label>
                            <input type="text" class="form-control" value="{{ $lomba->penyelenggara_lomba }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Deskripsi</label>
                            <textarea class="form-control" rows="3" disabled>{{ $lomba->deskripsi_lomba }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Periode Registrasi</label>
                            <input type="text" class="form-control" 
                                value="{{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') }} - {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y') }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Link Pendaftaran</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $lomba->link_pendaftaran_lomba }}" disabled>
                                <div class="input-group-append">
                                    <a href="{{ $lomba->link_pendaftaran_lomba }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Rekomendasi -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user-plus mr-2"></i>Form Rekomendasi</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">
                        <i class="fas fa-user mr-2"></i>Nama Peserta <span class="text-danger">*</span>
                    </label>
                    <div class="custom-validation">
                        <select name="user_id" id="user_id" class="form-control select2" required>
                            <option value="">-- Pilih Peserta --</option>
                            @foreach ($daftarMahasiswa as $m)
                                <option value="{{ $m->user_id }}"
                                    {{ old('user_id') == $m->user_id ? 'selected' : '' }}>
                                    {{ $m->nim_mahasiswa }} - {{ $m->nama_mahasiswa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="col-form-label font-weight-bold">
                        <i class="fas fa-comment mr-2"></i>Alasan Rekomendasi
                    </label>
                    <textarea name="pesan_notifikasi" id="pesan_notifikasi" class="form-control" rows="4"
                        placeholder="Anda direkomendasikan oleh {{ Auth::user()->getRole() }} '{{ Auth::user()->getName() }}' untuk mengikuti lomba '{{ $lomba->nama_lomba }}'. Silakan periksa informasi lomba lebih lanjut jika berminat."></textarea>
                    <small class="form-text text-muted">Berikan alasan mengapa mahasiswa ini cocok untuk lomba tersebut</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane mr-2"></i>Kirim Rekomendasi
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Tutup
        </button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<!-- Script Select2 (Dropdown Multiselect/Search) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
    }

    .select2-container--default .select2-selection--single {
        border: none;
        margin-top: 9px;
        margin-left: 9px;
    }

    .select2-container {
        min-height: 45px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
        z-index: 9999;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        margin-top: 9px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #7571F9;
        background-color: white !important;
        outline: 2px solid #7571F9 !important;
        border: none;
        border-radius: 4px;
        margin-top: 10px;
        margin-left: 12px
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        background-color: #7571F9;
    }

    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 12px;
        margin-left: 12px;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #7571F9;
    }

    /* Additional styles matching the registration details modal */
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .col-form-label {
        font-size: 0.875rem;
        color: #495057;
    }

    .form-control:disabled {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .btn-outline-primary:hover {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .form-text {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }
</style>

<script>
    $(document).ready(function() {
        // Fade in effect for cards
        $('.card').hide().fadeIn(600);
        
        // Initialize Select2
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).parent().parent()
            });
        });
    });

    customFormValidation(
        // Validasi form
        // ID form untuk validasi
        "#form-notifikasi", {
            // Field yang akan di validasi (name)
            user_id: {
                required: true,
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
            user_id: {
                required: "Nama peserta wajib diisi",
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

    // Force hide processing text after any modal operation
    setInterval(function() {
        if ($('.modal').is(':hidden')) {
            $('.processing, [class*="processing"]').hide();
        }
    }, 500);
</script>
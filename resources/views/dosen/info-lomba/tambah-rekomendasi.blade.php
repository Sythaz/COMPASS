<form id="form-notifikasi" method="POST" action="{{ route('info-lomba.rekomendasi-lomba.notifikasi') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-trophy mr-2"></i>Buat Rekomendasi Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Form Pilih Lomba -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-search mr-2"></i>Pilih Lomba</h6>
            </div>
            <div class="card-body">
                <div class="form-group custom-validation mb-0">
                    <label class="col-form-label font-weight-bold">
                        <i class="fas fa-trophy mr-2"></i>Nama Lomba <span class="text-danger">*</span>
                    </label>
                    <select name="lomba_id" id="select-lomba" class="form-control select2" required>
                        <option value="">-- Pilih Lomba --</option>
                        @foreach ($lomba as $l)
                            <option value="{{ $l->lomba_id }}">{{ $l->nama_lomba }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Informasi Lomba -->
        <div class="card mb-3 d-none" id="info-lomba-card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Lomba</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group d-none" id="kategori-lomba">
                            <label class="col-form-label font-weight-bold">Kategori</label>
                            <input type="text" name="kategori_lomba" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group d-none" id="tingkat-lomba">
                            <label class="col-form-label font-weight-bold">Tingkat</label>
                            <input type="text" name="tingkat_lomba" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group d-none" id="penyelenggara-lomba">
                            <label class="col-form-label font-weight-bold">Penyelenggara</label>
                            <input type="text" name="penyelenggara_lomba" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group d-none" id="periode-registrasi">
                            <label class="col-form-label font-weight-bold">Periode Registrasi</label>
                            <input type="text" name="periode_registrasi" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group d-none" id="deskripsi-lomba">
                            <label class="col-form-label font-weight-bold">Deskripsi</label>
                            <textarea name="deskripsi_lomba" class="form-control" rows="3" readonly></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group d-none mb-0" id="link-pendaftaran-lomba">
                            <label class="col-form-label font-weight-bold">Link Pendaftaran</label>
                            <div class="input-group">
                                <input type="text" name="link_pendaftaran_lomba" class="form-control" readonly>
                                <div class="input-group-append">
                                    <button type="button" id="btn-open-link" class="btn btn-outline-primary" disabled>
                                        <i class="fas fa-external-link-alt"></i>
                                    </button>
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
                <div class="form-group custom-validation">
                    <label class="col-form-label font-weight-bold">
                        <i class="fas fa-user mr-2"></i>Nama Peserta <span class="text-danger">*</span>
                    </label>
                    <select name="user_id" class="form-control select2" required>
                        <option value="">-- Pilih Peserta --</option>
                        @foreach ($daftarMahasiswa as $m)
                            <option value="{{ $m->user_id }}">{{ $m->nim_mahasiswa }} - {{ $m->nama_mahasiswa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="col-form-label font-weight-bold">
                        <i class="fas fa-comment mr-2"></i>Alasan Rekomendasi
                    </label>
                    <textarea name="pesan_notifikasi" class="form-control" rows="4"
                        placeholder="Anda direkomendasikan oleh Dosen '{{ Auth::user()->getName() }}' untuk mengikuti lomba ini. Silakan periksa informasi lomba lebih lanjut jika berminat."></textarea>
                    <small class="form-text text-muted">Berikan alasan mengapa mahasiswa ini cocok untuk lomba
                        tersebut</small>
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

{{-- Memanggil Custom CSS Select2 --}}
<link rel="stylesheet" href="{{ asset('css-custom/select2-custom.css') }}">

<style>
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

    .form-control:disabled,
    .form-control[readonly] {
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

    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group:has(#select-lomba) {
        position: relative;
        z-index: 1000;
    }

    .form-group:has(select[name="user_id"]) {
        position: relative;
        z-index: 999;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).parent().parent()
            });
        });

        // Fade in effect for initial cards
        $('.card').hide().fadeIn(600);

        // Hide all lomba info fields initially
        $('.form-group[id$="-lomba"]').addClass('d-none');
        $('#info-lomba-card').addClass('d-none');

        // When lomba selection changes
        $('#select-lomba').on('change', function() {
            const lombaId = $(this).val();

            if (!lombaId) {
                // Hide all lomba info fields if no lomba selected
                $('.form-group[id*="-lomba"]').addClass('d-none');
                $('#info-lomba-card').addClass('d-none');
                $('#btn-open-link').prop('disabled', true);
                return;
            }

            const url = `{{ url('api/data-lomba') }}/${lombaId}`;

            // Show loading state
            $('#info-lomba-card').removeClass('d-none').addClass('fade-in');

            $.get(url)
                .done(function(response) {
                    if (response.success) {
                        const data = response.data;
                        console.log('Data diterima:', data);

                        // Show all lomba info fields
                        $('.form-group[id$="-lomba"]').removeClass('d-none');

                        // Fill data to respective fields
                        $('#deskripsi-lomba textarea').val(data.deskripsi_lomba || '');
                        $('#kategori-lomba input').val(data.kategori || '');
                        $('#tingkat-lomba input').val(data.tingkat_lomba_id || '');
                        $('#penyelenggara-lomba input').val(data.penyelenggara_lomba || '');
                        $('#periode-registrasi input').val(data.periode_registrasi || '');
                        $('#link-pendaftaran-lomba input').val(data.link_pendaftaran_lomba || '');

                        // Enable and set up external link button
                        if (data.link_pendaftaran_lomba) {
                            $('#btn-open-link').prop('disabled', false).off('click').on('click',
                                function() {
                                    window.open(data.link_pendaftaran_lomba, '_blank');
                                });
                        } else {
                            $('#btn-open-link').prop('disabled', true);
                        }
                    } else {
                        Swal.fire("Error", response.message || "Gagal memuat data lomba", "error");
                    }
                })
                .fail(function(error) {
                    console.error('Error:', error);
                    Swal.fire("Error", "Tidak dapat mengambil data lomba.", "error");
                });
        });
    });

    customFormValidation(
        // Form validation
        "#form-notifikasi", {
            // Fields to validate (name)
            user_id: {
                required: true,
            },
            lomba_id: {
                required: true,
            }
        }, {
            // Validation messages for each field when invalid
            user_id: {
                required: "Nama peserta wajib diisi",
            },
            lomba_id: {
                required: "Lomba wajib diisi",
            }
        },

        function(response, form) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function() {
                    // Close modal
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

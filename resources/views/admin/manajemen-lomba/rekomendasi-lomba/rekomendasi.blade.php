<form id="form-notifikasi" method="POST" action="{{ route('rekomendasi-lomba.notifikasi') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-paper-plane mr-2"></i>Rekomendasi Lomba
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <!-- Informasi Lomba -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Lomba
                </h6>
            </div>
            <div class="card-body">
                <input type="hidden" name="lomba_id" value="{{ $lomba->lomba_id }}" required>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Nama Lomba</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $lomba->nama_lomba }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-trophy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Tingkat Lomba</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $lomba->tingkat_lomba->nama_tingkat }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-level-up-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Deskripsi Lomba</label>
                            <div class="input-group">
                                <textarea class="form-control" rows="3" disabled>{{ $lomba->deskripsi_lomba }}</textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Kategori Lomba</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui' }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-warning text-white">
                                        <i class="fas fa-tags"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Penyelenggara</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $lomba->penyelenggara_lomba }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-secondary text-white">
                                        <i class="fas fa-building"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Registrasi -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-calendar-check mr-2"></i>Periode Registrasi
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Awal Registrasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Akhir Registrasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y') }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-danger text-white">
                                        <i class="fas fa-calendar-minus"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Link Pendaftaran</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $lomba->link_pendaftaran_lomba }}" disabled>
                                <div class="input-group-append">
                                    <a href="{{ $lomba->link_pendaftaran_lomba }}" target="_blank" class="input-group-text bg-primary text-white" style="text-decoration: none;">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Status -->
                <div class="registration-status mt-3 p-3 bg-light rounded">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <strong>
                                <i class="fas fa-clock mr-2"></i>Status Registrasi:
                            </strong>
                        </div>
                        <div class="col-6 text-right">
                            @php
                                $now = \Carbon\Carbon::now();
                                $startReg = \Carbon\Carbon::parse($lomba->awal_registrasi_lomba);
                                $endReg = \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba);
                            @endphp
                            @if($now < $startReg)
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-hourglass-start mr-1"></i>Belum Dibuka
                                </span>
                            @elseif($now >= $startReg && $now <= $endReg)
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-door-open mr-1"></i>Sedang Dibuka
                                </span>
                            @else
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-door-closed mr-1"></i>Sudah Ditutup
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Rekomendasi -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-user-plus mr-2"></i>Form Rekomendasi
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Nama Peserta <span class="text-danger">*</span></label>
                            <div class="input-group custom-validation">
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
                            <div class="error-text" id="error-user_id"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Nama Dosen</label>
                            <div class="input-group">
                                <select name="dosen_id" id="dosen_id" class="form-control select2">
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach ($daftarDosen as $d)
                                        <option value="{{ $d->dosen_id }}"
                                            {{ old('dosen_id') == $d->dosen_id ? 'selected' : '' }}>
                                            {{ $d->nip_dosen }} - {{ $d->nama_dosen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Alasan Rekomendasi</label>
                            <div class="input-group">
                                <textarea name="pesan_notifikasi" id="pesan_notifikasi" class="form-control" rows="4"
                                    placeholder="Anda direkomendasikan oleh Admin '{{ Auth::user()->getName() }}' untuk mengikuti lomba '{{ $lomba->nama_lomba }}'. Silakan periksa informasi lomba lebih lanjut jika berminat."></textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-warning text-white">
                                        <i class="fas fa-comment-alt"></i>
                                    </span>
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
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }

    .registration-status {
        border: 1px solid #dee2e6;
        border-left: 4px solid #007bff;
    }

    .badge-lg {
        font-size: 0.95rem;
        padding: 0.5rem 0.75rem;
    }

    .form-group label {
        color: #495057;
        font-size: 0.9rem;
    }

    .input-group-text {
        border: none;
    }

    .form-control:disabled, .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .error-text {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Link styling */
    .input-group-text a {
        color: inherit !important;
    }

    .input-group-text a:hover {
        opacity: 0.8;
    }

    /* Select2 styling */
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + 0.75rem);
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem);
        right: 0.75rem;
    }
</style>

{{-- Memanggil Select2 single select --}}
<script>
    $(document).ready(function() {
        // Animasi saat modal dibuka
        $('.card').hide().fadeIn(600);
        
        // Initialize Select2
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).parent().parent()
            });
        });

        // Tooltip untuk status
        $('[data-toggle="tooltip"]').tooltip();
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
</script>
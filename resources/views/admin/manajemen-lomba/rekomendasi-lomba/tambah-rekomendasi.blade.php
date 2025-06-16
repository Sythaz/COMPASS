<form id="form-notifikasi" method="POST" action="{{ route('rekomendasi-lomba.notifikasi') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-trophy mr-2"></i>Buat Rekomendasi Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="modal-body">
        <!-- Informasi Lomba -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-trophy mr-2"></i>Informasi Lomba</h6>
            </div>
            <div class="card-body">
                <div class="form-group custom-validation">
                    <label class="col-form-label font-weight-bold">Nama Lomba <span class="text-danger">*</span></label>
                    <select name="lomba_id" id="select-lomba" class="form-control select2" required>
                        <option value="">-- Pilih Lomba --</option>
                        @foreach ($lomba as $l)
                            <option value="{{ $l->lomba_id }}">{{ $l->nama_lomba }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Detail Lomba yang akan muncul setelah memilih -->
                <div class="lomba-details" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group d-none" id="kategori-lomba">
                                <label class="col-form-label font-weight-bold">Kategori</label>
                                <div class="input-group">
                                    <input type="text" name="kategori_lomba" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-info text-white">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-none" id="tingkat-lomba">
                                <label class="col-form-label font-weight-bold">Tingkat</label>
                                <div class="input-group">
                                    <input type="text" name="tingkat_lomba" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-warning text-dark">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group d-none" id="deskripsi-lomba">
                        <label class="col-form-label font-weight-bold">Deskripsi</label>
                        <textarea name="deskripsi_lomba" class="form-control" rows="2" readonly></textarea>
                    </div>
                    
                    <div class="form-group d-none" id="penyelenggara-lomba">
                        <label class="col-form-label font-weight-bold">Penyelenggara</label>
                        <div class="input-group">
                            <input type="text" name="penyelenggara_lomba" class="form-control" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text bg-secondary text-white">
                                    <i class="fas fa-building"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group d-none" id="periode-registrasi">
                                <label class="col-form-label font-weight-bold">Periode Registrasi</label>
                                <div class="input-group">
                                    <input type="text" name="periode_registrasi" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-none" id="link-pendaftaran-lomba">
                                <label class="col-form-label font-weight-bold">Link Pendaftaran</label>
                                <div class="input-group">
                                    <input type="text" name="link_pendaftaran_lomba" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-link"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Rekomendasi -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-users mr-2"></i>Informasi Rekomendasi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group custom-validation">
                            <label class="col-form-label font-weight-bold">
                                <i class="fas fa-user-graduate text-primary mr-2"></i>Nama Peserta <span class="text-danger">*</span>
                            </label>
                            <select name="user_id" class="form-control select2" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach ($daftarMahasiswa as $m)
                                    <option value="{{ $m->user_id }}">{{ $m->nim_mahasiswa }} - {{ $m->nama_mahasiswa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group custom-validation">
                            <label class="col-form-label font-weight-bold">
                                <i class="fas fa-chalkboard-teacher text-success mr-2"></i>Nama Dosen
                            </label>
                            <select name="dosen_id" class="form-control select2">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($daftarDosen as $d)
                                    <option value="{{ $d->dosen_id }}">{{ $d->nip_dosen }} - {{ $d->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesan Rekomendasi -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-comment-dots mr-2"></i>Pesan Rekomendasi</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">
                        <i class="fas fa-comment-dots text-info mr-2"></i>Alasan Rekomendasi
                    </label>
                    <textarea name="pesan_notifikasi" class="form-control" rows="4"
                        placeholder="Anda direkomendasikan oleh Admin '{{ Auth::user()->getName() }}' untuk mengikuti lomba ini. Silakan periksa informasi lomba lebih lanjut jika berminat."></textarea>
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
    
    .lomba-details {
        border-left: 4px solid #7571F9;
        padding-left: 15px;
        margin-top: 15px;
        background-color: rgba(117, 113, 249, 0.05);
        border-radius: 0 8px 8px 0;
    }
    
    .form-control:focus {
        border-color: #7571F9;
        box-shadow: 0 0 0 0.2rem rgba(117, 113, 249, 0.25);
    }
    
    .select2-container--default .select2-selection--single:focus {
        border-color: #7571F9;
        box-shadow: 0 0 0 0.2rem rgba(117, 113, 249, 0.25);
    }
    
    .btn-primary {
        background-color: #7571F9;
        border-color: #7571F9;
    }
    
    .btn-primary:hover {
        background-color: #6461f2;
        border-color: #6461f2;
    }
    
    .card {
        border: 1px solid #e3e6f0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }
    
    .bg-light {
        background-color: #f8f9fc !important;
    }
</style>

{{-- Memanggil Select2 single select --}}
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).closest('.modal-body')
            });
        });

        // Sembunyikan semua field info lomba saat pertama kali load
        $('.form-group[id$="-lomba"]').addClass('d-none');
        $('.lomba-details').hide();
        
        // Ketika select lomba berubah
        $('#select-lomba').on('change', function() {
            const lombaId = $(this).val();
            if (!lombaId) {
                // Sembunyikan semua field info lomba jika tidak ada lomba yang dipilih
                $('.form-group[id*="-lomba"]').addClass('d-none');
                $('.lomba-details').slideUp(300);
                return;
            }

            const url = `{{ url('api/data-lomba') }}/${lombaId}`;
            $.get(url)
                .done(function(response) {
                    if (response.success) {
                        const data = response.data;
                        console.log('Data diterima:', data);

                        // Tampilkan wrapper detail lomba
                        $('.lomba-details').slideDown(300);
                        
                        // Tampilkan semua field info lomba dengan animasi
                        setTimeout(function() {
                            $('.form-group[id$="-lomba"]').removeClass('d-none').hide().fadeIn(400);
                        }, 200);

                        // Isi data ke masing-masing field
                        $('#deskripsi-lomba textarea').val(data.deskripsi_lomba || '');
                        $('#kategori-lomba input').val(data.kategori || '');
                        $('#tingkat-lomba input').val(data.tingkat_lomba_id || '');
                        $('#penyelenggara-lomba input').val(data.penyelenggara_lomba || '');
                        $('#periode-registrasi input').val(data.periode_registrasi || '');
                        $('#link-pendaftaran-lomba input').val(data.link_pendaftaran_lomba || '');
                    } else {
                        Swal.fire("Error", response.message || "Gagal memuat data lomba", "error");
                    }
                })
                .fail(function(error) {
                    console.error('Error:', error);
                    Swal.fire("Error", "Tidak dapat mengambil data lomba.", "error");
                });
        });
        
        // Animasi saat modal dibuka
        $('.card').hide().fadeIn(600);
    });

    customFormValidation(
        // Validasi form
        // ID form untuk validasi
        "#form-notifikasi", {
            // Field yang akan di validasi (name)
            user_id: {
                required: true,
            },
            lomba_id: {
                required: true,
            }
        }, {
            // Pesan validasi untuk setiap field saat tidak valid
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
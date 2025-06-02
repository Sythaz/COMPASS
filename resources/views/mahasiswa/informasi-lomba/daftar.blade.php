<form id="form-daftar" method="POST" action="{{ route('informasi-lomba.store', ['id' => $lomba->lomba_id]) }}"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-plus mr-2"></i>Daftar Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label class="col-form-label">Lomba <span class="text-danger">*</span></label>
            <input type="text" class="form-control" value="{{ $lomba->nama_lomba }}" disabled>
            <input type="hidden" name="lomba_id" value="{{ $lomba->lomba_id }}" required>
        </div>

        <!-- Lomba Tim atau Individu -->
        <input type="hidden" id="tipe_lomba" value="{{ $lomba->tipe_lomba }}">

        <!-- Kontrol Jumlah Anggota - Hanya tampil untuk lomba tim -->
        <div class="form-group" id="kontrol-jumlah" style="display: none;">
            <label for="jumlah_anggota" class="col-form-label mt-2">Jumlah Anggota Tim (termasuk ketua) <span
                    class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-outline-secondary" id="btn-minus">−</button>
                </div>
                <input type="number" id="jumlah_anggota" name="jumlah_anggota" class="form-control text-center" min="1"
                    max="10" value="1" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="btn-plus">+</button>
                </div>
            </div>
            <small class="form-text text-muted">Masukkan jumlah anggota tim (minimal 2, maksimal 10)</small>
        </div>

        <!-- Info untuk lomba individu -->
        <div class="alert alert-info" id="info-individu" style="display: none;">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Lomba Individu:</strong> Anda akan mendaftar sebagai peserta individu.
        </div>

        <!-- Container untuk form anggota -->
        <div id="anggota-container">
            <!-- Form anggota akan digenerate di sini -->
        </div>

        <div class="form-group">
            <label for="bukti_pendaftaran" class="col-form-label mt-2">
                Bukti Pendaftaran <small>(Maksimal 2MB)</small>
            </label>
            <div class="custom-validation">
                <div class="input-group mt-1">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="bukti_pendaftaran" accept=".png, .jpg, .jpeg"
                            onchange="$('#bukti_pendaftaran_label').text(this.files[0].name)">
                        <label class="custom-file-label" id="bukti_pendaftaran_label" for="bukti_pendaftaran">
                            Pilih File
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Daftar</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fa-solid fa-xmark mr-2"></i>Batal</button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    .anggota-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #7571F9;
    }
</style>

<!-- Memanggil Select2 single select -->
<script>
    $(document).ready(function () {
        $('select.select2:not(.normal)').each(function () {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).parent().parent()
            });
        });
    });
</script>

<script>
    const opsiMahasiswa = `
        <option value="">-- Pilih Mahasiswa --</option>
        @foreach ($daftarMahasiswa as $mhs)
            <option value="{{ $mhs->mahasiswa_id }}">{{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }}</option>
        @endforeach
    `;
</script>

<script>
    // Validasi duplikasi mahasiswa
    $(document).on('change', '.select-mahasiswa', function () {
        let selectedValues = [];

        $('.select-mahasiswa').each(function () {
            let val = $(this).val();
            if (val) selectedValues.push(val);
        });

        let isDuplicate = selectedValues.some((item, idx) => selectedValues.indexOf(item) !== idx);

        if (isDuplicate) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Nama mahasiswa tidak boleh sama!',
                confirmButtonColor: '#3085d6',
            });
            $(this).val('').trigger('change');
        }
    });
</script>

<script>
    $(document).ready(function () {
        // Fungsi untuk membuat form anggota
        function createAnggotaForm(jumlah) {
            let container = $('#anggota-container');
            container.empty();

            for (let i = 1; i <= jumlah; i++) {
                let label, placeholder;
                
                if (jumlah === 1) {
                    // Untuk lomba individu
                    label = 'Peserta';
                    placeholder = 'Pilih peserta';
                } else {
                    // Untuk lomba tim
                    label = (i === 1) ? 'Ketua Tim' : `Anggota ${i}`;
                    placeholder = (i === 1) ? 'Pilih ketua tim' : 'Pilih anggota tim';
                }

                let selectId = `mahasiswa_id_${i}`;
                let sectionClass = jumlah > 1 ? 'anggota-section' : '';

                let formHtml = `
                <div class="form-group anggota-item ${sectionClass}">
                    <label for="${selectId}" class="col-form-label">
                        <i class="fas fa-user mr-2"></i>${label} <span class="text-danger">*</span>
                    </label>
                    <div class="custom-validation">
                        <select name="mahasiswa_id[]" id="${selectId}" class="form-control select-mahasiswa" required>
                            ${opsiMahasiswa}
                        </select>
                    </div>
                </div>`;

                container.append(formHtml);
            }

            // Re-inisialisasi Select2
            $('.select-mahasiswa').select2({
                width: '100%',
                placeholder: 'Pilih mahasiswa',
                dropdownParent: $('#form-daftar')
            });
        }

        // Event: Tombol Tambah (+)
        $('#btn-plus').click(function () {
            let input = $('#jumlah_anggota');
            let val = parseInt(input.val());

            if (val < 10) {
                input.val(val + 1);
                createAnggotaForm(val + 1);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maksimal Anggota',
                    text: 'Jumlah anggota maksimal 10 orang!',
                    confirmButtonColor: '#3085d6',
                });
            }
        });

        // Event: Tombol Kurang (−)
        $('#btn-minus').click(function () {
            let input = $('#jumlah_anggota');
            let val = parseInt(input.val());

            if (val > 2) {
                input.val(val - 1);
                createAnggotaForm(val - 1);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Minimal Anggota',
                    text: 'Jumlah anggota minimal 2 untuk lomba tim!',
                    confirmButtonColor: '#3085d6',
                });
            }
        });

        // Event: Input jumlah_anggota berubah manual
        $('#jumlah_anggota').on('input change', function () {
            let jumlah = parseInt($(this).val());
            
            if (isNaN(jumlah) || jumlah < 2) {
                jumlah = 2;
            } else if (jumlah > 10) {
                jumlah = 10;
            }

            $(this).val(jumlah);
            createAnggotaForm(jumlah);
        });

        // Inisialisasi berdasarkan tipe lomba
        let tipe = $('#tipe_lomba').val().toLowerCase();

        if (tipe === 'individu') {
            // Lomba individu
            $('#kontrol-jumlah').hide();
            $('#info-individu').show();
            $('#jumlah_anggota').val(1);
            createAnggotaForm(1);
        } else {
            // Lomba tim/kelompok
            $('#kontrol-jumlah').show();
            $('#info-individu').hide();
            $('#jumlah_anggota').val(2);
            createAnggotaForm(2);
        }

        // Tampilkan informasi tipe lomba di console untuk debugging
        console.log('Tipe Lomba:', tipe);
    });
</script>

<script>
    // Submit Form Ajax
    $('#form-daftar').on('submit', function (e) {
        e.preventDefault();

        // Validasi sebelum submit
        let mahasiswaSelects = $('.select-mahasiswa');
        let allFilled = true;

        mahasiswaSelects.each(function() {
            if (!$(this).val()) {
                allFilled = false;
                return false;
            }
        });

        if (!allFilled) {
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Mohon pilih semua anggota tim yang diperlukan!',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message || 'Pendaftaran lomba berhasil disimpan!',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    location.reload();
                });
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan. Silakan coba lagi.';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = [];
                        
                        Object.keys(errors).forEach(function(key) {
                            errorMessages.push(errors[key][0]);
                        });
                        
                        msg = errorMessages.join('\n');
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: msg,
                    confirmButtonColor: '#d33',
                });
            }
        });
    });
</script>
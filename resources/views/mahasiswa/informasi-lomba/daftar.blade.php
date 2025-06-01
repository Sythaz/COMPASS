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

        <div class="form-group">
            <label for="jumlah_anggota" class="col-form-label mt-2">Jumlah Anggota Tim (termasuk ketua) <span
                    class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-outline-secondary" id="btn-minus">−</button>
                </div>
                <input type="number" id="jumlah_anggota" name="jumlah_anggota" class="form-control text-center" min="1"
                    max="10" value="1" required readonly>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="btn-plus">+</button>
                </div>
            </div>
            <small class="form-text text-muted">Masukkan jumlah anggota tim, termasuk ketua</small>
        </div>

        <div id="anggota-container">
            <div class="form-group anggota-item">
                <label for="mahasiswa_id_1" class="col-form-label mt-2">
                    Ketua Tim <span class="text-danger" style="color: red;">*</span>
                </label>
                <div class="custom-validation">
                    <select name="mahasiswa_id[]" id="mahasiswa_id_1" class="form-control select2" required>
                        <option value="">-- Pilih Ketua Tim --</option>
                        @foreach ($daftarMahasiswa as $mhs)
                            <option value="{{ $mhs->mahasiswa_id }}">{{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Lomba Tim atau Individu --}}
        <input type="hidden" id="tipe_lomba" value="{{ $lomba->tipe_lomba }}">

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
</style>

{{-- Memanggil Select2 single select --}}
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
        <option value="">-- Pilih Anggota --</option>
        @foreach ($daftarMahasiswa as $mhs)
            <option value="{{ $mhs->mahasiswa_id }}">{{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }}</option>
        @endforeach
    `;
</script>

<script>
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
        // Inisialisasi awal Select2
        $('.select-mahasiswa').select2({
            width: '100%',
            placeholder: 'Pilih anggota tim',
        });

        // Fungsi pembaruan anggota tim
        function updateAnggotaFields(jumlah) {
            let container = $('#anggota-container');
            container.empty();

            for (let i = 1; i <= jumlah; i++) {
                let label = (i === 1) ? 'Ketua Tim' : `Anggota ${i}`;
                let selectId = `mahasiswa_id_${i}`;

                let selectHtml = `
                <div class="form-group anggota-item">
                    <label for="${selectId}" class="col-form-label mt-2">${label} <span class="text-danger">*</span></label>
                    <div class="custom-validation">
                        <select name="mahasiswa_id[]" id="${selectId}" class="form-control select-mahasiswa" required>
                            ${opsiMahasiswa}
                        </select>
                    </div>
                </div>`;

                container.append(selectHtml);
            }

            // Re-inisialisasi Select2
            $('.select-mahasiswa').select2({
                width: '100%',
                placeholder: 'Pilih anggota tim',
                dropdownParent: $('#form-daftar') // agar dropdown tetap tampil di dalam modal
            });
        }

        // Event: Tombol Tambah (+)
        $('#btn-plus').click(function () {
            let input = $('#jumlah_anggota');
            let val = parseInt(input.val());
            let tipe = $('#tipe_lomba').val();

            if (tipe === 'individu') {
                Swal.fire({
                    icon: 'info',
                    title: 'Lomba Individu',
                    text: 'Tidak dapat menambah anggota untuk lomba individu.',
                    confirmButtonColor: '#3085d6',
                });
                return;
            }

            if (val < 10) {
                input.val(val + 1).trigger('change');
            }
        });

        // Event: Tombol Kurang (−)
        $('#btn-minus').click(function () {
            let input = $('#jumlah_anggota');
            let val = parseInt(input.val());
            let tipe = $('#tipe_lomba').val();

            if (tipe === 'individu') {
                Swal.fire({
                    icon: 'info',
                    title: 'Lomba Individu',
                    text: 'Tidak dapat mengurangi anggota untuk lomba individu.',
                    confirmButtonColor: '#3085d6',
                });
                return;
            }

            if (val > 2) {
                input.val(val - 1).trigger('change');
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Minimal Anggota',
                    text: 'Jumlah anggota minimal 2 untuk lomba tim!',
                    confirmButtonColor: '#3085d6',
                });
            }
        });

        // Event: Input jumlah_anggota berubah
        $('#jumlah_anggota').on('change', function () {
            let jumlah = parseInt($(this).val());
            let tipe = $('#tipe_lomba').val();

            if (tipe === 'tim') {
                if (jumlah < 2) jumlah = 2;
                if (jumlah > 10) jumlah = 10;
            } else {
                jumlah = 1;
            }

            $(this).val(jumlah);
            updateAnggotaFields(jumlah);
        });

        // Inisialisasi awal saat modal/form dibuka
        let tipe = $('#tipe_lomba').val();

        if (tipe === 'individu') {
            $('#jumlah_anggota').val(1).prop('readonly', true);
            $('#btn-plus, #btn-minus').attr('disabled', true);
            updateAnggotaFields(1);
        } else {
            $('#jumlah_anggota').val(2).prop('readonly', false);
            $('#btn-plus, #btn-minus').attr('disabled', false);
            updateAnggotaFields(2);
        }
    });
</script>

<script>
    // Submit Form Ajax
    $('#form-daftar').on('submit', function (e) {
        e.preventDefault(); // Cegah submit default

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message || 'Pendaftaran lomba berhasil disimpan!',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    location.reload(); // Reload halaman
                });
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan. Silakan coba lagi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
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
<form id="form-prestasi" method="POST" action="{{ route('mhs.prestasi.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-trophy mr-2"></i>Tambah Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">

        {{-- Pilih Lomba yang tersedia (Lomba yang ditampilkan hanya yang sudah berakhir) --}}
        <label for="lomba_id" class="col-form-label mt-2">Nama Lomba <span class="text-danger">*</span></label>
        <div class="custom-validation">
            <select name="lomba_id" id="lomba_id" class="form-control select2" required>
                <option value="">-- Pilih Lomba --</option>
                @foreach ($daftarLomba as $lomba)
                    <option value="{{ $lomba->lomba_id }}" data-tingkat="{{ $lomba->tingkat_lomba->nama_tingkat ?? '' }}"
                        data-tingkat-id="{{ $lomba->tingkat_lomba->tingkat_lomba_id ?? '' }}"
                        data-kategori="{{ optional($lomba->kategori)->pluck('nama_kategori')->implode(', ') }}"
                        data-kategori-json='@json($lomba->kategori->map(function ($k) {
                            return ['id' => $k->kategori_id, 'text' => $k->nama_kategori];
                        }))' data-tipe="{{ $lomba->tipe_lomba }}">
                        {{ $lomba->nama_lomba }}
                    </option>
                @endforeach
                <option value="lainnya">Lainnya</option>
            </select>

            {{-- Tingkat dan Kategori (Readonly jika pilih dari DB) --}}
            <div id="form-tingkat-lomba" class="form-group mt-2" style="display:none;">
                <label for="nama_tingkat_lomba" class="col-form-label">Tingkat Lomba</label>
                <input type="text" id="nama_tingkat_lomba" class="form-control" readonly>
                <input type="hidden" name="tingkat_lomba_id" id="tingkat_lomba_id">
            </div>
            {{-- Pilih kategori Lomba sesuai dengan lomba yang dipilih --}}
            <div id="form-kategori-lomba" class="form-group mt-2" style="display:none;">
                <label for="kategori_id" class="col-form-label">Kategori Lomba <span
                        class="text-danger">*</span></label>
                <select name="kategori_id" id="kategori_id" class="form-control select2" required>
                    {{-- Diisi via JS --}}
                </select>
            </div>

            {{-- Input Manual (Jika "Lainnya") --}}
            <div id="input-lomba-lainnya" class="form-group mt-2" style="display:none;">
                {{-- Input Nama Lomba --}}
                <label for="lomba_lainnya" class="col-form-label">Nama Lomba (Lainnya) <span
                        class="text-danger">*</span></label>
                <input type="text" name="lomba_lainnya" id="lomba_lainnya" class="form-control">
                {{-- Pilih Tingkat Lomba --}}
                <label for="tingkat_lomba_id" class="col-form-label mt-2">Tingkat Lomba <span
                        class="text-danger">*</span></label>
                <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control select2">
                    <option value="">-- Pilih Tingkat Lomba --</option>
                    @foreach ($daftarTingkatLomba as $tingkat)
                        <option value="{{ $tingkat->tingkat_lomba_id }}">{{ $tingkat->nama_tingkat }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Kategori Manual jika "Lainnya" --}}
            <div id="kategori-lomba-manual" class="form-group mt-2" style="display:none;">
                <label for="kategori_id_manual" class="col-form-label">Kategori Lomba <span
                        class="text-danger">*</span></label>
                <select name="kategori_id" id="kategori_id_manual" class="form-control select2" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tipe Prestasi --}}
        <div class="form-group">
            <label class="col-form-label mt-3">Tipe Prestasi <span class="text-danger">*</span></label>
            <select name="jenis_prestasi" id="jenis_prestasi" class="form-control select2" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="individu">Individu</option>
                <option value="tim">Tim</option>
            </select>
        </div>
        {{-- Anggota Tim --}}
        <div class="form-group mt-3">
            <label class="col-form-label">Jumlah Anggota</label>
            <div class="input-group">
                <button type="button" class="btn btn-outline-secondary" id="btn-minus">-</button>
                <input type="number" id="jumlah_anggota" class="form-control text-center" value="1" readonly>
                <button type="button" class="btn btn-outline-secondary" id="btn-plus">+</button>
            </div>
        </div>
        {{-- Pilih Anggota --}}
        <div id="anggota-container" class="mt-3">
            {{-- Akan di-render otomatis oleh JS --}}
        </div>

        {{-- Tanggal Prestasi --}}
        <label for="tanggal_prestasi" class="col-form-label mt-2">Tanggal Prestasi <span class="text-danger"
                style="color: red;">*</span></label>
        <div class="custom-validation">
            <input type="date" class="form-control" name="tanggal_prestasi" required>
        </div>
        {{-- Juara Prestasi --}}
        <label for="juara_prestasi" class="col-form-label mt-2">Juara Prestasi <span class="text-danger"
                style="color: red;">*</span></label>
        <div class="custom-validation">
            <input type="text" class="form-control" name="juara_prestasi" required>
        </div>
        {{-- Periode --}}
        <label for="periode_id" class="col-form-label mt-2">Periode <span class="text-danger"
                style="color: red;">*</span></label>
        <div class="custom-validation">
            <select name="periode_id" id="periode_id" class="form-control select2" required>
                @foreach ($daftarPeriode as $periode)
                    <option value="{{ $periode->periode_id }}">
                        {{ $periode->semester_periode }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- Dosen Pembimbing --}}
        <label for="dosen_id" class="col-form-label mt-2">Dosen (Opsional)</label>
        <div class="custom-validation">
            <select name="dosen_id" id="dosen_id" class="form-control select2">
                <option value="">-- Tidak ada dosen pembimbing --</option>
                @foreach ($daftarDosen as $dosen)
                    <option value="{{ $dosen->dosen_id }}">
                        {{ $dosen->nama_dosen }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- Gambar Kegiatan --}}
        <label for="img_kegiatan" class="col-form-label mt-2">Gambar Kegiatan <small>(Maksimal 2MB)</small> </label>
        <div class="custom-validation">
            <div class="input-group mt-1">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="img_kegiatan" accept=".png, .jpg, .jpeg"
                        onchange="$('#img_kegiatan_label').text(this.files[0].name)" nullable>
                    <label class="custom-file-label" id="img_kegiatan_label" for="img_kegiatan">Pilih File</label>
                </div>
            </div>
        </div>
        {{-- Bukti Prestasi --}}
        <label for="bukti_prestasi" class="col-form-label mt-2">Bukti Prestasi <small>(Maksimal 2MB)</small>
        </label>
        <div class="custom-validation">
            <div class="input-group mt-1">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="bukti_prestasi" accept=".png, .jpg, .jpeg"
                        onchange="$('#bukti_prestasi_label').text(this.files[0].name)" nullable>
                    <label class="custom-file-label" id="bukti_prestasi_label" for="bukti_prestasi">Pilih
                        File</label>
                </div>
            </div>
        </div>
        {{-- Surat Tugas Prestasi --}}
        <label for="surat_tugas_prestasi" class="col-form-label mt-2">Surat Tugas Prestasi <small>(Maksimal
                2MB)</small>
        </label>
        <div class="custom-validation">
            <div class="input-group mt-1">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="surat_tugas_prestasi" accept=".png, .jpg, .jpeg"
                        onchange="$('#surat_tugas_prestasi_label').text(this.files[0].name)" nullable>
                    <label class="custom-file-label" id="surat_tugas_prestasi_label" for="surat_tugas_prestasi">Pilih
                        File</label>
                </div>
            </div>
        </div>
    </div>
    {{-- Footer Modal--}}
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>

{{-- Debug: --}}
<script>
    window.mahasiswaLoginId = @json(auth()->user()->mahasiswa->mahasiswa_id);
    console.log("Mahasiswa Login ID:", window.mahasiswaLoginId);
</script>
<!-- Script Select2 (Dropdown Multiselect/Search) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Style Tambahan --}}
<style>
    .select2-container {
        min-height: 45px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
        z-index: 99999;
        position: relative;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da !important;
        padding: 8px 12px;
        height: 45px;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 50%;
        transform: translateY(-50%);
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0;
        border: 1px solid #ced4da !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #7571F9;
        background-color: white !important;
        outline: 2px solid #7571F9 !important;
        border: none;
        border-radius: 4px;
        margin-top: 10px;
        margin-left: 12px;
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
{{-- Validasi Inputan --}}
<script>
    $(document).ready(function () {
        // Inisialisasi Select2 untuk dropdown dosen
        $('#dosen_id').select2({
            placeholder: "-- Tidak ada dosen pembimbing --",
            width: '100%',
            minimumResultsForSearch: 0,
            dropdownParent: $('#dosen_id').parent()
        });

        // Inisialisasi Select2 untuk dropdown kategori
        $('#kategori_id_manual').select2({
            width: '100%'
        });

        // Validasi maksimal 3 kategori
        $('#kategori_id_manual').on('change', function () {
            var selected = $(this).val();
            if (selected && selected.length > 3) {
                selected.pop(); // hapus kategori terakhir yang dipilih
                $(this).val(selected).trigger('change.select2');

                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal Terlampaui',
                    text: 'Anda hanya bisa memilih maksimal 3 kategori.',
                    confirmButtonText: 'Oke'
                });
            }
        });
    });
</script>
{{-- Mengirim data dari PHP (Laravel Blade) ke JavaScript di browser. --}}
<script>
    window.daftarMahasiswa = @json($daftarMahasiswa);
</script>
{{-- Kode JavaScript/jQuery yang ingin dijalankan setelah halaman siap --}}
<script>
    $(document).ready(function () {

        const MAX_ANGGOTA_TIM = 5;
        const MIN_ANGGOTA_TIM = 2;
        const JUMLAH_INDIVIDU = 1;

        // Handle pilihan lomba
        $('#lomba_id').on('change', function () {
            const selected = $(this).val();
            const tingkat = $('option:selected', this).data('tingkat') || '';
            const kategoriLabel = $('option:selected', this).data('kategori') || '';
            const kategoriJson = $('option:selected', this).data('kategori-json') || [];
            const tipe = $('option:selected', this).data('tipe') || '';

            if (selected === 'lainnya') {
                // Sembunyikan bagian readonly
                $('#form-tingkat-lomba').hide();
                $('#form-kategori-lomba').hide();
                $('#form-kategori-dropdown').hide(); // tambahan: dropdown untuk kategori dari database
                $('#kategori_id').removeAttr('name').prop('required', false);
                $('#kategori_id_manual').attr('name', 'kategori_id').prop('required', true);
                // Kosongkan nilai readonly
                $('#nama_tingkat_lomba').val('');
                $('#nama_kategori_lomba').val('');

                // Tampilkan input manual lainnya
                $('#input-lomba-lainnya').show();
                $('#kategori-lomba-manual').show();

                // Reset dan aktifkan jenis prestasi
                $('#jenis_prestasi').val('').prop('disabled', false);

                // Set default jumlah anggota
                $('#jumlah_anggota').val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);

            } else if (selected) {
                const tingkat = $('option:selected', this).data('tingkat') || '';
                const tingkatId = $('option:selected', this).data('tingkat-id') || '';
                const kategoriLabel = $('option:selected', this).data('kategori') || '';
                const kategoriJson = $('option:selected', this).data('kategori-json') || [];

                // Tampilkan form readonly
                $('#form-tingkat-lomba').show();
                $('#form-kategori-lomba').show();
                $('#form-kategori-dropdown').show();

                // Set nilai readonly dan hidden input
                $('#nama_tingkat_lomba').val(tingkat);
                $('#tingkat_lomba_id').val(tingkatId);

                $('#nama_kategori_lomba').val(kategoriLabel);

                // Atur kategori dropdown
                $('#kategori_id_manual').removeAttr('name').prop('required', false);
                $('#kategori_id').attr('name', 'kategori_id').prop('required', true);
                $('#input-lomba-lainnya').hide();
                $('#kategori-lomba-manual').hide();

                // Isi dropdown kategori
                $('#kategori_id').html('');
                kategoriJson.forEach(item => {
                    $('#kategori_id').append(`<option value="${item.id}">${item.text}</option>`);
                });
                $('#kategori_id').val('').trigger('change');

                // Reset jenis prestasi
                $('#jenis_prestasi').val('').prop('disabled', false);

                // Atur berdasarkan tipe
                if (tipe) {
                    const tipeLower = tipe.toLowerCase();
                    $('#jenis_prestasi').val(tipeLower).prop('disabled', true);

                    if (tipeLower === 'individu') {
                        $('#jumlah_anggota').val(JUMLAH_INDIVIDU).prop('readonly', true);
                        renderAnggota(JUMLAH_INDIVIDU);
                    } else if (tipeLower === 'tim') {
                        $('#jumlah_anggota').val(MIN_ANGGOTA_TIM).prop('readonly', false);
                        renderAnggota(MIN_ANGGOTA_TIM);
                    }
                } else {
                    $('#jenis_prestasi').val('').prop('disabled', false);
                    $('#jumlah_anggota').val(JUMLAH_INDIVIDU).prop('readonly', true);
                    renderAnggota(JUMLAH_INDIVIDU);
                }

            } else {
                // Semua disembunyikan jika tidak ada yang dipilih
                $('#form-tingkat-lomba').hide();
                $('#form-kategori-lomba').hide();
                $('#form-kategori-dropdown').hide();
                $('#input-lomba-lainnya').hide();
                $('#kategori-lomba-manual').hide();

                $('#kategori_id').html('').val('').trigger('change');

                $('#jenis_prestasi').val('').prop('disabled', false);
                $('#jumlah_anggota').val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);
            }
        });



        // Handle tipe prestasi jika user bisa pilih (lomba lainnya)
        $('#jenis_prestasi').on('change', function () {
            const tipe = $(this).val();
            const jumlahInput = $('#jumlah_anggota');
            const isIndividu = tipe === 'individu';

            if (isIndividu) {
                jumlahInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);
            } else if (tipe === 'tim') {
                jumlahInput.val(MIN_ANGGOTA_TIM).prop('readonly', false);
                renderAnggota(MIN_ANGGOTA_TIM);
            } else {
                jumlahInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);
            }
        });

        $('#btn-plus').on('click', function () {
            const tipe = $('#jenis_prestasi').val();
            let current = parseInt($('#jumlah_anggota').val());

            if (tipe === 'individu') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa',
                    text: 'Jumlah anggota untuk individu harus 1'
                });
                return;
            }

            if (tipe === 'tim') {
                if (current < MAX_ANGGOTA_TIM) {
                    current++;
                    $('#jumlah_anggota').val(current);
                    renderAnggota(current);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: `Jumlah anggota maksimal adalah ${MAX_ANGGOTA_TIM}`
                    });
                }
            }
        });

        $('#btn-minus').on('click', function () {
            const tipe = $('#jenis_prestasi').val();
            let current = parseInt($('#jumlah_anggota').val());

            if (tipe === 'individu') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa',
                    text: 'Jumlah anggota untuk individu harus 1'
                });
                return;
            }

            if (tipe === 'tim') {
                if (current > MIN_ANGGOTA_TIM) {
                    current--;
                    $('#jumlah_anggota').val(current);
                    renderAnggota(current);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: `Jumlah anggota minimal adalah ${MIN_ANGGOTA_TIM}`
                    });
                }
            }
        });

        // Render input anggota tim
        function renderAnggota(jumlah) {
            const container = $('#anggota-container');
            container.empty();

            for (let i = 0; i < jumlah; i++) {
                const label = i === 0 ? 'Ketua Tim' : `Anggota ${i}`;
                const requiredAttr = i === 0 ? 'required' : '';

                let options = `<option value="">-- Pilih ${label} --</option>`;
                daftarMahasiswa.forEach(mhs => {
                    options += `<option value="${mhs.mahasiswa_id}">${mhs.nim_mahasiswa} - ${mhs.nama_mahasiswa}</option>`;
                });

                const anggotaHtml = `
            <div class="form-group anggota-item">
                <label class="col-form-label mt-2">${label} <span class="text-danger">*</span></label>
                <select name="mahasiswa_id[]" class="form-control anggota-select" ${requiredAttr}>
                    ${options}
                </select>
            </div>
        `;
                container.append(anggotaHtml);
            }

            // Inisialisasi ulang Select2 untuk elemen yang baru ditambahkan
            $('.anggota-select').select2({
                width: '100%',
                dropdownParent: $('#anggota-container') // Penting untuk select2 dalam dynamic/modal
            });
        }

    });
</script>

{{-- Cek Lomba Yang di Submit Duplikat atau tidak --}}
<script>
    const urlCekLombaDuplicate = "{{ route('mhs.prestasi.cekLombaDuplicate') }}";
</script>
{{-- Submit Handler --}}
<script>
    $(document).ready(function () {

        // Pastikan url cek duplicate sudah terdefinisi global atau buat variabel di sini
        const urlCekLombaDuplicate = "{{ route('mhs.prestasi.cekLombaDuplicate') }}";

        $('#form-prestasi').on('submit', function (e) {
            e.preventDefault();

            const mahasiswaLoginId = window.mahasiswaLoginId?.toString();
            if (!mahasiswaLoginId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Data mahasiswa login tidak ditemukan.',
                    confirmButtonText: 'Oke'
                });
                return;
            }

            // Ambil nilai lomba yang dipilih
            const lombaId = $('#lomba_id').val();
            if (!lombaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Lomba',
                    text: 'Silakan pilih lomba terlebih dahulu.',
                    confirmButtonText: 'Oke'
                });
                return;
            }

            // Ambil semua mahasiswa_id yang dipilih
            let anggotaTim = [];
            let foundLogin = false;
            let duplicateMahasiswa = false;

            $('select[name="mahasiswa_id[]"]').each(function () {
                const val = $(this).val();
                if (!val) return; // skip jika kosong

                if (val === mahasiswaLoginId) foundLogin = true;
                if (anggotaTim.includes(val)) duplicateMahasiswa = true;

                anggotaTim.push(val);
            });

            if (!foundLogin) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mahasiswa yang login harus menjadi salah satu anggota tim (Ketua atau Anggota).',
                    confirmButtonText: 'Oke'
                });
                return;
            }

            if (duplicateMahasiswa) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Nama mahasiswa yang dipilih tidak boleh sama atau dobel.',
                    confirmButtonText: 'Oke'
                });
                return;
            }

            // Cek duplicate lomba di server
            $.ajax({
                url: urlCekLombaDuplicate,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    mahasiswa_id: mahasiswaLoginId,
                    lomba_id: lombaId,
                },
                success: function (response) {
                    if (response.status === 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: response.message || 'Anda sudah pernah submit lomba ini sebelumnya.',
                            confirmButtonText: 'Oke'
                        });
                    } else {
                        submitPrestasiForm();
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal melakukan pengecekan lomba, silakan coba lagi.',
                        confirmButtonText: 'Oke'
                    });
                }
            });

            function submitPrestasiForm() {
                var form = $('#form-prestasi')[0];
                var formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Prestasi berhasil disimpan!',
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            $('#myModal').modal('hide');
                            location.reload();
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
            }

        });
    });
</script>
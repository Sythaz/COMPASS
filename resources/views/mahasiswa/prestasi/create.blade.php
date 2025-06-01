<form id="form-prestasi" method="POST" action="{{ route('mhs.prestasi.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-trophy mr-2"></i>Tambah Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        {{-- Nama Prestasi --}}
        <label for="lomba_id" class="col-form-label mt-2">Nama Lomba <span class="text-danger">*</span></label>
        <div class="custom-validation">
            <select name="lomba_id" id="lomba_id" class="form-control select2" required>
                <option value="">-- Pilih Lomba --</option>
                @foreach ($daftarLomba as $lomba)
                    <option value="{{ $lomba->lomba_id }}" data-tingkat="{{ $lomba->tingkat_lomba->nama_tingkat ?? '' }}"
                        data-kategori="{{ optional($lomba->kategori)->pluck('nama_kategori')->implode(', ') }}">
                        {{ $lomba->nama_lomba }}
                    </option>
                @endforeach
                <option value="lainnya">Lainnya</option>
            </select>

            {{-- Tampilkan nama tingkat otomatis (readonly) --}}
            <div id="tingkat-lomba-terpilih" class="form-group mt-2" style="display:none;">
                <label for="nama_tingkat_lomba" class="col-form-label">Tingkat Lomba</label>
                <input type="text" id="nama_tingkat_lomba" name="nama_tingkat_lomba" class="form-control" readonly>
            </div>

            {{-- Input manual jika pilih Lainnya --}}
            <div id="input-lomba-lainnya" class="form-group mt-2" style="display:none;">
                <label for="nama_lomba_lainnya" class="col-form-label">
                    Nama Lomba (Lainnya) <span class="text-danger">*</span>
                </label>
                <input type="text" name="nama_lomba_lainnya" id="nama_lomba_lainnya" class="form-control">

                <label for="tingkat_lomba_id" class="col-form-label mt-2">
                    Tingkat Lomba <span class="text-danger">*</span>
                </label>
                <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control select2">
                    <option value="">-- Pilih Tingkat Lomba --</option>
                    @foreach ($daftarTingkatLomba as $tingkat)
                        <option value="{{ $tingkat->tingkat_lomba_id }}">{{ $tingkat->nama_tingkat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Kategori Otomatis (readonly) jika pilih lomba terdaftar --}}
            <div id="kategori-lomba-otomatis" class="form-group mt-2" style="display:none;">
                <label class="col-form-label">Kategori Lomba</label>
                <input type="text" id="nama_kategori_lomba" class="form-control" readonly>
            </div>

            {{-- Pilih Kategori Manual jika pilih "Lainnya" --}}
            <div id="kategori-lomba-manual" class="form-group mt-2" style="display:none;">
                <label for="kategori_id_manual" class="col-form-label">Pilih Kategori <small>(boleh pilih lebih dari
                        satu)</small></label>
                <select name="kategori_id_manual[]" id="kategori_id_manual" class="form-control select2" multiple>
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tipe Prestasi --}}
        <div class="form-group">
            <label class="col-form-label">Tipe Prestasi <span class="text-danger">*</span></label>
            <select name="tipe_prestasi" id="tipe_prestasi" class="form-control select2" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="individu">Individu</option>
                <option value="tim">Tim</option>
            </select>
        </div>

        {{-- Jumlah Anggota Tim --}}
        <div class="form-group">
            <label class="col-form-label mt-2">Jumlah Anggota Tim (termasuk ketua) <span
                    class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-outline-secondary" id="btn-minus">−</button>
                </div>
                <input type="number" id="jumlah_anggota" name="jumlah_anggota" class="form-control text-center" min="1"
                    max="5" value="1" required readonly>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="btn-plus">+</button>
                </div>
            </div>
            <small class="form-text text-muted">Masukkan jumlah anggota tim, termasuk ketua</small>
        </div>

        {{-- Daftar Mahasiswa --}}
        <div id="anggota-container">
            <div class="form-group anggota-item">
                <label class="col-form-label mt-2">Ketua Tim <span class="text-danger">*</span></label>
                <select name="mahasiswa_id[]" class="form-control select-mahasiswa" required>
                    <option value="">-- Pilih Ketua Tim --</option>
                    @foreach ($daftarMahasiswa as $mhs)
                        <option value="{{ $mhs->mahasiswa_id }}">{{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>


{{-- Input manual jika pilih Lainnya --}}
<script>
    $(document).ready(function () {
        $('#lomba_id').on('change', function () {
            const selectedValue = $(this).val();
            const selectedOption = $('#lomba_id option:selected');

            if (selectedValue === 'lainnya') {
                // Tampilkan input manual lomba, tingkat, dan kategori manual
                $('#input-lomba-lainnya').show();
                $('#kategori-lomba-manual').show();

                // Set required
                $('#nama_lomba_lainnya, #tingkat_lomba_id').attr('required', true);
                $('#kategori_id_manual').attr('required', true);

                // Sembunyikan tingkat dan kategori otomatis
                $('#tingkat-lomba-terpilih').hide();
                $('#kategori-lomba-otomatis').hide();

                // Kosongkan nilai otomatis
                $('#nama_tingkat_lomba').val('');
                $('#nama_kategori_lomba').val('');
            } else if (selectedValue) {
                // Lomba terdaftar dipilih
                $('#input-lomba-lainnya, #kategori-lomba-manual').hide();

                // Hilangkan required dari input manual
                $('#nama_lomba_lainnya, #tingkat_lomba_id, #kategori_id_manual').removeAttr('required').val('');

                // Tampilkan tingkat otomatis
                const tingkat = selectedOption.data('tingkat');
                if (tingkat) {
                    $('#tingkat-lomba-terpilih').show();
                    $('#nama_tingkat_lomba').val(tingkat);
                } else {
                    $('#tingkat-lomba-terpilih').hide();
                    $('#nama_tingkat_lomba').val('');
                }

                // Tampilkan kategori otomatis (readonly)
                const kategori = selectedOption.data('kategori');
                if (kategori) {
                    $('#kategori-lomba-otomatis').show();
                    $('#nama_kategori_lomba').val(kategori);
                } else {
                    $('#kategori-lomba-otomatis').hide();
                    $('#nama_kategori_lomba').val('');
                }
            } else {
                // Reset semua jika tidak ada pilihan
                $('#input-lomba-lainnya, #tingkat-lomba-terpilih, #kategori-lomba-otomatis, #kategori-lomba-manual').hide();
                $('#nama_lomba_lainnya, #tingkat_lomba_id, #kategori_id_manual').removeAttr('required').val('');
                $('#nama_tingkat_lomba, #nama_kategori_lomba').val('');
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Inputan Anggota Dinamis --}}
<script>
    // Render daftar mahasiswa dari server (blade template)
    var optionsMahasiswa = `
        @foreach ($daftarMahasiswa as $mhs)
            <option value="{{ $mhs->mahasiswa_id }}">{{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }}</option>
        @endforeach
    `;

    $(document).ready(function () {
        // Inisialisasi Select2 dengan tema bootstrap4
        function initSelect2() {
            $('.select-mahasiswa').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#form-prestasi'), // sesuaikan dengan modal/form yang dipakai
                width: '100%',
                placeholder: 'Pilih anggota tim'
            });

            $('#tipe_prestasi, select[name="tingkat"]').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#form-prestasi'),
                width: '100%'
            });
        }

        // Render input anggota sesuai jumlah
        function updateAnggotaInputs(jumlah) {
            const container = $('#anggota-container');
            container.empty();

            for (let i = 0; i < jumlah; i++) {
                const label = (i === 0) ? 'Ketua Tim' : `Anggota #${i + 1}`;
                const html = `
        <div class="form-group anggota-item">
            <label class="col-form-label">${label} <span class="text-danger">*</span></label>
            <select name="mahasiswa_id[]" class="form-control select-mahasiswa" required>
                <option value="">-- Pilih ${label} --</option>
                ${optionsMahasiswa}
            </select>
        </div>`;
                container.append(html);
            }

            // Tambahkan ini supaya input jumlah_anggota selalu sinkron
            $('#jumlah_anggota').val(jumlah);

            initSelect2();
            disableSelectedOptions();
        }


        // Fungsi disable pilihan yang sudah dipilih di dropdown lain agar tidak bisa ganda
        function disableSelectedOptions() {
            let selected = [];
            $('.select-mahasiswa').each(function () {
                const val = $(this).val();
                if (val) selected.push(val);
            });

            $('.select-mahasiswa').each(function () {
                const current = $(this);
                const currentValue = current.val();

                current.find('option').each(function () {
                    const optVal = $(this).val();
                    if (optVal && optVal !== currentValue && selected.includes(optVal)) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });
        }

        // Update tombol tambah dan kurang serta input jumlah anggota
        function updateButtons(tipe, jumlah) {
            if (tipe === 'individu') {
                $('#btn-plus, #btn-minus').prop('disabled', true);
                jumlah = 1; // wajib 1 untuk individu
            } else if (tipe === 'tim') {
                $('#btn-plus').prop('disabled', jumlah >= 5);
                $('#btn-minus').prop('disabled', jumlah <= 2);
            } else {
                $('#btn-plus, #btn-minus').prop('disabled', true);
            }

            $('#jumlah_anggota').val(jumlah);  // pastikan ini ada
        }


        // Event klik tombol tambah
        $(document).on('click', '#btn-plus', function () {
            const tipe = $('#tipe_prestasi').val();
            let jumlah = $('#anggota-container .anggota-item').length;

            if (tipe === 'tim') {
                if (jumlah < 5) {
                    jumlah++;  // update jumlah

                    updateAnggotaInputs(jumlah);  // render ulang form anggota sesuai jumlah terbaru
                    updateButtons(tipe, jumlah);  // update tombol dan input jumlah_anggota
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jumlah Maksimal',
                        text: 'Jumlah anggota tim tidak boleh lebih dari 5.'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Tipe Prestasi Individu',
                    text: 'Jumlah anggota untuk tipe individu selalu 1.'
                });
            }
        });


        // Event klik tombol kurang
        $(document).on('click', '#btn-minus', function () {
            const tipe = $('#tipe_prestasi').val();
            let jumlah = $('#anggota-container .anggota-item').length;

            if (tipe === 'tim') {
                if (jumlah > 2) {
                    jumlah--;
                    updateAnggotaInputs(jumlah);
                    updateButtons(tipe, jumlah);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jumlah Minimal',
                        text: 'Jumlah anggota tim tidak boleh kurang dari 2.'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Tipe Prestasi Individu',
                    text: 'Jumlah anggota untuk tipe individu selalu 1.'
                });
            }
        });

        // Event change pada tipe prestasi
        $('#tipe_prestasi').change(function () {
            const tipe = $(this).val();
            const jumlah = (tipe === 'tim') ? 2 : 1;

            updateAnggotaInputs(jumlah);
            updateButtons(tipe, jumlah);
        });

        // Event disable opsi ganda saat pilih mahasiswa
        $(document).on('change', '.select-mahasiswa', function () {
            disableSelectedOptions();
        });

        // Inisialisasi awal saat form/modal dibuka
        const tipeAwal = $('#tipe_prestasi').val() || 'individu';
        const jumlahAwal = (tipeAwal === 'tim') ? 2 : 1;

        updateAnggotaInputs(jumlahAwal);
        updateButtons(tipeAwal, jumlahAwal);
    });
</script>
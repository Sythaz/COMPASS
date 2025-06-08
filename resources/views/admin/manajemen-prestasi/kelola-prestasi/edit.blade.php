<form id="form-prestasi" action="{{ route('kelola-prestasi.update', $prestasi->prestasi_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        {{-- Pilih Lomba yang tersedia --}}
        <label for="lomba_id" class="col-form-label mt-2">Nama Lomba <span class="text-danger">*</span></label>
        <div class="custom-validation">
            <select name="lomba_id" id="lomba_id" class="form-control select2" required>
                <option value="">-- Pilih Lomba --</option>
                @foreach ($daftarLomba as $lomba)
                    <option value="{{ $lomba->lomba_id }}"
                        data-tingkat="{{ $lomba->tingkat_lomba->nama_tingkat ?? '' }}"
                        data-tingkat-id="{{ $lomba->tingkat_lomba->tingkat_lomba_id ?? '' }}"
                        data-kategori="{{ optional($lomba->kategori)->pluck('nama_kategori')->implode(', ') }}"
                        data-kategori-json='@json(
                            $lomba->kategori->map(function ($k) {
                                return ['id' => $k->kategori_id, 'text' => $k->nama_kategori];
                            }))' data-tipe="{{ $lomba->tipe_lomba }}"
                        {{ $prestasi->lomba_id == $lomba->lomba_id ? 'selected' : '' }}>
                        {{ $lomba->nama_lomba }}
                    </option>
                @endforeach
                <option value="lainnya" {{ is_null($prestasi->lomba_id) ? 'selected' : '' }}>Lainnya</option>
            </select>

            {{-- Tingkat dan Kategori (Readonly jika pilih dari DB) --}}
            <div id="form-tingkat-lomba" class="form-group mt-2"
                style="{{ is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                <label for="nama_tingkat_lomba" class="col-form-label">Tingkat Lomba</label>
                <input type="text" id="nama_tingkat_lomba" class="form-control"
                    value="{{ $prestasi->lomba_id ? $prestasi->lomba->tingkat_lomba->nama_tingkat ?? '' : '' }}"
                    readonly>
                <input type="hidden" name="tingkat_lomba_id" id="tingkat_lomba_id"
                    value="{{ $prestasi->lomba_id ? $prestasi->lomba->tingkat_lomba->tingkat_lomba_id ?? '' : '' }}">
            </div>

            {{-- Pilih kategori Lomba sesuai dengan lomba yang dipilih --}}
            <div id="form-kategori-lomba" class="form-group mt-2"
                style="{{ is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                <label for="kategori_id" class="col-form-label">Kategori Lomba <span
                        class="text-danger">*</span></label>
                <select name="kategori_id" id="kategori_id" class="form-control select2" required>
                    @if ($prestasi->lomba_id)
                        @foreach ($prestasi->lomba->kategori as $kategori)
                            <option value="{{ $kategori->kategori_id }}"
                                {{ $prestasi->kategori_id == $kategori->kategori_id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- Input Manual (Jika "Lainnya") --}}
            <div id="input-lomba-lainnya" class="form-group mt-2"
                style="{{ !is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                {{-- Input Nama Lomba --}}
                <label for="lomba_lainnya" class="col-form-label">Nama Lomba (Lainnya) <span
                        class="text-danger">*</span></label>
                <input type="text" name="lomba_lainnya" id="lomba_lainnya" class="form-control"
                    value="{{ $prestasi->lomba_lainnya }}">

                {{-- Pilih Tingkat Lomba --}}
                <label for="tingkat_lomba_id" class="col-form-label mt-2">Tingkat Lomba <span
                        class="text-danger">*</span></label>
                <select name="tingkat_lomba_id" id="tingkat_lomba_id" class="form-control select2">
                    <option value="">-- Pilih Tingkat Lomba --</option>
                    @foreach ($daftarTingkatLomba as $tingkat)
                        <option value="{{ $tingkat->tingkat_lomba_id }}"
                            {{ $prestasi->tingkat_lomba_id == $tingkat->tingkat_lomba_id ? 'selected' : '' }}>
                            {{ $tingkat->nama_tingkat }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kategori Manual jika "Lainnya" --}}
            <div id="kategori-lomba-manual" class="form-group mt-2"
                style="{{ !is_null($prestasi->lomba_id) ? 'display:none;' : '' }}">
                <label for="kategori_id_manual" class="col-form-label">Kategori Lomba <span
                        class="text-danger">*</span></label>
                <select name="kategori_id" id="kategori_id_manual" class="form-control select2" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->kategori_id }}"
                            {{ $prestasi->kategori_id == $kategori->kategori_id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tipe Prestasi --}}
        <div class="form-group">
            <label class="col-form-label mt-3">Tipe Prestasi <span class="text-danger">*</span></label>
            <select name="jenis_prestasi" id="jenis_prestasi" class="form-control select2" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="individu"
                    {{ strtolower(old('jenis_prestasi', $prestasi->jenis_prestasi ?? '')) == 'individu' ? 'selected' : '' }}>
                    Individu</option>
                <option value="tim"
                    {{ strtolower(old('jenis_prestasi', $prestasi->jenis_prestasi ?? '')) == 'tim' ? 'selected' : '' }}>
                    Tim</option>
            </select>
        </div>

        {{-- Anggota Tim --}}
        <div class="form-group mt-3">
            <label class="col-form-label">Jumlah Anggota</label>
            <div class="input-group">
                <button type="button" class="btn btn-outline-secondary" id="btn-minus">-</button>
                <input type="number" id="jumlah_anggota" class="form-control text-center"
                    value="{{ count($anggotaTim) > 0 ? count($anggotaTim) : 1 }}" readonly>
                <button type="button" class="btn btn-outline-secondary" id="btn-plus">+</button>
            </div>
        </div>

        {{-- Pilih Anggota --}}
        <div id="anggota-container" class="mt-3">
            @foreach ($anggotaTim as $index => $anggota)
                <div class="form-group anggota-item">
                    <label class="col-form-label mt-2">
                        {{ $index === 0 ? 'Ketua Tim' : 'Anggota ' . $index }}
                        <span class="text-danger">*</span>
                    </label>
                    <select name="mahasiswa_id[]" class="form-control anggota-select"
                        {{ $index === 0 ? 'required' : '' }}>
                        <option value="">-- Pilih {{ $index === 0 ? 'Ketua Tim' : 'Anggota ' . $index }} --
                        </option>
                        @foreach ($daftarMahasiswa as $mhs)
                            <option value="{{ $mhs->mahasiswa_id }}"
                                {{ $anggota['mahasiswa_id'] == $mhs->mahasiswa_id ? 'selected' : '' }}>
                                {{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>

        {{-- Tanggal Prestasi --}}
        <label for="tanggal_prestasi" class="col-form-label mt-2">Tanggal Prestasi <span
                class="text-danger">*</span></label>
        <div class="custom-validation">
            <input type="date" class="form-control" name="tanggal_prestasi"
                value="{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('Y-m-d') }}" required>
        </div>

        {{-- Juara Prestasi --}}
        <label for="juara_prestasi" class="col-form-label mt-2">Juara Prestasi <span
                class="text-danger">*</span></label>
        <div class="custom-validation">
            <input type="text" class="form-control" name="juara_prestasi"
                value="{{ $prestasi->juara_prestasi }}" required>
        </div>

        {{-- Periode --}}
        <label for="periode_id" class="col-form-label mt-2">Periode <span class="text-danger">*</span></label>
        <div class="custom-validation">
            <select name="periode_id" id="periode_id" class="form-control select2" required>
                @foreach ($daftarPeriode as $periode)
                    <option value="{{ $periode->periode_id }}"
                        {{ $prestasi->periode_id == $periode->periode_id ? 'selected' : '' }}>
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
                    <option value="{{ $dosen->dosen_id }}"
                        {{ $prestasi->dosen_id == $dosen->dosen_id ? 'selected' : '' }}>
                        {{ $dosen->nama_dosen }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Status Verifikasi --}}
        <div class="form-group">
            <label class="col-form-label mt-3">Status Verifikasi <span class="text-danger">*</span></label>
            <select name="status_verifikasi" id="status_verifikasi" class="form-control select2" required>
                <option value="">-- Pilih Status --</option>
                <option value="Ditolak"
                    {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Ditolak') === 0 ? 'selected' : '' }}>Ditolak
                </option>
                <option value="Valid"
                    {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Valid') === 0 ? 'selected' : '' }}>Valid
                </option>
                <option value="Menunggu"
                    {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Menunggu') === 0 ? 'selected' : '' }}>Menunggu
                </option>
                <option value="Terverifikasi"
                    {{ strcasecmp($prestasi->status_verifikasi ?? '', 'Terverifikasi') === 0 ? 'selected' : '' }}>
                    Terverifikasi</option>
            </select>
        </div>

        {{-- Gambar Kegiatan --}}
        <label for="img_kegiatan" class="col-form-label mt-2">Gambar Kegiatan <small>(Maksimal 2MB)</small></label>
        <div class="custom-validation">
            <div class="input-group mt-1">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="img_kegiatan" accept=".png, .jpg, .jpeg"
                        onchange="$('#img_kegiatan_label').text(this.files[0] ? this.files[0].name : '{{ $prestasi->img_kegiatan ? basename($prestasi->img_kegiatan) : 'Pilih File' }}')">
                    <label class="custom-file-label" id="img_kegiatan_label" for="img_kegiatan">
                        {{ $prestasi->img_kegiatan ? basename($prestasi->img_kegiatan) : 'Pilih File' }}
                    </label>
                </div>
            </div>
            @if ($prestasi->img_kegiatan)
                <small class="text-muted">File saat ini: <a href="{{ asset($prestasi->img_kegiatan) }}"
                        target="_blank">{{ basename($prestasi->img_kegiatan) }}</a></small>
            @endif
        </div>

        {{-- Bukti Prestasi --}}
        <label for="bukti_prestasi" class="col-form-label mt-2">Bukti Prestasi <small>(Maksimal 2MB)</small></label>
        <div class="custom-validation">
            <div class="input-group mt-1">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="bukti_prestasi" accept=".png, .jpg, .jpeg"
                        onchange="$('#bukti_prestasi_label').text(this.files[0] ? this.files[0].name : '{{ $prestasi->bukti_prestasi ? basename($prestasi->bukti_prestasi) : 'Pilih File' }}')">
                    <label class="custom-file-label" id="bukti_prestasi_label" for="bukti_prestasi">
                        {{ $prestasi->bukti_prestasi ? basename($prestasi->bukti_prestasi) : 'Pilih File' }}
                    </label>
                </div>
            </div>
            @if ($prestasi->bukti_prestasi)
                <small class="text-muted">File saat ini: <a href="{{ asset($prestasi->bukti_prestasi) }}"
                        target="_blank">{{ basename($prestasi->bukti_prestasi) }}</a></small>
            @endif
        </div>

        {{-- Surat Tugas Prestasi --}}
        <label for="surat_tugas_prestasi" class="col-form-label mt-2">Surat Tugas Prestasi <small>(Maksimal
                2MB)</small></label>
        <div class="custom-validation">
            <div class="input-group mt-1">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="surat_tugas_prestasi"
                        accept=".png, .jpg, .jpeg"
                        onchange="$('#surat_tugas_prestasi_label').text(this.files[0] ? this.files[0].name : '{{ $prestasi->surat_tugas_prestasi ? basename($prestasi->surat_tugas_prestasi) : 'Pilih File' }}')">
                    <label class="custom-file-label" id="surat_tugas_prestasi_label" for="surat_tugas_prestasi">
                        {{ $prestasi->surat_tugas_prestasi ? basename($prestasi->surat_tugas_prestasi) : 'Pilih File' }}
                    </label>
                </div>
            </div>
            @if ($prestasi->surat_tugas_prestasi)
                <small class="text-muted">File saat ini: <a href="{{ asset($prestasi->surat_tugas_prestasi) }}"
                        target="_blank">{{ basename($prestasi->surat_tugas_prestasi) }}</a></small>
            @endif
        </div>
    </div>

    {{-- Footer Modal --}}
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>


<script>
    const daftarMahasiswa = @json($daftarMahasiswa);
</script>
<script>
    $(document).ready(function() {
        const MAX_ANGGOTA_TIM = 5;
        const MIN_ANGGOTA_TIM = 2;
        const JUMLAH_INDIVIDU = 1;

        const jenisPrestasiSelect = $('#jenis_prestasi');
        const jumlahAnggotaInput = $('#jumlah_anggota');

        // Event handler tombol PLUS
        $('#btn-plus').on('click', function() {
            const tipe = jenisPrestasiSelect.val();
            let current = parseInt(jumlahAnggotaInput.val());

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
                    jumlahAnggotaInput.val(current);
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

        // Event handler tombol MINUS
        $('#btn-minus').on('click', function() {
            const tipe = jenisPrestasiSelect.val();
            let current = parseInt(jumlahAnggotaInput.val());

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
                    jumlahAnggotaInput.val(current);
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

        // Event handler saat memilih lomba
        $('#lomba_id').on('change', function() {
            const selected = $(this).val();
            const selectedOption = $('option:selected', this);

            let kategoriJsonRaw = selectedOption.attr('data-kategori-json') || '[]';
            let kategoriJson = [];
            try {
                kategoriJson = JSON.parse(kategoriJsonRaw);
            } catch (e) {
                console.error('JSON parse error kategori-json:', e);
                kategoriJson = [];
            }

            const tingkat = selectedOption.data('tingkat') || '';
            const tipeLomba = selectedOption.data('tipe') || '';

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

                // Aktifkan dropdown tipe prestasi & set ke nilai lama (biar user bisa ubah atau tidak)
                jenisPrestasiSelect.prop('disabled', false);
                jenisPrestasiSelect.val(oldJenisPrestasi || 'individu');

                // Set jumlah anggota sesuai tipe prestasi lama
                if (jenisPrestasiSelect.val() === 'tim') {
                    jumlahAnggotaInput.val(oldJumlahAnggota || MIN_ANGGOTA_TIM).prop('readonly', false);
                    renderAnggota(oldJumlahAnggota || MIN_ANGGOTA_TIM);
                } else {
                    jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                    renderAnggota(JUMLAH_INDIVIDU);
                }
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

                $('#kategori_id').html('');
                kategoriJson.forEach(item => {
                    $('#kategori_id').append(
                        `<option value="${item.id}">${item.text}</option>`);
                });

                if (tipeLomba) {
                    jenisPrestasiSelect.val(tipeLomba.toLowerCase()).prop('disabled', true);

                    if (tipeLomba.toLowerCase() === 'individu') {
                        jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                        renderAnggota(JUMLAH_INDIVIDU);
                    } else {
                        const jumlah = jumlahAnggotaInput.val() > MIN_ANGGOTA_TIM ? jumlahAnggotaInput
                            .val() : MIN_ANGGOTA_TIM;
                        jumlahAnggotaInput.val(jumlah).prop('readonly', false);
                        renderAnggota(jumlah);
                    }
                } else {
                    jenisPrestasiSelect.prop('disabled', false);

                    const currentType = jenisPrestasiSelect.val();
                    if (currentType === 'individu') {
                        jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                        renderAnggota(JUMLAH_INDIVIDU);
                    } else if (currentType === 'tim') {
                        jumlahAnggotaInput.val(MIN_ANGGOTA_TIM).prop('readonly', false);
                        renderAnggota(MIN_ANGGOTA_TIM);
                    } else {
                        jenisPrestasiSelect.val('individu');
                        jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                        renderAnggota(JUMLAH_INDIVIDU);
                    }
                }
            } else {
                $('#form-tingkat-lomba').hide();
                $('#form-kategori-lomba').hide();
                $('#input-lomba-lainnya').hide();
                $('#kategori-lomba-manual').hide();

                jenisPrestasiSelect.prop('disabled', false);
                jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);
            }
        });

        // Event handler perubahan tipe prestasi
        jenisPrestasiSelect.on('change', function() {
            const tipe = $(this).val();
            if (tipe === 'individu') {
                jumlahAnggotaInput.val(JUMLAH_INDIVIDU).prop('readonly', true);
                renderAnggota(JUMLAH_INDIVIDU);
            } else {
                // minimal anggota tim
                jumlahAnggotaInput.val(MIN_ANGGOTA_TIM).prop('readonly', false);
                renderAnggota(MIN_ANGGOTA_TIM);
            }
        });

        // Fungsi render input anggota tim (ketua + anggota)
        function renderAnggota(jumlah) {
            const container = $('#anggota-container');
            container.empty();

            for (let i = 0; i < jumlah; i++) {
                const label = i === 0 ? 'Ketua Tim' : `Anggota ${i}`;
                const requiredAttr = i === 0 ? 'required' : '';

                let options = `<option value="">-- Pilih ${label} --</option>`;
                daftarMahasiswa.forEach(mhs => {
                    options +=
                        `<option value="${mhs.mahasiswa_id}">${mhs.nim_mahasiswa} - ${mhs.nama_mahasiswa}</option>`;
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

            // Set nilai anggota yang sudah ada jika ada data dari server
            @foreach ($anggotaTim as $index => $anggota)
                $(`select[name="mahasiswa_id[]"]:eq({{ $index }})`).val(
                    '{{ $anggota['mahasiswa_id'] }}');
            @endforeach
        }

        // Inisialisasi awal form berdasarkan data yang ada (misal edit mode)
        if ('{{ $prestasi->lomba_id }}' === '') {
            $('#input-lomba-lainnya').show();
            $('#kategori-lomba-manual').show();
            $('#form-tingkat-lomba').hide();
            $('#form-kategori-lomba').hide();
        }

        // Trigger manual change pada load page untuk inisialisasi form
        $('#lomba_id').trigger('change');

    });
</script>

<script>
    $(document).on('change', '.anggota-select', function() {
        const selectedVals = $('.anggota-select').map(function() {
            return $(this).val();
        }).get();

        const duplicates = selectedVals.filter((item, index) => selectedVals.indexOf(item) !== index);

        if (duplicates.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Duplikat Terdeteksi',
                text: 'Mahasiswa yang sama tidak boleh dipilih lebih dari sekali.'
            });

            $(this).val('').trigger('change'); // kosongkan input duplikat
        }
    });

    // Unbind dulu sebelum bind, supaya event submit tidak double
    $(document).off('submit', '#form-prestasi').on('submit', '#form-prestasi', function(e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = new FormData(this);

        $.ajax({
            url: actionUrl,
            method: 'POST', // Laravel handle PUT lewat @method('PUT') hidden input
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#btn-submit').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message || 'Data prestasi berhasil diperbarui'
                });

                $('#myModal').modal('hide');
                $('#prestasiTable').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan';
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    msg = Object.values(errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: msg
                });
            },
            complete: function() {
                $('#btn-submit').prop('disabled', false).text('Simpan');
            }
        });
    });
</script>

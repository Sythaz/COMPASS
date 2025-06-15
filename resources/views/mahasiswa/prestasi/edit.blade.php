<form id="form-prestasi" method="POST" action="{{ route('mhs.prestasi.update', $prestasi->prestasi_id) }}"
    enctype="multipart/form-data">
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
                <option value="individu" {{ $prestasi->jenis_prestasi == 'Individu' ? 'selected' : '' }}>Individu
                </option>
                <option value="tim" {{ $prestasi->jenis_prestasi == 'Tim' ? 'selected' : '' }}>Tim</option>
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
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>

<!-- Memanggil Fungsi Form Validation Custom -->
<script src="{{ asset('js-custom/form-validation.js') }}"></script>

<script>
    window.daftarMahasiswa = @json($daftarMahasiswa);

    $(document).ready(function() {
        const idDataTables = '#pendaftaranTable';
        let table;

        table = $(idDataTables).DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            pagingType: "simple_numbers",
            language: {
                lengthMenu: "Tampilkan _MENU_ entri",
                paginate: {
                    first: "Pertama",
                    previous: "Sebelum",
                    next: "Lanjut",
                    last: "Terakhir"
                },
                search: "Cari:",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                emptyTable: "Belum ada data prestasi tersedia"
            },
            ajax: {
                url: '{{ route('riwayat-pendaftaran.list') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function(d) {
                    d.status_verifikasi = $('#status_verifikasi').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'nama_mahasiswa',
                    name: 'mahasiswa.nama_mahasiswa'
                },
                {
                    data: 'nama_lomba',
                    name: 'lomba.nama_lomba'
                },
                {
                    data: 'tipe_lomba',
                    name: 'lomba.tipe_lomba',
                    className: 'text-center'
                },
                {
                    data: 'tanggal_daftar',
                    name: 'created_at',
                    className: 'text-center'
                },
                {
                    data: 'status_verifikasi',
                    name: 'status_pendaftaran',
                    className: 'text-center'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            layout: {
                topStart: null,
                topEnd: null,
                bottomStart: null,
                bottomEnd: null
            },
            drawCallback: function() {
                $(".dataTables_wrapper").css({
                    margin: "0",
                    padding: "0"
                });

                // Styling pagination
                $(".dataTables_paginate .pagination").addClass("justify-content-end");
                $(".dataTables_paginate .paginate_button")
                    .removeClass("paginate_button")
                    .addClass("page-item");
                $(".dataTables_paginate .paginate_button a")
                    .addClass("page-link")
                    .css("border-radius", "5px");
                $(".dataTables_paginate .paginate_button.previous a").text("Sebelum");
                $(".dataTables_paginate .paginate_button.next a").text("Lanjut");
                $(".dataTables_paginate .paginate_button.first a").text("Pertama");
                $(".dataTables_paginate .paginate_button.last a").text("Terakhir");

                // Styling input pencarian
                $(idDataTables + '_filter input').css({
                    height: "auto",
                    "border-radius": "5px",
                    border: "1px solid #ced4da"
                });

                // Styling dropdown jumlah entri
                $(idDataTables + '_length select').css({
                    width: "auto",
                    height: "auto",
                    "border-radius": "5px",
                    border: "1px solid #ced4da"
                });

                // Styling header & body tabel
                $(idDataTables + '_wrapper .table-bordered').css({
                    "border-top-left-radius": "5px",
                    "border-top-right-radius": "5px"
                });
                $(idDataTables + '_wrapper .dataTables_scrollBody table').css({
                    "border-top-left-radius": "0px",
                    "border-top-right-radius": "0px",
                    "border-bottom-left-radius": "5px",
                    "border-bottom-right-radius": "5px"
                });
            }
        });

        // Reload otomatis saat filter status berubah
        $('#status_verifikasi').on('change', function() {
            table.ajax.reload();
        });

        // Modal AJAX
        window.modalAction = function(url) {
            $.get(url)
                .done(function(res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function() {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        };

        // Tombol ekspor
        $('#exportExcel').on('click', function(e) {
            e.preventDefault();
            let status = $('#status_verifikasi').val();
            let url = '{{ route('pendaftaran.export-excel') }}' + (status ? '?status_verifikasi=' +
                status : '');
            window.location.href = url;
        });

        $('#exportPdf').on('click', function(e) {
            e.preventDefault();
            let status = $('#status_verifikasi').val();
            let url = '{{ route('pendaftaran.export-pdf') }}' + (status ? '?status_verifikasi=' +
                status : '');
            window.location.href = url;
        });
    });
</script>

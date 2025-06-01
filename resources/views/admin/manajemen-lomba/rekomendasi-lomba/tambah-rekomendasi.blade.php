<form id="form-notifikasi" method="POST" action="{{ route('rekomendasi-lomba.notifikasi') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">Buat Rekomendasi Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group custom-validation">
            <label>Nama Lomba <span class="text-danger">*</span></label>
            <select name="lomba_id" id="select-lomba" class="form-control select2" required>
                <option value="">-- Pilih Lomba --</option>
                @foreach ($lomba as $l)
                    <option value="{{ $l->lomba_id }}">{{ $l->nama_lomba }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group d-none" id="deskripsi-lomba">
            <label>Deskripsi</label>
            <textarea name="deskripsi_lomba" class="form-control" rows="2" readonly></textarea>
        </div>
        <div class="form-group d-none" id="kategori-lomba">
            <label>Kategori</label>
            <input type="text" name="kategori_lomba" class="form-control" readonly>
        </div>
        <div class="form-group d-none" id="tingkat-lomba">
            <label>Tingkat</label>
            <input type="text" name="tingkat_lomba" class="form-control" readonly>
        </div>
        <div class="form-group d-none" id="penyelenggara-lomba">
            <label>Penyelenggara</label>
            <input type="text" name="penyelenggara_lomba" class="form-control" readonly>
        </div>
        <div class="form-group d-none" id="periode-registrasi">
            <label>Periode Registrasi</label>
            <input type="text" name="periode_registrasi" class="form-control" readonly>
        </div>
        <div class="form-group d-none" id="link-pendaftaran-lomba">
            <label>Link Pendaftaran Lomba</label>
            <input type="text" name="link_pendaftaran_lomba" class="form-control" readonly>
        </div>
        <div class="form-group custom-validation">
            <label>Nama Peserta <span class="text-danger">*</span></label>
            <select name="user_id" class="form-control select2" required>
                <option value="">-- Pilih Peserta --</option>
                @foreach ($daftarMahasiswa as $m)
                    <option value="{{ $m->user_id }}">{{ $m->nim_mahasiswa }} - {{ $m->nama_mahasiswa }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Alasan Rekomendasi</label>
            <textarea name="pesan_notifikasi" class="form-control" rows="3"
                placeholder="Anda direkomendasikan oleh Admin '{{ Auth::user()->getName() }}' untuk mengikuti lomba ini. Silakan periksa informasi lomba lebih lanjut jika berminat."></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Submit
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Tutup
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
</style>

{{-- Memanggil Select2 single select --}}
<script>
    $(document).ready(function() {
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).parent().parent()
            });
        });

        // Sembunyikan semua field info lomba saat pertama kali load
        $('.form-group[id$="-lomba"]').addClass('d-none');
        // Ketika select lomba berubah
        $('#select-lomba').on('change', function() {
            const lombaId = $(this).val();
            if (!lombaId) {
                // Sembunyikan semua field info lomba jika tidak ada lomba yang dipilih
                $('.form-group[id*="-lomba"]').addClass('d-none');
                return;
            }

            const url = `{{ url('api/data-lomba') }}/${lombaId}`;
            $.get(url)
                .done(function(response) {
                    if (response.success) {
                        const data = response.data;
                        console.log('Data diterima:', data);

                        // Tampilkan semua field info lomba
                        $('.form-group[id$="-lomba"]').removeClass('d-none');

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


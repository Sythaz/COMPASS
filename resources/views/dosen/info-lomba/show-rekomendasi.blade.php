<form id="form-notifikasi" method="POST" action="{{ route('info-lomba.rekomendasi-lomba.notifikasi') }}">
    @csrf
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">Detail Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Nama Lomba: </th>
                <td class="text-start">
                    <input type="hidden" name="lomba_id" value="{{ $lomba->lomba_id }}" required>
                    {{ $lomba->nama_lomba }}
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Deskripsi: </th>
                <td class="text-start">{{ $lomba->deskripsi_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Kategori: </th>
                <td class="text-start">{{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui' }}
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Tingkat: </th>
                <td class="text-start">{{ $lomba->tingkat_lomba->nama_tingkat }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Penyelenggara: </th>
                <td class="text-start">{{ $lomba->penyelenggara_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Periode Registrasi: </th>
                <td class="text-start">
                    {{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') }} &nbsp;-&nbsp;
                    {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y') }}
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Link Pendaftaran Lomba: </th>
                <td class="text-start">
                    <a class="alert-primary" href="{{ $lomba->link_pendaftaran_lomba }}"
                        target="_blank">{{ $lomba->link_pendaftaran_lomba }}</a>
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Nama Peserta: <span class="text-danger">*</span></th>
                <td class="text-start">
                    <div class="custom-validation">
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
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Alasan Rekomendasi: </th>
                <td class="text-start">
                    <textarea name="pesan_notifikasi" id="pesan_notifikasi" class="form-control" rows="3"
                        placeholder="Anda direkomendasikan oleh {{ Auth::user()->getRole() }} '{{ Auth::user()->getName() }}' untuk mengikuti lomba '{{ $lomba->nama_lomba }}'. Silakan periksa informasi lomba lebih lanjut jika berminat."></textarea>
                </td>
            </tr>
        </table>
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

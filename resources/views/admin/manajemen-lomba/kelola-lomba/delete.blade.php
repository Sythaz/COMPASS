<form id="form-delete" method="POST" action="{{ url('admin/manajemen-lomba/kelola-lomba/' . $kelolaLomba->lomba_id) }}">
    @csrf
    @method('DELETE')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"><i class="fas fa-trash-alt mr-2"></i>Hapus Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk menghapus data ini?</strong>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Nama Lomba: </th>
                <td class="text-start">{{ $kelolaLomba->nama_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Deskripsi: </th>
                <td class="text-start">{{ $kelolaLomba->deskripsi_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Kategori: </th>
                <td class="text-start">{{ $kelolaLomba->kategori->nama_kategori }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Tingkat: </th>
                <td class="text-start">{{ $kelolaLomba->tingkat_lomba->nama_tingkat }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Penyelenggara: </th>
                <td class="text-start">{{ $kelolaLomba->penyelenggara_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Awal Registrasi: </th>
                <td class="text-start">{{ $kelolaLomba->awal_registrasi_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Akhir Registrasi: </th>
                <td class="text-start">{{ $kelolaLomba->akhir_registrasi_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Link Pendaftaran: </th>
                <td class="text-start">
                    <a class="alert-primary" href="{{ $kelolaLomba->link_pendaftaran_lomba }}" target="_blank">
                        {{ $kelolaLomba->link_pendaftaran_lomba }}
                    </a>
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Status: </th>
                <td class="text-start">
                    <span
                        class="label
                    @if ($kelolaLomba->status_verifikasi == 'Terverifikasi') label-success
                    @elseif ($kelolaLomba->status_verifikasi == 'Menunggu')
                        label-warning
                    @else
                        label-danger @endif">
                        {{ $kelolaLomba->status_verifikasi }}
                    </span>
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Gambar Poster Lomba:</th>
                <td class="text-start">
                    <a href="{{ asset('storage/img/lomba/' . $kelolaLomba->img_lomba) }}" data-lightbox="lomba"
                        data-title="Gambar Lomba">
                        <img class="img-thumbnail" src="{{ asset('storage/img/lomba/' . $kelolaLomba->img_lomba) }}"
                            width="100" alt="Gambar Lomba" style="cursor: zoom-in;">
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Hapus</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-delete'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-delete', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: 'DELETE',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        // Tutup modal
                        $('#myModal').modal('hide');

                        // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                        $('#tabel-kelola-lomba').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Hapus gagal.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    });
</script>

{{-- Style untuk Lightbox untuk membesarkan gambar --}}
<style>
    .lightbox .lb-data {
        top: 0;
        bottom: auto;
        background: rgba(0, 0, 0, 0.7);
    }

    .lightbox .lb-data .lb-caption {
        color: #fff;
        padding: 10px;
        font-size: 16px;
        text-align: center;
    }

    .lightbox .lb-close {
        top: 10px;
        right: 10px;
    }
</style>

{{-- Library Lightbox untuk membesarkan gambar --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<form id="form-delete" method="POST"
      action="{{ route('mahasiswa.prestasi.destroy', $prestasi->prestasi_id) }}">
    @csrf
    @method('DELETE')

    <div class="modal-header bg-danger rounded">
        <h5 class="modal-title text-white">
            <i class="fas fa-trash-alt mr-2"></i>Hapus Prestasi
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong>Apakah Anda yakin akan menghapus data prestasi berikut?</strong>
        </div>

        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Lomba</th>
                <td>{{ $prestasi->lomba->nama_lomba ?? '-' }}</td>
            </tr>
            <tr>
                <th>Dosen</th>
                <td>{{ $prestasi->dosen->nama_dosen ?? '-' }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $prestasi->kategori->nama_kategori ?? '-' }}</td>
            </tr>
            <tr>
                <th>Periode</th>
                <td>{{ $prestasi->periode->nama_periode ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Prestasi</th>
                <td>{{ $prestasi->tanggal_prestasi }}</td>
            </tr>
            <tr>
                <th>Juara</th>
                <td>{{ $prestasi->juara_prestasi }}</td>
            </tr>
            <tr>
                <th>Jenis</th>
                <td>{{ $prestasi->jenis_prestasi }}</td>
            </tr>
            <tr>
                <th>Foto Kegiatan</th>
                <td>
                    @if($prestasi->img_kegiatan && file_exists(public_path('storage/img/prestasi/'.$prestasi->img_kegiatan)))
                        <a href="{{ asset('storage/img/prestasi/'.$prestasi->img_kegiatan) }}"
                           data-lightbox="prestasi" data-title="Foto Kegiatan">
                            <img src="{{ asset('storage/img/prestasi/'.$prestasi->img_kegiatan) }}"
                                 width="100" class="img-thumbnail" alt="Foto Kegiatan">
                        </a>
                    @else
                        <span class="text-muted">Tidak ada foto</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Bukti Prestasi (PDF)</th>
                <td>
                    @if($prestasi->bukti_prestasi && file_exists(public_path('storage/pdf/prestasi/'.$prestasi->bukti_prestasi)))
                        <a href="{{ asset('storage/pdf/prestasi/'.$prestasi->bukti_prestasi) }}"
                           target="_blank">
                           {{ $prestasi->bukti_prestasi }}
                        </a>
                    @else
                        <span class="text-muted">Tidak ada bukti</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Surat Tugas (PDF)</th>
                <td>
                    @if($prestasi->surat_tugas_prestasi && file_exists(public_path('storage/pdf/prestasi/'.$prestasi->surat_tugas_prestasi)))
                        <a href="{{ asset('storage/pdf/prestasi/'.$prestasi->surat_tugas_prestasi) }}"
                           target="_blank">
                           {{ $prestasi->surat_tugas_prestasi }}
                        </a>
                    @else
                        <span class="text-muted">Tidak ada surat tugas</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt mr-2"></i>Hapus
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>Batal
        </button>
    </div>
</form>

{{-- AJAX delete --}}
<script>
    $(document).off('submit', '#form-delete');
    $(document).on('submit', '#form-delete', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'DELETE',
            data: form.serialize(),
            success: function(res) {
                if (res.success) {
                    Swal.fire('Terhapus!', res.message, 'success')
                        .then(() => {
                            $('#myModal').modal('hide');
                            $('#tabel-prestasi').DataTable().ajax.reload(null, false);
                        });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus.', 'error');
            }
        });
    });
</script>

{{-- lightbox2 --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css"
      rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

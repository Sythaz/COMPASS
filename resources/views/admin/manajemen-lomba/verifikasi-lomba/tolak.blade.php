<form id="form-tolak-verifikasi" method="POST"
    action="{{ url('admin/manajemen-lomba/verifikasi-lomba/tolak/' . $lomba->lomba_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"></i>Tolak Verifikasi Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk menolak verifikasi data lomba ini?</strong>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Nama Lomba: </th>
                <td class="text-start">{{ $lomba->nama_lomba }}</td>
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
                <th style="width: 30%">Awal Registrasi: </th>
                <td class="text-start">{{ $lomba->awal_registrasi_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Akhir Registrasi: </th>
                <td class="text-start">{{ $lomba->akhir_registrasi_lomba }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Link Pendaftaran Lomba: </th>
                <td class="text-start">
                    <a class="alert-primary" href="{{ $lomba->link_pendaftaran_lomba }}"
                        target="_blank">{{ $lomba->link_pendaftaran_lomba }}</a>
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Status: </th>
                <td class="text-start">
                    <span>
                        @switch($lomba->status_verifikasi)
                            @case('Menunggu')
                                {{-- Menunggu --}}
                                <span class="label label-warning">{{ $lomba->status_verifikasi }}</span>
                            @break

                            @case('Valid')
                                {{-- Valid --}}
                                <span class="label label-info">{{ $lomba->status_verifikasi }}</span>
                            @break

                            @default
                                {{-- Selain Menunggu --}}
                                <span class="label label-danger">{{ $lomba->status_verifikasi }}</span>
                        @endswitch
                    </span>
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Gambar Poster Lomba:</th>
                <td class="text-start">
                    @if (!is_null($lomba->img_lomba) && file_exists(public_path('storage/img/lomba/' . $lomba->img_lomba)))
                        <a href="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" data-lightbox="lomba"
                            data-title="Gambar Poster Lomba">
                            <img src="{{ asset('storage/img/lomba/' . $lomba->img_lomba) }}" width="100"
                                class="d-block mx-auto img-thumbnail" alt="Gambar Poster Lomba"
                                style="cursor: zoom-in;" />
                        </a>
                    @else
                        <p class="text-center text-muted">Gambar tidak ada atau belum di upload</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-danger"><i class="fas fa-times-circle mr-2"></i>Tolak</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-tolak-verifikasi'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-tolak-verifikasi', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        // Tutup modal
                        $('#myModal').modal('hide');

                        // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                        $('#tabel-verifikasi-lomba').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', 'Verifikasi gagal.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat verifikasi data.', 'error');
            }
        });
    });
</script>

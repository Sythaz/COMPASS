<form id="form-delete" method="POST" action="{{ route('kelola-prestasi.destroy', $prestasi->prestasi_id) }}">
    @csrf
    @method('DELETE')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">Hapus Data Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h5">Apakah Anda yakin ingin menghapus data prestasi ini?</strong>
        </div>

        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Nama Lomba:</th>
                <td>{{ $prestasi->lomba->nama_lomba ?? ($prestasi->lomba_lainnya ?? 'Tidak tersedia') }}</td>
            </tr>
            <tr>
                <th>Juara:</th>
                <td>{{ $prestasi->juara_prestasi ?? 'Tidak tersedia' }}</td>
            </tr>
            <tr>
                <th>Dosen Pembimbing:</th>
                <td>{{ $prestasi->dosen->nama_dosen ?? 'Tidak tersedia' }}</td>
            </tr>
            <tr>
                <th>Kategori:</th>
                <td>{{ $prestasi->kategori->nama_kategori ?? 'Tidak tersedia' }}</td>
            </tr>
            <tr>
                <th>Tingkat Lomba:</th>
                <td>{{ $prestasi->tingkat_lomba->nama_tingkat ?? 'Tidak tersedia' }}</td>
            </tr>
            <tr>
                <th>Periode:</th>
                <td>{{ $prestasi->periode->semester_periode ?? 'Tidak tersedia' }}</td>
            </tr>
            <tr>
                <th>Tanggal Prestasi:</th>
                <td>{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Status Verifikasi:</th>
                <td>
                    @switch($prestasi->status_verifikasi)
                        @case('Menunggu')
                            <span class="badge badge-warning">{{ $prestasi->status_verifikasi }}</span>
                        @break

                        @case('Tolak')
                            <span class="badge badge-danger">{{ $prestasi->status_verifikasi }}</span>
                        @break

                        @case('Valid')
                            <span class="badge badge-success">{{ $prestasi->status_verifikasi }}</span>
                        @break

                        @default
                            <span class="badge badge-secondary">{{ $prestasi->status_verifikasi }}</span>
                    @endswitch
                </td>
            </tr>
            <tr>
                <th>Anggota Tim:</th>
                <td>
                    @if ($prestasi->mahasiswa && $prestasi->mahasiswa->count())
                        <ul>
                            @foreach ($prestasi->mahasiswa as $mhs)
                                <li>{{ $mhs->nama_mahasiswa }} ({{ $mhs->pivot->peran ?? '-' }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Tidak ada anggota tim</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Gambar Kegiatan:</th>
                <td>
                    @if (!empty($prestasi->img_kegiatan) && file_exists(public_path('storage/prestasi/img/' . $prestasi->img_kegiatan)))
                        <a href="{{ asset('storage/prestasi/img/' . $prestasi->img_kegiatan) }}"
                            data-lightbox="gambar-kegiatan" data-title="Gambar Kegiatan">
                            <img src="{{ asset('storage/prestasi/img/' . $prestasi->img_kegiatan) }}"
                                class="img-thumbnail" width="120" style="cursor: zoom-in;" alt="Gambar Kegiatan">
                        </a>
                    @else
                        <p class="text-muted">Belum ada gambar kegiatan</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Bukti Prestasi:</th>
                <td>
                    @if (
                        !empty($prestasi->bukti_prestasi) &&
                            file_exists(public_path('storage/prestasi/bukti/' . $prestasi->bukti_prestasi)))
                        <a href="{{ asset('storage/prestasi/bukti/' . $prestasi->bukti_prestasi) }}" target="_blank"
                            class="btn btn-sm btn-primary">
                            <i class="fa fa-file-alt mr-1"></i>Lihat Bukti
                        </a>
                    @else
                        <p class="text-muted">Belum ada bukti prestasi</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Surat Tugas:</th>
                <td>
                    @if (
                        !empty($prestasi->surat_tugas_prestasi) &&
                            file_exists(public_path('storage/prestasi/surat/' . $prestasi->surat_tugas_prestasi)))
                        <a href="{{ asset('storage/prestasi/surat/' . $prestasi->surat_tugas_prestasi) }}"
                            target="_blank" class="btn btn-sm btn-primary">
                            <i class="fa fa-file-alt mr-1"></i>Lihat Surat Tugas
                        </a>
                    @else
                        <p class="text-muted">Belum ada surat tugas</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash mr-2"></i> Hapus
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i> Batal
        </button>
    </div>
</form>

{{-- JS --}}
<script>
    $(document).off('submit', '#form-delete'); // reset jika sudah pernah bind
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
                        $('#myModal').modal('hide');
                        $('#prestasiTable').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Gagal', response.message || 'Hapus gagal.', 'error');
                }
            },
            error: function() {
                Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    });
</script>

{{-- Lightbox --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

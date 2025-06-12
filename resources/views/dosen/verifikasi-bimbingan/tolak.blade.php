<form id="form-tolak-verifikasi" method="POST" action="{{ route('dosen.tolakPrestasi', $prestasi->prestasi_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white"></i>Tolak Verifikasi Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk menolak verifikasi data Prestasi ini?</strong>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Nama Lomba:</th>
                <td class="text-start">
                    {{ $prestasi->lomba->nama_lomba ?? ($prestasi->lomba_lainnya ?? 'Tidak tersedia') }}</td>
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
                            <span class="label label-warning">{{ $prestasi->status_verifikasi }}</span>
                        @break

                        @case('Tolak')
                            <span class="label label-danger">{{ $prestasi->status_verifikasi }}</span>
                        @break

                        @case('Valid')
                            <span class="label label-success">{{ $prestasi->status_verifikasi }}</span>
                        @break

                        @default
                            <span class="label label-default">{{ $prestasi->status_verifikasi }}</span>
                    @endswitch
                </td>
            </tr>
            <tr>
                <th>Anggota Tim:</th>
                <td>
                    @if ($prestasi->mahasiswa && $prestasi->mahasiswa->count() > 0)
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
                    @if (!is_null($prestasi->img_kegiatan) && file_exists(public_path('storage/prestasi/img/' . $prestasi->img_kegiatan)))
                        <a href="{{ asset('storage/prestasi/img/' . $prestasi->img_kegiatan) }}"
                            data-lightbox="prestasi" data-title="Gambar Kegiatan">
                            <img src="{{ asset('storage/prestasi/img/' . $prestasi->img_kegiatan) }}" width="100"
                                class="d-block mx-auto img-thumbnail" style="cursor: zoom-in;" alt="Gambar Kegiatan" />
                        </a>
                    @else
                        <p class="text-center text-muted">Gambar belum diupload</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Bukti Prestasi:</th>
                <td>
                    @if (
                        !is_null($prestasi->bukti_prestasi) &&
                            file_exists(public_path('storage/prestasi/bukti/' . $prestasi->bukti_prestasi)))
                        <a class="btn btn-primary"
                            href="{{ asset('storage/prestasi/bukti/' . $prestasi->bukti_prestasi) }}" target="_blank">
                            <i class="fa fa-file-alt"></i> <span class="ml-1">Lihat Bukti</span>
                        </a>
                    @else
                        <p class="text-center text-muted">Belum ada bukti prestasi</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Surat Tugas:</th>
                <td>
                    @if (
                        !is_null($prestasi->surat_tugas_prestasi) &&
                            file_exists(public_path('storage/prestasi/surat/' . $prestasi->surat_tugas_prestasi)))
                        <a class="btn btn-primary"
                            href="{{ asset('storage/prestasi/surat/' . $prestasi->surat_tugas_prestasi) }}"
                            target="_blank">
                            <i class="fa fa-file-alt"></i> <span class="ml-1">Lihat Surat Tugas</span>
                        </a>
                    @else
                        <p class="text-center text-muted">Belum ada surat tugas prestasi</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th style="width: 30%">
                    <label for="alasan_tolak" class="col-form-label mt-2">Alasan Penolakan: <span
                            class="text-danger">*</span></label>
                </th>
                <td class="text-start">
                    <div class="custom-validation">
                        <textarea name="alasan_tolak" id="alasan_tolak" cols="30" rows="3" class="form-control" required></textarea>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-check-circle mr-2"></i> Tolak
        </button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i> Batal
        </button>
    </div>

</form>

<script>
    $(document).off('submit', '#form-tolak-verifikasi');
    $(document).on('submit', '#form-tolak-verifikasi', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();
        console.log(data);

        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        $('#myModal').modal('hide');
                        $('#prestasiTable').DataTable().ajax.reload(null,
                            false);
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

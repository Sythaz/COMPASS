<form id="form-terima-verifikasi" method="POST" action="{{ route('verifikasi-prestasi.terimaPrestasi', $prestasi->prestasi_id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header bg-primary rounded">
        <h5 class="modal-title text-white">Terima Verifikasi Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-success">
            <i class="fas fa-check-circle fa-lg mr-2"></i>
            <strong class="alert-heading h4">Apakah anda yakin untuk verifikasi data prestasi ini?</strong>
        </div>

        @php
            // Ambil mahasiswa dengan peran Ketua
            $ketua = $prestasi->mahasiswa->firstWhere('pivot.peran', 'Ketua');
        @endphp

        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Nama Lomba:</th>
                <td class="text-start"> {{ $prestasi->lomba?->nama_lomba ?? $prestasi->lomba_lainnya ?? 'Tidak tersedia' }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Tipe Lomba</th>
                <td class="text-start">{{ $prestasi->jenis_prestasi }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Kategori:</th>
                <td class="text-start">{{ $prestasi->kategori->nama_kategori }}</td>
            </tr>
             <tr>
                <th style="width: 30%">Juara Prestasi:</th>
                <td class="text-start">{{ $prestasi->juara_prestasi }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Dosen Pembimbing:</th>
                <td class="text-start"> {{ $prestasi->dosen?->nama_dosen ?? 'Tidak ada' }}</td>
            </tr>
           <tr>
           <th style="width: 30%">Anggota Tim:</th>
               <td class="text-start">
                   <ul>
                      @foreach ($prestasi->mahasiswa as $mhs)
                   <li>{{ $mhs->nim_mahasiswa }} - {{ $mhs->nama_mahasiswa }} ({{ $mhs->pivot->peran }})</li>
                      @endforeach
                   </ul>
               </td>
           </tr>
            <tr>
                <th style="width: 30%">Periode:</th>
                <td class="text-start">{{ $prestasi->periode->semester_periode }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Tanggal Prestasi:</th>
                <td class="text-start">{{ $prestasi->tanggal_prestasi }}</td>
            </tr>
            <tr>
                <th style="width: 30%">Gambar Kegiatan:</th>
                <td class="text-start">
                    @if (!is_null($prestasi->img_kegiatan) && file_exists(public_path('storage/prestasi/img/' . $prestasi->img_kegiatan)))
                        <a href="{{ asset('storage/prestasi/img/' . $prestasi->img_kegiatan) }}" data-lightbox="prestasi" data-title="Gambar Kegiatan">
                            <img src="{{ asset('storage/prestasi/img/' . $prestasi->img_kegiatan) }}" width="100" class="d-block mx-auto img-thumbnail" alt="Gambar Kegiatan" style="cursor: zoom-in;" />
                        </a>
                    @else
                        <p class="text-center text-muted">Gambar tidak ada atau belum di upload</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Bukti Prestasi:</th>
                <td class="text-start">
                    @if (!is_null($prestasi->bukti_prestasi) && file_exists(public_path('storage/prestasi/bukti/' . $prestasi->bukti_prestasi)))
                        <a class="btn btn-primary" href="{{ asset('storage/prestasi/bukti/' . $prestasi->bukti_prestasi) }}" target="_blank">
                            <i class="fa fa-file-alt"></i> <span class="ml-1">Lihat Bukti</span>
                        </a>
                    @else
                        <p class="text-center text-muted">Bukti tidak ada atau belum di upload</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Surat Tugas:</th>
                <td class="text-start">
                    @if (!is_null($prestasi->surat_tugas_prestasi) && file_exists(public_path('storage/prestasi/surat/' . $prestasi->surat_tugas_prestasi)))
                        <a class="btn btn-primary" href="{{ asset('storage/prestasi/surat/' . $prestasi->surat_tugas_prestasi) }}" target="_blank">
                            <i class="fa fa-file-alt"></i> <span class="ml-1">Lihat Surat Tugas</span>
                        </a>
                    @else
                        <p class="text-center text-muted">Surat Tugas tidak ada atau belum di upload</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th style="width: 30%">Status:</th>
                <td class="text-start">
                    @switch($prestasi->status_verifikasi)
                        @case('Menunggu')
                            <span class="label label-warning">{{ $prestasi->status_verifikasi }}</span>
                            @break
                        @default
                            <span class="label label-danger">{{ $prestasi->status_verifikasi }}</span>
                    @endswitch
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success"><i class="fas fa-check-circle mr-2"></i>Terima</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Batal</button>
    </div>
</form>

<script>
    $(document).off('submit', '#form-terima-verifikasi'); // Hapus event handler lama (jika ada)
    $(document).on('submit', '#form-terima-verifikasi', function(e) {
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
                        $('#myModal').modal('hide');
                        $('#prestasiTable').DataTable().ajax.reload(null, false);
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

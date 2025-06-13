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
        <!-- Informasi Prestasi -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-trophy mr-2"></i>Informasi Prestasi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Nama Lomba</label>
                            <input type="text" class="form-control"
                                value="@if ($prestasi->lomba_id && $prestasi->lomba) {{ $prestasi->lomba->nama_lomba }}@elseif($prestasi->lomba_lainnya){{ $prestasi->lomba_lainnya }}@else Lomba tidak tersedia @endif"
                                disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Juara</label>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    value="{{ $prestasi->juara_prestasi ?? 'Tidak tersedia' }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-warning text-white">
                                        <i class="fas fa-medal"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Kategori</label>
                            <input type="text" class="form-control"
                                value="{{ $prestasi->kategori->nama_kategori ?? 'Tidak tersedia' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Tingkat Lomba</label>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    value="@if ($prestasi->lomba_id && $prestasi->lomba && $prestasi->lomba->tingkat_lomba) {{ $prestasi->lomba->tingkat_lomba->nama_tingkat }}@elseif($prestasi->tingkat_lomba){{ $prestasi->tingkat_lomba->nama_tingkat }}@else Tidak tersedia @endif"
                                    disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-layer-group"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Dosen Pembimbing</label>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    value="{{ $prestasi->dosen->nama_dosen ?? 'Tidak tersedia' }}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-success text-white">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Tanggal Prestasi</label>
                            <input type="text" class="form-control"
                                value="@if ($prestasi->tanggal_prestasi) {{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d M Y') }}@else Tidak tersedia @endif"
                                disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Status Verifikasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    value="{{ ucfirst($prestasi->status_verifikasi ?? '') }}" disabled>
                                <div class="input-group-append">
                                    @php
                                        $status = $prestasi->status_verifikasi ?? '';
                                        $statusClass = '';
                                        $statusIcon = '';

                                        switch ($status) {
                                            case 'Terverifikasi':
                                                $statusClass = 'bg-success';
                                                $statusIcon = 'fas fa-check';
                                                break;
                                            case 'Ditolak':
                                                $statusClass = 'bg-danger';
                                                $statusIcon = 'fas fa-times';
                                                break;
                                            case 'Valid':
                                                $statusClass = 'bg-primary';
                                                $statusIcon = 'fas fa-check-circle';
                                                break;
                                            case 'Menunggu':
                                            default:
                                                $statusClass = 'bg-warning';
                                                $statusIcon = 'fas fa-clock';
                                                break;
                                        }
                                    @endphp
                                    <span class="input-group-text {{ $statusClass }} text-white">
                                        <i class="{{ $statusIcon }}"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label font-weight-bold">Periode</label>
                            <input type="text" class="form-control"
                                value="{{ $prestasi->periode->semester_periode ?? 'Tidak tersedia' }}" disabled>
                        </div>
                    </div>
                </div>

                {{-- Menampilkan Alasan jika prestasi ditolak  --}}
                @if ($prestasi->status_verifikasi === 'Ditolak')
                    <div class="mt-3">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Prestasi Ditolak</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="col-form-label font-weight-bold text-danger">Alasan Penolakan</label>
                                    <textarea class="form-control border-danger text-danger" rows="3" disabled>{{ $prestasi->alasan_tolak }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informasi Tim -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-users mr-2"></i>Anggota Tim
                    @if ($prestasi->mahasiswa && $prestasi->mahasiswa->count() > 0)
                        <span class="badge badge-primary ml-2">
                            {{ $prestasi->mahasiswa->count() }} Anggota
                        </span>
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if ($prestasi->mahasiswa && $prestasi->mahasiswa->count() > 0)
                    @foreach ($prestasi->mahasiswa as $index => $mhs)
                        <div class="anggota-item bg-light rounded p-3 mb-2">
                            <div class="form-group mb-0">
                                <label class="col-form-label font-weight-bold">
                                    @if (($mhs->pivot->peran ?? 'Anggota') === 'Ketua')
                                        <i
                                            class="fas fa-crown mr-2 text-warning"></i>{{ $mhs->pivot->peran ?? 'Anggota' }}
                                    @else
                                        <i class="fas fa-user mr-2"></i>{{ $mhs->pivot->peran ?? 'Anggota' }}
                                        {{ $index + 1 }}
                                    @endif
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $mhs->nama_mahasiswa }}"
                                        disabled>
                                    <div class="input-group-append">
                                        @if (($mhs->pivot->peran ?? 'Anggota') === 'Ketua')
                                            <span class="input-group-text bg-warning text-dark">
                                                <i class="fas fa-crown"></i>
                                            </span>
                                        @else
                                            <span class="input-group-text bg-info text-white">
                                                <i class="fas fa-user-check"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Total Anggota -->
                    <div class="total-anggota mt-3 p-2 bg-light rounded">
                        <div class="row">
                            <div class="col-6">
                                <strong><i class="fas fa-calculator mr-2"></i>Total Anggota:</strong>
                            </div>
                            <div class="col-6 text-right">
                                <span class="badge badge-primary badge-lg">
                                    {{ $prestasi->mahasiswa->count() }} Orang
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-users-slash fa-3x mb-2"></i>
                        <p>Data anggota tidak tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dokumentasi dan Berkas -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-folder-open mr-2"></i>Dokumentasi & Berkas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Gambar Kegiatan -->
                    <div class="col-md-4 mb-3">
                        <div class="file-section">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-image mr-2"></i>Gambar Kegiatan
                            </h6>
                            @if ($prestasi->img_kegiatan && Storage::disk('public')->exists('img/prestasi/' . $prestasi->img_kegiatan))
                                <div class="text-center">
                                    <div class="file-preview mb-2">
                                        <a href="{{ Storage::url('img/prestasi/' . $prestasi->img_kegiatan) }}"
                                            data-lightbox="prestasi" data-title="Gambar Kegiatan">
                                            <img src="{{ Storage::url('img/prestasi/' . $prestasi->img_kegiatan) }}"
                                                width="100" class="img-thumbnail" style="cursor: zoom-in;"
                                                alt="Gambar Kegiatan" />
                                        </a>
                                    </div>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle mr-1"></i>File tersedia
                                    </small>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <p class="small mb-0">Gambar belum diupload</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bukti Prestasi -->
                    <div class="col-md-4 mb-3">
                        <div class="file-section">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-certificate mr-2"></i>Bukti Prestasi
                            </h6>
                            @if ($prestasi->bukti_prestasi && Storage::disk('public')->exists('img/prestasi/' . $prestasi->bukti_prestasi))
                                <div class="text-center">
                                    <div class="file-preview mb-2">
                                        <i class="fas fa-file-alt fa-3x text-primary mb-2"></i>
                                        <p class="small mb-2">File berhasil diunggah</p>
                                    </div>
                                    <a href="{{ Storage::url('img/prestasi/' . $prestasi->bukti_prestasi) }}"
                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-file-times fa-2x mb-2"></i>
                                    <p class="small mb-0">Belum ada bukti prestasi</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Surat Tugas -->
                    <div class="col-md-4 mb-3">
                        <div class="file-section">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-envelope mr-2"></i>Surat Tugas
                            </h6>
                            @if (
                                $prestasi->surat_tugas_prestasi &&
                                    Storage::disk('public')->exists('img/prestasi/' . $prestasi->surat_tugas_prestasi))
                                <div class="text-center">
                                    <div class="file-preview mb-2">
                                        <i class="fas fa-file-contract fa-3x text-success mb-2"></i>
                                        <p class="small mb-2">File berhasil diunggah</p>
                                    </div>
                                    <a href="{{ Storage::url('img/prestasi/' . $prestasi->surat_tugas_prestasi) }}"
                                        target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download mr-1"></i> Lihat
                                    </a>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-file-times fa-2x mb-2"></i>
                                    <p class="small mb-0">Belum ada surat tugas</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Footer --}}
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

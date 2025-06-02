{{-- Modal Detail Prestasi --}}
<div class="modal fade" id="modalDetailPrestasi" tabindex="-1" role="dialog" aria-labelledby="modalDetailPrestasiLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary rounded">
                <h5 class="modal-title text-white" id="modalDetailPrestasiLabel">
                    <i class="fas fa-trophy mr-2"></i>Detail Prestasi
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{-- Nama Lomba --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Nama Lomba</label>
                    <input type="text" class="form-control"
                        value="{{ $prestasi->lomba_id === 'lainnya' ? $prestasi->lomba_lainnya : ($prestasi->lomba->nama_lomba ?? '-') }}"
                        readonly>
                </div>

                {{-- Dosen Pembimbing --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Dosen Pembimbing</label>
                    <input type="text" class="form-control"
                        value="{{ $prestasi->dosen ? $prestasi->dosen->nama_dosen : '-' }}" readonly>
                </div>

                {{-- Tingkat Lomba --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Tingkat Lomba</label>
                    <input type="text" class="form-control" value="{{ $prestasi->tingkatLomba->nama_tingkat ?? '-' }}"
                        readonly>
                </div>

                {{-- Kategori Lomba --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Kategori Lomba</label>
                    <input type="text" class="form-control"
                        value="{{ $prestasi->kategori->pluck('nama_kategori')->implode(', ') ?? '-' }}" readonly>
                </div>

                {{-- Tipe Prestasi --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Tipe Prestasi</label>
                    <input type="text" class="form-control" value="{{ ucfirst($prestasi->jenis_prestasi) ?? '-' }}"
                        readonly>
                </div>

                {{-- Jumlah Anggota --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Jumlah Anggota</label>
                    <input type="number" class="form-control" value="{{ $prestasi->anggota->count() ?? 0 }}" readonly>
                </div>

                {{-- Daftar Anggota --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Anggota Tim</label>
                    @if($prestasi->anggota->count() > 0)
                        <ul class="list-group">
                            @foreach($prestasi->anggota as $anggota)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $anggota->nama_mahasiswa }}
                                    <span
                                        class="badge badge-primary badge-pill">{{ $anggota->pivot->peran ?? 'Anggota' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Tidak ada anggota</p>
                    @endif
                </div>

                {{-- Tanggal Prestasi --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Tanggal Prestasi</label>
                    <input type="date" class="form-control"
                        value="{{ optional($prestasi->tanggal_prestasi)->format('Y-m-d') }}" readonly>
                </div>

                {{-- Juara Prestasi --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Juara Prestasi</label>
                    <input type="text" class="form-control" value="{{ $prestasi->juara_prestasi ?? '-' }}" readonly>
                </div>

                {{-- Periode --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Periode</label>
                    <input type="text" class="form-control" value="{{ $prestasi->periode->semester_periode ?? '-' }}"
                        readonly>
                </div>

                {{-- Gambar Kegiatan --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Gambar Kegiatan</label>
                    @if($prestasi->img_kegiatan)
                        <img src="{{ asset('storage/' . $prestasi->img_kegiatan) }}" alt="Gambar Kegiatan"
                            class="img-fluid rounded">
                    @else
                        <p class="text-muted">Tidak ada gambar</p>
                    @endif
                </div>

                {{-- Bukti Prestasi --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Bukti Prestasi</label>
                    @if($prestasi->bukti_prestasi)
                        <a href="{{ asset('storage/' . $prestasi->bukti_prestasi) }}" target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-download mr-1"></i> Lihat Bukti
                        </a>
                    @else
                        <p class="text-muted">Tidak ada bukti</p>
                    @endif
                </div>

                {{-- Surat Tugas Prestasi --}}
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Surat Tugas Prestasi</label>
                    @if($prestasi->surat_tugas_prestasi)
                        <a href="{{ asset('storage/' . $prestasi->surat_tugas_prestasi) }}" target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-download mr-1"></i> Lihat Surat Tugas
                        </a>
                    @else
                        <p class="text-muted">Tidak ada surat tugas</p>
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Tutup
                </button>
            </div>

        </div>
    </div>
</div>
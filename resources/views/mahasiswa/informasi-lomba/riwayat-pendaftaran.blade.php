<div class="modal-header bg-info rounded">
    <h5 class="modal-title text-white"><i class="fas fa-eye mr-2"></i>Detail Pendaftaran Lomba</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="col-form-label">Nama Lomba</label>
        <input type="text" class="form-control" value="{{ $pendaftaran->lomba->nama_lomba ?? '-' }}" disabled>
    </div>

    <div class="form-group">
        <label class="col-form-label">Tipe Lomba</label>
        <input type="text" class="form-control" value="{{ ucfirst($pendaftaran->lomba->tipe_lomba ?? '-') }}" disabled>
    </div>

    <div class="form-group">
        <label class="col-form-label">Jumlah Anggota</label>
        @if(($pendaftaran->lomba->tipe_lomba ?? '') === 'tim')
            <input type="text" class="form-control" value="{{ $pendaftaran->anggota->count() }}" disabled>
        @else
            <input type="text" class="form-control" value="1" disabled>
        @endif
    </div>

    <div class="form-group">
        <label class="col-form-label">Ketua Tim</label>
        <input type="text" class="form-control" value="{{ $pendaftaran->mahasiswa->nama_mahasiswa ?? '-' }}" disabled>
    </div>

    @if(($pendaftaran->lomba->tipe_lomba ?? '') === 'tim')
        @php
            $anggotaTim = $pendaftaran->anggota->filter(function ($mhs) use ($pendaftaran) {
                return $mhs->mahasiswa_id !== $pendaftaran->mahasiswa_id;
            });
        @endphp

        @foreach ($anggotaTim as $index => $anggota)
            <div class="form-group">
                <label class="col-form-label">Anggota {{ $index + 1 }}</label>
                <input type="text" class="form-control" value="{{ $anggota->nama_mahasiswa ?? '-' }}" disabled>
            </div>
        @endforeach
    @endif

    <div class="form-group">
        <label class="col-form-label">Bukti Pendaftaran</label>
        @if ($pendaftaran->bukti_pendaftaran)
            <div>
                <a href="{{ asset('storage/' . $pendaftaran->bukti_pendaftaran) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm mt-1">
                    <i class="fa fa-image mr-1"></i>Lihat Bukti
                </a>
            </div>
        @else
            <p class="text-muted">Tidak ada file diunggah</p>
        @endif
    </div>

    <div class="form-group">
        <label class="col-form-label">Tanggal Daftar</label>
        <input type="text" class="form-control"
            value="{{ $pendaftaran->created_at ? $pendaftaran->created_at->translatedFormat('d F Y H:i') : '-' }}"
            disabled>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
        <i class="fa-solid fa-xmark mr-2"></i>Tutup
    </button>
</div>
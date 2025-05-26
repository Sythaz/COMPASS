<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Lomba</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">Nama Lomba</th>
            <td class="text-start">{{ $lomba->nama_lomba }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td class="text-start">
                {{ $lomba->kategori->kategori_nama ?? '-' }}
            </td>
        </tr>
        <tr>
            <th>Tingkat</th>
            <td class="text-start">
                {{ $lomba->tingkat->nama_tingkat ?? '-' }}
            </td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td class="text-start">{{ $lomba->deskripsi_lomba ?? '-' }}</td>
        </tr>
        <tr>
            <th>Penyelenggara</th>
            <td class="text-start">{{ $lomba->penyelenggara_lomba }}</td>
        </tr>
        <tr>
            <th>Periode Registrasi</th>
            <td class="text-start">
                {{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('Y-m-d') }}
                &nbsp;—&nbsp;
                {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('Y-m-d') }}
            </td>
        </tr>
        <tr>
            <th>Link Pendaftaran</th>
            <td class="text-start">
                @if($lomba->link_pendaftaran_lomba)
                    <a href="{{ $lomba->link_pendaftaran_lomba }}" target="_blank">
                        {{ $lomba->link_pendaftaran_lomba }}
                    </a>
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <th>Status Verifikasi</th>
            <td class="text-start">{{ $lomba->status_verifikasi }}</td>
        </tr>
        <tr>
            <th>Status Lomba</th>
            <td class="text-start">{{ $lomba->status_lomba }}</td>
        </tr>
        @if($lomba->img_lomba)
        <tr>
            <th>Poster</th>
            <td class="text-center">
                <img src="{{ asset('storage/lomba/' . $lomba->img_lomba) }}"
                     alt="Poster {{ $lomba->nama_lomba }}"
                     class="img-fluid"
                     style="max-height: 200px;">
            </td>
        </tr>
        @endif
    </table>
</div>

<!-- Modal Footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>

<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Prestasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">Nama Lomba:</th>
            <td class="text-start">
                {{ $prestasi->lomba ? $prestasi->lomba->nama_lomba : $prestasi->lomba_lainnya ?? 'Tidak tersedia' }}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Juara:</th>
            <td class="text-start">{{ $prestasi->juara_prestasi }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Dosen Pembimbing:</th>
            <td class="text-start">{{ $prestasi->dosen->nama_dosen ?? 'Tidak tersedia' }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Kategori:</th>
            <td class="text-start">{{ $prestasi->kategori->nama_kategori ?? 'Tidak tersedia' }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Tingkat Lomba:</th>
            <td class="text-start">
                {{ $prestasi->lomba_id ? $prestasi->lomba->tingkat_lomba->nama_tingkat : $prestasi->tingkat_lomba->nama_tingkat }}
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Periode:</th>
            <td class="text-start">{{ $prestasi->periode->semester_periode ?? 'Tidak tersedia' }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Tanggal Prestasi:</th>
            <td class="text-start">{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d M Y') }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Status Verifikasi:</th>
            {{-- Style status_verifikasi ada di controller PrestasiController --}}
            <td class="text-start">{!! $statusBadge !!}</td>
        </tr>
        <tr>
            <th style="width: 30%">Anggota Tim:</th>
            <td class="text-start">
                <ul>
                    @foreach ($prestasi->mahasiswa as $mhs)
                        <li>{{ $mhs->nama_mahasiswa }} ({{ $mhs->pivot->peran }})</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Gambar Kegiatan:</th>
            <td class="text-start">
                @if($prestasi->img_kegiatan && file_exists(public_path('storage/img/prestasi/' . $prestasi->img_kegiatan)))
                    <a href="{{ asset('storage/img/prestasi/' . $prestasi->img_kegiatan) }}" data-lightbox="prestasi"
                        data-title="Gambar Kegiatan">
                        <img src="{{ asset('storage/img/prestasi/' . $prestasi->img_kegiatan) }}" width="100"
                            class="d-block mx-auto img-thumbnail" style="cursor: zoom-in;" alt="Gambar Kegiatan" />
                    </a>
                @else
                    <p class="text-center text-muted">Gambar belum diupload</p>
                @endif
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Bukti Prestasi:</th>
            <td class="text-start">
                @if($prestasi->bukti_prestasi && file_exists(public_path('storage/img/prestasi/' . $prestasi->bukti_prestasi)))
                    <a href="{{ asset('storage/img/prestasi/' . $prestasi->bukti_prestasi) }}" target="_blank">
                        Lihat Bukti
                    </a>
                @else
                    <span class="text-muted">Belum ada bukti prestasi</span>
                @endif
            </td>
        </tr>

        <tr>
            <th style="width: 30%">Surat Tugas Prestasi:</th>
            <td class="text-start">
                @if($prestasi->surat_tugas_prestasi && file_exists(public_path('storage/img/prestasi/' . $prestasi->surat_tugas_prestasi)))
                    <a href="{{ asset('storage/img/prestasi/' . $prestasi->surat_tugas_prestasi) }}" target="_blank">
                        Lihat Surat Tugas
                    </a>
                @else
                    <span class="text-muted">Belum ada surat tugas prestasi</span>
                @endif
            </td>
        </tr>

    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>

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

<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
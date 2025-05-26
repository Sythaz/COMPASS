<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Prestasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">NIM: </th>
            <td class="text-start">{{ $kelolaPrestasi->mahasiswa->nim_mahasiswa }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Mahasiswa: </th>
            <td class="text-start">{{ $kelolaPrestasi->mahasiswa->nama_mahasiswa }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Lomba: </th>
            <td class="text-start">{{ $kelolaPrestasi->lomba->nama_lomba }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Kategori: </th>
            <td class="text-start">{{ $kelolaPrestasi->kategori->nama_kategori }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Jenis Prestasi: </th>
            <td class="text-start">{{ $kelolaPrestasi->jenis_prestasi }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Dosen Pembimbing: </th>
            <td class="text-start">{{ $kelolaPrestasi->dosen->nama_dosen }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Periode: </th>
            <td class="text-start">{{ $kelolaPrestasi->periode->semester_periode }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Tanggal Prestasi: </th>
            <td class="text-start">{{ $kelolaPrestasi->tanggal_prestasi }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Juara Prestasi: </th>
            <td class="text-start">{{ $kelolaPrestasi->juara_prestasi }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Gambar Kegiatan:</th>
            <td class="text-start">
                @if (
                    !is_null($kelolaPrestasi->img_kegiatan) &&
                        file_exists(public_path('storage/prestasi/img/' . $kelolaPrestasi->img_kegiatan)))
                    <a href="{{ asset('storage/prestasi/img/' . $kelolaPrestasi->img_kegiatan) }}"
                        data-lightbox="prestasi" data-title="Gambar Kegiatan">
                        <img src="{{ asset('storage/prestasi/img/' . $kelolaPrestasi->img_kegiatan) }}" width="100"
                            class="d-block mx-auto img-thumbnail" alt="Gambar Kegiatan" style="cursor: zoom-in;" />
                    </a>
                @else
                    <p class="text-center text-muted">Gambar tidak ada atau belum di upload</p>
                @endif
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Bukti Prestasi: </th>
            <td class="text-start">
                @if (
                    !is_null($kelolaPrestasi->bukti_prestasi) &&
                        file_exists(public_path('storage/prestasi/bukti/' . $kelolaPrestasi->bukti_prestasi)))
                    <a class="btn btn-primary"
                        href="{{ asset('storage/prestasi/bukti/' . $kelolaPrestasi->bukti_prestasi) }}"
                        target="_blank">
                        <i class="fa fa-file-alt"></i>
                        <span class="ml-1">Lihat Bukti</span>
                    </a>
                @else
                    <p class="text-center text-muted">Bukti tidak ada atau belum di upload</p>
                @endif
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Surat Tugas: </th>
            <td class="text-start">
                @if (
                    !is_null($kelolaPrestasi->surat_tugas_prestasi) &&
                        file_exists(public_path('storage/prestasi/surat/' . $kelolaPrestasi->surat_tugas_prestasi)))
                    <a class="btn btn-primary"
                        href="{{ asset('storage/prestasi/surat/' . $kelolaPrestasi->surat_tugas_prestasi) }}"
                        target="_blank">
                        <i class="fa fa-file-alt"></i>
                        <span class="ml-1">Lihat Surat Tugas</span>
                    </a>
                @else
                    <p class="text-center text-muted">Surat Tugas tidak ada atau belum di upload</p>
                @endif
            </td>
        </tr>
        <tr>
            <th style="width: 30%">Status: </th>
            <td class="text-start">
                <span>
                    @switch($kelolaPrestasi->status_verifikasi)
                        @case('Terverifikasi')
                            {{-- Terverifikasi --}}
                            <span class="label label-success">{{ $kelolaPrestasi->status_verifikasi }}</span>
                        @break

                        @case('Valid')
                            {{-- Valid (diverifikasi admin) --}}
                            <span class="label label-info">{{ $kelolaPrestasi->status_verifikasi }}</span>
                        @break

                        @default
                            {{-- Ditolak --}}
                            <span class="label label-danger">{{ $kelolaPrestasi->status_verifikasi }}</span>
                    @endswitch
                </span>
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

{{-- Library Lightbox untuk membesarkan gambar --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

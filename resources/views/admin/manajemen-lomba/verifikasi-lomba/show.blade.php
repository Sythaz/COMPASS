<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Lomba</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">Nama Lomba: </th>
            <td class="text-start">{{ $lomba->nama_lomba }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Pengusul: </th>
            <td class="text-start">{{ $namaPengusul }}</td>
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
                            {{-- Terverifikasi --}}
                            <span class="label label-warning">{{ $lomba->status_verifikasi }}</span>
                        @break

                        @default
                            {{-- Ditolak --}}
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
                            class="d-block mx-auto img-thumbnail" alt="Gambar Poster Lomba" style="cursor: zoom-in;" />
                    </a>
                @else
                    <p class="text-center text-muted">Gambar tidak ada atau belum di upload</p>
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

{{-- Library Lightbox untuk membesarkan gambar --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

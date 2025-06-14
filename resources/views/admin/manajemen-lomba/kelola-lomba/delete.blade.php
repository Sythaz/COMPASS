@if (empty($kelolaLomba))
    <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Kesalahan</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <div class="d-flex align-items-center">
                    <i class="fas fa-ban fa-2x mr-3"></i>
                    <div>
                        <h5 class="mb-1">Data Tidak Ditemukan</h5>
                        <p class="mb-0">Maaf, data yang Anda cari tidak ada dalam database.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </button>
        </div>
    </div>
@else
    <form id="form-delete" method="POST"
        action="{{ url('admin/manajemen-lomba/kelola-lomba/' . $kelolaLomba->lomba_id) }}">
        @csrf
        @method('DELETE')
        <div class="modal-header bg-primary rounded">
            <h5 class="modal-title text-white"><i class="fas fa-trash-alt mr-2"></i>Hapus Lomba</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle fa-lg mr-2"></i>
                <strong class="alert-heading h4">Apakah anda yakin untuk menghapus data ini?</strong>
            </div>
            <!-- Informasi Dasar Lomba -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Dasar</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Nama Lomba</label>
                                <input type="text" class="form-control" value="{{ $kelolaLomba->nama_lomba }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Pengusul</label>
                                <input type="text" class="form-control" value="{{ $namaPengusul }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Kategori</label>
                                <input type="text" class="form-control"
                                    value="{{ $kelolaLomba->kategori->pluck('nama_kategori')->join(', ') ?: 'Tidak Diketahui' }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Tingkat</label>
                                <input type="text" class="form-control"
                                    value="{{ $kelolaLomba->tingkat_lomba->nama_tingkat }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Penyelenggara</label>
                                <input type="text" class="form-control"
                                    value="{{ $kelolaLomba->penyelenggara_lomba }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Status</label>
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                        value="{{ ucfirst($kelolaLomba->status_verifikasi ?? '') }}" disabled>
                                    <div class="input-group-append">
                                        @php
                                            $status = $kelolaLomba->status_verifikasi ?? '';
                                            $statusClass = '';
                                            $statusIcon = '';

                                            switch (strtolower($status)) {
                                                case 'Terverifikasi':
                                                case 'terverifikasi':
                                                    $statusClass = 'bg-success';
                                                    $statusIcon = 'fas fa-check';
                                                    break;
                                                case 'Ditolak':
                                                case 'ditolak':
                                                    $statusClass = 'bg-danger';
                                                    $statusIcon = 'fas fa-times';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-warning';
                                                    $statusIcon = 'fas fa-clock';
                                            }
                                        @endphp
                                        <span class="input-group-text {{ $statusClass }} text-white">
                                            <i class="{{ $statusIcon }}"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Deskripsi</label>
                        <textarea class="form-control" rows="3" disabled>{{ $kelolaLomba->deskripsi_lomba }}</textarea>
                    </div>

                    {{-- Menampilkan Alasan jika pengajuan Lomba ditolak  --}}
                    @if ($kelolaLomba->status_verifikasi === 'Ditolak')
                        <div class="alert alert-danger d-flex align-items-start gap-2 shadow-sm rounded">
                            <i class="bi bi-exclamation-circle-fill fs-4 mt-1 text-white"></i>
                            <div class="w-100">
                                <label class="fw-bold mb-1">Alasan Penolakan</label>
                                <div class="bg-white border border-danger rounded p-2 text-danger"
                                    style="min-height: 100px;">
                                    {{ $kelolaLomba->alasan_tolak ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Informasi Pendaftaran -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Informasi Pendaftaran</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Awal Registrasi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                        value="{{ $kelolaLomba->awal_registrasi_lomba }}" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-play"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Akhir Registrasi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                        value="{{ $kelolaLomba->akhir_registrasi_lomba }}" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-danger text-white">
                                            <i class="fas fa-stop"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label font-weight-bold">Link Pendaftaran</label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                value="{{ $kelolaLomba->link_pendaftaran_lomba }}" disabled>
                            <div class="input-group-append">
                                <a href="{{ $kelolaLomba->link_pendaftaran_lomba }}" target="_blank"
                                    class="btn btn-primary">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Poster Lomba -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-image mr-2"></i>Poster Lomba</h6>
                </div>
                <div class="card-body">
                    @if (!is_null($kelolaLomba->img_lomba) && file_exists(public_path('storage/img/lomba/' . $kelolaLomba->img_lomba)))
                        <div class="text-center">
                            <div class="file-preview mb-3">
                                <a href="{{ asset('storage/img/lomba/' . $kelolaLomba->img_lomba) }}"
                                    data-lightbox="lomba" data-title="Poster Lomba - {{ $kelolaLomba->nama_lomba }}">
                                    <img src="{{ asset('storage/img/lomba/' . $kelolaLomba->img_lomba) }}"
                                        class="img-thumbnail" style="max-width: 300px; cursor: zoom-in;"
                                        alt="Poster Lomba" />
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="{{ asset('storage/img/lomba/' . $kelolaLomba->img_lomba) }}" target="_blank"
                                    class="btn btn-primary">
                                    <i class="fas fa-eye mr-2"></i>Lihat Poster
                                </a>
                                <a href="{{ asset('storage/img/lomba/' . $kelolaLomba->img_lomba) }}" download
                                    class="btn btn-outline-primary">
                                    <i class="fas fa-download mr-2"></i>Unduh
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-image-times fa-3x mb-2"></i>
                            <p>Tidak ada poster lomba yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- Footer  --}}
        <div class="modal-footer">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Hapus</button>
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                    class="fas fa-times mr-2"></i>Batal</button>
        </div>
    </form>

    <style>
        .file-preview {
            padding: 20px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        .card-header h6 {
            color: #495057;
            font-weight: 600;
        }

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

        .img-thumbnail {
            transition: transform 0.3s ease;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
        }
    </style>

    <script>
        // Animasi saat modal dibuka
        $(document).ready(function() {
            $('.card').hide().fadeIn(600);

            // Tooltip untuk status
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Force hide processing text after any modal operation
        setInterval(function() {
            if ($('.modal').is(':hidden')) {
                $('.processing, [class*="processing"]').hide();
            }
        }, 500);
    </script>

    {{-- Library Lightbox untuk membesarkan gambar --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

    <script>
        $(document).off('submit', '#form-delete'); // Hapus event handler lama (jika ada)
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
                            // Tutup modal
                            $('#myModal').modal('hide');

                            // Reload tabel DataTables (Sesuaikan dengan ID tabel DataTables di Index)
                            $('#tabel-kelola-lomba').DataTable().ajax.reload(null, false);
                        });
                    } else {
                        Swal.fire('Error', 'Hapus gagal.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
            });
        });
    </script>
@endif

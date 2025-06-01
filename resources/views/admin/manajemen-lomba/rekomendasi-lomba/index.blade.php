{{-- resources/views/lombas/recommendations.blade.php --}}
@extends('layouts.template')

@section('title', 'Rekomendasi Lomba | COMPASS')

@section('page-title', 'Rekomendasi Lomba')

@section('page-description', 'Rekomendasikan lomba untuk mahasiswa!')

@section('content')
    <div>
        <!-- Filter Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control rounded" id="categoryFilter">
                                            <option value=""
                                                {{ empty(request()->get('kategori_id')) ? 'selected' : '' }}>
                                                Filter Kategori</option>
                                            @foreach ($daftarKategori as $kategori)
                                                <option value="{{ $kategori->kategori_id }}"
                                                    {{ in_array($kategori->kategori_id, old('kategori_id', request()->get('kategori_id', []))) ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control rounded" id="levelFilter">
                                            <option value="">Filter Tingkat Lomba</option>
                                            @foreach ($daftarTingkatLomba as $tingkat_lomba)
                                                <option value="{{ $tingkat_lomba->tingkat_lomba_id }}"
                                                    {{ old('tingkat_lomba_id', $kelolaLomba->tingkat_lomba_id ?? '') == $tingkat_lomba->tingkat_lomba_id ? 'selected' : '' }}>
                                                    {{ $tingkat_lomba->nama_tingkat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control rounded" id="searchInput"
                                            placeholder="Cari nama lomba...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-right">
                                <button class="btn btn-primary font-weight-bold"
                                    onclick="modalAction('{{ url('/admin/manajemen-lomba/rekomendasi-lomba/tambah_rekomendasi_ajax') }}')">
                                    <i class="fa-solid fa-plus"></i> Rekomendasi Lomba
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lomba Cards -->
        <div class="row" id="lombaCards">
            @foreach ($dataLomba as $lomba)
                <div class="col-lg-4 col-md-6 lomba-item"
                    data-category="{{ $lomba->kategori->pluck('kategori_id')->join(',') }}"
                    data-level="{{ $lomba->tingkat_lomba_id }}" data-name="{{ $lomba->nama_lomba }}">
                    <div class="card gradient-1 lomba-card">
                        <div class="card-body position-relative">
                            <div class="mb-3">
                                <img src="{{ asset('assets/images/logo/compass-putih.svg') }}" alt="Compass Logo"
                                    style="height: 40px; object-fit: cover;">
                            </div>

                            <div class="lomba-content text-white">
                                <h4 class="font-weight-bold text-white mb-3">{{ $lomba->nama_lomba }}</h4>
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="label label-primary">
                                        {{ $lomba->kategori->pluck('nama_kategori')->join(', ') ?? 'Tidak Diketahui' }}
                                    </span>
                                </div>

                                <div class="lomba-info mb-3">
                                    <p class="text-white mb-1">
                                        <i class="fa fa-trophy mr-2"></i>
                                        <small>{{ $lomba->tingkat_lomba->nama_tingkat }}</small>
                                    </p>
                                    <p class="text-white mb-1">
                                        <i class="fa fa-building mr-2"></i>
                                        <small>{{ $lomba->penyelenggara_lomba }}</small>
                                    </p>
                                    <p class="text-white mb-1">
                                        <i class="fa fa-calendar mr-2"></i>
                                        <small>
                                            {{ \Carbon\Carbon::parse($lomba->awal_registrasi_lomba)->format('d F Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->format('d F Y') }}
                                        </small>
                                    </p>
                                </div>

                                <div class="lomba-deadline">
                                    <div class="alert alert-info py-2 mb-2">
                                        <small><i class="fa fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($lomba->akhir_registrasi_lomba)->diffInDays(\Carbon\Carbon::now()) }}
                                            hari lagi</small>
                                    </div>
                                </div>

                                <div class="lomba-actions mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <button class="btn btn-secondary text-white btn-sm btn-block"
                                                onclick="modalAction('{{ url('/admin/manajemen-lomba/rekomendasi-lomba/' . $lomba->lomba_id . '/show_ajax') }}')">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button
                                                onclick="modalAction('{{ url('/admin/manajemen-lomba/rekomendasi-lomba/' . $lomba->lomba_id . '/rekomendasi_ajax') }}')"
                                                class="btn btn-success text-white btn-sm btn-block">
                                                <i class="fa fa-external-link-alt"></i> Rekomen
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thropy Icon -->
                            <span class="position-absolute" style="top: 25px; right: 25px; font-size: 2rem; opacity: 0.9;">
                                <i class="fa fa-trophy"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Saat filter atau pencarian tidak ditemukan -->
        <div class="row" id="emptyState" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fa-solid fa-search fa-5x text-primary mb-4"></i>
                        <h4>Tidak ada lomba yang ditemukan</h4>
                        <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Bootstrap untuk AJAX (Hanya 1 modal) -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="ajaxModalContent">
                    <!-- Konten modal akan dimuat via AJAX -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Custom CSS untuk daftar lomba --}}
    <link href="{{ asset('css-custom/daftar-card-lomba.css') }}" rel="stylesheet">
@endpush

@push('js')
    <!-- Custom show header JS -->
    <script src="{{ asset('js-custom/header-show-bootstrap5.js') }}"></script>

    <script>
        // Fungsi utama untuk filtering lomba
        function filterLomba() {
            const category = $('#categoryFilter').val()?.toLowerCase(); // Ambil nilai kategori
            const level = $('#levelFilter').val()?.toLowerCase(); // Ambil tingkat lomba
            const search = $('#searchInput').val()?.toLowerCase(); // Ambil kata kunci pencarian

            let visibleCount = 0;

            $('.lomba-item').each(function() {
                const item = $(this);
                const itemCategory = item.attr('data-category')?.toLowerCase().split(',') || [];
                const itemLevel = item.attr('data-level')?.toLowerCase();
                const itemName = item.attr('data-name')?.toLowerCase();

                // Cek kecocokan
                const matchCategory = !category || itemCategory.includes(category); // Cocok jika ada di kategori
                const matchLevel = !level || itemLevel === level; // Cocok jika tingkat sama
                const matchSearch = !search || itemName.includes(search); // Cocok jika nama mengandung keyword

                if (matchCategory && matchLevel && matchSearch) {
                    item.show();
                    visibleCount++;
                } else {
                    item.hide();
                }
            });

            // Tampilkan pesan kosong jika tidak ada hasil
            if (visibleCount === 0) {
                $('#lombaCards').hide();
                $('#emptyState').show();
            } else {
                $('#lombaCards').show();
                $('#emptyState').hide();
            }
        }

        // Event listener untuk input/filter
        $('#categoryFilter, #levelFilter, #searchInput').on('input change', function() {
            filterLomba();
        });

        // Inisialisasi saat halaman dimuat
        $(document).ready(function() {
            filterLomba();
        });

        // Fungsi untuk menampilkan modal via AJAX
        function modalAction(url) {
            $.get(url)
                .done(function(res) {
                    $('#ajaxModalContent').html(res);
                    $('#myModal').modal('show');
                })
                .fail(function() {
                    Swal.fire('Gagal', 'Tidak dapat memuat data dari server.', 'error');
                });
        }
    </script>
@endpush

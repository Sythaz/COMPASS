@extends('layouts.template')

@section('title', 'Notifikasi | COMPASS')

@section('page-title', 'Notifikasi')

@section('page-description', 'Halaman notifikasi, tempat anda dapat melihat semua notifikasi yang dikirim untuk anda.')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0">Semua Notifikasi</h5>
                            <span class="badge badge-primary ml-2 badge-jumlah-notif mr-3">{{ $notifikasi->count() }}</span>

                            <!-- Dropdown Filter Status -->
                            <div class="dropdown mr-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    id="filterStatusDropdown" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-filter mr-1"></i> Status
                                </button>
                                <div class="dropdown-menu p-3" style="width: 200px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filter-status-all" checked>
                                        <label class="custom-control-label" for="filter-status-all">Semua</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filter-status-unread"
                                            checked>
                                        <label class="custom-control-label" for="filter-status-unread">Belum Dibaca</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filter-status-read" checked>
                                        <label class="custom-control-label" for="filter-status-read">Sudah Dibaca</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Dropdown Filter Jenis -->
                            <div class="dropdown mr-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    id="filterJenisDropdown" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-tag mr-1"></i> Jenis
                                </button>
                                <div class="dropdown-menu p-3" style="width: 200px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filter-jenis-all" checked>
                                        <label class="custom-control-label p-0" for="filter-jenis-all">Semua</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filter-jenis-rekomendasi"
                                            checked>
                                        <label class="custom-control-label" for="filter-jenis-rekomendasi">Rekomendasi
                                            Lomba</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="filter-jenis-verifikasi-lomba" checked>
                                        <label class="custom-control-label" for="filter-jenis-verifikasi-lomba">Verifikasi
                                            Lomba</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="filter-jenis-verifikasi-prestasi" checked>
                                        <label class="custom-control-label"
                                            for="filter-jenis-verifikasi-prestasi">Verifikasi
                                            Prestasi</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="filter-jenis-verifikasi-prestasi" checked>
                                        <label class="custom-control-label"
                                            for="filter-jenis-verifikasi-prestasi">Verifikasi
                                            Pendaftaran</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="input-group mr-3" style="width: 250px; height: calc(2.25rem + 2px);">
                                <input type="text" class="form-control rounded-left" placeholder="Cari notifikasi..."
                                    id="searchInput" style="height: 100%;">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="height: 100%;">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-baca-semua" id="btn-baca-semua" href="javascript:void(0)">
                                <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="delete-button-container d-none bg-primary rounded p-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="ml-3 text-white"><span id="total-selected">0</span>
                                    baris notifikasi terpilih
                                </span>
                                <div>
                                    <button class="btn btn-light mr-2" id="btn-tandai-dibaca-terpilih">
                                        <i class="fas fa-check-double mr-1"></i> Tandai Dibaca
                                    </button>
                                    <button class="btn btn-danger mr-2" id="btn-hapus-notifikasi-terpilih">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50px">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="pilihSemua">
                                            <label class="custom-control-label notification-checkbox" for="pilihSemua">
                                            </label>
                                        </div>
                                    </th>
                                    <th>Pengirim</th>
                                    <th>Jenis</th>
                                    <th>Pesan</th>
                                    <th>Tanggal</th>
                                    <th class="text-center" width="70px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="notificationTableBody">
                                @forelse ($notifikasi as $notif)
                                    <tr class="notification-row {{ $notif->status_notifikasi == 'Belum Dibaca' ? 'bg-light' : '' }}  jenis-notifikasi-{{ strtolower(str_replace(' ', '-', $notif->jenis_notifikasi)) }}""
                                        data-id="{{ $notif->notifikasi_id }}">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input notification-checkbox"
                                                    id="check{{ $notif->notifikasi_id }}"
                                                    value="{{ $notif->notifikasi_id }}">
                                                <label class="custom-control-label notification-checkbox"
                                                    for="check{{ $notif->notifikasi_id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm mr-3">
                                                    <img src="
                                                    @if ($notif->pengirim_role == 'Admin') {{ asset('assets/images/profil/default-profile.png') }}
                                                    @elseif ($notif->pengirim_role == 'Dosen') {{ asset('assets/images/profil/default-profile.png') }}
                                                    @elseif ($notif->pengirim_role == 'Sistem') {{ asset('assets/images/logo/compass-ungu.svg') }} @endif
                                                     "
                                                        alt="profile" class="rounded-circle"
                                                        style="width: 40px; height: 40px;" />
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-semi-bold">{{ $notif->getPengirimNama() }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        @if ($notif->pengirim_role != 'Sistem')
                                                            {{ $notif->pengirim_role }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                @if ($notif->jenis_notifikasi == 'Rekomendasi')
                                                    <i class="fas fa-trophy mr-1"></i>
                                                    Rekomendasi Lomba
                                                @elseif ($notif->jenis_notifikasi == 'Verifikasi Lomba')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Verifikasi Lomba Baru
                                                @elseif ($notif->jenis_notifikasi == 'Verifikasi Prestasi')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Verifikasi Prestasi
                                                @elseif ($notif->jenis_notifikasi == 'Verifikasi Pendaftaran')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Verifikasi Pendaftaran
                                                @else
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    {{ $notif->jenis_notifikasi }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="notification-message mr-2">
                                                <p class="mb-1">{{ $notif->pesan_notifikasi }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="row mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($notif->created_at)->format('d M Y') }}
                                                    </small>
                                                </div>
                                                <div class="row">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        @php
                                            $routes = [
                                                'Rekomendasi' => [
                                                    'Dosen' => route('dosen.info-lomba.index'),
                                                    'Mahasiswa' => route('mahasiswa.informasi-lomba.index'),
                                                ],
                                                'Verifikasi Lomba' => [
                                                    'Dosen' => route('dosen.info-lomba.index'),
                                                    'Mahasiswa' => route('mahasiswa.informasi-lomba.index'),
                                                ],
                                                'Verifikasi Prestasi' => [
                                                    'Dosen' => route('dosen.verifikasi-bimbingan.index'),
                                                    'Mahasiswa' => route('mhs.prestasi.index'),
                                                ],
                                            ];
                                            $role = auth()->user()->getRole();
                                            $route =
                                                $routes[$notif->jenis_notifikasi][$role] ?? route('notifikasi.index');
                                        @endphp
                                        <td class="text-center">
                                            <a href="{{ $route }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="mt-2 btn btn-sm btn-danger hapus-notifikasi"
                                                data-id="{{ $notif->notifikasi_id }}">
                                                <i class="fas fa-trash-alt"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="empty-state">
                                                <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">Tidak ada notifikasi</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Notifikasi -->
    <div class="modal fade" id="notificationDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Notifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="notificationDetailContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('css-custom/notifikasi-custom.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script>
        // Tandai notifikasi sebagai dibaca semua dan perbarui jumlah notifikasi di header
        function updateHeaderNotifCount(newCount) {
            const badge = document.getElementById('header-notif-count');
            if (badge) {
                badge.textContent = newCount > 0 ? newCount : '';
                if (newCount <= 0) {
                    badge.style.display = 'none';
                } else {
                    badge.style.display = '';
                }
            }
        }

        function applyFilters() {
            const statusUnread = $('#filter-status-unread').is(':checked');
            const statusRead = $('#filter-status-read').is(':checked');
            const jenisRekomendasi = $('#filter-jenis-rekomendasi').is(':checked');
            const jenisLomba = $('#filter-jenis-verifikasi-lomba').is(':checked');
            const jenisPrestasi = $('#filter-jenis-verifikasi-prestasi').is(':checked');

            $('.notification-row').each(function() {
                const row = $(this);
                const isUnread = row.hasClass('bg-light');

                const isRekomendasi = row.hasClass('jenis-notifikasi-rekomendasi');
                const isLomba = row.hasClass('jenis-notifikasi-verifikasi-lomba');
                const isPrestasi = row.hasClass('jenis-notifikasi-verifikasi-prestasi');

                const matchStatus = (isUnread && statusUnread) || (!isUnread && statusRead);
                const matchJenis = (isRekomendasi && jenisRekomendasi) ||
                    (isLomba && jenisLomba) ||
                    (isPrestasi && jenisPrestasi);

                row.toggle(matchStatus && matchJenis);
            });

            const visibleCheckboxes = $('#notificationTableBody tr:visible .notification-checkbox');
            const allChecked = visibleCheckboxes.length > 0 &&
                visibleCheckboxes.length === visibleCheckboxes.filter(':checked').length;
            $('#pilihSemua').prop('checked', allChecked);

            updateSelectedCount();
        }

        function updateSelectedCount() {
            const count = $('#notificationTableBody tr:visible .notification-checkbox:checked').length;
            const totalVisible = $('#notificationTableBody tr:visible .notification-checkbox').length;

            $('#total-selected').text(count);
            $('.delete-button-container').toggleClass('d-none', count === 0);

            // Update status "Select All" checkbox
            $('#pilihSemua').prop('checked', count > 0 && count === totalVisible);
        }

        // Event listener saat checkbox berubah
        $('.dropdown input[type="checkbox"]').on('change', function() {
            // Jika semua dicentang, centang "semua" dan sebaliknya
            if ($(this).attr('id') === 'filter-status-all' && $(this).is(':checked')) {
                $('#filter-status-unread').prop('checked', true);
                $('#filter-status-read').prop('checked', true);
            } else if ($(this).attr('id') === 'filter-jenis-all' && $(this).is(':checked')) {
                $('#filter-jenis-rekomendasi').prop('checked', true);
                $('#filter-jenis-verifikasi-lomba').prop('checked', true);
                $('#filter-jenis-verifikasi-prestasi').prop('checked', true);
            }

            // Jika salah satu tidak dicentang, hilangkan centang "semua"
            if ($(this).attr('id') !== 'filter-status-all') {
                const allChecked = $('#filter-status-unread').is(':checked') &&
                    $('#filter-status-read').is(':checked');
                $('#filter-status-all').prop('checked', allChecked);
            }

            if ($(this).attr('id') !== 'filter-jenis-all') {
                const allJenisChecked = $('#filter-jenis-rekomendasi').is(':checked') &&
                    $('#filter-jenis-verifikasi-lomba').is(':checked') &&
                    $('#filter-jenis-verifikasi-prestasi').is(':checked');
                $('#filter-jenis-all').prop('checked', allJenisChecked);
            }

            applyFilters();
        });

        $(document).ready(function() {
            // Fungsi untuk menampilkan detail notifikasi saat banyak baris notifikasi terpilih
            function updateSelectedCount() {
                const count = $('.notification-checkbox:checked').length;
                $('#total-selected').text(count);

                $('.delete-button-container').toggleClass('d-none', count === 0);
            }

            // Update jumlah baris notifikasi terpilih saat checkbox diubah
            $(document).on('change', '.notification-checkbox, #pilihSemua', function() {
                updateSelectedCount();
            });

            // Select All Checkbox
            $('#pilihSemua').change(function() {
                const isChecked = $(this).prop('checked');
                $('#notificationTableBody tr:visible .notification-checkbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            // Fungsi Search
            $('#searchInput').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('#notificationTableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Hapus notifikasi
            $('.hapus-notifikasi').click(function(e) {
                e.preventDefault();
                const notifikasiId = $(this).data('id');
                const row = $('#check' + notifikasiId).closest('tr');

                Swal.fire({
                    title: 'Hapus Notifikasi?',
                    text: 'Anda yakin ingin menghapus notifikasi ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('notifikasi.hapusNotifikasi', '') }}/' +
                                notifikasiId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    row.fadeOut(300, function() {
                                        row.remove();
                                    });

                                    // Perbarui badge jumlah notifikasi menjadi berkurang 1
                                    const $badge = $(
                                        '.badge-jumlah-notif'
                                    ); // Target badge notifikasi saja
                                    let currentBadgeCount = parseInt($badge.text());

                                    if (!isNaN(currentBadgeCount) && currentBadgeCount >
                                        0) {
                                        $badge.text(currentBadgeCount - 1);
                                    } else {
                                        $badge.remove(); // Hapus badge jika sudah 0
                                    }
                                } else {
                                    alert(response.message ||
                                        'Gagal menghapus notifikasi.');
                                }
                            },
                            error: function() {
                                alert('Terjadi kesalahan saat menghapus notifikasi.');
                            }
                        });
                    }
                });
            });

            // Hapus banyak notifikasi
            $('#btn-hapus-notifikasi-terpilih').click(function() {
                const selectedIds = $('.notification-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    alert('Pilih setidaknya satu notifikasi untuk dihapus.');
                    return;
                }

                Swal.fire({
                    title: 'Hapus Notifikasi?',
                    text: `Anda yakin ingin menghapus ${selectedIds.length} notifikasi?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('notifikasi.hapusBanyakNotifikasi') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                ids: selectedIds
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari tabel
                                    response.deleted_ids.forEach(function(id) {
                                        $('#check' + id).closest('tr').fadeOut(
                                            300,
                                            function() {
                                                $(this).remove();
                                            });

                                        // Update badge jumlah notifikasi
                                        const $badge = $('.badge-jumlah-notif');
                                        let current = parseInt($badge.text());
                                        if (!isNaN(current) && current > 0) {
                                            $badge.text(current - 1);
                                            if (current - 1 <= 0) {
                                                $badge.remove();
                                            }
                                        }
                                    });
                                } else {
                                    alert(response.message ||
                                        'Gagal menghapus notifikasi.');
                                }
                            },
                            error: function() {
                                alert('Terjadi kesalahan saat menghapus notifikasi.');
                            }
                        });
                    }
                });
            });

            // Tombol "Tandai Semua Dibaca"
            $('.btn-baca-semua').click(function() {
                Swal.fire({
                    title: 'Tandai Semua Dibaca?',
                    text: 'Anda yakin ingin menandai semua notifikasi sebagai dibaca?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tandai Semua Dibaca'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim permintaan AJAX untuk menandai semua notifikasi sebagai dibaca
                        $.ajax({
                            url: '{{ route('notifikasi.bacaSemuaNotifikasi') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Ubah status notifikasi di UI
                                    $('.notification-row').each(function() {
                                        $(this).removeClass('bg-light');
                                    });

                                    updateHeaderNotifCount(0);

                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Semua notifikasi berhasil ditandai sebagai dibaca',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Terjadi kesalahan saat menandai semua notifikasi sebagai dibaca',
                                        icon: 'error',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghubungi server',
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });


            // Fungsi untuk menandai notifikasi terpilih sebagai dibaca
            $('#btn-tandai-dibaca-terpilih').click(function() {
                const selectedIds = $('.notification-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire({
                        title: 'Peringatan',
                        text: 'Pilih setidaknya satu notifikasi untuk ditandai dibaca',
                        icon: 'warning'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Tandai Dibaca?',
                    text: `Anda yakin ingin menandai ${selectedIds.length} notifikasi sebagai dibaca?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tandai Dibaca'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('notifikasi.tandaiDibacaBanyakNotifikasi') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                ids: selectedIds
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Update UI untuk notifikasi yang ditandai dibaca
                                    selectedIds.forEach(function(id) {
                                        $('#check' + id).closest('tr')
                                            .removeClass('bg-light');
                                    });

                                    // Reset seleksi
                                    $('.notification-checkbox').prop('checked', false);
                                    $('#pilihSemua').prop('checked', false);
                                    updateSelectedCount();

                                    // Update badge jumlah notifikasi di header jika ada
                                    if (typeof updateHeaderNotifCount === 'function') {
                                        const currentUnread = $(
                                            '.notification-row.bg-light').length;
                                        updateHeaderNotifCount(currentUnread);
                                    }

                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Notifikasi berhasil ditandai sebagai dibaca',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Gagal menandai notifikasi sebagai dibaca',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat menandai notifikasi sebagai dibaca',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

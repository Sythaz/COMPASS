@extends('layouts.template')

@section('title', 'Kelola Bobot Kriteria | COMPASS')

@section('page-title', 'Kelola Bobot Kriteria')

@section('page-description', 'Halaman untuk mengatur bobot kriteria pada perhitungan PROMETHEE rekomendasi!')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="bobotKriteriaForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <h4 class="card-title mb-0">Pengaturan Bobot Kriteria</h4>
                            <p class="text-muted mb-0">Atur bobot untuk setiap kriteria. Total bobot harus sama dengan 100%
                            </p>
                        </div>

                        <!-- Progress Bar Total -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="font-weight-bold">Total Bobot:</label>
                                <span id="totalWeight"
                                    class="badge badge-primary">{{ array_sum(array_column($criteriaArray, 'bobot')) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div id="totalProgress" class="progress-bar" role="progressbar"
                                    style="width: {{ array_sum(array_column($criteriaArray, 'bobot')) }}%"
                                    aria-valuenow="{{ array_sum(array_column($criteriaArray, 'bobot')) }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <small id="weightStatus" class="text-success">✓ Total bobot sudah sesuai</small>
                        </div>

                        <div class="row">
                            @foreach ($criteriaArray as $kode => $data)
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label for="{{ strtolower($kode) }}_weight" class="font-weight-bold">
                                                    {{ $kode }} - {{ $data['nama'] }}
                                                </label>
                                                <span id="{{ strtolower($kode) }}_value"
                                                    class="badge badge-info">{{ $data['bobot'] }}%</span>
                                            </div>
                                            <input type="range" class="custom-range weight-slider"
                                                id="{{ strtolower($kode) }}_weight" name="weights[{{ $kode }}]"
                                                min="0" max="100" value="{{ $data['bobot'] }}"
                                                data-criteria="{{ $kode }}">
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">0%</small>
                                                <small class="text-muted">100%</small>
                                            </div>
                                            @if ($data['type'] === 'cost')
                                                <small class="text-info">
                                                    <i class="fas fa-info-circle"></i> Kriteria Cost (semakin rendah semakin
                                                    baik)
                                                </small>
                                            @else
                                                <small class="text-success">
                                                    <i class="fas fa-info-circle"></i> Kriteria Benefit (semakin tinggi
                                                    semakin baik)
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo mr-2"></i>Reset ke Nilai Awal
                                    </button>
                                    <div>
                                        <button type="submit" id="saveBtn" class="btn btn-primary">
                                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Sweetalert CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .weight-slider {
            cursor: pointer;
        }

        .custom-range::-webkit-slider-thumb {
            background: #007bff;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .custom-range::-moz-range-thumb {
            background: #007bff;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 0.8em;
        }

        .alert-dismissible {
            animation: slideInDown 0.3s ease-in-out;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endpush

@push('js')
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Bobot default untuk setiap kriteria
            const defaultWeights = {
                @foreach ($criteriaArray as $kode => $data)
                    '{{ $kode }}': {{ $data['bobot'] }},
                @endforeach
            }

            // Perbarui total bobot dan tampilkan status yang sesuai
            function updateTotalWeight() {
                let total = 0;
                $('.weight-slider').each(function() {
                    total += parseFloat($(this).val());
                });

                $('#totalWeight').text(total + '%');
                $('#totalProgress').css('width', Math.min(total, 100) + '%');

                if (total === 100) {
                    // Jika total tepat 100
                    $('#totalProgress').attr('class', 'progress-bar bg-success');
                    $('#weightStatus').attr('class', 'text-success').html('✓ Total bobot sudah sesuai');
                    $('#saveBtn').prop('disabled', false);
                } else {
                    // Jika total tidak 100
                    const status = total < 100 ? {
                        text: `⚠ Total bobot kurang dari 100% (${100 - total}% tersisa)`,
                        color: 'warning'
                    } : {
                        text: `✗ Total bobot melebihi 100% (kelebihan ${total - 100}%)`,
                        color: 'danger'
                    };

                    $('#totalProgress').attr('class', `progress-bar bg-${status.color}`);
                    $('#weightStatus').attr('class', `text-${status.color}`).html(status.text);
                    $('#saveBtn').prop('disabled', true);
                }
            }

            // Perbarui tampilan bobot untuk setiap kriteria
            function updateWeightDisplay(criteria, value) {
                $(`#${criteria.toLowerCase()}_value`).text(value + '%');
            }

            // Tampilkan pesan alert
            function showAlert(message, type = 'success') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <strong>${type === 'success' ? 'Berhasil!' : 'Error!'}</strong> ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                $('.card-body').prepend(alertHtml);
                setTimeout(() => $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                }), 2000);
            }

            // Event handler untuk perubahan input slider bobot
            $('.weight-slider').on('input', function() {
                updateWeightDisplay($(this).data('criteria'), parseFloat($(this).val()));
                updateTotalWeight();
            });

            // Reset bobot ke default
            $('#resetBtn').click(() => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Merestart bobot ke nilai default akan menghapus semua perubahan yang belum disimpan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, reset bobot!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.post('{{ route('bobot-rekomendasi.reset') }}', {
                                _token: '{{ csrf_token() }}'
                            })
                            .done(response => {
                                if (response.success) {
                                    Object.keys(defaultWeights).forEach(criteria => {
                                        const slider = $(
                                            `[data-criteria="${criteria}"]`);
                                        slider.val(defaultWeights[criteria]);
                                        updateWeightDisplay(criteria, defaultWeights[
                                            criteria]);
                                    });
                                    updateTotalWeight();
                                    showAlert(response.message, 'success');
                                } else {
                                    showAlert(response.message, 'danger');
                                }
                            })
                            .fail(xhr => showAlert(xhr.responseJSON?.message || 'Terjadi kesalahan',
                                'danger'));
                    }
                });
            });

            // Kirim form bobot
            $('#bobotKriteriaForm').submit(function(e) {
                e.preventDefault();

                const total = $('.weight-slider').get().reduce((sum, el) => sum + parseFloat($(el).val()),
                    0);
                if (total !== 100) {
                    showAlert(`Total bobot harus sama dengan 100%. Saat ini total: ${total}%`, 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Anda akan memperbarui bobot kriteria. Lanjutkan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, perbarui!'
                }).then(result => {
                    if (result.isConfirmed) {
                        const formData = new FormData(this);
                        $.ajax({
                            url: '{{ route('bobot-rekomendasi.update') }}',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: response => showAlert(response.message, response
                                .success ? 'success' : 'danger'),
                            error: xhr => {
                                const response = xhr.responseJSON;
                                if (xhr.status === 422) {
                                    let errorMessage = 'Data tidak valid:<br>';
                                    if (response.errors) {
                                        Object.values(response.errors).flat().forEach(
                                            error =>
                                            errorMessage += `• ${error}<br>`);
                                    }
                                    showAlert(errorMessage, 'danger');
                                } else {
                                    showAlert(response?.message || 'Terjadi kesalahan',
                                        'danger');
                                }
                            },
                        });
                    }
                });
            });

            updateTotalWeight();
            setTimeout(() => $('.alert').fadeOut('slow'), 5000);
        });
    </script>
@endpush

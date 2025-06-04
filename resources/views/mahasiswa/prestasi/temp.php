<script>
    $('#lomba_id').on('change', function () {
        const selected = $(this).val();
        const tingkat = $('option:selected', this).data('tingkat') || '';
        const kategori = $('option:selected', this).data('kategori') || '';
        const tipe = $('option:selected', this).data('tipe') || '';

        if (selected === 'lainnya') {
            // Sembunyikan readonly
            $('#form-tingkat-lomba').hide();
            $('#form-kategori-lomba').hide();

            // Kosongkan nilai readonly
            $('#nama_tingkat_lomba').val('');
            $('#nama_kategori_lomba').val('');

            // Tampilkan input manual
            $('#input-lomba-lainnya').show();
            $('#kategori-lomba-manual').show();

            // Reset dan aktifkan jenis prestasi
            $('#jenis_prestasi').val('').prop('disabled', false);

            // Set jumlah anggota default dan render
            $('#jumlah_anggota').val(JUMLAH_INDIVIDU).prop('readonly', true);
            renderAnggota(JUMLAH_INDIVIDU);

        } else if (selected) {
            // Tampilkan readonly dan isi label
            $('#form-tingkat-lomba').show();
            $('#form-kategori-lomba').show();

            // Sembunyikan input manual
            $('#input-lomba-lainnya').hide();
            $('#kategori-lomba-manual').hide();

            // Isi readonly berdasarkan data DB
            $('#nama_tingkat_lomba').val(tingkat);
            $('#nama_kategori_lomba').val(kategori);

            // Reset jenis prestasi
            $('#jenis_prestasi').val('').prop('disabled', false);

            // Set jumlah anggota default
            $('#jumlah_anggota').val(JUMLAH_INDIVIDU).prop('readonly', true);
            renderAnggota(JUMLAH_INDIVIDU);
</script>


{{-- Cek Lomba Yang di Submit Duplikat atau tidak --}}
<script>
            const urlCekLombaDuplicate = "{{ route('mhs.prestasi.cekLombaDuplicate') }}";
</script>
{{-- Submit Handler --}}
<script>
            $(document).ready(function () {

                // Pastikan url cek duplicate sudah terdefinisi global atau buat variabel di sini
                const urlCekLombaDuplicate = "{{ route('mhs.prestasi.cekLombaDuplicate') }}";

                $('#form-prestasi').on('submit', function (e) {
                    e.preventDefault();

                    const mahasiswaLoginId = window.mahasiswaLoginId?.toString();
                    if (!mahasiswaLoginId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Data mahasiswa login tidak ditemukan.',
                            confirmButtonText: 'Oke'
                        });
                        return;
                    }

                    // Ambil nilai lomba yang dipilih
                    const lombaId = $('#lomba_id').val();
                    if (!lombaId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Lomba',
                            text: 'Silakan pilih lomba terlebih dahulu.',
                            confirmButtonText: 'Oke'
                        });
                        return;
                    }

                    // Ambil semua mahasiswa_id yang dipilih
                    let anggotaTim = [];
                    let foundLogin = false;
                    let duplicateMahasiswa = false;

                    $('select[name="mahasiswa_id[]"]').each(function () {
                        const val = $(this).val();
                        if (!val) return; // skip jika kosong

                        if (val === mahasiswaLoginId) foundLogin = true;
                        if (anggotaTim.includes(val)) duplicateMahasiswa = true;

                        anggotaTim.push(val);
                    });

                    if (!foundLogin) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Mahasiswa yang login harus menjadi salah satu anggota tim (Ketua atau Anggota).',
                            confirmButtonText: 'Oke'
                        });
                        return;
                    }

                    if (duplicateMahasiswa) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Nama mahasiswa yang dipilih tidak boleh sama atau dobel.',
                            confirmButtonText: 'Oke'
                        });
                        return;
                    }

                    // Cek duplicate lomba di server
                    $.ajax({
                        url: urlCekLombaDuplicate,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            mahasiswa_id: mahasiswaLoginId,
                            lomba_id: lombaId,
                        },
                        success: function (response) {
                            if (response.status === 'error') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal',
                                    text: response.message || 'Anda sudah pernah submit lomba ini sebelumnya.',
                                    confirmButtonText: 'Oke'
                                });
                            } else {
                                submitPrestasiForm();
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal melakukan pengecekan lomba, silakan coba lagi.',
                                confirmButtonText: 'Oke'
                            });
                        }
                    });

                    function submitPrestasiForm() {
                        var form = $('#form-prestasi')[0];
                        var formData = new FormData(form);

                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message || 'Prestasi berhasil disimpan!',
                                    confirmButtonColor: '#3085d6',
                                }).then(() => {
                                    $('#myModal').modal('hide');
                                    location.reload();
                                });
                            },
                            error: function (xhr) {
                                let msg = 'Terjadi kesalahan. Silakan coba lagi.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: msg,
                                    confirmButtonColor: '#d33',
                                });
                            }
                        });
                    }

                });
            });
</script>
@props([
    'nama' => 'Nama Default',
    'nim' => '0000000000',
    'role' => 'Frontend Developer',
    'deskripsi' => 'Deskripsi default',
    'quote' => 'Quote Default....',
    'foto' => 'https://via.placeholder.com/400x600',
    'link' => '#',
])

<div class="col-12 col-md-6 col-lg-4 mb-4 split-animate">
    <div class="card-custom">
        <div class="card-custom-inner">
            <div class="card-custom-front d-flex flex-column w-100 overflow-hidden relative h-100">
                <img src="{{ $foto }}" alt="{{ $nama }}" class="w-100 h-100"
                    style="object-fit: cover; border-radius: inherit;">
                <div class="overlay"
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8)); border-radius: inherit;">
                </div>
                <div class="position-absolute w-100 bottom-0 p-3" style="bottom: 0;">
                    <div class="woilah">
                        <h3 class="text-white mb-0">{{ $nama }}</h3>
                        <p class="text-muted">{{ $deskripsi }}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-id-badge text-success mr-1"></i>
                            <span class="text-white font-weight-bold">{{ $nim }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-laptop-code text-success mr-1"></i>
                            <span class="text-white font-weight-bold">{{ $role }}</span>
                        </div>
                        <div>
                            <a href="{{ $link }}" target="_blank"
                                class="fw-bold btn btn-success btn-rounded font-weight-bold">Ikuti
                                <i class="ml-1 fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-custom-back d-flex flex-column p-3" onclick="window.open('{{ $link }}', '_blank')">
                <img src="{{ asset('assets/images/logo/compass-putih.svg') }}" alt="COMPASS Logo" class="logo" />
                <img src="{{ asset('assets/images/quote.png') }}" alt="Quote icon" class="quote-icon" />
                <img src="{{ $foto }}" alt="Foto {{ $nama }}" class="profile-pic" style="" />
                <p class="testimonial-text">
                    "{{ $quote }}"
                </p>
                <img style="right: 20%;" src="{{ asset('assets/images/bintang.png') }}" alt="Bintang icon" class="bintang-icon" />
                <img style="right: 40%;" src="{{ asset('assets/images/bintang.png') }}" alt="Bintang icon" class="bintang-icon" />
                <img style="left: 20%;" src="{{ asset('assets/images/bintang.png') }}" alt="Bintang icon" class="bintang-icon" />
                <img style="left: 40%;" src="{{ asset('assets/images/bintang.png') }}" alt="Bintang icon" class="bintang-icon" />
            </div>
        </div>
    </div>
</div>

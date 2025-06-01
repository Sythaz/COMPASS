<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Dosen</h5>
    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">NIP</th>
            <td class="text-start">{{ $dosen->nip_dosen }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td class="text-start">{{ $dosen->nama_dosen }}</td>
        </tr>
        <tr>
            <th>Username</th>
            <td class="text-start">{{ $dosen->users?->username ?? '-' }}</td>
        </tr>
        <tr>
            <th>Bidang</th>
            <td class="text-start">
                {{ $dosen->kategoris->pluck('nama_kategori')->implode(', ') ?: '-' }}
            </td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td class="text-start">{{ $dosen->kelamin ?? '-' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td class="text-start">{{ $dosen->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>No Handphone</th>
            <td class="text-start">{{ $dosen->no_hp ?? '-' }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td class="text-start">{{ $dosen->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td class="text-start">{{ $dosen->users->role }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td class="text-start">{{ $dosen->status ?? '-' }}</td>
        </tr>
    </table>
</div>

<!-- Modal Footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>
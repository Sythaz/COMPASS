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
            <th>Role</th>
            <td class="text-start">{{ $dosen->users->role }}</td>
        </tr>
        <tr>
            <th>Bidang</th>
            <td class="text-start">{{ $dosen->kategori->nama_kategori ?? '-' }}</td>
        </tr>
    </table>
</div>

<!-- Modal Footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>
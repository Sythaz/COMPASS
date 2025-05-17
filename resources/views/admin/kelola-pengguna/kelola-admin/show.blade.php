<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Admin
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">NIP</th>
            <td class="text-start">{{ $admin->nip_admin }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td class="text-start">{{ $admin->nama_admin }}</td>
        </tr>
        <tr>
            <th>Username</th>
            <td class="text-start">{{ $admin->users->username ?? '-' }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td class="text-start"> {{ $admin->users->role }}</td>
        </tr>
    </table>
</div>

<!-- Modal Footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>
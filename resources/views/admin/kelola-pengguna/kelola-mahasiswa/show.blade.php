<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Mahasiswa</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">NIM</th>
            <td class="text-start">{{ $mahasiswa->nim_mahasiswa }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td class="text-start">{{ $mahasiswa->nama_mahasiswa }}</td>
        </tr>
        <tr>
            <th>Username</th>
            <td class="text-start">{{ $mahasiswa->users?->username ?? '-' }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td class="text-start">{{ $mahasiswa->users->role }}</td>
        </tr>
        <tr>
            <th>Program Studi</th>
            <td class="text-start">{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</td>
        </tr>
        <tr>
            <th>Periode</th>
            <td class="text-start">{{ $mahasiswa->periode->semester_periode ?? '-' }}</td>
        </tr>
        <tr>
            <th>Level Minat Bakat</th>
            <td class="text-start">{{ $mahasiswa->level_minat_bakat->level_minbak ?? '-' }}</td>
        </tr>
    </table>
</div>

<!-- Modal Footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>
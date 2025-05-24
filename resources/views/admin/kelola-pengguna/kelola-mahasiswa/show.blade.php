<!-- Modal Header -->
<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Mahasiswa</h5>
    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<!-- Modal Body -->
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th>NIM</th>
            <td class="text-start">{{ $mahasiswa->nim_mahasiswa }}</td>
        </tr>
        <tr>
            <th>Username</th>
            <td class="text-start">{{ $mahasiswa->users?->username ?? '-' }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td class="text-start">{{ $mahasiswa->nama_mahasiswa }}</td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td class="text-start">{{ $mahasiswa->kelamin }}</td>
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
            <th>Angkatan</th>
            <td class="text-start">{{ $mahasiswa->angkatan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td class="text-start">{{ $mahasiswa->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>No Handphone</th>
            <td class="text-start">{{ $mahasiswa->no_hp ?? '-' }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td class="text-start">{{ $mahasiswa->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <th>Level Minat Bakat</th>
            <td class="text-start">{{ $mahasiswa->level_minat_bakat->level_minbak ?? '-' }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td class="text-start">{{ $mahasiswa->users->role }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td class="text-start">{{ $mahasiswa->status ?? '-' }}</td>
        </tr>
    </table>
</div>

<!-- Modal Footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>
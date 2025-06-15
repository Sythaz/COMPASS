<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Kategori</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">Semester Periode: </th>
            <td class="text-start">{{ $periode->semester_periode }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Tanggal Mulai: </th>
            <td class="text-start">{{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Tanggal Akhir: </th>
            <td class="text-start">{{ \Carbon\Carbon::parse($periode->tanggal_akhir)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Status: </th>
            <td class="text-start">{{ $periode->status_periode }}</td>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>

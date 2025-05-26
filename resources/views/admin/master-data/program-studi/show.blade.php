<div class="modal-header bg-primary rounded">
    <h5 class="modal-title text-white">Detail Program Studi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">Program Studi: </th>
            <td class="text-start">{{ $prodi->nama_prodi }}</td>
        </tr>
        <tr>
            <th style="width: 30%">Status: </th>
            <td class="text-start">{{ $prodi->status_prodi }}</td>
        </tr>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>

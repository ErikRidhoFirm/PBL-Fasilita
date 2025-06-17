@empty($fasilitas)
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-body"><div class="alert alert-danger">Data tidak ditemukan</div></div>
  </div></div>
@else
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">

    <div class="modal-header bg-info text-white">
      <h5 class="modal-title"><i class="mdi mdi-file-document-box"></i> Detail Fasilitas</h5>
      <button type="button" class="btn-close btn-close-white"
              data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body p-4">
      <table class="table table-borderless">
        <tr><th>Nama Fasilitas</th><td>{{ $fasilitas->nama_fasilitas }}</td></tr>
      </table>
    </div>

    <div class="modal-footer border-0">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        Tutup
      </button>
    </div>

  </div>
</div>
@endempty

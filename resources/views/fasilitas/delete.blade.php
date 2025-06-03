@empty($fasilitas)
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-body">
      <div class="alert alert-danger">Data tidak ditemukan</div>
    </div>
  </div></div>
@else
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">

    <div class="modal-header bg-danger text-white">
      <h5 class="modal-title"><i class="mdi mdi-trash-can"></i> Hapus Fasilitas</h5>
      <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close">x</button>
    </div>

    <form id="form-fasilitas-delete"
          action="{{ route('fasilitas.destroy', $fasilitas) }}"
          method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-body p-4">
        Yakin menghapus fasilitas <strong>{{ $fasilitas->nama_fasilitas }}</strong>?
      </div>
      <div class="modal-footer border-0">
        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
      </div>
    </form>

  </div>
</div>

<script>
$('#form-fasilitas-delete').on('submit', function(e){
  e.preventDefault();
  $.ajax({
    url: this.action,
    type: 'DELETE',
    data: $(this).serialize(),
    success(res){
      $('#myModal').modal('hide');
      Swal.fire('Terhapus', res.message, 'success');
      tableFasilitas.ajax.reload(null,false);
    },
    error(err){
      Swal.fire('Error', err.responseJSON?.message||err.statusText, 'error');
    }
  });
});
</script>
@endempty

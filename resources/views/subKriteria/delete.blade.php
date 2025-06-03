<div class="modal-dialog modal-lg w-50" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Hapus Skoring</h5>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <form id="form-delete-skoring" action="{{ route('skoring.destroy', $sk->id_skoring_kriteria) }}" method="POST">
      @csrf @method('DELETE')
      <div class="modal-body">
        <p>Yakin ingin menghapus parameter <strong>{{ $sk->parameter }}</strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Hapus</button>
      </div>
    </form>
  </div>
</div>

<script>
$(function(){
  $('#form-delete-skoring').on('submit', function(e){
    e.preventDefault();
    $.ajax({
      url: this.action,
      type: 'DELETE',
      data: $(this).serialize(),
      dataType: 'json',
      headers: { 'Accept': 'application/json' },
      success: function(res){
        if(res.status){
          $('#myModal').modal('hide');
          Swal.fire('Terhapus', res.message, 'success');
          window.tableSkoring['{{ $sk->id_kriteria }}'].ajax.reload(null,false);
        }
      }
    });
  });
});
</script>

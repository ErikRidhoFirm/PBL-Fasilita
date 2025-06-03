<div class="modal-dialog modal-lg w-50" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">
        Edit Skoring: {{ $k->kode_kriteria }} — {{ $k->nama_kriteria }}
      </h5>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <form id="form-edit-skoring" action="{{ route('skoring.update', $sk->id_skoring_kriteria) }}" method="POST">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-group">
          <label>Parameter</label>
          <input type="text" name="parameter" class="form-control" value="{{ $sk->parameter }}">
          <small id="error-parameter" class="form-text text-danger"></small>
        </div>
        <div class="form-group">
          <label>Nilai Referensi</label>
          <input type="number" name="nilai_referensi" class="form-control" value="{{ $sk->nilai_referensi }}">
          <small id="error-nilai_referensi" class="form-text text-danger"></small>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
$(function(){
  $('#form-edit-skoring').validate({
    rules: {
      parameter:       { required:true, maxlength:255 },
      nilai_referensi: { required:true, digits:true }
    },
    submitHandler: function(form) {
      $.ajax({
        url:     form.action,
        type:    'PUT',
        data:    $(form).serialize(),
        dataType:'json',
        headers: { 'Accept': 'application/json' },
        success: function(res){
          if(res.status){
            $('#myModal').modal('hide');
            Swal.fire('Sukses', res.message, 'success');
            // reload tabel yang sesuai
            window.tableSkoring[ res.id_kriteria ?? '{{ $k->id_kriteria }}' ]
              .ajax.reload(null,false);
          } else {
            $('.form-text.text-danger').text('');
            $.each(res.msgField, function(f,v){
              $('#error-'+f).text(v[0]);
            });
            Swal.fire('Error', res.message, 'error');
          }
        }
      });
      return false;
    }
  });
});
</script>

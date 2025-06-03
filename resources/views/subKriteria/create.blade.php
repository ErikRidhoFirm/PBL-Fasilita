<div class="modal-dialog modal-lg w-50" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">
        Tambah Skoring: {{ $kriteria->kode_kriteria }} — {{ $kriteria->nama_kriteria }}
      </h5>
      <button type="button" class="close" data-dismiss="modal">
        <span>&times;</span>
      </button>
    </div>

    <form id="form-create-skoring" action="{{ route('skoring.store', $kriteria->id_kriteria) }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label>Parameter</label>
          <input type="text" name="parameter" class="form-control">
          <small id="error-parameter" class="form-text text-danger"></small>
        </div>
        <div class="form-group">
          <label>Nilai Referensi</label>
          <input type="number" name="nilai_referensi" class="form-control">
          <small id="error-nilai_referensi" class="form-text text-danger"></small>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
$(function() {
  $('#form-create-skoring').validate({
    rules: {
      parameter:       { required: true, maxlength: 255 },
      nilai_referensi: { required: true, digits: true }
    },
    submitHandler: function(form) {
      $.ajax({
        url: form.action,
        method: form.method,
        data: $(form).serialize(),
        dataType: 'json',
        headers: { 'Accept': 'application/json' },
        success: function(res) {
          if (res.status) {
            $('#myModal').modal('hide');
            Swal.fire('Berhasil', res.message, 'success');
             window.tableSkoring[{{ $kriteria->id_kriteria }}]
                .ajax.reload(null, false);
          } else {
            $('small.text-danger').text('');
            $.each(res.msgField, function(field, msgs){
              $('#error-' + field).text(msgs[0]);
            });
            Swal.fire('Error', res.message, 'error');
          }
        },
      });
      return false;
    }
  });
});
</script>

<div class="modal-dialog modal-lg w-50" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Tambah Kriteria</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <form id="form-kriteria" action="{{ route('kriteria.store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label>Kode Kriteria</label>
          <input type="text" name="kode_kriteria" class="form-control">
          <small id="error-kode_kriteria" class="form-text text-danger"></small>
        </div>
        <div class="form-group">
          <label>Nama Kriteria</label>
          <input type="text" name="nama_kriteria" class="form-control">
          <small id="error-nama_kriteria" class="form-text text-danger"></small>
        </div>
        <div class="form-group">
          <label>Bobot</label>
          <input type="number" name="bobot_kriteria" step="0.01" class="form-control">
          <small id="error-bobot_kriteria" class="form-text text-danger"></small>
        </div>
        <div class="form-group">
          <label>Tipe Kriteria</label>
          <select name="tipe_kriteria" class="form-control">
            <option value="">-- Pilih Tipe --</option>
            <option value="benefit">Benefit</option>
            <option value="cost">Cost</option>
          </select>
          <small id="error-tipe_kriteria" class="form-text text-danger"></small>
        </div>
        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="deskripsi" class="form-control"></textarea>
          <small id="error-deskripsi" class="form-text text-danger"></small>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
$(function() {
  $('#form-kriteria').validate({
    rules: {
      kode_kriteria: { required: true, maxlength: 10 },
      nama_kriteria: { required: true, maxlength: 100 },
      bobot_kriteria: { required: true, number: true },
      tipe_kriteria: { required: true }
    },
    submitHandler: function(form) {
      $.ajax({
        url: form.action,
        method: form.method,
        data: $(form).serialize(),
        success: function(res) {
          if (res.status) {
            $('#myModal').modal('hide');
            Swal.fire('Sukses', res.message, 'success');
            tableKriteria.ajax.reload(null, false);
          } else {
            $('.form-text.text-danger').text('');
            $.each(res.msgField, function(key, value) {
              $('#error-' + key).text(value[0]);
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

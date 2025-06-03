<div class="modal-dialog modal-lg w-50" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Edit Kriteria</h5>
      <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @empty($kriteria)
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
          Data yang anda cari tidak ditemukan
        </div>
        <a href="{{ route('kriteria.index') }}" class="btn btn-warning">Kembali</a>
      </div>
    @else
      <form id="form-edit-kriteria" action="{{ route('kriteria.update', $kriteria->id_kriteria) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Kriteria</label>
            <input type="text" name="kode_kriteria" class="form-control" value="{{ $kriteria->kode_kriteria }}">
            <small id="error-kode_kriteria" class="error-text form-text text-danger"></small>
          </div>
          <div class="form-group">
            <label>Nama Kriteria</label>
            <input type="text" name="nama_kriteria" class="form-control" value="{{ $kriteria->nama_kriteria }}">
            <small id="error-nama_kriteria" class="error-text form-text text-danger"></small>
          </div>
          <div class="form-group">
            <label>Bobot</label>
            <input type="number" name="bobot_kriteria" step="0.01" class="form-control" value="{{ $kriteria->bobot_kriteria }}">
            <small id="error-bobot_kriteria" class="error-text form-text text-danger"></small>
          </div>
          <div class="form-group">
            <label>Tipe Kriteria</label>
            <select name="tipe_kriteria" class="form-control">
              <option value="">Pilih Tipe...</option>
              <option value="benefit" {{ $kriteria->tipe_kriteria=='benefit'?'selected':'' }}>Benefit</option>
              <option value="cost"    {{ $kriteria->tipe_kriteria=='cost'   ?'selected':'' }}>Cost</option>
            </select>
            <small id="error-tipe_kriteria" class="error-text form-text text-danger"></small>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ $kriteria->deskripsi }}</textarea>
            <small id="error-deskripsi" class="error-text form-text text-danger"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    @endempty
  </div>
</div>

<script>
$(document).ready(function() {
  $('#form-edit-kriteria').validate({
    rules: {
      kode_kriteria: { required:true, maxlength:10 },
      nama_kriteria: { required:true, maxlength:100 },
      bobot_kriteria:{ required:true, number:true },
      tipe_kriteria: { required:true }
    },
    submitHandler: function(form) {
      $.ajax({
        url: form.action,
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response.status) {
            $('#myModal').modal('hide');
            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            tableKriteria.ajax.reload();
          } else {
            $('.error-text').text('');
            $.each(response.msgField, function(prefix, val) {
              $('#error-' + prefix).text(val[0]);
            });
            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
          }
        }
      });
      return false;
    }
  });
});
</script>

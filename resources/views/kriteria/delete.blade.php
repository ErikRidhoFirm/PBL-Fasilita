<div class="modal-dialog modal-lg w-50">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Hapus Data Kriteria</h5>
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
      <form id="form-delete-kriteria" action="{{ route('kriteria.destroy', $kriteria->id_kriteria) }}" method="POST">
        @csrf @method('DELETE')
        <div class="modal-body">
          <h3 class="mb-5">Apakah anda yakin ingin menghapus data ini?</h3>
          <table class="table table-striped">
            <tr><th>Kode Kriteria</th><td>{{ $kriteria->kode_kriteria }}</td></tr>
            <tr><th>Nama Kriteria</th><td>{{ $kriteria->nama_kriteria }}</td></tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    @endempty
  </div>
</div>

<script>
$(document).ready(function() {
  $('#form-delete-kriteria').validate({
    submitHandler: function(form) {
      $.ajax({
        url: form.action,
        type: form.method,
        data: $(form).serialize(),
        success: function(response) {
          if (response.status) {
            $('#myModal').modal('hide');
            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            tableKriteria.ajax.reload();
          } else {
            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
          }
        }
      });
      return false;
    }
  });
});
</script>

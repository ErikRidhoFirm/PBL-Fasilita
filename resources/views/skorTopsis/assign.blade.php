<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Tugaskan: {{ $lap->fasilitas->nama_fasilitas }}</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
      <form id="form-assign">
        @csrf
        <div class="mb-3">
          <label>Pilih Teknisi:</label>
          <select name="teknisi_id" class="form-select" required>
            <option value="">-- pilih --</option>
            @foreach($teknisis as $t)
              <option value="{{ $t->id_pengguna }}">{{ $t->nama }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Tugaskan</button>
      </form>
    </div>
  </div>
</div>

<script>
$(function(){
  $('#form-assign').on('submit', function(e){
    e.preventDefault();
    let url = "{{ route('skorTopsis.assign', $sk->id_skor_topsis) }}";

    $.ajax({
      url: url,
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json'
    })
    .done(res => {
      // tutup modal
      if (assignModal) assignModal.hide();

      // SweetAlert di tengah
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: res.message,
        timer: 1500,
        showConfirmButton: false
      });

      // reload datatable tanpa memaksakan refresh
      $('#tbl-prioritas').DataTable().ajax.reload(null, false);
    })
    .fail(xhr => {
      let msg = 'Gagal menugaskan';
      if (xhr.status === 422) {
        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
      }
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: msg
      });
    });
  });
});
</script>

@empty($fasilitas)
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-body"><div class="alert alert-danger">Data tidak ditemukan.</div></div>
  </div></div>
@else
<div class="modal-dialog modal-lg modal-dialog-centered">
  <div class="modal-content rounded-4 shadow-lg border-0">

    <!-- HEADER -->
    <div class="modal-header text-white"
         style="background:linear-gradient(135deg,#eab308 0%,#facc15 100%);">
      <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Edit Fasilitas</h5>
      <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close">x</button>
    </div>

    <!-- FORM -->
    <form id="form-edit-fasilitas"
          action="{{ route('fasilitas.update', $fasilitas) }}"
          method="POST">
      @csrf
      @method('PUT')

      <div class="modal-body p-4">

        <div class="form-group mb-3">
          <label>Nama Fasilitas</label>
          <input type="text" name="nama_fasilitas" class="form-control"
                 value="{{ $fasilitas->nama_fasilitas }}" required maxlength="100">
          <small id="error-nama_fasilitas" class="error-text text-danger small"></small>
        </div>

      </div>

      <div class="modal-footer border-0">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save me-1"></i>Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ─── Script AJAX khusus modal ini ─── -->
<script>
$(function () {

  // inisiasi validate
  $('#form-edit-fasilitas').validate({
     rules:{
        nama_fasilitas:{ required:true, maxlength:100 },
        jumlah_fasilitas:{ required:true, digits:true, min:1 }
     },
     submitHandler: sendAjax
  });

  // fungsi kirim AJAX
  function sendAjax(form){
      $.ajax({
        url : form.action,
        type: form.method,
        data: $(form).serialize(),
        success(res){
          if(res.status){
            $('#myModal').modal('hide');
            Swal.fire('Berhasil', res.message, 'success');
            if(typeof tableFasilitas !== 'undefined'){
              tableFasilitas.ajax.reload();
            }
          }else{
            $('.error-text').text('');
            if(res.msgField){
               $.each(res.msgField, (n,v)=>$('#error-'+n).text(v[0]));
            }
            Swal.fire('Gagal', res.message ?? 'Terjadi kesalahan', 'error');
          }
        },
        error(xhr){
          Swal.fire('Error '+xhr.status, xhr.statusText, 'error');
        }
      });
      return false;
  }

});
</script>
@endempty

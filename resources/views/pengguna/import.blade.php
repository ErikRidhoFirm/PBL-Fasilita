<form id="form-import" action="{{ route('pengguna.import_ajax') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-lg w-50" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Import Data Pengguna</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Download Template</label>
            <a href="{{ asset('assets/excel/template_data_pengguna.xlsx') }}"
               class="btn btn-info btn-sm" download>
              <i class="fa fa-file-excel"></i> Download
            </a>
          </div>
          <div class="form-group">
            <label>Pilih File (.xlsx)</label>
            <input type="file" name="file_user" id="file_user" class="form-control" required>
            <small id="error-file_user" class="form-text text-danger"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </div>
    </div>
  </form>

  <script>
  $(function(){
    $("#form-import").validate({
      rules: {
        file_user: { required: true, extension: "xlsx" }
      },
      submitHandler: function(form) {
        var fd = new FormData(form);
        $.ajax({
          url: form.action,
          type: form.method,
          data: fd,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(res) {
            if (res.status) {
              $('#myModal').modal('hide');
              Swal.fire('Berhasil', res.message, 'success');
              tablePengguna.ajax.reload(null, false);
            } else {
              $('small.text-danger').text('');
              $.each(res.msgField || {}, function(f,v){
                $('#error-'+f).text(v[0]);
              });
              Swal.fire('Error', res.message, 'error');
            }
          },
          error: function(xhr) {
            if (xhr.status === 403 || xhr.status === 401) {
              window.location.href = '{{ route("login") }}';
            }
          }
        });
        return false;
      }
    });
  });
  </script>

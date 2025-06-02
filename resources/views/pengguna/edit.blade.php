<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Ubah Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="form-edit" action="{{ route('pengguna.update', $pengguna->id_pengguna) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="{{ $pengguna->username }}">
            <small id="error-username" class="form-text text-danger"></small>
          </div>
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $pengguna->nama }}">
            <small id="error-nama" class="form-text text-danger"></small>
          </div>
          <div class="form-group">
            <label>Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
            <small id="error-password" class="form-text text-danger"></small>
          </div>
          <div class="form-group">
            <label>Peran</label>
            <select name="id_peran" class="form-control">
              <option value="">Pilih peran...</option>
              @foreach($peran as $r)
                <option value="{{ $r->id_peran }}"
                  {{ $pengguna->id_peran==$r->id_peran?'selected':'' }}>
                  {{ $r->nama_peran }}
                </option>
              @endforeach
            </select>
            <small id="error-id_peran" class="form-text text-danger"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                username: { required:true, minlength:4, maxlength:50 },
                nama:     { required:true, minlength:3, maxlength:255 },
                password: { minlength:5 },
                id_peran: { required:true }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    dataType: 'json',
                    headers: { 'Accept': 'application/json' },
                    data: $(form).serialize(),
                    success: function(res) {
                        if (res.redirect) {
                            Swal.fire({
                                title: 'Peran Diubah',
                                text: res.message,
                                icon: 'info',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = res.redirect;
                            });
                            return;
                        }
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            tablePengguna.ajax.reload(null, false);
                        } else {
                            $('small.text-danger').text('');
                            $.each(res.msgField, function(f,v){
                                $('#error-'+f).text(v[0]);
                            });
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 403 || xhr.status === 401) {
                            window.location.href = '{{ route('login') }}';
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>


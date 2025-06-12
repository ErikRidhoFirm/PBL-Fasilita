<div class="modal-dialog modal-lg w-75">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Ubah Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="form-edit" action="{{ route('pengguna.update', $pengguna->id_pengguna) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label>Nomor Induk</label>
            <input type="text" name="no_induk" class="form-control" value="{{ $pengguna->no_induk }}">
            @if(isset($verificationInfo) && $verificationInfo['type'] !== 'Tidak Diketahui' && $verificationInfo['type'] !== 'Tidak Valid')
                <div class="alert alert-info mt-2 p-2">
                    <small>
                        <strong><i class="fas fa-info-circle"></i> Tipe Teridentifikasi:</strong> {{ $verificationInfo['type'] }}
                        <ul class="mb-0 pl-3">
                            @foreach($verificationInfo['data'] as $key => $value)
                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </small>
                </div>
            @else
                <small class="form-text text-muted mt-1">
                    Format Nomor Induk tidak dikenali sebagai NIM/NIDN/NIP.
                </small>
            @endif
            <small id="error-no_induk" class="form-text text-danger"></small>
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
            no_induk: { required:true, minlength:5, maxlength:20 },
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
                        $('.error-text').text('');  // clear all
                        $.each(res.msgField, function(f, msgs) {
                            $('#error-' + f).text(msgs[0]);
                        });
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function(xhr) {
                if (xhr.status === 422) {
                    let errs = xhr.responseJSON.msgField;
                    $('.error-text').text('');
                    $.each(errs, function(field, msgs) {
                    $('#error-' + field).text(msgs[0]);
                    });
                    // Ambil pesan pertama untuk Swal
                    let firstMsg = Object.values(errs)[0][0];
                    Swal.fire('Gagal', firstMsg, 'error');
                } else if (xhr.status === 403 || xhr.status === 401) {
                    window.location.href = '{{ route("login") }}';
                } else {
                    Swal.fire('Kesalahan Server', 'Tidak dapat menyimpan data.', 'error');
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
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>



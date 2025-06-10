<div class="modal-dialog modal-lg w-75">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Tambah Pengguna</h5>
            <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-tambah-pengguna" action="{{ route('pengguna.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                {{-- Peran --}}
                <div class="form-group">
                    <label>Peran</label>
                    <select name="id_peran" class="form-control">
                        <option value="">-- Pilih Peran --</option>
                        @foreach($peran as $r)
                            <option value="{{ $r->id_peran }}">{{ $r->nama_peran }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_peran" class="error-text form-text text-danger"></small>
                </div>
                {{-- Nomor Induk --}}
                <div class="form-group">
                    <label>Nomor Induk</label>
                    <input type="text" name="no_induk" class="form-control" value="">
                    <small id="error-no_induk" class="error-text form-text text-danger"></small>
                </div>
                {{-- Username --}}
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="">
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                {{-- Nama --}}
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="">
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                {{-- Password --}}
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#form-tambah-pengguna").validate({
          rules: {
    no_induk:   { required: true, minlength: 5 },
    id_peran:   { required: true },
    username:   { required: true, minlength: 4, maxlength: 50 },
    nama:       { required: true, minlength: 3, maxlength: 255 },
    password:   { required: true, minlength: 5 }
  },
  messages: {
    no_induk:   { required: "Nomor induk wajib diisi", minlength: "Minimal 5 karakter" },
    id_peran:   { required: "Harap pilih peran" }
  },
  submitHandler: function(form) {
    $.ajax({
      url: form.action,
      type: form.method,
      data: $(form).serialize(),
      dataType: 'json',
      success: function(response) {
        if (response.status) {
          $('#myModal').modal('hide');
          Swal.fire('Berhasil', response.message, 'success');
          tablePengguna.ajax.reload();
        } else {
          // bersihkan error sebelumnya
          $('.error-text').text('');
          // tampilkan semua pesan field
          $.each(response.msgField, function(field, msgs) {
            $('#error-' + field).text(msgs[0]);
          });
          Swal.fire('Gagal', response.message, 'error');
        }
      },
      error: function(xhr) {
        if (xhr.status === 422) {
          let errs = xhr.responseJSON.msgField;
          $('.error-text').text('');
          $.each(errs, function(field, msgs) {
            $('#error-' + field).text(msgs[0]);
          });
          Swal.fire('Gagal', 'Validasi gagal.', 'error');
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

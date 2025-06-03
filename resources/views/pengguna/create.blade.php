<div class="modal-dialog modal-lg w-50">
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
            username: {
                required: true,
                minlength: 4,
                maxlength: 50
            },
            nama: {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            password: {
                required: true,
                minlength: 5
            },
            id_peran: {
                required: true
            }
        },
        messages: {
            id_peran: {
                required: "Harap pilih peran"
            },
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        tablePengguna.ajax.reload();
                    } else {
                        // bersihkan error sebelumnya
                        $('.error-text').text('');
                        $.each(response.msgField, function(field, msgs) {
                            $('#error-' + field).text(msgs[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Server',
                        text: 'Tidak dapat menyimpan data.'
                    });
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

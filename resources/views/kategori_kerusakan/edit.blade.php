<div class="modal-dialog modal-lg w-50">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Data Kategori Kerusakan</h5>
            <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @empty($kategoriKerusakan)
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kategori_kerusakan') }}" class="btn btn-warning">Kembali</a>
            </div>
        @else
            <form action="{{ url('/kategori_kerusakan/update/' . $kategoriKerusakan->id_kategori_kerusakan) }}" method="POST" id="form-edit">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Form fields --}}
                    <div class="form-group">
                        <label>Kode Kategori Kerusakan</label>
                        <input type="text" name="kode_kerusakan" class="form-control" value="{{ $kategoriKerusakan->kode_kerusakan }}">
                        <small id="error-kode_kerusakan" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Kategori Kerusakan</label>
                        <input type="text" name="nama_kerusakan" class="form-control" value="{{ $kategoriKerusakan->nama_kerusakan }}">
                        <small id="error-nama_kerusakan" class="error-text form-text text-danger"></small>
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
        $("#form-edit").validate({
            rules: {
                kode_kerusakan: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                nama_kerusakan: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                }
            },
            messages: {
                kode_kerusakan: {
                    required: 'Kode kategori harus diisi',
                    minlength: 'Kode kategori minimal 3 karakter',
                    maxlength: 'Kode kategori maksimal 20 karakter'
                },
                nama_kerusakan: {
                    required: 'Nama kategori harus diisi',
                    minlength: 'Nama kategori minimal 3 karakter',
                    maxlength: 'Nama kategori maksimal 100 karakter'
                }
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
                            tablePeran.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            let msgErr = Object.values(response.msgField)[0][0];
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: msgErr
                            });
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

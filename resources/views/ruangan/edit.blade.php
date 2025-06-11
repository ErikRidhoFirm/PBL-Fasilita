@empty($ruangan)
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-danger">Data tidak ditemukan</div>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="mdi mdi-pencil"></i> Edit Ruangan</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                    data-bs-dismiss="modal">x</button>
            </div>

            <form id="form-ruangan-edit" class="ajax" action="{{ route('ruangan.update', $ruangan) }}" method="POST">
                @csrf
                @method('PUT') {{-- method spoofing to PUT --}}
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label>Kode Ruangan</label>
                        <input name="kode_ruangan" type="text" class="form-control" value="{{ $ruangan->kode_ruangan }}"
                            required maxlength="20">
                        <small class="error-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label>Nama Ruangan</label>
                        <input name="nama_ruangan" type="text" class="form-control" value="{{ $ruangan->nama_ruangan }}"
                            required maxlength="100">
                        <small class="error-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('#form-ruangan-edit').validate({
            rules: {
                kode_ruangan: {
                    required: true,
                    maxlength: 20
                },
                nama_ruangan: {
                    required: true,
                    maxlength: 100
                },
            },
            messages: {
                kode_ruangan: {
                    required: 'Kode ruangan harus diisi',
                    maxlength: 'Kode ruangan maksimal 20 karakter'
                },
                nama_ruangan: {
                    required: 'Nama ruangan harus diisi',
                    maxlength: 'Nama ruangan maksimal 100 karakter'
                },
            },
            submitHandler(form) {
                $.ajax({
                    url: form.action,
                    type: 'PUT',
                    data: $(form).serialize(),
                    success(res) {
                      if (res.status) {
                        
                        $('#myModal').modal('hide');
                        Swal.fire('Berhasil', res.message, 'success');
                        tableRuangan.ajax.reload(null, false);
                      } else {
                        $('.error-text').text('');
                        if (res.msgField) {
                            $.each(res.msgField, function(name, val) {
                                $('#error-' + name).text(val[0]);
                            });
                        }
                        let errMsg = Object.values(res.msgField)[0][0];
                        Swal.fire('Gagal', errMsg, 'error');
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
    </script>
@endempty

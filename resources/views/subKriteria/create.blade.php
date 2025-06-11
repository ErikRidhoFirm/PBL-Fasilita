<div class="modal-dialog modal-lg w-50" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                Tambah Skoring: {{ $kriteria->kode_kriteria }} — {{ $kriteria->nama_kriteria }}
            </h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>

        <form id="form-create-skoring" action="{{ route('skoring.store', $kriteria->id_kriteria) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Parameter</label>
                    <input type="text" name="parameter" class="form-control">
                    <small id="error-parameter" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nilai Referensi</label>
                    <input type="number" name="nilai_referensi" class="form-control">
                    <small id="error-nilai_referensi" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        $('#form-create-skoring').validate({
            rules: {
                parameter: {
                    required: true,
                    maxlength: 255
                },
                nilai_referensi: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                parameter: {
                    required: 'Parameter harus diisi',
                    maxlength: 'Parameter maksimal 255 karakter'
                },
                nilai_referensi: {
                    required: 'Nilai referensi harus diisi',
                    digits: 'Nilai referensi harus angka'
                }
            }
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: $(form).serialize(),
                    success: function(res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            window.tableSkoring[{{ $kriteria->id_kriteria }}]
                                .ajax.reload(null, false);
                        } else {
                            $('.error-text').text('');
                            $.each(res.msgField, function(field, msgs) {
                                $('#error-' + field).text(msgs[0]);
                            });
                            let msgErr = Object.values(res.msgField)[0][0];
                            Swal.fire('Error', msgErr, 'error');
                        }
                    },
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

{{-- resources/views/gedung/edit.blade.php --}}
@empty($gedung)
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-danger">Data tidak ditemukan.</div>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">

            <!-- HEADER -->
            <div class="modal-header text-white" style="background:linear-gradient(135deg,#2563eb 0%,#4688ff 100%);">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Edit Gedung</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal">X</button>
            </div>

            <!-- FORM -->
            <form id="form-edit-gedung" action="{{ route('gedung.update', $gedung) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body p-4">

                    <div class="form-group mb-3">
                        <label>Kode Gedung</label>
                        <input type="text" name="kode_gedung" class="form-control" value="{{ $gedung->kode_gedung }}"
                            required maxlength="10">
                        <small id="error-kode_gedung" class="error-text text-danger small"></small>
                    </div>

                    <div class="form-group">
                        <label>Nama Gedung</label>
                        <input type="text" name="nama_gedung" class="form-control" value="{{ $gedung->nama_gedung }}"
                            required maxlength="100">
                        <small id="error-nama_gedung" class="error-text text-danger small"></small>
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
        $(function() {

            // inisiasi validate
            $('#form-edit-gedung').validate({
                rules: {
                    kode_gedung: {
                        required: true,
                        maxlength: 10
                    },
                    nama_gedung: {
                        required: true,
                        maxlength: 100
                    }
                },
                messages: {
                    kode_gedung: {
                        required: 'Kode gedung harus diisi',
                        maxlength: 'Kode gedung maksimal 10 karakter'
                    },
                    nama_gedung: {
                        required: 'Nama gedung harus diisi',
                        maxlength: 'Nama gedung maksimal 100 karakter'
                    }
                },
                // fungsi kirim AJAX
                submitHandler(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method, // POST (spoof PUT)
                        data: $(form).serialize(),
                        success(res) {
                            if (res.status) {
                                $('#myModal').modal('hide');
                                Swal.fire('Berhasil', res.message, 'success');
                                window.tableGedung.ajax.reload();
                            } else {
                                // tampilkan pesan field error (jika backend kirim msgField)
                                $('.error-text').text('');
                                if (res.msgField) {
                                    $.each(res.msgField, (n, v) => $('#error-' + n).text(v[0]));
                                }
                                let errMsg = Object.values(res.msgField)[0][0];
                                Swal.fire('Gagal', errMsg, 'error');
                            }
                        }
                    });
                    return false; // cegah submit normal
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
            })
        });
    </script>
@endempty

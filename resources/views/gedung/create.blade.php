{{-- Modal Tambah Gedung --}}
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">

        {{-- HEADER --}}
        <div class="modal-header text-white bg-gradient"
            style="background:linear-gradient(135deg,#2563eb 0%,#4688ff 100%);">
            <h5 class="modal-title">
                <i class="bi bi-building me-2"></i> Tambah Gedung
            </h5>
            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close">X</button>
        </div>

        {{-- FORM --}}
        <form id="form-gedung" action="{{ url('/gedung/store') }}" method="POST">
            @csrf
            <div class="modal-body p-4">

                <div class="form-floating mb-3">
                    <label>Kode Gedung</label>
                    <input type="text" name="kode_gedung" class="form-control" placeholder="Kode Gedung" required>
                    <small id="error-kode_gedung" class="error-text text-danger small"></small>
                </div>

                <div class="form-floating mb-3">
                    <label>Nama Gedung</label>
                    <input type="text" name="nama_gedung" class="form-control" placeholder="Nama Gedung" required>
                    <small id="error-nama_gedung" class="error-text text-danger small"></small>
                </div>
            </div>

            <div class="modal-footer border-0 justify-content-end d-flex between">
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- VALIDATION + AJAX --}}
<script>
    $(function() {
        $('#form-gedung').validate({
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
            }
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');

                            // reload DataTables Gedung
                            if (typeof tableGedung !== 'undefined') {
                                tableGedung.ajax.reload();
                            }
                        } else {
                            // hapus pesan lama, tampilkan yang baru
                            $('.error-text').text('');
                            if (res.msgField) {
                                $.each(res.msgField, function(name, val) {
                                    $('#error-' + name).text(val[0]);
                                });
                            }
                            let errMsg = Object.values(res.msgField)[0][0];
                            Swal.fire('Gagal', errMsg, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error ' + xhr.status, xhr.statusText, 'error');
                    }
                });
                return false; // cegah submit form normal
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger small');
                element.closest('.form-group').find('.error-text').html(error);
            },
            highlight: function(el) {
                $(el).addClass('is-invalid');
            },
            unhighlight: function(el) {
                $(el).removeClass('is-invalid');
            }
        });
    });
</script>

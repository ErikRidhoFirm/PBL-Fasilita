{{-- resources/views/gedung/create-ruangan.blade.php --}}
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="mdi mdi-plus-box"></i> Tambah Ruangan</h5>
            <button type="button" class="btn-close btn-close-white" data-dismiss="modal">×</button>
        </div>

        <form id="form-ruangan-create" action="{{ route('lantai.ruangan.store', $lantai) }}"
              method="POST">
            @csrf
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label>Kode Ruangan</label>
                    <input name="kode_ruangan" type="text" class="form-control" required maxlength="20">
                    <small id="error-kode_ruangan" class="error-text text-danger small"></small>
                </div>
                <div class="mb-3">
                    <label>Nama Ruangan</label>
                    <input name="nama_ruangan" type="text" class="form-control" required maxlength="100">
                    <small id="error-nama_ruangan" class="error-text text-danger small"></small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</div>

<script>
$(function() {
    // Inisialisasi jQuery Validate
    $('#form-ruangan-create').validate({
        rules: {
            kode_ruangan: {
                required: true,
                maxlength: 20
            },
            nama_ruangan: {
                required: true,
                maxlength: 100
            }
        },
        messages: {
            kode_ruangan: {
                required: 'Kode ruangan harus diisi',
                maxlength: 'Kode ruangan maksimal 20 karakter'
            },
            nama_ruangan: {
                required: 'Nama ruangan harus diisi',
                maxlength: 'Nama ruangan maksimal 100 karakter'
            }
        },    // <-- koma penting di sini
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                dataType: 'json',
                data: $(form).serialize(),
                success: function(res) {
                    if (res.status) {
                        // Tutup dan bersihkan modal
                        $('#myModal').modal('hide').html('');
                        // Reload DataTable global
                        window.tableRuangan.ajax.reload(null, false);
                        // Tampilkan popup sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        // Reset pesan error
                        $('.error-text').text('');
                        if (res.msgField) {
                            $.each(res.msgField, function(name, msgs) {
                                $('#error-' + name).text(msgs[0]);
                            });
                            // Tampilkan popup error dengan field pertama
                            let first = Object.values(res.msgField)[0][0];
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: first
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message || 'Terjadi kesalahan'
                            });
                        }
                    }
                },
                error: function(xhr) {
                    // Jika validasi jQuery saja
                    if (xhr.status !== 422) {
                        Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                    }
                }
            });
            return false; // cegah submit normal
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            // letakkan di <small class="error-text"> yang sesuai
            let field = element.attr('name');
            $('#error-' + field).text(error.text());
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

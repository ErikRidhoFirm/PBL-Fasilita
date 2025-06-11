@empty($lantai)
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-danger mb-0">Data tidak ditemukan.</div>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-pencil-box me-2"></i>Edit Lantai</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" data-dismiss="modal">X</button>
            </div>

            <form id="form-lantai" class="ajax" action="{{ route('lantai.update', $lantai) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nomor Lantai</label>
                        <input type="text" name="nomor_lantai" class="form-control" value="{{ $lantai->nomor_lantai }}"
                            required maxlength="10">
                        <small class="error-text text-danger"></small>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(function() {
            $('#form-lantai').on('submit', function(e) {
                e.preventDefault();
                var $f = $(this),
                    m = $f.find('input[name="_method"]').val() || this.method;
                $.ajax({
                    url: this.action,
                    type: m.toUpperCase(), // akan jadi "PUT"
                    data: $f.serialize(),
                    success(res) {
                        $('#myModal').modal('hide');
                        Swal.fire('Berhasil', res.message, 'success');
                        if (typeof tableLantai !== 'undefined') tableLantai.ajax.reload();
                    },
                    error(xhr) {
                        Swal.fire('Error ' + xhr.status, xhr.statusText, 'error');
                    }
                });
            });
        });
    </script>
@endempty

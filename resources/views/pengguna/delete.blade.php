<div class="modal-dialog modal-lg w-50">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title text-danger">Hapus Pengguna</h5>
            <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @empty($pengguna)
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/pengguna') }}" class="btn btn-warning">Kembali</a>
            </div>
        @else
        <form action="{{ route('pengguna.destroy', $pengguna->id_pengguna) }}" method="POST" id="form-delete">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <h3 class="mb-4">Yakin ingin menghapus data pengguna ini?</h3>
                <table class="table table-striped">
                    <tr>
                        <th>Username</th>
                        <td>{{ $pengguna->username }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $pengguna->nama }}</td>
                    </tr>
                    <tr>
                        <th>Peran</th>
                        <td>{{ $pengguna->peran->nama_peran ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
        @endempty
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#form-delete").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: this.action,
                type: this.method,
                data: $(this).serialize(),
                success: function (res) {
                    if (res.status) {
                        $('#myModal').modal('hide');
                        Swal.fire('Terhapus', res.message, 'success');
                        tablePengguna.ajax.reload();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 403 || xhr.status === 401) {
                        window.location.href = '{{ route('login') }}';
                    }
                }
            });
        });
    });
</script>

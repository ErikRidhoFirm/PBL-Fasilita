<div class="modal-dialog modal-lg w-50" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Detail Data Kriteria</h5>
      <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    @empty($kriteria)
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
          Data yang anda cari tidak ditemukan
        </div>
        <a href="{{ route('kriteria.index') }}" class="btn btn-warning">Kembali</a>
      </div>
    @else
      <div class="modal-body">
        <table class="table table-striped">
          <tr>
            <th>Kode Kriteria</th>
            <td>{{ $kriteria->kode_kriteria }}</td>
          </tr>
          <tr>
            <th>Nama Kriteria</th>
            <td>{{ $kriteria->nama_kriteria }}</td>
          </tr>
          <tr>
            <th>Bobot</th>
            <td>{{ $kriteria->bobot_kriteria }}</td>
          </tr>
          <tr>
            <th>Tipe Kriteria</th>
            <td>{{ ucfirst($kriteria->tipe_kriteria) }}</td>
          </tr>
          <tr>
            <th>Deskripsi</th>
            <td>{{ $kriteria->deskripsi ?? '-' }}</td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
      </div>
    @endempty
  </div>
</div>

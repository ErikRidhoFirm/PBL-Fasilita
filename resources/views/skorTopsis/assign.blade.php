<div class="modal-dialog modal-lg w-50">
  <div class="modal-content">
    {{-- Header --}}
    <div class="modal-header bg-primary text-white">
      <h5 class="modal-title">
        <i class="mdi mdi-account-wrench-outline me-2"></i>
        Tugaskan Teknisi
      </h5>
      <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
    </div>

    {{-- Body --}}
    <div class="modal-body px-4 py-3">
      {{-- Tampilkan Foto Fasilitas --}}
      @if($lap->path_foto)
        <div class="text-center mb-4">
          <img src="{{ asset('storage/' . $lap->path_foto) }}"
               alt="Foto Fasilitas"
               class="img-fluid rounded"
               style="max-height: 200px;">
          <p class="text-muted mt-2 mb-0">Foto Fasilitas</p>
        </div>
      @else
        <div class="alert alert-info mb-4">
          <i class="mdi mdi-information-outline"></i> Tidak ada foto fasilitas
        </div>
      @endif
      {{-- Tampilkan Data LaporanFasilitas dan Laporan --}}
    <div class="table-responsive mb-4">
        <table class="table table-striped mb-0">
        <tbody>
          <tr>
            <th style="width: 30%;">Nama Fasilitas</th>
            <td>{{ $lap->fasilitas->nama_fasilitas }}</td>
          </tr>
          <tr>
            <th>Gedung</th>
            <td>{{ optional($lap->laporan->gedung)->nama_gedung ?? '-' }}</td>
          </tr>
          <tr>
            <th>Lantai</th>
            <td>{{ optional($lap->laporan->lantai)->nomor_lantai ?? '-' }}</td>
          </tr>
          <tr>
            <th>Ruangan</th>
            <td>{{ optional($lap->laporan->ruangan)->nama_ruangan ?? '-' }}</td>
          </tr>
          <tr>
            <th>Deskripsi Kerusakan</th>
            <td>{{ $lap->deskripsi ?? '-' }}</td>
          </tr>
          <tr>
            <th>Pelapor</th>
            <td>{{ $lap->laporan->pengguna->nama }} <small class="text-muted">({{ $lap->laporan->pengguna->email }})</small></td>
          </tr>
          <tr>
            <th>Tanggal Laporan</th>
            <td>{{ $lap->laporan->created_at->translatedFormat('d F Y H:i') }}</td>
          </tr>
        </tbody>
      </table>
      </div>

      {{-- Form Input Teknisi --}}
      <form id="form-assign">
        @csrf

        <div class="form-group mb-4">
            <label for="teknisi_id" class="form-label fw-semibold">Pilih Teknisi</label>
            <select name="teknisi_id" id="teknisi_id" class="form-control" required>
                <option value="" selected>-- Pilih Teknisi --</option>
                @foreach($teknisis as $t)
                    <option value="{{ $t->id_pengguna }}">
                    {{ $t->nama }}
                    @if($t->pending_tasks_count)
                        ({{ $t->pending_tasks_count }} tugas belum selesai)
                    @else
                        (Tidak ada tugas)
                    @endif
                    </option>
                @endforeach
            </select>
            <small id="error-teknisi_id" class="error-text form-text text-danger"></small>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success">
            <i class="mdi mdi-send me-1"></i> Tugaskan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
$(function(){
  $('#form-assign').on('submit', function(e){
    e.preventDefault();
    let url = "{{ route('skorTopsis.assign', $sk->id_skor_topsis) }}";

    $.ajax({
      url: url,
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json'
    })
    .done(res => {
      // tutup modal
      if (assignModal) assignModal.hide();

      // SweetAlert di tengah
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: res.message,
        timer: 1500,
        showConfirmButton: false
      });

      // reload datatable tanpa memaksakan refresh
      $('#tbl-prioritas').DataTable().ajax.reload(null, false);
    })
    .fail(xhr => {
      let msg = 'Gagal menugaskan';
      if (xhr.status === 422) {
        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
      }
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: msg
      });
    });
  });
});
</script>

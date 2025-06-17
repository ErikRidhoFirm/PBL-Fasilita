<div class="modal-dialog modal-lg">
  <div class="modal-content">

    <div class="modal-header bg-primary text-white">
      <h5 class="modal-title">
        <i class="fas fa-file-alt me-2"></i> Detail & Perbaikan
      </h5>
       <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
    </div>

    <form id="form-perbaikan" enctype="multipart/form-data">
      @csrf

      <div class="modal-body">
        {{-- Foto awal & detail laporan --}}
        <div class="row mb-4">
          <div class="col-md-4">
            <img src="{{ asset('storage/' . $lapfas->path_foto) }}"
                 class="img-fluid rounded" alt="Foto Fasilitas">
          </div>
          <div class="col-md-8">
            <h6>{{ $lapfas->fasilitas->nama_fasilitas }}</h6>
            <p><strong>Jenis Kerusakan:</strong> {{ $lapfas->tingkatKerusakan->parameter }}</p>
            <p><strong>Dampak yang dirasakan pengguna:</strong> {{ $lapfas->dampakPengguna->parameter }}</p>
            <p><strong>Pelapor:</strong> {{ $lapfas->laporan->pengguna->nama }}</p>
            <p><strong>Lokasi:</strong>
              {{ $lapfas->laporan->gedung->nama_gedung }} /
              Lantai {{ $lapfas->laporan->lantai->nomor_lantai }} /
              {{ $lapfas->laporan->ruangan->nama_ruangan }}
            </p>
            <p><strong>Status Saat Ini:</strong>
              <span class="badge bg-info">{{ $lapfas->status->nama_status }}</span>
            </p>
          </div>
        </div>

        {{-- Penugasan --}}
        @if($lapfas->penugasan)
        <div class="card mb-4">
          <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Penugasan Teknisi</h6>
          </div>
          <div class="card-body">
            <p><strong>Teknisi:</strong> {{ $lapfas->penugasan->teknisi->nama }}</p>
            <p><strong>Tanggal Ditugaskan:</strong>
               {{ $lapfas->penugasan->created_at->format('d M Y H:i') }}
            </p>
          </div>
        </div>
        @endif

        {{-- Input hasil perbaikan --}}
        <div class="mb-3">
          <label class="form-label">Foto Hasil Perbaikan</label>
          <input type="file" name="foto_perbaikan" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Jenis Perbaikan</label>
            <select name="jenis_perbaikan" class="form-select" required>
                <option value="" disabled selected>Pilih jenis perbaikan</option>
                <option value="penggantian">Penggantian</option>
                <option value="perbaikan">Perbaikan</option>
            </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi Perbaikan</label>
          <textarea name="deskripsi_perbaikan" class="form-control" rows="3" required></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Selesai</button>
      </div>
    </form>
  </div>
</div>

<script>
  $(function(){
    $('#form-perbaikan').on('submit', function(e){
      e.preventDefault();
      let fd = new FormData(this);
      let modal = $('#modalContainer'); // Get modal reference

      $.ajax({
        url: '{{ route("laporanFasilitas.perbaikan.submit", $lapfas->id_laporan_fasilitas) }}',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json'
      }).done(res => {
        // Show SweetAlert on success
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: res.message,
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          // Close modal after alert
          window.location.href = '{{ route("penugasan.index") }}';
          bootstrap.Modal.getInstance(modal[0]).hide();
          // Reload DataTable
          if (typeof window.parent.table !== 'undefined') {
            window.parent.table.ajax.reload(null, false);
          }
        });
      }).fail(xhr => {
        if (xhr.status === 422) {
          const errs = Object.values(xhr.responseJSON.errors)
                           .flat()
                           .join('\n');
          Swal.fire({
            icon: 'error',
            title: 'Validasi Error',
            text: errs
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan. Silakan coba lagi.'
          });
        }
      });
    });
  });
</script>

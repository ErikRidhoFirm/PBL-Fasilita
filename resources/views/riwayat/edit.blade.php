<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header bg-warning text-dark">
      <h5 class="modal-title">
        <i class="fas fa-edit me-2"></i>Edit Status: {{ $lapfas->fasilitas->nama_fasilitas }}
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form id="form-edit-riwayat" method="POST" action="{{ route('riwayat.update', $lapfas->id_laporan_fasilitas) }}">
      @csrf
      @method('PUT')
      <input type="hidden" name="riwayat_id" value="{{ $riwayat->id_riwayat_laporan_fasilitas }}">

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Status Saat Ini:</label>
          <input type="text" class="form-control" value="{{ $riwayat->status->nama_status }}" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Pilih Status Baru:</label>
          <select name="new_status_id" class="form-select" required>
            <option value="" disabled selected>-- Pilih Status --</option>
            @foreach($statuses as $st)
              <option value="{{ $st->id_status }}"
                {{ $st->id_status == $riwayat->id_status ? 'disabled' : '' }}>
                {{ $st->nama_status }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Catatan (opsional):</label>
          <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
        </div>
      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
    </form>
  </div>
</div>

{{-- AJAX submit --}}
<script>
$('#form-edit-riwayat').on('submit', function(e){
  e.preventDefault();
  let form = $(this);
  $.ajax({
    url: form.attr('action'),
    method: form.find('input[name="_method"]').val() || form.attr('method'),
    data: form.serialize(),
    success(res){
      $('#modalContainer').modal('hide');
      $('#tabel-laporan').DataTable().ajax.reload();
      alert(res.message);
    },
    error(xhr){
      alert(xhr.responseJSON.message || 'Terjadi kesalahan.');
    }
  });
});
</script>

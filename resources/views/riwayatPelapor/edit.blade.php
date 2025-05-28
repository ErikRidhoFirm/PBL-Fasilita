{{-- resources/views/riwayatPelapor/edit.blade.php --}}
@php
  // $lf = LaporanFasilitas instance
  $gedung   = $lf->fasilitas->ruangan->lantai->gedung;
  $lantai   = $lf->fasilitas->ruangan->lantai;
  $ruangan  = $lf->fasilitas->ruangan;
  $fasilitas= $lf->fasilitas;
  $kategori = $lf->kategoriKerusakan;
@endphp
<div class="modal-content">
  <div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
      <i class="mdi mdi-pencil-box me-2"></i>Edit Laporan
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>

  <form id="form-edit-laporan"
        action="{{ route('riwayatPelapor.update', $lf->id_laporan_fasilitas) }}"
        method="POST"
        enctype="multipart/form-data">
    @csrf @method('PUT')

      <div class="modal-body">

        {{-- Tampilkan semua data sebagai teks (disabled) --}}
        <div class="row mb-2">
          <div class="col-sm-6">
            <label class="form-label">Gedung</label>
            <input type="text" class="form-control" value="{{ $gedung->nama_gedung }}" disabled>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Lantai</label>
            <input type="text" class="form-control" value="{{ $lantai->nomor_lantai }}" disabled>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-sm-6">
            <label class="form-label">Ruangan</label>
            <input type="text" class="form-control"
                   value="{{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }}"
                   disabled>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Fasilitas</label>
            <input type="text" class="form-control"
                   value="{{ $fasilitas->nama_fasilitas }}" disabled>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori Kerusakan</label>
          <input type="text" class="form-control"
                 value="{{ $kategori->nama_kategori_kerusakan }}" disabled>
        </div>

        {{-- Foto saat ini --}}
        @if($lf->path_foto)
          <div class="mb-3 text-center">
            <label class="form-label d-block">Foto Saat Ini</label>
            <img src="{{ asset('storage/'.$lf->path_foto) }}"
                 class="img-fluid rounded" style="max-height:200px;">
          </div>
        @endif

        {{-- Hanya ini yang boleh diedit: foto baru --}}
        <div class="mb-3">
          <label class="form-label">Unggah Foto Baru (opsional)</label>
          <input type="file" name="path_foto"
                 class="form-control @error('path_foto') is-invalid @enderror">
          @error('path_foto')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Hanya ini yang boleh diedit: deskripsi --}}
        <div class="mb-3">
          <label class="form-label">Deskripsi Kerusakan</label>
          <textarea name="deskripsi"
                    rows="4"
                    class="form-control @error('deskripsi') is-invalid @enderror"
                    required>{{ old('deskripsi', $lf->deskripsi) }}</textarea>
          @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div> {{-- /.modal-body --}}

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="mdi mdi-close me-1"></i> Batal
      </button>
      <button type="submit" class="btn btn-warning">
        <i class="mdi mdi-content-save me-1"></i> Simpan
      </button>
    </div>
  </form>
</div>

{{-- JS untuk AJAX submit --}}
<script>
$(document).ready(function() {
  // Delegate form submission to the document level
  $(document).on('submit', '#form-edit-laporan', function(e) {
    e.preventDefault();
    const $form = $(this);
    const submitBtn = $form.find('button[type="submit"]');
    const originalText = submitBtn.html();

    // Show loading state
    submitBtn.prop('disabled', true).html(`
      <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
      Menyimpan...
    `);

    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function(response) {
        $('#myModal').modal('hide');
        Swal.fire({
          title: 'Berhasil!',
          text: response.message,
          icon: 'success'
        }).then(() => {
          window.location.reload();
        });
      },
      error: function(xhr) {
        submitBtn.prop('disabled', false).html(originalText);

        // Clear previous errors
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();

        // Show new errors
        const errors = xhr.responseJSON?.errors || {};
        Object.keys(errors).forEach(field => {
          const $input = $form.find(`[name="${field}"]`);
          $input.addClass('is-invalid')
                .after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
        });
      }
    });
  });
});
</script>

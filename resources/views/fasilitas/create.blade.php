<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">

    {{-- HEADER --}}
    <div class="modal-header bg-primary text-white">
      <h5 class="modal-title"><i class="mdi mdi-plus-box"></i> Tambah Fasilitas</h5>
      <button type="button"
              class="btn-close btn-close-white"
              data-dismiss="modal">X</button>
    </div>

    {{-- FORM --}}
    <form id="form-fasilitas-create" class="ajax"
          action="{{ route('ruangan.fasilitas.store', $ruangan) }}"
          method="POST">
      @csrf
      <div class="modal-body p-4">

        {{-- KATEGORI --}}
        <div class="mb-3">
          <label class="form-label">Kategori</label>
          <select name="id_kategori" class="form-control" required>
            <option value="">— Pilih Kategori —</option>
            @foreach($kategories as $kat)
              <option value="{{ $kat->id_kategori }}">
                {{ $kat->nama_kategori }}
              </option>
            @endforeach
          </select>
          <small class="error-text text-danger"></small>
        </div>

        {{-- NAMA --}}
        <div class="mb-3">
          <label class="form-label">Nama Fasilitas</label>
          <input name="nama_fasilitas"
                 type="text"
                 class="form-control"
                 required
                 maxlength="100">
          <small class="error-text text-danger"></small>
        </div>

      </div>
      <div class="modal-footer border-0">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>

  </div>
</div>

<script>
$('#form-fasilitas-create').validate({
  rules:{
    id_kategori:      { required:true },
    nama_fasilitas:   { required:true, maxlength:100 },
    jumlah_fasilitas: { required:true, number:true, min:1 }
  },
  submitHandler(form){
    $.ajax({
      url:  form.action,
      type: form.method,
      data: $(form).serialize(),
      success(res){
        $('#myModal').modal('hide');
        Swal.fire('Berhasil', res.message, 'success');
        tableFasilitas.ajax.reload(null,false);
      },
      error(xhr){
        // tampilkan kesalahan validasi
        const err = xhr.responseJSON.errors || {};
        Object.keys(err).forEach(f => {
          $(`#form-fasilitas-create [name="${f}"]`)
            .next('.error-text').text(err[f][0])
        });
      }
    });
    return false;
  }
});
</script>

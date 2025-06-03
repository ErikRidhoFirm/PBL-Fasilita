<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Feedback untuk “{{ $lf->fasilitas->nama_fasilitas }}”</h5>
      <button type="button" class="btn-close" data-dismiss="modal"></button>
    </div>
    <form id="form-feedback">
      @csrf
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Rating</label>
          <div id="starRating">
            @for($i=1;$i<=5;$i++)
              <i class="mdi mdi-star-outline star text-muted" data-value="{{ $i }}"
                 style="font-size:1.5rem; cursor:pointer;"></i>
            @endfor
            <input type="hidden" name="nilai" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Komentar (opsional)</label>
          <textarea name="komentar" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim Feedback</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Bintang interaktif
  $('#starRating .star').on('click', function(){
    let v = $(this).data('value');
    $('input[name=nilai]').val(v);

    $('#starRating .star').each(function(){
      let val = $(this).data('value');
      if (val <= v) {
        // beri shape solid dan warna kuning
        $(this)
          .removeClass('mdi-star-outline text-muted')
          .addClass('mdi-star text-warning');
      } else {
        // outline dan warna abu
        $(this)
          .removeClass('mdi-star text-warning')
          .addClass('mdi-star-outline text-muted');
      }
    });
  });

  // AJAX submit
  $('#form-feedback').submit(function(e){
    e.preventDefault();
    let url = "{{ route('feedback.store', $lf->id_laporan_fasilitas) }}";
    $.ajax({
      url, method:'POST',
      data: $(this).serialize(),
      dataType:'json'
    })
    .done(res=>{
      Swal.fire({
        icon:'success',
        title:'Sukses',
        text:res.message,
        timer:1500,
        showConfirmButton:false
      }).then(()=> location.reload());
    })
    .fail(xhr=>{
      let errs = Object.values(xhr.responseJSON.errors||{}).flat().join('\n');
      Swal.fire('Error', errs,'error');
    });
  });
</script>

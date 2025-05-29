<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Edit Feedback</h5>
      <button type="button" class="btn-close" data-dismiss="modal"></button>
    </div>
    <form id="form-feedback-edit">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Rating</label>
          <div id="starRatingEdit">
            @for($i=1; $i<=5; $i++)
              <i class="mdi {{ $i <= $penilaian->nilai ? 'mdi-star' : 'mdi-star-outline' }} star-edit"
                 data-value="{{ $i }}"
                 style="font-size:1.5rem;cursor:pointer; {{ $i <= $penilaian->nilai ? 'color:#FFC107' : '' }}"></i>
            @endfor
            <input type="hidden" name="nilai" value="{{ $penilaian->nilai }}" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Komentar (opsional)</label>
          <textarea name="komentar" class="form-control" rows="3">{{ $penilaian->komentar }}</textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  // bintang interaktif untuk edit
 $('#starRatingEdit .star-edit').on('click', function(){
    const v = $(this).data('value');
    $('input[name=nilai]').val(v);

    $('#starRatingEdit .star-edit').each(function(){
      const $star = $(this);
      if ($star.data('value') <= v) {
        // bintang terisi
        $star
          .removeClass('mdi-star-outline text-muted')
          .addClass('mdi-star text-warning');
      } else {
        // bintang kosong
        $star
          .removeClass('mdi-star text-warning')
          .addClass('mdi-star-outline text-muted');
      }
    });
  });

  // AJAX submit edit
  $('#form-feedback-edit').submit(function(e){
    e.preventDefault();
    $.ajax({
      url: "{{ route('feedback.update', $penilaian->id_penilaian_pengguna) }}",
      method: 'PUT',
      data: $(this).serialize(),
      dataType: 'json'
    }).done(res=>{
      Swal.fire('Sukses', res.message, 'success')
        .then(()=> location.reload());
    }).fail(xhr=>{
      let errs = Object.values(xhr.responseJSON.errors||{}).flat().join('\n');
      Swal.fire('Error', errs,'error');
    });
  });
</script>

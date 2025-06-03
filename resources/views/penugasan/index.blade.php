@extends('layouts.main')
@section('content')

<div class="card">
  <div class="card-body">
    <h4>Daftar Tugas Teknisi</h4>
    <div class="table-responsive">
      <table id="tbl-tugas" class="table">
        <thead>
          <tr>
            <th>No</th><th>Fasilitas</th><th>Pelapor</th><th>Status</th><th>Aksi</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

{{-- Modal container untuk memuat detail --}}
<div id="modalContainer" class="modal fade" tabindex="-1" aria-hidden="true"></div>

@endsection

@push('js')
<script>
$(function(){
  // Deklarasikan table di scope global
  window.table = $('#tbl-tugas').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route("penugasan.list") !!}',
    columns:[
      { data:'DT_RowIndex', orderable:false, searchable:false },
      { data:'fasilitas' },
      { data:'pelapor' },
      { data:'status' },
      { data:'aksi', orderable:false, searchable:false },
    ]
  });

  // Ketika tombol detail diklik, load view show ke dalam modal
  $('#tbl-tugas').on('click', '.btn-detail', function(){
    let url = $(this).data('url');
    $('#modalContainer').load(url, function(){
      let modal = new bootstrap.Modal(this);
      modal.show();
    });
  });

  // Fungsi untuk menangani event dari child window
  window.refreshTable = function() {
    table.ajax.reload(null, false);
  };
});
</script>
@endpush

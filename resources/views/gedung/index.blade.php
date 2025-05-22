@extends('layouts.main')
@section('content')
  <style>
    .btn-pilih {
  border-radius: 8px;      /* sudut siku */
  width: 100px;                     /* lebar tetap */
  height: 40px;                     /* tinggi tetap */
  padding: 0;                       /* reset padding */
  display: inline-flex;             /* agar centering mudah */
  align-items: center;              /* center vertikal */
  justify-content: center;          /* center horizontal */
  font-weight: 600;
}
  </style>
  <div class="w-100 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title my-5 w-25">Data Gedung</h3>
            <div>
                <button class="btn btn-danger btn-sm mr-2" style="min-width: 80px; height: 40px;">
                    <a href="{{ route('gedung.export_pdf') }}"
                      class="text-white text-decoration-none d-flex align-items-center justify-content-center w-100 h-100"
                      target="_blank">
                        <i class="fa fa-file-pdf mr-1"></i> PDF
                    </a>
                </button>
                <button class="btn btn-success btn-sm mr-2" onclick="modalAction('{{ route('gedung.import') }}')" style="min-width: 120px; height: 40px;">
                    <i class="fa fa-file-import"></i> Import
                </button>
                <button class="btn btn-primary btn-sm" onclick="modalAction('{{ route('gedung.create') }}')"
                        style="min-width: 120px; height: 40px;">
                    Tambah Gedung
                </button>
            </div>
        </div>

        <table class="table table-hover table-striped" id="table-gedung">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Gedung</th>
              <th>Nama Gedung</th>    {{-- kolom baru --}}
              <th>Aksi</th>
              <th>Pilih</th>
            </tr>
          </thead>
        </table>

      </div>
    </div>
  </div>

  <div id="myModal" class="modal fade"></div>
@endsection

@push('js')
<script>
  function modalAction(url=''){
    $('#myModal').load(url, ()=> $('#myModal').modal('show'));
  }

  let tableGedung = $('#table-gedung').DataTable({
    processing: true,
    serverSide: true,
    ajax: { url: "{{ route('gedung.list') }}", type: 'GET' },
    columns: [
      { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false },
      { data:'kode_gedung',  name:'kode_gedung' },
      { data:'nama_gedung',  name:'nama_gedung' },
      { data:'aksi',         name:'aksi',    orderable:false, searchable:false },
      { data:'pilih',        name:'pilih',   orderable:false, searchable:false },
    ]
  });
</script>
@endpush

@extends('layouts.main')

@section('content')
  {{-- Breadcrumb --}}
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
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('gedung.index') }}">Gedung</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        {{ $gedung->nama_gedung }}
      </li>
    </ol>
  </nav>

  <div class="card">
  <div class="card-body">
    <h4 class="card-title">Lantai — {{ $gedung->nama_gedung }}</h4>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="card-title my-5 w-25">Data Lantai</h3>
  <div class="d-flex">
    <!-- Tombol Export PDF -->
    <a href="{{ route('gedung.lantai.export_pdf', $gedung->id_gedung) }}"
       class="btn btn-danger btn-sm mr-2 d-flex align-items-center justify-content-center"
       style="min-width: 80px; height: 40px;" target="_blank">
      <i class="fa fa-file-pdf mr-1"></i> PDF
    </a>

    <!-- Tombol Import -->
    <button class="btn btn-success btn-sm mr-2 d-flex align-items-center justify-content-center"
            style="min-width: 100px; height: 40px;"
            onclick="modalAction('{{ route('gedung.lantai.import', $gedung->id_gedung) }}')">
      <i class="fa fa-file-import mr-1"></i> Import
    </button>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
            style="min-width: 120px; height: 40px;"
            onclick="modalAction('{{ route('gedung.lantai.create', $gedung->id_gedung) }}')">Tambah Lantai
    </button>
  </div>
</div>


    <table class="table table-hover table-striped" id="table-lantai">
      <thead>
        <tr>
          <th>No</th>
          <th>Nomor Lantai</th>
          <th>Aksi</th>
          <th>Pilih</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- modal wrapper --}}
<div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
@endsection

@push('js')
<script>
  // helper untuk load modal via AJAX
  function modalAction(url){
    $('#myModal').load(url, function(){
      $(this).modal('show');
    });
  }

  // jadikan window.tableLantai global
  window.tableLantai = $('#table-lantai').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('gedung.lantai.list',$gedung) }}",
      type: 'GET'
    },
    columns: [
      { data:'DT_RowIndex', orderable:false, searchable:false },
      { data:'nomor_lantai' },
      { data:'aksi', orderable:false, searchable:false },
      { data:'pilih', orderable:false, searchable:false },
    ]
  });
</script>
@endpush


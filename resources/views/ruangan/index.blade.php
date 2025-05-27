@extends('layouts.main')

@section('content')
  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('gedung.index') }}">Gedung</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('gedung.lantai.index', $lantai->gedung) }}">
          {{ $lantai->gedung->nama_gedung }}
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        {{ $lantai->nomor_lantai }}
      </li>
    </ol>
  </nav>

  <div class="card">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="card-title my-5 w-25">Data Ruangan</h3>
  <div>
    <button class="btn btn-danger btn-sm mr-2" style="min-width: 80px; height: 40px;">
      <a href="{{ route('lantai.ruangan.export_pdf', $lantai->id_lantai) }}"
         class="text-white text-decoration-none d-flex align-items-center justify-content-center w-100 h-100"
         target="_blank">
        <i class="fa fa-file-pdf mr-1"></i> PDF
      </a>
    </button>

    <button class="btn btn-success btn-sm mr-2" 
            onclick="modalAction('{{ route('lantai.ruangan.import', $lantai) }}')" 
            style="min-width: 100px; height: 40px;">
      <i class="fa fa-file-import"></i> Import
    </button>
    <button class="btn btn-primary btn-sm" 
            onclick="modalAction('{{ route('lantai.ruangan.create', $lantai) }}')" 
            style="min-width: 120px; height: 40px;"> Tambah Ruangan
    </button>
  </div>
</div>

    <table class="table table-hover table-striped" id="table-ruangan">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>

  </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
@endsection

@push('js')
<script>
  function modalAction(url){
    $('#myModal').load(url, ()=> $('#myModal').modal('show'));
  }

  window.tableRuangan = $('#table-ruangan').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('lantai.ruangan.list',$lantai) }}",
      type: 'GET'
    },
    columns: [
      { data: 'DT_RowIndex', orderable:false, searchable:false },
      { data: 'kode_ruangan' },
      { data: 'nama_ruangan' },
      { data: 'aksi', orderable:false, searchable:false },
    ]
  });
</script>
@endpush

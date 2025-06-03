@extends('layouts.main')

@section('content')
  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('gedung.index') }}">Gedung</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('gedung.lantai.index', $ruangan->lantai->gedung) }}">
          {{ $ruangan->lantai->gedung->nama_gedung }}
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('lantai.ruangan.index', $ruangan->lantai) }}">
          {{ $ruangan->lantai->nomor_lantai }}
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        {{ $ruangan->nama_ruangan }}
      </li>
    </ol>
  </nav>

  <div class="card">
  <div class="card-body">
    <h4 class="mb-4">Fasilitas di {{ $ruangan->nama_ruangan }}</h4>
    <div class="d-flex justify-content-between align-items-center">
    <h3 class="card-title my-5 w-25">Data Fasilitas</h3>
    <div>
<!-- Tombol PDF -->
<button class="btn btn-danger btn-sm mr-2" style="min-width: 80px; height: 40px;">
  <a href="{{ route('ruangan.fasilitas.export_pdf', $ruangan->id_ruangan) }}"
     class="text-white text-decoration-none d-flex align-items-center justify-content-center w-100 h-100"
     target="_blank">
    <i class="fa fa-file-pdf mr-1"></i> PDF
  </a>
</button>

<!-- Tombol Import -->
<button class="btn btn-success btn-sm mr-2" 
        onclick="modalAction('{{ route('ruangan.fasilitas.import', $ruangan) }}')" 
        style="min-width: 100px; height: 40px;">
  <i class="fa fa-file-import mr-1"></i> Import
</button>

<!-- Tombol Tambah Fasilitas -->
<button class="btn btn-primary btn-sm" 
        onclick="modalAction('{{ route('ruangan.fasilitas.create', $ruangan) }}')" 
        style="min-width: 120px; height: 40px;"> Tambah Fasilitas
</button>

    </div>
</div>


    <table class="table table-hover table-striped" id="table-fasilitas">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Fasilitas</th>
          <th>Jumlah</th>
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

  window.tableFasilitas = $('#table-fasilitas').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('ruangan.fasilitas.list',$ruangan) }}",
      type: 'GET'
    },
    columns:[
      { data:'DT_RowIndex', orderable:false, searchable:false },
      { data:'nama_fasilitas' },
      { data:'jumlah_fasilitas' },
      { data:'aksi', orderable:false, searchable:false },
    ]
  });
</script>
@endpush
